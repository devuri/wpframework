<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework;

interface SetupInterface
{
    /**
     * Runs config setup.
     *
     * Define in child class.
     *
     * @param null|string[] $environment .
     * @param bool          $setup       .
     */
    public function config(?array $environment = null, ?bool $setup = true): self;

    /**
     * Debug Settings.
     *
     * @param null|string $errorLogsDir
     *
     * @return self
     */
    public function debug(?string $errorLogsDir): self;

    /**
     * Symfony Debug.
     *
     * @param $enable
     *
     * @return self
     */
    public function setErrorHandler(?string $handler = null): self;

    /**
     * Site Url Settings.
     *
     * @return self
     */
    public function siteUrl(): self;

    /**
     *  DB settings.
     *
     * @return self
     */
    public function database(): self;

    /**
     * Optimize.
     *
     * @return self
     */
    public function optimize(): self;

    /**
     * Memory Settings.
     *
     * @return self
     */
    public function memory(): self;

    /**
     * Authentication Unique Keys and Salts.
     *
     * @return self
     */
    public function salts(): self;

    /**
     * SSL.
     *
     * @return self
     */
    public function forceSsl(): self;

    /**
     * AUTOSAVE and REVISIONS.
     *
     * @return self
     */
    public function autosave(): self;

    /**
     * ASSET_URL or env(ASSET_URL).
     *
     * @return self
     */
    public function assetUrl(): self;

}
