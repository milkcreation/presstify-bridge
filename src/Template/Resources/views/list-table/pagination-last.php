<?php
/**
 * Pagination - Accès à la dernière page.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\ListTable\Viewer $this
 */
echo partial('tag', $this->pagination()->get('last', []));