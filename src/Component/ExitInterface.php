<?php

namespace WPframework;

interface ExitInterface
{
    public function terminate( $status = 0): void;
}
