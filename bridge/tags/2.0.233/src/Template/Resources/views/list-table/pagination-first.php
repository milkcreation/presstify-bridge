<?php
/**
 * Pagination - Accès à la première page.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\ListTable\Viewer $this
 */
echo partial('tag', $this->pagination()->get('first', []));