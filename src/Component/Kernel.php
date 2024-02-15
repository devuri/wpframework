<?php

namespace WPframework\Component;

use Exception;
use WPframework\Component\Http\BaseKernel;

class Kernel extends BaseKernel
{
    /**
     * Setup Kernel.
     *
     * @param string   $app_path
     * @param string[] $args
     *
     * @throws Exception
     */
    public function __construct( string $app_path, array $args = [] )
    {
        parent::__construct( $app_path, $args );
    }
}