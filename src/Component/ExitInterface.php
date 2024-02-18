<?php

namespace WPframework\Component;

interface ExitInterface
{
    public function terminate( $status = 0): void;
}
