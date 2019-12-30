<?php
/**
 * Pagination - Accès à la première page.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\ListTable\View $this
 */
echo partial('tag', $this->pagination()->get('first', []));