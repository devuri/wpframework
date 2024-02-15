<?php

namespace WPframework\Component\Http;

class HttpFactory
{
    /**
     * Creates and returns an instance of HostManager.
     *
     * @return HostManager An instance of the HostManager class.
     */
    public static function init(): HostManager
    {
        return new HostManager();
    }
}
