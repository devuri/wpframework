<?php

namespace WPframework\Component\Http;

/**
 * Interface for managing different environment settings.
 */
interface EnvSwitcherInterface
{
    /**
     * Configure the environment for secure production.
     *
     * This method should be used in a secure production environment.
     */
    public function secure(): void;

    /**
     * Configure the environment for production.
     *
     * This method should be used in a production environment.
     */
    public function production(): void;

    /**
     * Configure the environment for staging.
     *
     * This method should be used in a staging environment.
     */
    public function staging(): void;

    /**
     * Configure the environment for development.
     *
     * This method should be used in a development environment.
     */
    public function development(): void;

    /**
     * Configure the environment for debugging.
     *
     * This method should be used for debugging purposes.
     */
    public function debug(): void;

    /**
     * Switches between different environments based on the value of $environment.
     *
     * @param string $environment    The environment to switch to.
     * @param string $error_logs_dir The error logs directory relative path.
     *
     * @return void
     */
    public function create_environment( string $environment, ?string $error_logs_dir ): void;
}