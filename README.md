# booze

A simple HTTP library based on Guzzle.

## Installation

```bash
composer require liopoos/booze
```

## Usage

```php
class ApiClient extends Client
{
    public function __construct($guzzleOptions = [])
    {
        $guzzleOptions = [
            'verify' => false
        ];

        parent::__construct($guzzleOptions);
    }
}


$apiClient = new ApiClient();

$response = $apiClient->get("https://httpbin.org/get");
```

## Testing

Run the test suite:

```bash
./vendor/bin/phpunit
```

See the [tests/README.md](tests/README.md) for more information.

## License

MIT