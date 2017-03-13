##Geolocation Middleware

**Default Configuration**

```php
$_defaultConfiguration = [
    'attribute' => 'geolocation',
    'geocoder' => null,
    'identifier' => function ($request, $response) {
        return $request->clientIp();
    }
];
```

**How to use**

Open up Application.php and include the middleware.

```php
use chrisShick\CakeMiddlewares\GeolocationMiddleware;
```

**Default**
The default configuration will return a Gelocation object in the geolocation attribute of the request.

```php
public function middleware($middleware)
{
    // Various other middlewares for error handling, routing etc. added here.
    
    $geolocationMiddleware = new GeolocationMIddleware();
    
    $middlewareQueue->add($geolocationMiddleware);
    
    return $middlewareQueue;
}
```

**Customized**

You must instantiate the Geocoder. 
For more more information on gecoders you can click [here](https://github.com/geocoder-php/Geocoder)

```php
public function middleware($middleware)
{
    // Various other middlewares for error handling, routing etc. added here.
     
    $geocoder = new ProviderAggregator();
    $geocoder->registerProvider(new FreeGeoIp(new FopenHttpAdapter()));
    
    $geolocationMiddleware = new GeolocationMIddleware([
        'geocoder' => $geocoder
    ]);
    
    $middlewareQueue->add($geolocationMiddleware);
    
    return $middlewareQueue;
}
```