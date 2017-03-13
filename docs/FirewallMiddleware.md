##Firewall Middleware

**Default Configuration**

```php
$_defaultConfiguration = [
    'message' => 'You are not authorized to perform this action.',
    'whitelist' => [],
    'blacklist' => [],
    'defaultState' => false,
    'identifier' => function ($request, $response) {
        return $request->clientIp();
    }
];
```

**How to use**

Open up Application.php and include the middleware.

```php
use chrisShick\CakeMiddlewares\FirewallMiddleware;
```

**Default**

```php
public function middleware($middleware)
{
    // Various other middlewares for error handling, routing etc. added here.
     
    $firewallMiddleware = new FirewallMiddleware();
    
    $middlewareQueue->add($firewallMiddleware);
    
    return $middlewareQueue;
}
```
By default, this will reject all ip addresses. 
If you want to allow all ips by default then you can change the defaultState to true.

```php
public function middleware($middleware)
{
    // Various other middlewares for error handling, routing etc. added here.
     
    $firewallMiddleware = new FirewallMiddleware([
        'defaultState' => true
    ]);
    
    $middlewareQueue->add($firewallMiddleware);
    
    return $middlewareQueue;
}
```

**Customized**

To customize your add ips to your whitelist or blacklist 
then you can do so through the configuration

```php
public function middleware($middleware)
{
    // Various other middlewares for error handling, routing etc. added here.
     
    $firewallMiddleware = new FirewallMiddleware([
         'whitelist' => [
            '192.168.211.12'
         ],
         'blacklist' => [
            '*',
         ]
    ]);
    
    $middlewareQueue->add($firewallMiddleware);
    
    return $middlewareQueue;
}
```

Or you can use the setBlacklist/setWhitelist methods
```php
public function middleware($middleware)
{
    // Various other middlewares for error handling, routing etc. added here.
     
    $firewallMiddleware = new FirewallMiddleware();
    
    $firewallMiddleware->setBlacklist([
        '192.168.211.12'
    ]);
    
    $firewallMiddleware->setWhitelist([
        '192.168.*'
    ]);
    
    $middlewareQueue->add($firewallMiddleware);
    
    return $middlewareQueue;
}
```

You can customize the 403 error message by providing it in the configuration

```php
public function middleware($middleware)
{
    // Various other middlewares for error handling, routing etc. added here.
     
    $firewallMiddleware = new FirewallMiddleware([
        'message' => 'My custom forbidden message.'
    ]);
    
    $middlewareQueue->add($firewallMiddleware);
    
    return $middlewareQueue;
}
```

If you want to change the way your are identifying a client, 
you can provide an identifier in the configuration

```php
public function middleware($middleware)
{
    // Various other middlewares for error handling, routing etc. added here.
     
    $firewallMiddleware = new FirewallMiddleware([
        'identifier' => function ($request, $response) {
            // return an ip address 
        }
    ]);
    
    $middlewareQueue->add($firewallMiddleware);
    
    return $middlewareQueue;
}
```

##TODO
- Add white listing and black listing of other types of identifiers.