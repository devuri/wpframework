<?php

namespace WPframework\Http;

use WPframework\Setup;

interface KernelInterface
{
    /**
     * Returns the application setup instance.
     *
     * @return Setup
     */
    public function get_app(): Setup;

    /**
     * Retrieves the security settings of the application.
     *
     * @return array
     */
    public function get_app_security(): array;

    /**
     * Gets the application path.
     *
     * @return string
     */
    public function get_app_path(): string;

    /**
     * Returns the arguments used in the application.
     *
     * @return array
     */
    public function get_args(): array;

    /**
     * Retrieves the application configuration.
     *
     * @return array
     */
    public function get_app_config(): array;

    /**
     * Defines configuration constants for the application.
     *
     * @return void
     */
    public function set_config_constants(): void;

    /**
     * Initializes the application with environment settings.
     *
     * @param null|string $environment_type Optional environment type.
     * @param bool        $constants        Whether to set constants.
     *
     * @return KernelInterface
     */
    public function app( ?string $environment_type = null, bool $constants = true): self;

    /**
     * Sets an environment secret using a key.
     *
     * @param string $key The secret key.
     *
     * @return void
     */
    public function set_env_secret( string $key): void;

    /**
     * Retrieves the list of environment secret keys.
     *
     * @return array
     */
    public function get_secret(): array;

    /**
     * Gets all defined constants.
     *
     * @return array
     */
    public function get_defined(): array;

    /**
     * Returns server environment variables if in debug mode.
     *
     * @return null|array
     */
    public function get_server_env(): ?array;

    /**
     * Retrieves user-defined constants if in debug mode.
     *
     * @return null|array
     */
    public function get_user_constants(): ?array;
}
