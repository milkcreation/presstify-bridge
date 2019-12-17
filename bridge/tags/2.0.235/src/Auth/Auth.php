<?php declare(strict_types=1);

namespace tiFy\Auth;

use Psr\Container\ContainerInterface as Container;
use tiFy\Contracts\Auth\{Auth as AuthContract, Signin as SigninContract, Signup as SignupContract};
use tiFy\Auth\{Signin\Signin, Signup\Signup};

class Auth implements AuthContract
{
    /**
     * Instance du conteneur d'injection de dépendances.
     * @var Container|null
     */
    protected $container;

    /**
     * Liste de instance de formulaire d'authentification.
     * @var SigninContract|array
     */
    protected $signin = [];

    /**
     * Liste de instance de formulaire d'authentification.
     * @var SigninContract|array
     */
    protected $signup = [];

    /**
     * CONSTRUCTEUR.
     *
     * @param Container|null $container Conteneur d'injection de dépendances.
     *
     * @return void
     */
    public function __construct(?Container $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function getContainer(): ?Container
    {
        return $this->container;
    }

    /**
     * @inheritDoc
     */
    public function registerSignin(string $name, array $attrs = []): SigninContract
    {
        if (isset($this->signin[$name])) {
            return $this->signin[$name];
        }

        $signin = ($container = $this->getContainer()) ? new Signin($this) : $container->get('auth.signin');

        return $this->signin[$name] = $signin->prepare($name, $attrs);
    }

    /**
     * @inheritDoc
     */
    public function registerSignup(string $name, array $attrs = []): SignupContract
    {
        if (isset($this->signin[$name])) {
            return $this->signin[$name];
        }

        $signin = ($container = $this->getContainer()) ? new Signup($this) : $container->get('auth.signin');

        return $this->signin[$name] = $signin->prepare($name, $attrs);
    }

    /**
     * @inheritDoc
     */
    public function resourcesDir(string $path = ''): string
    {
        $path = $path ? '/Resources/' . ltrim($path, '/') : '/Resources';

        return file_exists(__DIR__ . $path) ? __DIR__ . $path : '';
    }

    /**
     * @inheritDoc
     */
    public function resourcesUrl(string $path = ''): string
    {
        $cinfo = class_info($this);
        $path = '/Resources/' . ltrim($path, '/');

        return file_exists($cinfo->getDirname() . $path) ? class_info($this)->getUrl() . $path : '';
    }

    /**
     * @inheritDoc
     */
    public function signin(string $name): ?SigninContract
    {
        return $this->signin[$name] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function signup(string $name): ?SignupContract
    {
        return $this->signup[$name] ?? null;
    }
}