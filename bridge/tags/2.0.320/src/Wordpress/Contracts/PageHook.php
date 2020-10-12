<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts;

use Illuminate\Support\Collection;

interface PageHook
{
    /**
     * Récupération de la liste des instances des pages d'accroche déclarées.
     *
     * @return PageHookItem[]|array
     */
    public function all(): array;

    /**
     * Récupération de l'instance de la page d'accroche courante.
     *
     * @return PageHookItem|null
     */
    public function current(): ?PageHookItem;

    /**
     * Récupération du nom de qualification de la page d'accroche courante.
     *
     * @return string|null
     */
    public function currentName(): ?string;

    /**
     * Récupération de la collection des instances des pages d'accroche déclarées.
     *
     * @return Collection
     */
    public function collect(): Collection;

    /**
     * Récupération de la classe de rappel d'une page d'accroche déclarée.
     *
     * @param string $name Nom de qualification.
     *
     * @return PageHookItem|null
     */
    public function get($name): ?PageHookItem;

    /**
     * Vérification d'existance d'un contenu d'accroche sur la page d'affichage courante.
     *
     * @return boolean
     */
    public function has(): bool;

    /**
     * Déclaration de page d'accroche.
     *
     * @param string|array $name Nom de qualification ou liste des pages à déclarer.
     * @param array $attrs Liste des attributs de configuration .
     *
     * @return static
     */
    public function set($name, $attrs = []): PageHook;
}