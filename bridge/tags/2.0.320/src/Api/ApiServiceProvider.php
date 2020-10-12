<?php declare(strict_types=1);

namespace tiFy\Api;

use tiFy\Api\Facebook\Facebook;
use tiFy\Api\Facebook\FacebookLoginProfile;
use tiFy\Api\Facebook\FacebookLoginSignin;
use tiFy\Api\Facebook\FacebookLoginSignup;
use tiFy\Api\Recaptcha\Recaptcha;
use tiFy\Api\Youtube\Youtube;
use tiFy\Container\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * {@internal Permet le chargement différé des services qualifié.}
     * @var string[]
     */
    protected $provides = [
        'api',
        'api.facebook',
        'api.facebook.login.profile',
        'api.facebook.login.signin',
        'api.facebook.login.signup',
        'api.recaptcha',
        'api.youtube',
    ];

    /**
     * @inheritdoc
     */
    public function boot(): void
    {
        add_action('after_setup_theme', function () {
            $this->getContainer()->get('api');
        });
    }

    /**
     * @inheritdoc
     */
    public function register(): void
    {
        $this->getContainer()->share('api', function () {
            return new Api($this->getContainer());
        });

        $this->getContainer()->share('api.facebook', function () {
            return Facebook::create(config('api.facebook', []), $this->getContainer());
        });

        $this->getContainer()->share('api.facebook.login.profile', function () {
            $concrete = config('api.facebook.profile', FacebookLoginProfile::class);
            return new $concrete($this->getContainer()->get('api.facebook'));
        });

        $this->getContainer()->share('api.facebook.login.signin', function () {
            $concrete = config('api.facebook.signin', FacebookLoginSignin::class);
            return new $concrete($this->getContainer()->get('api.facebook'));
        });

        $this->getContainer()->share('api.facebook.login.signup', function () {
            $concrete = config('api.facebook.signup', FacebookLoginSignup::class);
            return new $concrete($this->getContainer()->get('api.facebook'));
        });

        $this->getContainer()->share('api.recaptcha', function () {
            return Recaptcha::instance(config('api.recaptcha', []));
        });

        $this->getContainer()->share('api.youtube', function () {
            return Youtube::create(config('api.youtube', []));
        });
    }
}