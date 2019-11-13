<?php declare(strict_types=1);

namespace tiFy\Template\Factory;

use BadMethodCallException;
use Exception;
use Illuminate\Database\Eloquent\Model;
use tiFy\Contracts\Template\{FactoryDb as FactoryDbContract, TemplateFactory};

class Db implements FactoryDbContract
{
    use FactoryAwareTrait;

    /**
     * Modèle de délégation d'appel des méthodes de la classe.
     * @var Model|object|null
     */
    protected $delegate;

    /**
     * Instance du gabarit d'affichage.
     * @var TemplateFactory
     */
    protected $factory;

    /**
     * Délégation d'appel des méthode du modèle associé.
     *
     * @param string $name Nom de qualification de la mèthode.
     * @param array $arguments Liste des variables passés en arguments à la méthode.
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        try {
            return $this->delegate->$name(...$arguments);
        } catch (Exception $e) {
            throw new BadMethodCallException(sprintf(__('La méthode %s n\'est pas disponible.', 'tify'), $name));
        }
    }

    /**
     * @inheritDoc
     */
    public function delegate()
    {
        return $this->delegate;
    }

    /**
     * @inheritDoc
     */
    public function setDelegate(object $delegate): FactoryDbContract
    {
        $this->delegate = $delegate;

        return $this;
    }
}