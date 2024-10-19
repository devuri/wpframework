To run only a specific test (such as your `ResponseTest`):

### 1. **Run a Specific Test Class**

If you want to run just the `ResponseTest` class, you can specify the path to the test file when running PHPUnit:

```bash
./vendor/bin/phpunit tests/Unit/Component/Http/Message/ResponseTest.php --testdox
```

### 2. **Run a Specific Test Method in a Class**

If you want to run a specific method within the `ResponseTest`, you can use the `--filter` option and provide the method name:

```bash
./vendor/bin/phpunit --filter testGetProtocolVersion tests/ResponseTest.php
```

This will run only the `testGetProtocolVersion` method inside the `ResponseTest.php` file.


### 3. **Run All Tests with a Pattern**

You can also run all test files that match a certain pattern using `--filter`. For example, to run all tests containing the word "Response":

```bash
./vendor/bin/phpunit --filter Response
```

This will run all test methods across all files that contain "Response" in the method or class name.

These methods will help you isolate and run only the tests you’re interested in.
