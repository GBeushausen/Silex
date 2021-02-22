<?php

/*
 * This file is part of the PrestoPHP framework.
 *
 * (c) Gunnar Beushausen <gunnar@prestophp.com>	
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrestoPHP\Provider\Routing;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Matcher\RedirectableUrlMatcher as BaseRedirectableUrlMatcher;

/**
 * Implements the RedirectableUrlMatcherInterface for PrestoPHP.
 *
 * @author Gunnar Beushausen <gunnar@prestophp.com>
 * @author Fabien Potencier <fabien@symfony.com>
 */
class RedirectableUrlMatcher extends BaseRedirectableUrlMatcher
{
    /**
     * {@inheritdoc}
     */
    public function redirect($path, $route, $scheme = null)
    {
        $url = $this->context->getBaseUrl().$path;
        $query = $this->context->getQueryString() ?: '';

        if ('' !== $query) {
            $url .= '?'.$query;
        }

        if ($this->context->getHost()) {
            if ($scheme) {
                $port = '';
                if ('http' === $scheme && 80 != $this->context->getHttpPort()) {
                    $port = ':'.$this->context->getHttpPort();
                } elseif ('https' === $scheme && 443 != $this->context->getHttpsPort()) {
                    $port = ':'.$this->context->getHttpsPort();
                }

                $url = $scheme.'://'.$this->context->getHost().$port.$url;
            }
        }

        return [
            '_controller' => function ($url) { return new RedirectResponse($url, 301); },
            '_route' => $route,
            'url' => $url,
        ];
    }
}
