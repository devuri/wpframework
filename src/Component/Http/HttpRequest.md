# `HttpRequest` Class - Quick Usage Guide

The `HttpRequest` class is part of the `WPframework\Http` namespace and is designed to handle HTTP request parameters in a structured manner. It encapsulates the common global variables (`$_GET`, `$_POST`, `$_FILES`, etc.) and provides a clean API to interact with them.

## Installation

Ensure to include the namespace at the top of your PHP files:

```php
use WPframework\Http\HttpRequest;
```

## Usage

### Instantiating the Class

Create an instance of the `HttpRequest` class by passing in HTTP parameters, usually from PHP superglobals:

```php
$request = new HttpRequest(
    $_GET,
    $_POST,
    [],
    $_COOKIE,
    $_FILES,
    $_SERVER,
    file_get_contents('php://input') // For raw body content
);
```

### Accessing HTTP Data

You can access various parts of the HTTP request using provided getter methods:

- **GET Parameters**:
  ```php
  $queryParams = $request->getQuery();
  ```

- **POST Parameters**:
  ```php
  $postParams = $request->getRequest();
  ```

- **Cookies**:
  ```php
  $cookies = $request->getCookies();
  ```

- **Uploaded Files**:
  ```php
  $files = $request->getFiles();
  ```

- **Raw Content**:
  ```php
  $content = $request->getContent();
  ```

### Updating HTTP Data

You can also modify HTTP parameters using setter methods:

- **Set GET Parameters**:
  ```php
  $request->setQuery(['param1' => 'value1']);
  ```

- **Set POST Parameters**:
  ```php
  $request->setRequest(['key' => 'value']);
  ```

- **Set Cookies**:
  ```php
  $request->setCookies(['cookie_name' => 'cookie_value']);
  ```

- **Set Raw Content**:
  ```php
  $request->setContent('Raw body content here...');
  ```

### Example Scenario

Suppose you need to log the incoming POST data and modify a query parameter:

```php
use WPframework\Http\HttpRequest;

// Create an instance with the global variables.
$request = new HttpRequest($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER, file_get_contents('php://input'));

// Log the POST data
$postData = $request->getRequest();
error_log(print_r($postData, true));

// Modify GET parameters
$request->setQuery(['updated_key' => 'new_value']);
```

## Notes

- The `HttpRequest` class ensures that the content passed is either a string, resource, or `null`.
- Validation is provided for setting content, and using the class accessors helps maintain consistency across your application.
