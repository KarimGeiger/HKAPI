<?php

namespace HKAPI;


use HKAPI\Devices\AVR;
use HKAPI\Exceptions\HKAPIInvalidZoneException;
use HKAPI\Exceptions\HKAPISocketException;
use HKAPI\Exceptions\HKAPITimeoutException;
use HKAPI\Interfaces\DeviceInterface;

class API
{
    /**
     * Relative path to template folder.
     */
    const TEMPLATE_PATH = '/../templates/';

    /**
     * Timeout in seconds.
     */
    const RESPONSE_TIMEOUT = 2;

    /**
     * @var string
     */
    protected $ip;

    /**
     * @var int
     */
    protected $port;

    /**
     * @var Zone[]
     */
    protected $zones = [];

    /**
     * @var resource
     */
    protected $socket;

    /**
     * @var DeviceInterface
     */
    protected $deviceType;

    /**
     * API constructor.
     *
     * @param string $ip
     * @param int $port
     * @param DeviceInterface $deviceType Default: AVR
     * @param array $zones List of available zones
     */
    public function __construct($ip, $port = 10025, DeviceInterface $deviceType = null, $zones = [])
    {
        $this->ip = $ip;
        $this->port = (int)$port;
        $this->deviceType = $deviceType ?: new AVR();

        if (empty($zones)) {
            $zones = $this->deviceType->getDefaultZones();
        }
        foreach ($zones as $zone) {
            $this->zones[$zone] = new Zone($this, $zone);
        }

        $this->connect();
    }

    /**
     * Get Zone object.
     *
     * @param string $name
     * @return Zone
     * @throws HKAPIInvalidZoneException
     */
    public function zone($name)
    {
        if (isset($this->zones[$name])) {
            return $this->zones[$name];
        }
        throw new HKAPIInvalidZoneException('Zone could not be found: ' . $name);
    }

    /**
     * Verify socket connection. On missing or ended socket, connect.
     *
     * @throws HKAPISocketException
     */
    protected function connect()
    {
        if (is_resource($this->socket)) {
            if (!feof($this->socket)) {
                return;
            } else {
                socket_close($this->socket);
            }
        }
        $errno = null;
        $errmsg = null;
        $this->socket = @fsockopen($this->ip, $this->port, $errno, $errmsg, self::RESPONSE_TIMEOUT);

        if (!is_resource($this->socket)) {
            throw new HKAPISocketException($errmsg, $errno);
        }

        stream_set_blocking($this->socket, 0);
    }

    /**
     * Manually send raw XML request to AVR.
     * You should not use this method since there are Actions for all types.
     *
     * @param string $data
     * @throws HKAPISocketException
     */
    public function sendRequest($data)
    {
        // Verify connection before every request
        $this->connect();

        // Clear buffer
        fread($this->socket, 4096);

        fwrite($this->socket, sprintf(
            "%s\r\nContent-Length: %d\r\n\r\n%s",
            $this->deviceType->getHeader(),
            strlen($data),
            $data
        ));
    }

    /**
     * Read response from AVR or throw exception after timeout exceeded.
     *
     * @return string XML response
     * @throws HKAPITimeoutException
     */
    public function readResponse()
    {
        $i = 0;
        do {
            if ($i >= self::RESPONSE_TIMEOUT * 2) {
                throw new HKAPITimeoutException(sprintf(
                    'Exceeded timeout of %d seconds while waiting for response.',
                    self::RESPONSE_TIMEOUT
                ));
            }
            $response = fread($this->socket, 4096);
            usleep(500000); // wait half a second
            $i++;
        } while (empty($response));

        return '<?xml' . explode('<?xml', $response)[1] . '>';
    }

    /**
     * Generate request using XML template.
     *
     * @param string $name Action name.
     * @param string $zone Zone name.
     * @param string|null $para Parameter, if any.
     * @param string $template Name of template file.
     * @return string
     */
    public function generateRequest($name, $zone, $para = null, $template = 'avr')
    {
        return trim(str_replace(
            ['{{ name }}', '{{ zone }}', '{{ para }}'],
            [$name, $zone, $para],
            file_get_contents(__DIR__ . self::TEMPLATE_PATH . $template . '.xml')
        ));
    }

    /**
     * Get device type.
     *
     * @return DeviceInterface
     */
    public function getDeviceType()
    {
        return $this->deviceType;
    }
}