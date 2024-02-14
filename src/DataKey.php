<?php

namespace Urisoft;

class DataKey
{
    /**
     * Wrapper function for accessing nested data using dot notation.
     *
     * @param array|object $data    The array or object to access.
     * @param string       $key     The dot notation key to access the data.
     * @param mixed        $default The default value to return if the key is not found.
     *
     * @return mixed The value associated with the key or the default value.
     */
    public static function get( $data, $key, $default = null )
    {
        $data = new DotAccess( $data );

        return $data->get( $key, $default );
    }
}
