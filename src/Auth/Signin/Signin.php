<?php declare(strict_types=1);

namespace tiFy\Auth\Signin;

use tiFy\Contracts\{Form\FormFactory, View\PlatesEngine};
use tiFy\Contracts\Auth\{Auth, Signin as SigninContract};
use tiFy\Support\{Arr, ParamsBag, Proxy\Form, Proxy\Request, Proxy\View};

class Signin extends ParamsBag implements SigninContract
{
    /**
     * Instance du formulaire.
     * @var FormFactory
     */
    protected $form;

    /**
     * Instance du gestionnaire d'interface d'authentification.
     * @var Auth
     */
    protected $manager;

    /**
     * Nom de qualification.
     * @var string
     */
    protected $name = '';

    /**
     * Indicateur d'initialisation de la classe.
     * @var boolean
     */
    protected $prepared = false;

    /**
     * Instance du gestionnaire de gabarits d'affichage.
     * @var PlatesEngine
     */
    protected $viewer;

    /**
     * CONSTRUCTEUR.
     *
     * @param Auth $manager Instance du gestionnaire d'authentification.
     *
     * @return void
     */
    public function __construct(Auth $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return (string)$this->renderForm();
    }

    /**
     * @inheritDoc
     */
    public function boot(): void { }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [
            'form'          => [],
            'logout'        => [],
            'lost_password' => [],
            'roles'         => [],
            'redirect_url'  => '',
            'attempt'       => -1,
            'notices'       => [],
        ];
    }

    /**
     * @inheritDoc
     */
    public function form(): FormFactory
    {
        return $this->form;
    }

    /**
     * @inheritDoc
     */
    public function getAuthRedirectUrl(?string $redirect_url = null): string
    {
        if (!$redirect_url) {
            $redirect_url = $this->get('redirect_url', '');
        }

        return $redirect_url;
    }

    /**
     * @inheritDoc
     */
    public function getLogoutRedirectUrl(?string $redirect_url = null): string
    {
        if (!$redirect_url) {
            $redirect_url = $this->get('redirect_url', '');
        }

        return $redirect_url;
    }

    /**
     * @inheritDoc
     */
    public function getLogoutUrl(?string $redirect_url = null): string
    {
        $args = [];
        if ($redirect_url) {
            $args['redirect_to'] = $redirect_url;
        }
        $args['action'] = 'logout';
        $args['signin'] = $this->getName();

        $logout_url = add_query_arg($args, $this->get('redirect_url', site_url('wp-login.php', 'login')));
        $logout_url = wp_nonce_url($logout_url, 'signin-logout-' . $this->getName());

        return $logout_url;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getRoles(): array
    {
        return Arr::wrap($this->get('roles', []));
    }

    /**
     * @inheritDoc
     */
    public function handle(): void
    {
        if (!$signin = Request::input('signin', false)) {
            return;
        }

        if ($signin !== $this->getName()) {
            return;
        }
        switch ($action = Request::input('action', 'login')) {
            default :
                break;
            case 'login' :
                events()->trigger('user.signin.handle.login', [$this]);
                break;
            case 'logout' :
                events()->trigger('user.signin.handle.logout', [$this]);
                break;
        }
    }

    /**
     * @inheritDoc
     */
    public function hasRole($role): bool
    {
        return (!$roles = $this->getRoles()) ? true : !!array_intersect(Arr::wrap($role), $roles);
    }

    /**
     * @inheritDoc
     */
    public function manager(): Auth
    {
        return $this->manager;
    }

    /**
     * {@inheritDoc}
     *
     * @return SigninContract
     */
    public function parse(): SigninContract
    {
        parent::parse();

        $this->set([
            'name',
            $this->getName(),
            'form.action'      => '',
            'form.attrs.class' => sprintf($this->get('form.attrs.class', '%s'), 'Signin-auth'),
            'form.name'        => "Signin-auth--{$this->getName()}",
            'form.method'      => 'post'
        ]);

        if (!$this->has('form.attrs.id')) {
            $this->set('form.attrs.id', 'Signin-auth--' . $this->getName());
        }

        if ($fields = $this->get('form.fields', ['username', 'password', 'remember', 'submit'])) {
            foreach ($fields as $name => $attrs) {
                if (is_numeric($name)) {
                    $name = $attrs;
                    $attrs = [];
                }
                if (in_array($name, ['username', 'password', 'remember', 'submit'])) {
                    switch ($name) {
                        case 'username' :
                            $attrs = array_merge([
                                'attrs' => [
                                    'id'           => 'Signin-username--' . $this->getName(),
                                    'placeholder'  => __('Renseignez votre identifiant', 'tify'),
                                    'size'         => 20,
                                    'autocomplete' => 'username',
                                ],
                                'title' => __('Identifiant', 'tify'),
                                'type'  => 'text',
                            ], $attrs);

                            $attrs['name'] = 'log';
                            break;
                        case 'password' :
                            $attrs = array_merge([
                                'attrs' => [
                                    'id'           => 'Signin-password--' . $this->getName(),
                                    'placeholder'  => __('Renseignez votre mot de passe', 'tify'),
                                    'size'         => 20,
                                    'autocomplete' => 'current-password',
                                ],
                                'title' => __('Mot de passe', 'tify'),
                                'type'  => 'password',
                            ], $attrs);

                            $attrs['name'] = 'pwd';
                            break;
                        case 'remember' :
                            $attrs = array_merge([
                                'attrs' => [
                                    'id'    => 'Signin-remember--' . $this->getName(),
                                    'value' => 0,
                                ],
                                'title' => __('Se souvenir de moi', 'tify'),
                                'type'  => 'checkbox'
                            ], $attrs);

                            $attrs['name'] = 'remember';
                            break;
                        case 'submit' :
                            $attrs = array_merge([
                                'attrs'   => [
                                    'id' => 'Signin-submit--' . $this->getName(),
                                ],
                                'value' => __('Se connecter', 'tify'),
                                'type'    => 'submit',
                            ], $attrs);

                            $attrs['name'] = 'submit';
                            break;
                    }
                }

                $attrs['attrs']['class'] = sprintf(
                    $attrs['attrs']['class'] ?? '%s', 'Signin-input Signin-input--' . $name
                );

                $this->set("form.fields.{$name}", $attrs);
            }
        }

        $this->set([
            'logout'        => array_merge([
                'redirect' => '',
                'text'     => __('Déconnection', 'tify'),
                'class'    => 'Signin-logoutLink',
                'title'    => __('se déconnecter', 'tify'),
            ], $this->get('logout', [])),
            'lost_password' => array_merge([
                'redirect' => '',
                'content'  => __('Mot de passe oublié', 'tify'),
            ], $this->get('lost_password', [])),
        ]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function prepare(string $name, array $attrs = []): SigninContract
    {
        if (!$this->prepared) {
            $this->name = $name;

            $this->boot();

            $this->set($attrs)->parse();

            Form::set($this->getName(), $this->get('form', []));

            $this->form = Form::get($this->getName());

            $this->prepared = true;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function renderForm(): string
    {
        return (string)$this->viewer('form/signin', ['form' => $this->form()]);
    }

    /**
     * @inheritDoc
     */
    public function renderLogout(): string
    {
        return (string)$this->viewer('logout/logout', $this->get('logout'));
    }

    /**
     * @inheritDoc
     */
    public function renderLostpassword(): string
    {
        return (string)$this->viewer('lostpassword/lostpassword', $this->get('lost_password'));
    }

    /**
     * Instance du gestionnaire des gabarits d'affichage ou instance d'un gabarit d'affichage.
     * {@internal Si aucun argument n'est passé à la méthode, retourne l'intance du controleur principal.}
     * {@internal Sinon récupére le gabarit d'affichage et passe les variables en argument.}
     *
     * @param string|null view Nom de qualification du gabarit.
     * @param array $data Liste des variables passées en argument.
     *
     * @return PlatesEngine|string
     */
    public function viewer(?string $view = null, array $data = [])
    {
        if (!$this->viewer) {
            $this->viewer = View::getPlatesEngine([
                'directory' => $this->manager()->resourcesDir('/views/signin'),
                'factory'   => SigninView::class,
                'signin' => $this
            ]);
        }

        if (func_num_args() === 0) {
            return $this->viewer;
        }

        return $this->viewer->render($view, $data);
    }
}