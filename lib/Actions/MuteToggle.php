<?php

namespace HKAPI\Actions;

use HKAPI\Interfaces\ActionInterface;

class MuteToggle implements ActionInterface
{
    /**
     * Get command for action.
     *
     * @return string
     */
    public function getName()
    {
        return 'mute-toggle';
    }

    /**
     * Get parameters for action (if any).
     *
     * @return string|null
     */
    public function getParams()
    {
        return null;
    }

    /**
     * Determine if this action has a response. This is important!
     * If you do not supply the correct value here, it will run into a timeout.
     *
     * @return bool
     */
    public function hasResponse()
    {
        return false;
    }
}