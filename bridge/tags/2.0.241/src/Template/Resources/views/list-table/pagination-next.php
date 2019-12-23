<?php
/**
 * Pagination - Accès à la page suivante.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\ListTable\Viewer $this
 */
echo partial('tag', $this->pagination()->get('next', []));