<?php declare(strict_types=1);

namespace tiFy\Contracts\Template;

use Psr\Http\Message\ServerRequestInterface;

interface FactoryHttpController extends FactoryAwareTrait
{
    /**
     * Répartition de la requête selon la méthode utilisée.
     *
     * @param ServerRequestInterface $psrRequest Instance de la requête Psr.
     *
     * @return mixed
     */
    public function __invoke(ServerRequestInterface $psrRequest);

    /**
     * Message de notification.
     * @see \tiFy\Partial\Driver\Notice\Notice
     *
     * @param string $message Message de notification
     * @param string $type Type de message. error|info|success|warning.
     * @param array $attrs Liste des attributs de personnalisation.
     *
     * @return string
     */
    public function notice(string $message, string $type = 'info', array $attrs = []): string;
}