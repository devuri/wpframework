# DotAccess - Convenient Access to Nested Data Using Dot Notation

The `DotAccess` class provides a user-friendly wrapper around the functionality of the `Dflydev\DotAccessData\Data` package, allowing easy access to nested data using dot notation in PHP.

## Installation

1. Ensure you have [Composer](https://getcomposer.org/) installed on your system.
2. Run the following command to install the package:

```bash
composer require devuri/dot-access
```

## Getting Started

1. Include the `DotAccess` class in your PHP script:

```php

use Urisoft\DotAccess;

```

2. Create an instance of the `DotAccess` class and pass the nested data (array or object) to the constructor:

```php
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

$dotdata = new DotAccess($data);
```

## Accessing Data

The `DotAccess` class provides the following methods to access the nested data using dot notation:

### Get the Value

Use the `get()` method to retrieve the value associated with a dot notation key:

```php
$name = $dotdata->get('user.name');
$email = $dotdata->get('user.email');
$city = $dotdata->get('user.address.city');
```

### Set the Value

Use the `set()` method to set a value for a dot notation key:

```php
$dotdata->set('user.age', 30);
```

### Checking for Key Existence

Use the `has()` method to check if a dot notation key exists in the data:

```php
$emailExists = $dotdata->has('user.email');
```

### Removing a Key

Use the `remove()` method to unset the value associated with a dot notation key:

```php
$dotdata->remove('user.address.country');
```

## Example

```php
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

$dotdata = new DotAccess($data);

$name = $dotdata->get('user.name'); // Output: "John Doe"
$dotdata->set('user.age', 30);
$emailExists = $dotdata->has('user.email'); // Output: true
$dotdata->remove('user.address.country');

echo "Name: $name\n";
echo "Age: " . $dotdata->get('user.age') . "\n";
echo "Email exists: " . ($emailExists ? 'Yes' : 'No') . "\n";
```

## Wrapper Function - DataKey:get()

In addition to the `DotAccess` class, we also provide a standalone wrapper function `DataKey` that simplifies accessing nested data using dot notation.

### Usage

The `DataKey:get()` function allows you to quickly access nested data without having to create an instance of the `DotAccess` class. It takes three parameters:

1. The data array or object to access.
2. The dot notation key to access the data.
3. An optional default value to return if the key is not found.

Here's how you can use the `DataKey:get()` function:

```php
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

// Using the wrapper function
$name = DataKey:get($data, 'user.name');
$email = DataKey:get($data, 'user.email');
$city = DataKey:get($data, 'user.address.city');
$zipCode = DataKey:get($data, 'user.address.zip_code', 'N/A'); // Provide a default value if the key doesn't exist

echo "Name: $name\n";
echo "Email: $email\n";
echo "City: $city\n";
echo "Zip Code: $zipCode\n";
```

### When to Use `DataKey:get()` vs. `DotAccess`

Both the `DataKey:get()` function and the `DotAccess` class serve the same purpose: accessing nested data using dot notation. The choice between them depends on your specific use case and coding preferences.

Use `DataKey:get()` when:

- You prefer a simple function call over creating an instance of the `DotAccess` class.
- You only need to access nested data at a few specific points in your code.
- You don't need to perform multiple operations (e.g., setting, checking, or removing keys).

Use `DotAccess` class when:

- You need to perform multiple operations on the same nested data within your code.
- You prefer an object-oriented approach for handling nested data.
- You need better encapsulation and separation of concerns in your code.

Both approaches provide a convenient and user-friendly way to work with nested data using dot notation. Choose the one that best fits your coding style and requirements.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgments

The `DotAccess` class is a simple wrapper around the `Dflydev\DotAccessData\Data` package, which provides the core functionality for accessing nested data using dot notation. Special thanks to the authors of the `Dflydev\DotAccessData` package for their excellent work.
