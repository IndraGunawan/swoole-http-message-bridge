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

use Swoole\Http\Request as SwooleRequest;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request as SfRequest;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
class Request
{
    /**
     * Creates new Symfony request with values from SwooleRequest.
     *
     * @param SwooleRequest $swooleRequest
     *
     * @return SfRequest
     */
    public static function createFromSwooleRequest(SwooleRequest $swooleRequest): SfRequest
    {
        $headers = array_combine(array_map(function ($key) {
            return 'HTTP_'.str_replace('-', '_', $key);
        }, array_keys($swooleRequest->header)), array_values($swooleRequest->header));

        $server = array_change_key_case(array_merge($swooleRequest->server, $headers), CASE_UPPER);

        if ($trustedProxies = $server['TRUSTED_PROXIES'] ?? false) {
            SfRequest::setTrustedProxies(explode(',', $trustedProxies), self::HEADER_X_FORWARDED_ALL ^ self::HEADER_X_FORWARDED_HOST);
        }

        if ($trustedHosts = $server['TRUSTED_HOSTS'] ?? false) {
            SfRequest::setTrustedHosts(explode(',', $trustedHosts));
        }

        $sfRequest = new SfRequest(
            $swooleRequest->get ?? [],
            $swooleRequest->post ?? [],
            [],
            $swooleRequest->cookie ?? [],
            $swooleRequest->files ?? [],
            $server,
            $swooleRequest->rawContent()
        );

        if (0 === strpos($sfRequest->headers->get('CONTENT_TYPE'), 'application/x-www-form-urlencoded')
            && in_array(strtoupper($sfRequest->server->get('REQUEST_METHOD', 'GET')), ['PUT', 'DELETE', 'PATCH'], true)
        ) {
            parse_str($sfRequest->getContent(), $data);
            $sfRequest->request = new ParameterBag($data);
        }

        return $sfRequest;
    }
}
