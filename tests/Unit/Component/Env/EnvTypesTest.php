<?php

namespace WPframework\Tests\Env;

use PHPUnit\Framework\TestCase;
use WPframework\Env\EnvTypes;

/**
 * @covers \WPframework\Env\EnvTypes
 *
 * @internal
 */
final class EnvTypesTest extends TestCase
{
    /**
     * Test that isValid returns true for all valid environment types.
     */
    public function test_is_valid_returns_true_for_valid_types(): void
    {
        $validTypes = EnvTypes::getAll();

        foreach ($validTypes as $type) {
            $this->assertTrue(
                EnvTypes::isValid($type),
                "Failed asserting that '{$type}' is a valid environment type."
            );
        }
    }

    /**
     * Test that isValid returns false for invalid environment types.
     */
    public function test_is_valid_returns_false_for_invalid_types(): void
    {
        $invalidTypes = [
            'invalid',
            'test',
            'production ', // Trailing space
            ' DEV',        // Leading space
            '',
            null,
            123,
            true,
            false,
        ];

        foreach ($invalidTypes as $type) {
            $this->assertFalse(
                EnvTypes::isValid($type),
                "Failed asserting that '{$type}' is not a valid environment type."
            );
        }
    }

    /**
     * Test that getAll returns all expected environment types.
     */
    public function test_get_all_returns_expected_types(): void
    {
        $expectedTypes = [
            EnvTypes::SECURE,
            EnvTypes::SEC,
            EnvTypes::PRODUCTION,
            EnvTypes::PROD,
            EnvTypes::STAGING,
            EnvTypes::DEVELOPMENT,
            EnvTypes::DEV,
            EnvTypes::DEBUG,
            EnvTypes::DEB,
            EnvTypes::LOCAL,
        ];

        $this->assertSame(
            $expectedTypes,
            EnvTypes::getAll(),
            'Failed asserting that getAll() returns the expected environment types.'
        );
    }
}
