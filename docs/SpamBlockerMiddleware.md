##Spam Blocker Middlware

**Default Configuration**

```php
$_defaultConfiguration = [
    'message' => "We do not allow spam. Get out of here!",
    'spammers' => [],
    'spammersFile' => '/piwik/referrer-spam-blacklist/spammers.txt'
];
```

**How to use**

Open up Application.php and include the middleware.

```php
use chrisShick\CakeMiddlewares\SpamBlockerMiddleware;
```

**Default**

By default, the SpamBlocker uses 
[Piwik's Referrer Spam Blacklist](https://github.com/piwik/referrer-spam-blacklist)

```php
public function middleware($middleware)
{
    // Various other middlewares for error handling, routing etc. added here.
    
    $spamBlockerMiddleware = new SpamBlockerMiddleware();
    
    $middlewareQueue->add($spamBlockerMiddleware);
    
    return $middlewareQueue;
}
```

To add your own spammers to the default list, you can pass it into the configuration. 
To ensure this works, you should not have https:// or www

```php
public function middleware($middleware)
{
    // Various other middlewares for error handling, routing etc. added here.
    
    $spamBlockerMiddleware = new SpamBlockerMiddleware([
        'my.spammers.com'
    ]);
    
    $middlewareQueue->add($spamBlockerMiddleware);
    
    return $middlewareQueue;
}
```

To use your own spammer file, you can provide the path to the file in the configuration

```php
public function middleware($middleware)
{
    // Various other middlewares for error handling, routing etc. added here.
    
    $spamBlockerMiddleware = new SpamBlockerMiddleware([
        'spammersFile' => '/path/to/file.txt"
    ]);
    
    $middlewareQueue->add($spamBlockerMiddleware);
    
    return $middlewareQueue;
}
```

You can customize the 403 error message by providing it in the configuration

```php
public function middleware($middleware)
{
    // Various other middlewares for error handling, routing etc. added here.
    
    $spamBlockerMiddleware = new SpamBlockerMiddleware([
        'message' => 'Custom 403 message"
    ]);
    
    $middlewareQueue->add($spamBlockerMiddleware);
    
    return $middlewareQueue;
}
```

##TODO
- Add custom strategy methods