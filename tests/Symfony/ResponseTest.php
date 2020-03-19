<?php

/*
 * This file is part of the swoole-http-message-bridge
 *
 * (c) Indra Gunawan <hello@indra.my.id>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indragunawan\SwooleHttpMessageBridge\Tests\Symfony;

use Indragunawan\SwooleHttpMessageBridge\Symfony\Response;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response as SfResponse;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
class ResponseTest extends TestCase
{
    public function testWriteResponseWithEnd()
    {
        $sfResponse = new SfResponse();

        $swooleResponse = $this->createMock(\Swoole\Http\Response::class);

        $swooleResponse->expects($this->once())->method('end');
        Response::writeSwooleResponse($swooleResponse, $sfResponse, true);
    }

    public function testWriteResponseWithoutEnd()
    {
        $sfResponse = new SfResponse();

        $swooleResponse = $this->createMock(\Swoole\Http\Response::class);

        $swooleResponse->expects($this->once())->method('write');
        Response::writeSwooleResponse($swooleResponse, $sfResponse, false);
    }
}
