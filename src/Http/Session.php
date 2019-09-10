<?php declare(strict_types=1);

namespace tiFy\Http;

use Psr\Container\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Session\Session as BaseSession;
use tiFy\Contracts\Http\{Session as SessionContract, SessionFlashBag as SessionFlashbagContract};

class Session extends BaseSession implements SessionContract
{
    /**
     * Instance du conteneur d'injection de dépendances.
     * @var Container
     */
    protected $container;

    /**
     * Instance du gestionnaire d'attributs de session ephémères.
     * @var SessionFlashbagContract
     */
    protected $flashBag;

    /**
     * CONSTRUCTEUR.
     *
     * @param Container $container Instance du conteneur d'injection de dépendances.
     *
     * @return void
     */
    public function __construct(?Container $container = null)
    {
        $this->container = $container;

        parent::__construct();

        $this->registerBag($this->flashBag = new SessionFlashBag());
    }


    /**
     * @inheritDoc
     */
    public function flash($key = null, $value = null)
    {
        $flash = $this->getFlashBag();

        if (is_null($key)) {
            return $flash;
        } elseif (is_array($key)) {
            foreach($key as $k => $v) {
                $flash->add($k, $v);
            }
            return $this;
        } elseif (is_string($key)) {
            return $flash->get($key, is_array($value) ? $value : (array)$value);
        }

        return null;
    }

    /**
     * {@inheritDoc}
     *
     * @return SessionFlashbagContract
     */
    public function getFlashBag()
    {
        return $this->flashBag;
    }

    /**
     * @inheritDoc
     */
    public function setContainer(Container $container): SessionContract
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @inheritDoc
     * @todo
     */
    public function reflash(?array $keys = null): SessionContract
    {
        return !is_null($keys) ? $this->flash($this->flash()->all()) : $this->flash($this->flash()->only($keys)) ;
    }
}
