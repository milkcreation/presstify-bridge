<?php
/**
 * Pagination - Accès à la page suivante.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\ListTable\View $this
 */
echo partial('tag', $this->pagination()->get('next', []));