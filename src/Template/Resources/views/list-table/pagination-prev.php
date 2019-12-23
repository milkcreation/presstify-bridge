<?php
/**
 * Pagination - Accès à la page précédente.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\ListTable\Viewer $this
 */
echo partial('tag', $this->pagination()->get('prev', []));