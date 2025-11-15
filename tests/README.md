# Booze Tests

This directory contains unit tests for the Booze HTTP library.

## Running Tests

To run all tests:

```bash
./vendor/bin/phpunit
```

To run tests with detailed output:

```bash
./vendor/bin/phpunit --testdox
```

To run tests with code coverage (requires Xdebug or PCOV):

```bash
./vendor/bin/phpunit --coverage-html coverage
```

## Test Structure

- `Unit/` - Unit tests for individual components
  - `ClientTest.php` - Tests for the Client class
  - `Http/HttpClientTest.php` - Tests for the HttpClient class
  - `Http/Middleware/RequestHandlerMiddlewareTest.php` - Tests for request middleware
  - `Http/Middleware/ResponseHandlerMiddlewareTest.php` - Tests for response middleware
  - `Utils/ResponseStreamTest.php` - Tests for the ResponseStream utility
  - `Exception/ExceptionTest.php` - Tests for exception classes

## Test Coverage

The test suite covers:
- All HTTP methods (GET, POST, PUT, PATCH, DELETE)
- JSON and multipart requests
- Request and response middleware
- Response stream parsing (JSON, XML, plain text)
- Exception handling
- Header management
