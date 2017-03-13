##Delay Middleware

**Default Configuration**

```php
$_defaultConfiguration = [
    'header' => 'X-Delay-Time',
    'attribute' => 'delayTime',
    'seconds' => [1, 2]
];
```

**How to use**

Open up Application.php and include the middleware.

```php
use chrisShick\CakeMiddlewares\DelayMiddleware;
```

**Default**

```php
public function middleware($middleware)
{
    // Various other middlewares for error handling, routing etc. added here.
     
    $delayMiddleware = new DelayMiddleware();
    
    $middlewareQueue->add($delayMiddleware);
    
    return $middlewareQueue;
}
```

This will choose a random time between 1 and 2 seconds for a delay 
and pass the delayed time into X-Delay-Time header and delayTime attribute.


**Customized**

```php
public function middleware($middleware)
{
    // Various other middlewares for error handling, routing etc. added here.
     
    $delayMiddleware = new DelayMiddleware([
        'header' => 'My-Delay-Time',
        'attribute' => 'myDelayTime',
        'seconds' => 6
    ]);
    
    $middlewareQueue->add($delayMiddleware);
    
    return $middlewareQueue;
}
```

This is will delay the request by 6 seconds 
and pass the delayed time into the My-Delay-Time header 
and the myDelayTime attribute.

**Note:** You can use an array value of two numbers to choose between for the random seconds 
or you can choose a set amount of seconds.
