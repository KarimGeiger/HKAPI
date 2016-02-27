<?php

namespace HKAPI\Interfaces;


interface DeviceInterface
{
    /**
     * Get name for device.
     *
     * @return string
     */
    public function getName();

    /**
     * Get template file for device.
     *
     * @return string
     */
    public function getTemplate();

    /**
     * Get default zones for device.
     *
     * @return array|null
     */
    public function getDefaultZones();

    /**
     * Get HTTP header for device.
     *
     * @return string
     */
    public function getHeader();
}