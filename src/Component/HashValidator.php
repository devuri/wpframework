<?php

namespace WPframework\Component;

class HashValidator
{
    /**
     * Checks if a given string is a valid MD5 hash.
     *
     * This method checks the length of the string to ensure it is 32 characters long
     * and verifies that all characters are hexadecimal digits, which is the format of an MD5 hash.
     *
     * @param string $string The string to check.
     *
     * @return bool Returns true if the string is a valid MD5 hash, otherwise false.
     */
    public static function isMd5( $string )
    {
        return 32 == \strlen( $string ) && ctype_xdigit( $string );
    }
}
