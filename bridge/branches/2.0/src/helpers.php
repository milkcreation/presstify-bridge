<?php declare(strict_types=1);

use App\App;
use Illuminate\Database\Query\Builder as LaraDatabaseQueryBuilder;
use tiFy\Contracts\Asset\Asset;
use tiFy\Contracts\Container\Container;
use tiFy\Contracts\Cron\CronJob;
use tiFy\Contracts\Cron\CronManager;
use tiFy\Contracts\Database\Database;
use tiFy\Contracts\Db\DbFactory;
use tiFy\Contracts\Db\DbManager;
use tiFy\Contracts\Field\Field;
use tiFy\Contracts\Field\FieldFactory;
use tiFy\Contracts\Filesystem\Filesystem;
use tiFy\Contracts\Filesystem\StorageManager;
use tiFy\Contracts\Form\FormFactory;
use tiFy\Contracts\Form\FormManager;
use tiFy\Contracts\Http\RedirectResponse;
use tiFy\Contracts\Http\Request;
use tiFy\Contracts\Kernel\ClassLoader;
use tiFy\Contracts\Kernel\Config;
use tiFy\Contracts\Kernel\EventsManager;
use tiFy\Contracts\Kernel\Path;
use tiFy\Contracts\Log\LogManager;
use tiFy\Contracts\Partial\PartialFactory;
use tiFy\Contracts\Partial\Partial;
use tiFy\Contracts\PostType\PostTypeFactory;
use tiFy\Contracts\PostType\PostTypeManager;
use tiFy\Contracts\Routing\Redirector;
use tiFy\Contracts\Routing\Route;
use tiFy\Contracts\Routing\Router;
use tiFy\Contracts\Routing\Url;
use tiFy\Contracts\Routing\UrlFactory;
use tiFy\Contracts\Support\ClassInfo;
use tiFy\Contracts\Support\ParamsBag;
use tiFy\Contracts\Taxonomy\TaxonomyFactory;
use tiFy\Contracts\Taxonomy\TaxonomyManager;
use tiFy\Contracts\Template\TemplateFactory;
use tiFy\Contracts\Template\TemplateManager;
use tiFy\Contracts\User\User;
use tiFy\Contracts\Validation\Validator;
use tiFy\Contracts\View\ViewController;
use tiFy\Contracts\View\ViewEngine;
use tiFy\tiFy;

if (!function_exists('app')) {
    /**
     * App - Controleur de l'application.
     * {@internal Si $abstract est null > Retourne l'instance de l'appication.}
     * {@internal Si $abstract est qualifié > Retourne la résolution du service qualifié.}
     *
     * @param string|null $abstract Nom de qualification du service.
     * @param array $args Liste des variables passé en arguments lors de la résolution du service.
     *
     * @return App|mixed
     */
    function app($abstract = null, $args = [])
    {
        /* @var App $factory */
        $factory = container('app');

        if (is_null($abstract)) {
            return $factory;
        }
        return $factory->get($abstract, $args);
    }
}

if (!function_exists('asset')) {
    /**
     * Assets - Gestionnaire des assets.
     *
     * @return Asset
     */
    function asset(): Asset
    {
        return app('asset');
    }
}

if (!function_exists('class_info')) {
    /**
     * ClassInfo - Controleur d'informations sur une classe.
     *
     * @param string|object Nom complet ou instance de la classe.
     *
     * @return ClassInfo
     */
    function class_info($class): ClassInfo
    {
        return app('class-info', [$class]);
    }
}

if (!function_exists('class_loader')) {
    /**
     * ClassLoader - Controleur de déclaration d'espaces de nom et d'inclusion de fichier automatique.
     *
     * @return ClassLoader
     */
    function class_loader(): ClassLoader
    {
        return container('class-loader');
    }
}

if (!function_exists('config')) {
    /**
     * Controleur de configuration.
     * {@internal
     * - null $key Retourne l'instance du controleur de configuration.
     * - array $key Définition d'attributs de configuration.
     * - string $key Récupération de la valeur d'un attribut de configuration.
     * }
     *
     * @param null|array|string Clé d'indice (Syntaxe à point permise)|Liste des attributs de configuration à définir.
     * @param mixed $default Valeur de retour par défaut lors de la récupération d'un attribut.
     *
     * @return Config|mixed
     */
    function config($key = null, $default = null)
    {
        /* @var Config $factory */
        $factory = container('config');

        if (is_null($key)) {
            return $factory;
        } elseif (is_array($key)) {
            return $factory->set($key);
        } else {
            return $factory->get($key, $default);
        }
    }
}

if (!function_exists('container')) {
    /**
     * Container - Controleur d'injection de dépendances.
     * {@internal Si $alias est null > Retourne la classe de rappel du controleur.}
     *
     * @param string $abstract Nom de qualification du service à récupérer.
     *
     * @return Container|mixed
     */
    function container($abstract = null)
    {
        $factory = tiFy::instance();

        if (is_null($abstract)) {
            return $factory;
        }
        return $factory->get($abstract);
    }
}

if (!function_exists('cron')) {
    /**
     * Récupération du gestionnaire de tâches planifiées ou d'une instance d'un tâche déclarée.
     *
     * @param null|string $name Nom de qualification de la tâche déclarée.
     *
     * @return CronManager|CronJob|null
     */
    function cron(?string $name = null)
    {
        /* @var CronManager $manager */
        $manager = app('cron');

        if (is_null($name)) {
            return $manager;
        }
        return $manager->getItem($name);
    }
}

if (!function_exists('database')) {
    /**
     * Récupération du gestionnaire de base de données ou Table de traitement de requête.
     *
     * @param string|null $table Nom de qualification de la table sur laquelle traiter les requête.
     *
     * @return Database|LaraDatabaseQueryBuilder|null
     */
    function database(?string $table = null)
    {
        /* @var Database $manager */
        $manager = app('database');

        if (is_null($table)) {
            return $manager;
        }
        return $manager::table($table);
    }
}

if (!function_exists('db')) {
    /**
     * Récupération du gestionnaire de base de données ou d'une instance de controleur de base de données.
     *
     * @param null|string $name Nom de qualification du controleur de base de données.
     *
     * @return DbManager|DbFactory|null
     */
    function db(?string $name = null)
    {
        /* @var DbManager $manager */
        $manager = app('db');

        if (is_null($name)) {
            return $manager;
        }
        return $manager->get($name);
    }
}

if (!function_exists('events')) {
    /**
     * Events - Controleur d'événements.
     *
     * @return EventsManager
     */
    function events(): EventsManager
    {
        return app('events');
    }
}

if (!function_exists('field')) {
    /**
     * Field - Controleur de champs.
     *
     * @param null|string $name Nom de qualification.
     * @param mixed $id Nom de qualification ou Liste des attributs de configuration.
     * @param mixed $attrs Liste des attributs de configuration.
     *
     * @return Field|FieldFactory|null
     */
    function field($name = null, $id = null, $attrs = null)
    {
        /* @var Field $manager */
        $manager = app('field');

        if (is_null($name)) {
            return $manager;
        }
        return $manager->get($name, $id, $attrs);
    }
}

if (!function_exists('form')) {
    /**
     * Formulaire - Controleur de champs.
     *
     * @param null|string $name Nom de qualification du formulaire.
     *
     * @return null|FormManager|FormFactory
     */
    function form($name = null)
    {
        /* @var FormManager $factory */
        $factory = app('form');

        if (is_null($name)) {
            return $factory;
        }
        return $factory->get($name);
    }
}

if (! function_exists('logger')) {
    /**
     * Logger - Controleur de journalisation des actions.
     *
     * @param  string|null  $message
     * @param  array  $context
     *
     * @return LogManager|void
     */
    function logger($message = null, array $context = []): ?LogManager
    {
        /* @var LogManager $manager */
        $manager = app('log');

        if (is_null($message)) {
            return $manager;
        }

        $manager->debug($message, $context);
    }
}

if (!function_exists('params')) {
    /**
     * Instance de contrôleur de paramètres.
     *
     * @param mixed $params Liste des paramètres.
     *
     * @return ParamsBag
     */
    function params($params = []): ParamsBag
    {
        return app('params.bag', [$params]);
    }
}

if (!function_exists('partial')) {
    /**
     * Partial - Contrôleurs d'affichage.
     *
     * @param string|null $name Nom de qualification.
     * @param mixed $id Nom de qualification ou Liste des attributs de configuration.
     * @param mixed $attrs Liste des attributs de configuration.
     *
     * @return null|Partial|PartialFactory
     */
    function partial(?string $name = null, $id = null, ?array $attrs = null)
    {
        /* @var Partial $manager */
        $manager = app('partial');

        if (is_null($name)) {
            return $manager;
        }
        return $manager->get($name, $id, $attrs);
    }
}

if (!function_exists('paths')) {
    /**
     * Path - Controleur des chemins vers les répertoires de l'application.
     *
     * @return Path
     */
    function paths(): Path
    {
        return container('path');
    }
}

if (!function_exists('post_type')) {
    /**
     * Récupération de l'intance du controleur des types de contenu ou instance d'un type de contenu déclaré.
     *
     * @param string|null $name Nom de qualification du type de contenu.
     *
     * @return PostTypeManager|PostTypeFactory
     */
    function post_type($name = null)
    {
        /* @var PostTypeManager $manager */
        $manager = app('post-type');

        if (is_null($name)) {
            return $manager;
        }
        return $manager->get($name);
    }
}

if (!function_exists('redirect')) {
    /**
     * HTTP - Récupération d'une instance du contrôleur de redirection ou redirection vers une url.
     *
     * @param string|null $to Url de redirection.
     * @param int $status Code de la redirection.
     * @param array $headers Liste des entête HTTP
     * @param bool $secure Activation de la sécurisation
     *
     * @return Redirector|RedirectResponse
     */
    function redirect(?string $to = null, ?int $status = 302, ?array $headers = [], ?bool $secure = null)
    {
        if (is_null($to)) {
            return app('redirect');
        }
        return app('redirect')->to($to, $status, $headers, $secure);
    }
}

if (!function_exists('request')) {
    /**
     * HTTP - Controleur de traitement de la requête principale.
     *
     * @return Request
     */
    function request(): Request
    {
        return app('request');
    }
}

if (!function_exists('route')) {
    /**
     * Routing - Récupération de l'url vers une route.
     *
     * @param string $name Nom de qualification de la route.
     * @param array $parameters Liste des variables passées en argument dans l'url.
     * @param boolean $absolute Activation de sortie de l'url absolue.
     *
     * @return string|null
     */
    function route($name, $parameters = [], $absolute = true): ?string
    {
        return router()->url($name, $parameters, $absolute);
    }
}

if (!function_exists('route_exists')) {
    /**
     * Vérification si la requête courante répond à une route déclarée.
     *
     * @return bool
     */
    function route_exists(): bool
    {
        return router()->hasCurrent();
    }
}

if (!function_exists('router')) {
    /**
     * Routing - Récupération de l'instance du controleur de routage ou déclaration d'une nouvelle route.
     *
     * @param string|null $name Nom de qualification de la route.
     * @param array $attrs Liste des attributs de configuration.
     *
     * @return Router|Route
     */
    function router(?string $name = null, ?array $attrs = [])
    {
        /* @var Router $factory */
        $factory = app('router');

        if (is_null($name)) {
            return $factory;
        }
        return $factory->register($name, $attrs);
    }
}

if (!function_exists('storage')) {
    /**
     * Récupération du gestionnaire de point de montage ou instance d'un point de montage.
     *
     * @param string|null Nom de qualification du point de montage à récupéré.
     *
     * @return StorageManager|Filesystem
     */
    function storage(?string $name = null)
    {
        /* @var StorageManager $manager */
        $manager = app('storage');

        if (is_null($name)) {
            return $manager;
        }
        return $manager->disk($name);
    }
}

if (!function_exists('taxonomy')) {
    /**
     * Récupération de l'instance du contrôleur de taxonomies ou instance d'une taxonomie déclarée.
     *
     * @param string|null $name Nom de qualification de la taxonomie déclarée.
     *
     * @return TaxonomyManager|TaxonomyFactory
     */
    function taxonomy(?string $name = null)
    {
        /* @var TaxonomyManager $manager */
        $manager = app('taxonomy');

        if (is_null($name)) {
            return $manager;
        }
        return $manager->get($name);
    }
}

if (!function_exists('template')) {
    /**
     * Instance de controleur de gabarits d'affichage ou Instance d'un gabarit.
     *
     * @param string|null Nom de qualification du gabarit.
     * @param array $params Liste des paramètres appliqués au gabarit.
     *
     * @return TemplateFactory|TemplateManager|null
     */
    function template(?string $name = null, array $params = [])
    {
        /* @var TemplateManager $manager */
        $manager = app('template');

        if (is_null($name)) {
            return $manager;
        } elseif ($template = $manager->get($name)) {
            $template->param($params);
            return $template;
        } else {
            return null;
        }
    }
}

if (!function_exists('url')) {
    /**
     * Récupération de l'instance du contrôleur d'url.
     *
     * @return Url
     */
    function url(): Url
    {
        return app('url');
    }
}

if (!function_exists('url_factory')) {
    /**
     * Récupération de l'instance du contrôleur de traitement d'url.
     *
     * @param string $url Url à traiter.
     *
     * @return UrlFactory
     */
    function url_factory($url): UrlFactory
    {
        return app()->get('url.factory', [$url]);
    }
}

if (!function_exists('user')) {
    /**
     * Instance du gestionnaire utilisateur.
     *
     * @return User
     */
    function user(): User
    {
        return app('user');
    }
}

if (!function_exists('validator')) {
    /**
     * Récupération d'un instance du contrôleur de validation.
     *
     * @return Validator
     */
    function validator(): Validator
    {
        return app('validator');
    }
}

if (!function_exists('view')) {
    /**
     * View - Récupération d'une instance du controleur des vues ou l'affichage d'un gabarit.
     * {@internal Si aucun argument n'est passé à la méthode, retourne l'intance du controleur principal.}
     * {@internal Sinon récupére le gabarit d'affichage et passe les variables en argument.}
     *
     * @param null|string view Nom de qualification du gabarit.
     * @param array $data Liste des variables passées en argument.
     *
     * @return ViewController|ViewEngine
     */
    function view($view = null, $data = [])
    {
        /* @var ViewEngine $factory */
        $factory = app('viewer');

        if (func_num_args() === 0) {
            return $factory;
        }
        return $factory->make($view, $data);
    }
}