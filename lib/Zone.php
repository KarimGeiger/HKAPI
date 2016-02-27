<?php

namespace HKAPI;


use HKAPI\Exceptions\HKAPIInvalidActionException;
use HKAPI\Interfaces\ActionInterface;

/**
 * Class Zone
 *
 * @method void back()
 * @method void down()
 * @method void forward()
 * @method string heartAlive()
 * @method void home()
 * @method void info()
 * @method void left()
 * @method void muteToggle()
 * @method void next()
 * @method void off()
 * @method void ok()
 * @method void on()
 * @method void options()
 * @method void pause()
 * @method void play()
 * @method void previous()
 * @method void reverse()
 * @method void right()
 * @method void selectSource($source)
 * @method void up()
 * @method void volumeDown()
 * @method void volumeUp()
 */
class Zone
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var API
     */
    protected $api;

    /**
     * Zone constructor.
     *
     * @param API $api
     * @param string $name
     */
    public function __construct(API $api, $name)
    {
        $this->name = $name;
        $this->api = $api;
    }

    /**
     * Run action. You can also use the magic method to do so.
     *
     * @param ActionInterface $action
     * @return string|void
     * @throws Exceptions\HKAPITimeoutException
     */
    public function run(ActionInterface $action)
    {
        $this->api->sendRequest(
            $this->api->generateRequest(
                $action->getName(),
                $this->name,
                $action->getParams(),
                $this->api->getDeviceType()->getTemplate()
            )
        );
        if ($action->hasResponse()) {
            return $this->api->readResponse();
        }
    }

    /**
     * If you're lazy and don't want to create an Action object all the time, just use this.
     *
     * @see self::run()
     * @param string $name Name of Action class
     * @param array $arguments Optional argument (must be an array)
     * @return null|string
     * @throws HKAPIInvalidActionException
     */
    public function __call($name, $arguments)
    {
        $className = __NAMESPACE__ . "\\Actions\\" . ucfirst($name);

        if (class_exists($className) && is_subclass_of($className, ActionInterface::class)) {
            $action = new $className($arguments[0]);
        } else {
            throw new HKAPIInvalidActionException('Invalid action ' . $className);
        }

        return $this->run($action);
    }
}