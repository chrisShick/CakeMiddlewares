# CakeMiddlewares

[![Build Status](https://img.shields.io/travis/chrisShick/CakeMiddlewares/master.svg?style=flat-square)](https://travis-ci.org/chrisShick/CakeMiddlewares)
[![Coverage Status](https://codecov.io/gh/chrisShick/CakeMiddlewares/branch/master/graph/badge.svg)](https://codecov.io/gh/chrisShick/CakeMiddlewares)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE)

This is a collection of Cakephp Middlewares. 

This plugin provides commonly used middlewares to make it easier to integrate into your cakephp project.

## Requirements

- [CakePHP][cakephp] Version 3.4+

## Installation

```
composer require chrisshick/cakemiddlewares
```
To make your application load the plugin either run:

```bash
./bin/cake plugin load chrisShick/CakeMiddlewares
```

or add the following line to ``config/bootstrap.php``:

```php
Plugin::load('chrisShick/CakeMiddlewares');
```
## Available Middlewares
- [DelayMiddleware](http://github.com/chrisShick/CakeMiddlewares/docs/DelayMiddleware.md)
- [FirewallMiddleware](http://github.com/chrisShick/CakeMiddlewares/docs/FirewallMiddleware.md)
- [GeolocationMiddleware](http://github.com/chrisShick/CakeMiddlewares/docs/GeolocationMiddleware.md)
- [SpamBlockerMiddleware](http://github.com/chrisShick/CakeMiddlewares/docs/SpamBlockerMiddleware.md)

## Patches & Features

* Fork
* Mod, fix
* Test - This is important, so it's not unintentionally broken
* Commit - Please do not mess with license, todo, version, etc.
* Pull request

To ensure your PRs are considered for upstream, you MUST follow the CakePHP coding standards.

## Bugs & Feedback

http://github.com/chrisShick/CakeMiddlewares/issues

## Credits
This repository was inspired by [Oscarotero's PSR-7 Middelwares](https://github.com/oscarotero/psr7-middlewares)

The requirements and readme was inspired by [UseMuffin's](https://github.com/UseMuffin) repositories. 

## License

Copyright (c) 2017, chrisShick and licensed under [The MIT License][mit].

[cakephp]:http://cakephp.org
[composer]:http://getcomposer.org
[mit]:http://www.opensource.org/licenses/mit-license.php
