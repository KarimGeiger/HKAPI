<?php

namespace HKAPI\Devices;


use HKAPI\Interfaces\DeviceInterface;

class AVR implements DeviceInterface
{
    /**
     * Get name for device.
     *
     * @return string
     */
    public function getName()
    {
        return 'AVR';
    }

    /**
     * Get template file for device.
     *
     * @return string
     */
    public function getTemplate()
    {
        return 'avr';
    }

    /**
     * Get default zones for device.
     *
     * @return array|null
     */
    public function getDefaultZones()
    {
        return ['Main Zone', 'Zone 2'];
    }

    /**
     * Get HTTP header for device.
     *
     * @return string
     */
    public function getHeader()
    {
        return "POST AVR HTTP/1.1\r\nHost: :10025\r\nUser-Agent: Harman Kardon AVR Remote Controller /2.0";
    }
}