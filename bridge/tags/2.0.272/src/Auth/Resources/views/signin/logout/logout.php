<?php
/**
 * Déconnexion | .
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Auth\Signin\SigninView $this
 */
echo partial('tag', [
    'tag'     => $this->get('tag', 'a'),
    'attrs'   => $this->get('attrs', []),
    'content' => $this->get('content', ''),
]);