# Booze

[![Latest Version](https://img.shields.io/packagist/v/liopoos/booze.svg?style=flat-square)](https://packagist.org/packages/liopoos/booze)
[![License](https://img.shields.io/packagist/l/liopoos/booze.svg?style=flat-square)](LICENSE)
[![PHP Version](https://img.shields.io/packagist/php-v/liopoos/booze.svg?style=flat-square)](https://packagist.org/packages/liopoos/booze)

A simple and elegant HTTP client library for PHP, built on top of Guzzle. Booze provides a clean, fluent interface for making HTTP requests with automatic response parsing, middleware support, and comprehensive error handling.

## Features

- 🚀 **Simple & Intuitive API** - Clean, fluent interface for making HTTP requests
- 🔄 **Automatic Response Parsing** - Smart content-type detection and parsing (JSON, XML, plain text)
- 🛡️ **Built-in Error Handling** - Automatic exception throwing for HTTP error responses
- ⚙️ **Middleware Support** - Easy request/response manipulation with Guzzle middleware
- 📦 **Multiple Request Types** - Support for GET, POST, PUT, PATCH, DELETE with various content types
- 🎯 **Type-Safe** - Full type hints and return types for better IDE support

## Requirements

- PHP 7.3 or higher
- ext-json
- ext-simplexml

## Installation

Install via Composer:

```bash
composer require liopoos/booze
```

## Quick Start

```php
use Liopoos\Booze\Client;

class ApiClient extends Client
{
    public function __construct(array $guzzleOptions = [])
    {
        // Configure Guzzle options
        $guzzleOptions = array_merge([
            'base_uri' => 'https://api.example.com',
            'timeout' => 30,
        ], $guzzleOptions);

        parent::__construct($guzzleOptions);
    }
}

$client = new ApiClient();
$response = $client->get('/users');
```

## Usage

### Basic HTTP Methods

#### GET Request

```php
// Simple GET request
$response = $client->get('https://httpbin.org/get');

// GET with query parameters
$response = $client->get('https://httpbin.org/get', [
    'page' => 1,
    'limit' => 10
]);

// GET with custom headers
$response = $client->get('https://httpbin.org/get', [], [
    'Authorization' => 'Bearer token123'
]);
```

#### POST Request

```php
// POST with form data
$response = $client->post('https://httpbin.org/post', [
    'name' => 'John Doe',
    'email' => 'john@example.com'
]);

// POST with JSON
$response = $client->postJson('https://httpbin.org/post', [
    'name' => 'John Doe',
    'email' => 'john@example.com'
]);

// POST with multipart (file uploads)
$response = $client->postMultiPart('https://httpbin.org/post', [
    [
        'name' => 'file',
        'contents' => fopen('/path/to/file.jpg', 'r'),
        'filename' => 'file.jpg'
    ]
]);
```

#### PUT Request

```php
// PUT with form data
$response = $client->put('https://httpbin.org/put', [
    'name' => 'Jane Doe'
]);

// PUT with JSON
$response = $client->putJson('https://httpbin.org/put', [
    'name' => 'Jane Doe'
]);

// PUT with multipart
$response = $client->putMultiPart('https://httpbin.org/put', [
    [
        'name' => 'data',
        'contents' => 'value'
    ]
]);
```

#### PATCH Request

```php
// PATCH with JSON (default)
$response = $client->patch('https://httpbin.org/patch', [
    'name' => 'Updated Name'
]);
```

#### DELETE Request

```php
// Simple DELETE
$response = $client->delete('https://httpbin.org/delete');

// DELETE with JSON body
$response = $client->deleteJson('https://httpbin.org/delete', [
    'reason' => 'No longer needed'
]);
```

### Working with Headers

Set persistent headers for all requests:

```php
$client->withHeaders([
    'Authorization' => 'Bearer your-token',
    'Accept' => 'application/json',
    'User-Agent' => 'MyApp/1.0'
]);

// All subsequent requests will include these headers
$response = $client->get('https://api.example.com/data');
```

### Response Handling

Booze automatically parses responses based on the `Content-Type` header:

```php
// JSON responses are automatically decoded to arrays
$response = $client->get('https://api.example.com/users');
// $response is an array

// XML responses are converted to arrays
$response = $client->get('https://api.example.com/data.xml');
// $response is an array

// Plain text responses are returned as strings
$response = $client->get('https://example.com/text');
// $response is a string
```

### Error Handling

Booze provides specific exceptions for common HTTP errors:

```php
use Liopoos\Booze\Exception\UnauthorizedHttpException;
use Liopoos\Booze\Exception\NotFoundHttpException;
use Liopoos\Booze\Exception\AccessDeniedHttpException;
use Liopoos\Booze\Exception\ApiException;

try {
    $response = $client->get('https://api.example.com/protected');
} catch (UnauthorizedHttpException $e) {
    // Handle 401 Unauthorized
    echo "Authentication failed: " . $e->getMessage();
} catch (AccessDeniedHttpException $e) {
    // Handle 403 Forbidden
    echo "Access denied: " . $e->getMessage();
} catch (NotFoundHttpException $e) {
    // Handle 404 Not Found
    echo "Resource not found: " . $e->getMessage();
} catch (ApiException $e) {
    // Handle all other HTTP errors (4xx, 5xx)
    echo "API error: " . $e->getMessage();
}
```

### Advanced Usage

#### Custom Guzzle Configuration

```php
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

class MyApiClient extends Client
{
    public function __construct()
    {
        $handler = HandlerStack::create();
        
        // Add custom middleware
        $handler->push(Middleware::log(
            $logger,
            new MessageFormatter('{method} {uri} HTTP/{version} {code}')
        ));
        
        parent::__construct([
            'handler' => $handler,
            'base_uri' => 'https://api.example.com',
            'timeout' => 30,
            'verify' => true,
            'http_errors' => false,
        ]);
    }
}
```

#### Accessing Raw Guzzle Client and Response

```php
// Get the underlying Guzzle client
$guzzleClient = $client->getHttpClient();

// Get the last HTTP response object
$client->get('https://httpbin.org/get');
$httpResponse = $client->getHttpResponse();

// Access response details
$statusCode = $httpResponse->getStatusCode();
$headers = $httpResponse->getHeaders();
$body = $httpResponse->getBody();
```

## API Reference

### Client Methods

#### HTTP Methods

| Method | Description | Parameters |
|--------|-------------|------------|
| `get(string $url, array $query = [], array $headers = [])` | Send GET request | URL, query parameters, headers |
| `post(string $url, array $body = [], array $headers = [])` | Send POST with form data | URL, form data, headers |
| `postJson(string $url, array $body, array $headers = [])` | Send POST with JSON | URL, JSON data, headers |
| `postMultiPart(string $url, array $body, array $headers = [])` | Send POST with multipart | URL, multipart data, headers |
| `put(string $url, array $body = null, array $headers = [])` | Send PUT with form data | URL, form data, headers |
| `putJson(string $url, array $body = null, array $headers = [])` | Send PUT with JSON | URL, JSON data, headers |
| `putMultiPart(string $url, array $body = null, array $headers = [])` | Send PUT with multipart | URL, multipart data, headers |
| `patch(string $url, array $body = [], array $headers = [])` | Send PATCH with JSON | URL, JSON data, headers |
| `delete(string $url, array $headers = [])` | Send DELETE request | URL, headers |
| `deleteJson(string $url, array $body = [], array $headers = [])` | Send DELETE with JSON | URL, JSON data, headers |

#### Utility Methods

| Method | Description | Returns |
|--------|-------------|---------|
| `withHeaders(array $headers)` | Set persistent headers for all requests | `Client` (chainable) |
| `getHttpClient()` | Get the underlying Guzzle client | `\GuzzleHttp\Client` |
| `getHttpResponse()` | Get the last HTTP response object | `\GuzzleHttp\Psr7\Response` |

### Exceptions

| Exception | HTTP Status | Description |
|-----------|-------------|-------------|
| `UnauthorizedHttpException` | 401 | Authentication required or failed |
| `AccessDeniedHttpException` | 403 | Access forbidden |
| `NotFoundHttpException` | 404 | Resource not found |
| `ApiException` | Other 4xx/5xx | Generic API error |

All exceptions extend `\GuzzleHttp\Exception\TransferException`.

## Testing

Run the test suite:

```bash
# Run all tests
./vendor/bin/phpunit

# Run with detailed output
./vendor/bin/phpunit --testdox

# Run with code coverage
./vendor/bin/phpunit --coverage-html coverage
```

See [tests/README.md](tests/README.md) for more information about the test suite.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This library is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Credits

- Built on top of [Guzzle HTTP Client](https://github.com/guzzle/guzzle)
- Developed by [hades](mailto:hades_dev@foxmail.com)

## Support

If you encounter any issues or have questions, please [open an issue](https://github.com/liopoos/booze/issues) on GitHub.