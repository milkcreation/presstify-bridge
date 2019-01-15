<?php

namespace tiFy\Kernel\Http;

use Illuminate\Http\Request as IlluminateHttpRequest;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Psr\Http\Message\ServerRequestInterface;
use tiFy\Contracts\Kernel\Request as RequestContract;

class Request extends IlluminateHttpRequest implements RequestContract
{
    /**
     * @inheritdoc
     */
    public function getProperty($property = '')
    {
        switch (strtolower($property)) :
            default :
                return $this;
                break;
            case 'post' :
            case 'request' :
                return $this->request;
                break;
            case 'get' :
            case 'query' :
                return $this->query;
                break;
            case 'cookie' :
            case 'cookies' :
                return $this->cookies;
                break;
            case 'attributes' :
                return $this->attributes;
                break;
            case 'files' :
                return $this->files;
                break;
            case 'server' :
                return $this->server;
                break;
            case 'headers' :
                return $this->headers;
                break;
        endswitch;
    }

    /**
     * @inheritdoc
     */
    public function createFromPsr(ServerRequestInterface $psrRequest)
    {
        $request = (new HttpFoundationFactory())->createRequest($psrRequest);

        return self::createFromBase($request);
    }
}