<?php declare(strict_types=1);

namespace tiFy\Template\Factory;

use Exception;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ServerRequestInterface;
use tiFy\Contracts\Template\FactoryHttpController as FactoryHttpControllerContract;
use tiFy\Support\Proxy\Partial;

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

        if ($action = $this->factory->request()->input($this->factory->actions()->getIndex())) {
            try {
                $response = $this->factory->actions()->execute($action, func_get_args());
            } catch (Exception $e) {
                $response = new Response($e->getMessage(), 405);
            }
        } elseif (method_exists($this, $method)) {
            $response = $this->{$method}($psrRequest);
        }

        return is_null($response) ? new Response('php://memory', 405) : $response;
    }

    /**
     * @inheritDoc
     */
    public function notice($message, $type = 'info', $attrs = []): string
    {
        return Partial::get('notice', array_merge([
            'type'    => $type,
            'content' => $message
        ], $attrs))->render();
    }
}