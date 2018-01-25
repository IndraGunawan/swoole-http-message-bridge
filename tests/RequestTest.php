<?php

/*
 * This file is part of the swoole-http-message-bridge
 *
 * (c) Indra Gunawan <hello@indra.my.id>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indragunawan\SwooleHttpMessageBridge\Tests;

use Indragunawan\SwooleHttpMessageBridge\Request;
use PHPUnit\Framework\TestCase;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
class RequestTest extends TestCase
{
    public function testCreateRequest()
    {
        $swooleRequest = $this->createMock(\Swoole\Http\Request::class);
        $swooleRequest->header = [
            'user-agent' => 'Swolle',
        ];

        $swooleRequest->server = [
            'request_method' => 'GET',
        ];

        $swooleRequest->get = [
            'k' => 'v',
        ];

        $request = Request::createFromSwooleRequest($swooleRequest);

        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\Request::class, $request);
        $this->assertSame(1, $request->headers->count());
        $this->assertSame(2, $request->server->count());
        $this->assertSame('GET', $request->getMethod());
        $this->assertArrayHasKey('HTTP_USER_AGENT', $request->server->all());
    }

    public function testCreateRequestWithContent()
    {
        $swooleRequest = $this->createMock(\Swoole\Http\Request::class);
        $swooleRequest->header = [
            'user-agent' => 'Swoole',
            'content-type' => 'application/x-www-form-urlencoded',
        ];

        $swooleRequest->server = [
            'request_method' => 'PUT',
        ];

        $swooleRequest->method('rawContent')
            ->willReturn('a=av&b=bv');

        $request = Request::createFromSwooleRequest($swooleRequest);

        $this->assertSame(2, $request->request->count());
    }
}
