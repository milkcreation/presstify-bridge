<?php declare(strict_types=1);

namespace tiFy\Wordpress\Partial\Driver\Modal;

use tiFy\Partial\Driver\Modal\Modal as BaseModal;
use tiFy\Wordpress\Contracts\Partial\PartialDriver as PartialDriverContract;

class Modal extends BaseModal implements PartialDriverContract
{
    /**
     * {@inheritDoc}
     *
     * @return array $attributes {
     *      @var string $before Contenu placé avant.
     *      @var string $after Contenu placé après.
     *      @var array $attrs Attributs de balise HTML.
     *      @var array $viewer Attributs de configuration du controleur de gabarit d'affichage.
     *      @var array $options {
     *          Liste des options d'affichage.
     *      }
     *      @var bool $animation Activation de l'animation.
     *      @var string $size Taille d'affichage de la fenêtre de dialogue lg|sm|full|flex.
     *      @var bool|string|callable $backdrop_close_button Affichage d'un bouton fermeture externe. Chaine de
     *                                                      caractère à afficher ou booléen pour activer désactiver ou
     *                                                      fonction/méthode d'affichage.
     *      @var bool|string|callable $header Affichage de l'entête de la fenêtre. Chaine de caractère à afficher ou
     *                                        booléen pour activer désactiver ou fonction/méthode d'affichage.
     *      @var bool|string|callable $body Affichage du corps de la fenêtre. Chaine de caractère à afficher ou booléen
     *                                      pour activer désactiver ou fonction/méthode d'affichage.
     *      @var bool|string|callable $footer Affichage d'un bouton fermeture externe. Chaine de caractère à afficher ou
     *                                        booléen pour activer désactiver ou fonction/méthode d'affichage.
     *      @var bool $in_footer Ajout automatique de la fenêtre de dialogue dans le pied de page du site.
     *      @var bool|string|array $ajax Activation du chargement du contenu Ajax ou Contenu a charger ou liste des
     *                                   attributs de récupération Ajax
     * }
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'in_footer'      => true
        ]);
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        if ($this->get('in_footer')) {
            add_action((!is_admin() ? 'wp_footer' : 'admin_footer'), function () {
                echo parent::render();
            }, 999999);

            return '';
        } else {
            return parent::render();
        }
    }
}