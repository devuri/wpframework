<?php

namespace Tests\Unit\App\Console;

use PHPUnit\Framework\TestCase;
use Urisoft\DotAccess;

/**
 * @internal
 *
 * @coversNothing
 */
class DotAccessTest extends TestCase
{
    public function test_get_existing_key(): void
    {
        $data = [
            'user' => [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'address' => [
                    'city' => 'New York',
                    'country' => 'USA',
                ],
            ],
        ];

        $wrapper = new DotAccess($data);

        $name = $wrapper->get('user.name');
        $email = $wrapper->get('user.email');
        $city = $wrapper->get('user.address.city');

        $this->assertEquals('John Doe', $name);
        $this->assertEquals('john.doe@example.com', $email);
        $this->assertEquals('New York', $city);
    }

    public function test_get_non_existing_key_with_default(): void
    {
        $data = [
            'user' => [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'address' => [
                    'city' => 'New York',
                    'country' => 'USA',
                ],
            ],
        ];

        $wrapper = new DotAccess($data);

        $default = 'N/A';
        $zipCode = $wrapper->get('user.address.zip_code', $default);

        $this->assertEquals($default, $zipCode);
    }

    public function test_set_key(): void
    {
        $data = [
            'user' => [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
            ],
        ];

        $wrapper = new DotAccess($data);

        $age = 30;
        $wrapper->set('user.age', $age);

        $this->assertEquals($age, $wrapper->get('user.age'));
    }

    public function test_has_key(): void
    {
        $data = [
            'user' => [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
            ],
        ];

        $wrapper = new DotAccess($data);

        $this->assertTrue($wrapper->has('user.name'));
        $this->assertTrue($wrapper->has('user.email'));
        $this->assertFalse($wrapper->has('user.age'));
    }

    public function test_remove_key(): void
    {
        $data = [
            'user' => [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
            ],
        ];

        $wrapper = new DotAccess($data);

        $wrapper->remove('user.email');

        $this->assertFalse($wrapper->has('user.email'));
    }
}
