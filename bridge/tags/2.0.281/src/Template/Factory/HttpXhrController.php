<?php declare(strict_types=1);

namespace tiFy\Template\Factory;

use Exception;
use Psr\Http\Message\ServerRequestInterface;
use tiFy\Contracts\Template\FactoryHttpXhrController as FactoryHttpXhrControllerContract;
use League\Route\Http\Exception\MethodNotAllowedException;

class HttpXhrController extends HttpController implements FactoryHttpXhrControllerContract
{
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
                throw new MethodNotAllowedException([], $e->getMessage());
            }
        } elseif (method_exists($this, $method)) {
            $response = $this->{$method}($psrRequest);
        }

        if (is_null($response)) {
            throw new MethodNotAllowedException();
        }

        return $response;
    }
}