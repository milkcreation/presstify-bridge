<?php declare(strict_types=1);

namespace tiFy\Template\Factory;

use Psr\Http\Message\ServerRequestInterface;
use tiFy\Contracts\Template\FactoryHttpController as FactoryHttpControllerContract;
use Zend\Diactoros\Response;

class HttpController implements FactoryHttpControllerContract
{
    use FactoryAwareTrait;

    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequestInterface $psrRequest)
    {
        $method = strtolower($psrRequest->getMethod());
        $response = null;

        if (method_exists($this, $method)) {
            $this->factory->prepare();
            $response = $this->{$method}($psrRequest);
        }

        if (is_null($response)) {
            $response = new Response('php://memory', 405);
        }

        return $response;
    }

    /**
     * @inheritDoc
     */
    public function notice($message, $type = 'info', $attrs = []): string
    {
        return (string)partial('notice', array_merge([
            'type'    => $type,
            'content' => $message
        ], $attrs));
    }
}