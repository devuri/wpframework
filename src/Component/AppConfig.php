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

class AppConfig
{
    /**
     * @var ConstantBuilder
     */
    private $constantBuilder;

    /**
     * Constructor to inject the ConstantBuilder dependency.
     *
     * @param ConstantBuilder $constantBuilder
     */
    public function __construct(ConstantBuilder $constantBuilder)
    {
        $this->constantBuilder = $constantBuilder;
    }

    /**
     * Define a new constant.
     *
     * @param string $name
     * @param mixed  $value
     */
    public function addConstant(string $name, $value)
    {
        $this->constantBuilder->define($name, $value);
    }

	/**
     * Define a new constant.
     *
     * @param string $name
     * @param mixed  $value
     */
    public function addConst(string $name, $value)
    {
        $this->constantBuilder->define($name, $value);
    }

    /**
     * Get the value of a defined constant.
     *
     * @param string $name
     * @return mixed
     */
    public function getConstant(string $name)
    {
        return $this->constantBuilder->getConstant($name);
    }

    /**
     * Check if a constant is defined.
     *
     * @param string $name
     * @return bool
     */
    public function isConstantDefined(string $name): bool
    {
        return $this->constantBuilder->isDefined($name);
    }

	public function setConstantMap(): void
    {
        $this->constantBuilder->setMap();
    }

	public function getDefinedConstants(): array
    {
        return $this->constantBuilder->getAllConstants();
    }
}
