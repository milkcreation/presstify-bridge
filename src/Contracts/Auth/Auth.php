<?php declare(strict_types=1);

namespace tiFy\Contracts\Auth;

use Psr\Container\ContainerInterface as Container;

interface Auth
{
    /**
     * Récupération de l'instance du conteneur d'injection de dépendances.
     *
     * @return Container|null
     */
    public function getContainer(): ?Container;

    /**
     * Déclaration d'un formulaire d'authentification.
     *
     * @param string $name Nom de qualification.
     * @param array $attrs Liste des attributs de configuration.
     *
     * @return Signin
     */
    public function registerSignin(string $name, array $attrs = []): Signin;

    /**
     * Déclaration d'un formulaire d'inscription.
     *
     * @param string $name Nom de qualification.
     * @param array $attrs Liste des attributs de configuration.
     *
     * @return Signup
     */
    public function registerSignup(string $name, array $attrs = []): Signup;

    /**
     * Récupération du chemin absolu vers le répertoire des ressources.
     *
     * @param string $path Chemin relatif du sous-repertoire.
     *
     * @return string
     */
    public function resourcesDir(string $path = ''): string;

    /**
     * Récupération de l'url absolue vers le répertoire des ressources.
     *
     * @param string $path Chemin relatif du sous-repertoire.
     *
     * @return string
     */
    public function resourcesUrl(string $path = ''): string;

    /**
     * Récupération d'un formulaire d'authentification déclaré.
     *
     * @param string $name Nom de qualification du formulaire.
     *
     * @return Signin
     */
    public function signin(string $name): ?Signin;

    /**
     * Récupération d'un formulaire d'inscription déclaré.
     *
     * @param string $name Nom de qualification du formulaire.
     *
     * @return Signup
     */
    public function signup(string $name): ?Signup;
}