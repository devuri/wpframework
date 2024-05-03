<?php

/**
 * Determines whether strict environment variables should be enforced.
 *
 * By setting this constant to false, the application will not enforce
 * strict environment variables when loading values using the env() function.
 * This can be useful in development environments where not all environment
 * variables are set, allowing the application to run without throwing errors
 * due to undefined variables.
 */
define( 'USE_STRICT_ENV_VARS', false );
