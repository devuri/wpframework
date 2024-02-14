<?php

namespace Urisoft;

use Dflydev\DotAccessData\Data;

class DotAccess
{
    private $data;

    /**
     * Constructor to initialize the wrapper with the data.
     *
     * @param array|object $data The array or object to access.
     */
    public function __construct( $data )
    {
        $this->data = new Data( $data );
    }

    /**
     * Get the value associated with the provided dot notation key.
     *
     * @param string $key     The dot notation key to access the data.
     * @param mixed  $default The default value to return if the key is not found.
     *
     * @return mixed The value associated with the key or the default value.
     */
    public function get( $key, $default = null )
    {
        return $this->data->get( $key, $default );
    }

    /**
     * Set the value associated with the provided dot notation key.
     *
     * @param string $key   The dot notation key to set the data.
     * @param mixed  $value The value to set for the key.
     *
     * @return void
     */
    public function set( $key, $value ): void
    {
        $this->data->set( $key, $value );
    }

    /**
     * Check if the provided dot notation key exists in the data.
     *
     * @param string $key The dot notation key to check.
     *
     * @return bool Whether the key exists or not.
     */
    public function has( $key )
    {
        return $this->data->has( $key );
    }

    /**
     * Unset the value associated with the provided dot notation key.
     *
     * @param string $key The dot notation key to unset the data.
     *
     * @return void
     */
    public function remove( $key ): void
    {
        $this->data->remove( $key );
    }
}
