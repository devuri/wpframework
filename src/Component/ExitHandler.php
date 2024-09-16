<?php

namespace WPframework;

class ExitHandler implements ExitInterface
{
    public function terminate( $status = 0 ): void
    {
        exit( $status );
    }
}
