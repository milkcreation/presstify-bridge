<?php declare(strict_types=1);

namespace tiFy\Contracts\Template;

use Psr\Http\Message\ServerRequestInterface;
use League\Route\Http\Exception\MethodNotAllowedException;

interface FactoryHttpXhrController extends FactoryAwareTrait, FactoryHttpController
{
    /**
     * Répartition de la requête selon la méthode utilisée.
     *
     * @param ServerRequestInterface $psrRequest Instance de la requête Psr.
     *
     * @throws MethodNotAllowedException
     *
     * @return mixed
     */
    public function __invoke(ServerRequestInterface $psrRequest);
}