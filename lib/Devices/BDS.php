<?php

namespace HKAPI\Devices;


use HKAPI\Interfaces\DeviceInterface;

class BDS implements DeviceInterface
{
    /**
     * Get name for device.
     *
     * @return string
     */
    public function getName()
    {
        return 'BDS';
    }

    /**
     * Get template file for device.
     *
     * @return string
     */
    public function getTemplate()
    {
        return 'bds';
    }

    /**
     * Get default zones for device.
     *
     * @return array|null
     */
    public function getDefaultZones()
    {
        return ['Main Zone'];
    }

    /**
     * Get HTTP header for device.
     *
     * @return string
     */
    public function getHeader()
    {
        return "POST HK_APP HTTP/1.1\r\nHost: :10025\r\nUser-Agent: Harman Kardon BDS Remote Controller/1.0";
    }
}