<?php declare(strict_types=1);

namespace tiFy\Wordpress;

use tiFy\Container\ServiceProvider;
use tiFy\Wordpress\Asset\Asset;
use tiFy\Wordpress\Auth\Auth;
use tiFy\Wordpress\Column\Column;
use tiFy\Wordpress\Cookie\Cookie;
use tiFy\Wordpress\Database\Database;
use tiFy\Wordpress\Db\Db;
use tiFy\Wordpress\Filesystem\Filesystem;
use tiFy\Wordpress\Field\Field;
use tiFy\Wordpress\Form\Form;
use tiFy\Wordpress\Mail\Mail;
use tiFy\Wordpress\Media\Download;
use tiFy\Wordpress\Media\Media;
use tiFy\Wordpress\Media\Upload;
use tiFy\Wordpress\Metabox\Metabox;
use tiFy\Wordpress\Options\Options;
use tiFy\Wordpress\PageHook\PageHook;
use tiFy\Wordpress\Partial\Partial;
use tiFy\Wordpress\PostType\PostType;
use tiFy\Wordpress\Query\QueryPost;
use tiFy\Wordpress\Query\QueryPosts;
use tiFy\Wordpress\Query\QueryTerm;
use tiFy\Wordpress\Query\QueryTerms;
use tiFy\Wordpress\Query\QueryUser;
use tiFy\Wordpress\Query\QueryUsers;
use tiFy\Wordpress\Routing\Routing;
use tiFy\Wordpress\Routing\WpQuery;
use tiFy\Wordpress\Routing\WpScreen;
use tiFy\Wordpress\Taxonomy\Taxonomy;
use tiFy\Wordpress\Template\Template;
use tiFy\Wordpress\User\User;
use tiFy\Wordpress\User\Role\RoleFactory;
use WP_Query;
use WP_Post;
use WP_Screen;
use WP_Term;
use WP_Term_Query;
use WP_User;
use WP_User_Query;

class WordpressServiceProvider extends ServiceProvider
{
    /**
     * Liste des services fournis.
     * @var array
     */
    protected $provides = [
        'wp',
        'wp.asset',
        'wp.auth',
        'wp.column',
        'wp.cookie',
        'wp.database',
        'wp.db',
        'wp.filesystem',
        'wp.field',
        'wp.form',
        'wp.login-redirect',
        'wp.mail',
        'wp.media',
        'wp.media.download',
        'wp.media.upload',
        'wp.metabox',
        'wp.page-hook',
        'wp.partial',
        'wp.options',
        'wp.post-type',
        'wp.query.post',
        'wp.query.posts',
        'wp.query.term',
        'wp.query.terms',
        'wp.query.user',
        'wp.query.users',
        'wp.routing',
        'wp.taxonomy',
        'wp.template',
        'wp.user',
        'wp.wp_query',
        'wp.wp_screen',
    ];

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        require_once __DIR__ . '/helpers.php';

        add_action('after_setup_theme', function () {
            /* @var Wordpress $wp */
            $wp = $this->getContainer()->get('wp');

            if ($wp->is()) {
                if ($this->getContainer()->has('router')) {
                    $this->getContainer()->get('wp.routing');
                }

                if ($this->getContainer()->has('asset')) {
                    $this->getContainer()->get('wp.asset');
                }

                $this->getContainer()->get('wp.auth');

                if ($this->getContainer()->has('column')) {
                    $this->getContainer()->get('wp.column');
                }

                if ($this->getContainer()->has('cookie')) {
                    $this->getContainer()->get('wp.cookie');
                }

                if ($this->getContainer()->has('cron')) {
                    $this->getContainer()->get('cron');
                }

                if ($this->getContainer()->has('database')) {
                    $this->getContainer()->get('wp.database');
                }

                if ($this->getContainer()->has('db')) {
                    $this->getContainer()->get('wp.db');
                }

                if ($this->getContainer()->has('field')) {
                    $this->getContainer()->get('wp.field');
                }

                if ($this->getContainer()->has('form')) {
                    $this->getContainer()->get('wp.form');
                }

                if ($this->getContainer()->has('mailer')) {
                    $this->getContainer()->get('wp.mail');
                }

                $this->getContainer()->get('wp.media');

                if ($this->getContainer()->has('metabox')) {
                    $this->getContainer()->get('wp.metabox');
                }

                $this->getContainer()->get('wp.page-hook');

                if ($this->getContainer()->has('options')) {
                    $this->getContainer()->get('wp.options');
                }

                if ($this->getContainer()->has('partial')) {
                    $this->getContainer()->get('wp.partial');
                }

                if ($this->getContainer()->has('post-type')) {
                    $this->getContainer()->get('wp.post-type');
                }

                if ($this->getContainer()->has('validator')) {
                    $this->getContainer()->get('validator');
                }

                if ($this->getContainer()->has('storage')) {
                    $this->getContainer()->get('wp.filesystem');
                }

                if ($this->getContainer()->has('taxonomy')) {
                    $this->getContainer()->get('wp.taxonomy');
                }

                if ($this->getContainer()->has('template')) {
                    $this->getContainer()->get('wp.template');
                }

                if ($this->getContainer()->has('user')) {
                    $this->getContainer()->get('wp.user');
                    $this->getContainer()->add('user.role.factory', function () {
                        return new RoleFactory();
                    });
                }
            }
        }, 1);
    }

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->registerManager();
        $this->registerAsset();
        $this->registerAuth();
        $this->registerColumn();
        $this->registerCookie();
        $this->registerDatabase();
        $this->registerFilesystem();
        $this->registerField();
        $this->registerForm();
        $this->registerMail();
        $this->registerMedia();
        $this->registerMetabox();
        $this->registerOptions();
        $this->registerPageHook();
        $this->registerPartial();
        $this->registerPostType();
        $this->registerQuery();
        $this->registerRouting();
        $this->registerTaxonomy();
        $this->registerTemplate();
        $this->registerUser();
    }

    /**
     * Déclaration du gestionnaire d'assets.
     *
     * @return void
     */
    public function registerAsset(): void
    {
        $this->getContainer()->share('wp.asset', function () {
            return new Asset($this->getContainer()->get('asset'));
        });
    }
    /**
     * Déclaration du gestionnaire d'authentification.
     *
     * @return void
     */
    public function registerAuth(): void
    {
        $this->getContainer()->share('wp.auth', function () {
            return new Auth();
        });
    }


    /**
     * Déclaration du controleur des colonnes.
     *
     * @return void
     */
    public function registerColumn(): void
    {
        $this->getContainer()->share('wp.column', function () {
            return new Column($this->getContainer()->get('column'));
        });
    }

    /**
     * Déclaration du controleur des cookies.
     *
     * @return void
     */
    public function registerCookie(): void
    {
        $this->getContainer()->share('wp.cookie', function () {
            return new Cookie($this->getContainer()->get('cookie'));
        });
    }

    /**
     * Déclaration du controleur de base de données.
     *
     * @return void
     */
    public function registerDatabase(): void
    {
        $this->getContainer()->share('wp.database', function () {
            return new Database($this->getContainer()->get('database'));
        });
        /**
         * @todo supprimer toutes les occurences.
         * @deprecated
         */
        $this->getContainer()->share('wp.db', function () {
            return new Db($this->getContainer()->get('db'));
        });
    }

    /**
     * Déclaration du controleur de système de fichiers.
     *
     * @return void
     */
    public function registerFilesystem(): void
    {
        $this->getContainer()->share('wp.filesystem', function () {
            return new Filesystem($this->getContainer()->get('storage'));
        });
    }

    /**
     * Déclaration du controleur des champs.
     *
     * @return void
     */
    public function registerField(): void
    {
        $this->getContainer()->share('wp.field', function () {
            return new Field($this->getContainer()->get('field'));
        });
    }

    /**
     * Déclaration du controleur des formulaires.
     *
     * @return void
     */
    public function registerForm(): void
    {
        $this->getContainer()->share('wp.form', function () {
            return new Form($this->getContainer()->get('form'));
        });
    }

    /**
     * Déclaration du controleur de gestion de Wordpress.
     *
     * @return void
     */
    public function registerMail(): void
    {
        $this->getContainer()->share('wp.mail', function () {
            return new Mail();
        });
    }

    /**
     * Déclaration du controleur de gestion de Wordpress.
     *
     * @return void
     */
    public function registerManager(): void
    {
        $this->getContainer()->share('wp', function () {
            return new Wordpress();
        });
    }

    /**
     * Déclaration du controleur de gestion des Medias.
     *
     * @return void
     */
    public function registerMedia(): void
    {
        $this->getContainer()->share('wp.media', function () {
            return new Media();
        });

        $this->getContainer()->share('wp.media.download', function () {
            return new Download();
        });

        $this->getContainer()->share('wp.media.upload', function () {
            return new Upload();
        });
    }

    /**
     * Déclaration du controleur de gestion de metaboxes.
     *
     * @return void
     */
    public function registerMetabox(): void
    {
        $this->getContainer()->share('wp.metabox', function () {
            return new Metabox($this->getContainer()->get('metabox'));
        });
    }

    /**
     * Déclaration du controleur des options
     *
     * @return void
     */
    public function registerOptions(): void
    {
        $this->getContainer()->share('wp.options', function () {
            return new Options($this->getContainer()->get('options'));
        });
    }

    /**
     * Déclaration du controleur des pages d'accroche.
     *
     * @return void
     */
    public function registerPageHook(): void
    {
        $this->getContainer()->share('wp.page-hook', function () {
            return new PageHook();
        });
    }

    /**
     * Déclaration du controleur des gabarits d'affichage.
     *
     * @return void
     */
    public function registerPartial(): void
    {
        $this->getContainer()->share('wp.partial', function () {
            return new Partial($this->getContainer()->get('partial'));
        });
    }

    /**
     * Déclaration du controleur des types de contenu.
     *
     * @return void
     */
    public function registerPostType(): void
    {
        $this->getContainer()->share('wp.post-type', function () {
            return new PostType($this->getContainer()->get('post-type'));
        });
    }

    /**
     * Déclaration des controleurs de requête de récupération des éléments Wordpress.
     *
     * @return void
     */
    public function registerQuery(): void
    {
        $this->getContainer()->add('wp.query.posts', function (?WP_Query $wp_query = null) {
            return !is_null($wp_query) ? new QueryPosts($wp_query) : QueryPosts::createFromGlobals();
        });

        $this->getContainer()->add('wp.query.post', function (?WP_Post $wp_post = null) {
            return !is_null($wp_post) ? new QueryPost($wp_post) : QueryPost::createFromGlobal();
        });

        $this->getContainer()->add('wp.query.terms', function (WP_Term_Query $wp_term_query) {
            return new QueryTerms($wp_term_query);
        });

        $this->getContainer()->add('wp.query.term', function (WP_Term $wp_term) {
            return new QueryTerm($wp_term);
        });

        $this->getContainer()->add('wp.query.users', function (WP_User_Query $wp_user_query) {
            return new QueryUsers($wp_user_query);
        });

        $this->getContainer()->add('wp.query.user', function (?WP_User $wp_user = null) {
            return !is_null($wp_user) ? new QueryUser($wp_user) : QueryUser::createFromGlobal();
        });
    }

    /**
     * Déclaration des controleurs de routage.
     *
     * @return void
     */
    public function registerRouting(): void
    {
        $this->getContainer()->share('wp.routing', function () {
            return new Routing($this->getContainer()->get('router'));
        });

        $this->getContainer()->share('wp.wp_query', function () {
            return new WpQuery();
        });

        $this->getContainer()->add('wp.wp_screen', function (?WP_Screen $wp_screen = null) {
            return new WpScreen($wp_screen);
        });
    }

    /**
     * Déclaration du controleur de taxonomie.
     *
     * @return void
     */
    public function registerTaxonomy(): void
    {
        $this->getContainer()->share('wp.taxonomy', function () {
            return new Taxonomy($this->getContainer()->get('taxonomy'));
        });
    }

    /**
     * Déclaration du controleur de gabarit.
     *
     * @return void
     */
    public function registerTemplate(): void
    {
        $this->getContainer()->share('wp.template', function () {
            return new Template($this->getContainer()->get('template'));
        });
    }

    /**
     * Déclaration du controleur utilisateur.
     *
     * @return void
     */
    public function registerUser(): void
    {
        $this->getContainer()->share('wp.user', function () {
            return new User($this->getContainer()->get('user'));
        });
    }
}