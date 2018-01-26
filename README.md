# Swoole Http Message Bridge

[![license](https://img.shields.io/github/license/IndraGunawan/swoole-http-message-bridge.svg?style=flat-square)](https://github.com/IndraGunawan/swoole-http-message-bridge/blob/master/LICENSE)
[![Source](https://img.shields.io/badge/source-IndraGunawan%2Fswoole--http--message--bridge-blue.svg)](https://github.com/IndraGunawan/swoole-http-message-bridge)
[![Packagist](https://img.shields.io/badge/packagist-indragunawan%2Fswoole--http--message--bridge-blue.svg)](https://packagist.org/packages/indragunawan/swoole-http-message-bridge)
[![Travis](https://img.shields.io/travis/IndraGunawan/swoole-http-message-bridge.svg?style=flat-square)](https://travis-ci.org/IndraGunawan/swoole-http-message-bridge)

Provides integration Swoole Http Request / Response to Symfony Request / Response.

## Installation

Require the package with composer. (`indragunawan/api-rate-limit-bundle` on [Packagist](https://packagist.org/packages/indragunawan/swoole-http-message-bridge));
```bash
composer require indragunawan/swoole-http-message-bridge
```

## Usage

### Symfony Request and Response

```php
<?php

use Indragunawan\SwooleHttpMessageBridge\Symfony\Request;
use Indragunawan\SwooleHttpMessageBridge\Symfony\Response;

$http = new swoole_http_server(/*...*/);

$http->on('request', function (swoole_http_request $request, swoole_http_response $response) {
    $sfRequest = Request::createFromSwooleRequest($request);
    // ...
    // $sfResponse = run_something_here that return Symfony response
    Response::writeSwooleResponse($response, $sfResponse);
    // ...
    $response->end();
});

$http->start();
```