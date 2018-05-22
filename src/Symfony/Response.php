<?php

/*
 * This file is part of the swoole-http-message-bridge
 *
 * (c) Indra Gunawan <hello@indra.my.id>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indragunawan\SwooleHttpMessageBridge\Symfony;

use Swoole\Http\Response as SwooleResponse;
use Symfony\Component\HttpFoundation\Response as SfResponse;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
class Response
{
    /**
     * Writes SwooleResponse with values from SfResponse.
     *
     * @param SwooleResponse $swooleResponse
     * @param SfResponse     $sfResponse
     * @param bool           $end
     */
    public static function writeSwooleResponse(SwooleResponse $swooleResponse, SfResponse $sfResponse, $end = true)
    {
        // write headers
        self::writeHeaders($swooleResponse, $sfResponse);

        if (true === $end) {
            $swooleResponse->end($sfResponse->getContent());
        } else {
            $swooleResponse->write($sfResponse->getContent());
        }
    }

    /**
     * Writes SwooleResponse headers with values from SfResponse headers.
     *
     * @param SwooleResponse $swooleResponse
     * @param SfResponse     $sfResponse
     */
    protected static function writeHeaders(SwooleResponse $swooleResponse, SfResponse $sfResponse)
    {
        // headers have already been sent by the developer
        if (headers_sent()) {
            return;
        }

        // headers
        foreach ($sfResponse->headers->allPreserveCaseWithoutCookies() as $name => $values) {
            foreach ($values as $value) {
                $swooleResponse->header($name, $value);
            }
        }

        // status
        $swooleResponse->status($sfResponse->getStatusCode());

        // cookies
        foreach ($sfResponse->headers->getCookies() as $cookie) {
            $swooleResponse->cookie($cookie->getName(), $cookie->getValue(), $cookie->getExpiresTime(), $cookie->getPath(), $cookie->getDomain(), $cookie->isSecure(), $cookie->isHttpOnly());
        }
    }
}
