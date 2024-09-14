<?php

namespace WPframework\Http;

use Urisoft\HttpClient;

class Client extends HttpClient
{
    public function __construct( string $base_url, array $context = [ 'timeout' => 20 ] )
    {
        parent::__construct( $base_url, $context );
    }
}
