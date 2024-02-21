<?php

namespace WPframework\Component;

class ConstLoader
{
    /**
     * An array to hold the names of expected constants.
     *
     * @var array
     */
    private $expectedConstants;

    /**
     * Constructor to initialize the class with expected constant names.
     *
     * @param array $expectedConstants An array of expected constant names.
     */
    public function __construct( array $expectedConstants = [] )
    {
        $this->expectedConstants = $expectedConstants;
    }

    /**
     * Safely retrieves the value of a global constant if it is defined and expected.
     * Exits the script with a message if the constant is undefined or unexpected.
     *
     * @param string $name The constant name.
     *
     * @return mixed The value of the constant if defined and expected.
     */
    public function get_constant( $name )
    {
        if ( \in_array( $name, $this->expectedConstants, true ) && \defined( $name ) ) {
            return \constant( $name );
        }

        exit( 'An error occurred. Please contact the administrator.' );
    }

    /**
     * Checks if a constant is defined and expected.
     *
     * @param string $name The constant name.
     *
     * @return bool True if the constant is defined and expected; false otherwise.
     */
    public function is_constant_defined( $name )
    {
        return \in_array( $name, $this->expectedConstants, true ) && \defined( $name );
    }
}

// // Define some global constants for demonstration purposes.
// define('API_KEY', '123456');
// define('API_SECRET', 'abcdef');
//
// // Example usage:
// $constantLoader = new ConstantLoader(['API_KEY', 'API_SECRET', 'NON_EXISTENT']);
//
// // Retrieve a constant safely.
// $apiKey = $constantLoader->get_constant('API_KEY');
// echo "API Key: $apiKey\n";
//
// // Trying to retrieve an undefined or unexpected constant.
// // This will exit the script with the specified message.
// $constantLoader->get_constant('NON_EXISTENT');
