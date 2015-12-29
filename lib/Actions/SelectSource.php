<?php

namespace HKAPI\Actions;

use HKAPI\Interfaces\ActionInterface;

class SelectSource implements ActionInterface
{
    /**
     * @var string
     */
    protected $source;

    /**
     * Set source.
     *
     * @param string $source
     */
    public function __construct($source)
    {
        $this->source = $source;
    }

    /**
     * Get command for action.
     *
     * @return string
     */
    public function getName()
    {
        return 'source-selection';
    }

    /**
     * Get parameters for action (if any).
     *
     * @return string|null
     */
    public function getParams()
    {
        return $this->source;
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