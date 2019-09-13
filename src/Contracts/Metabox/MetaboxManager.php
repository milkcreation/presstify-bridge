<?php

namespace tiFy\Contracts\Metabox;

interface MetaboxManager
{
    /**
     * Ajout d'un élément.
     *
     * @param string Nom de qualification.
     * @param null|string $screen Ecran d'affichage de l'élément. Null correspond à l'écran d'affichage courant.
     * @param array $attrs Liste des attributs de configuration de l'élément.
     *
     * @return $this
     */
    public function add($name, $screen = null, $attrs = []);

    /**
     * Récupération de la collection d'éléments déclarés.
     *
     * @return Collection|MetaboxFactory[]
     */
    public function collect();

    /**
     * Déclaration d'une boîte de saisie à supprimer
     *
     * @param string $id Identifiant de qualification HTML de la metaboxe.
     * @param string $screen Ecran d'affichage de l'élément. Null pour l'écran courant.
     * @param string $context normal|side|advanced.
     *
     * @return $this
     */
    public function remove($id, $screen = null, $context = 'normal');

    /**
     * Personnalisation des attributs de configuration d'une boîte à onglets.
     *
     * @param string $attrs Liste des attributs de personnalisation.
     * @param string $screen Ecran d'affichage de l'élément. Null pour l'écran courant.
     *
     * @return $this
     */
    public function tab($attrs = [], $screen = null);
}