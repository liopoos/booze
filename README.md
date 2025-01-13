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

## License

MIT