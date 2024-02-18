<?php

namespace WPframework\Component;

class ExitHandler implements ExitInterface
{
    public function terminate($status = 0): void
    {
        exit($status);
    }
}
