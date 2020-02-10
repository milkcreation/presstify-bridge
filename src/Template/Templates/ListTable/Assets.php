<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

use tiFy\Template\Factory\Assets as BaseAssets;
use tiFy\Template\Templates\ListTable\Contracts\Assets as AssetsContract;

class Assets extends BaseAssets implements AssetsContract
{
    /**
     * Instance du gabarit associé.
     * @var Factory
     */
    protected $factory;

    /**
     * @inheritdoc
     */
    public function scripts()
    {
        if ($preview_item_mode = $this->factory->param('preview_item_mode')) {
            wp_enqueue_script(
                'Template-listTable',
                '',
                ['jquery', 'url'],
                171118,
                true
            );

            wp_localize_script(
                'Template-listTable',
                'Template-listTable',
                [
                    'action'          => $this->factory->name() . '_preview_item',
                    'mode'            => $preview_item_mode,
                    'nonce_action'    => '_wpnonce',
                    'item_index_name' => $this->factory->param('item_index_name'),
                ]
            );

            if ($preview_item_mode === 'dialog') {
                wp_enqueue_style('wp-jquery-ui-dialog');
                wp_enqueue_script('jquery-ui-dialog');
            }
        }
    }
}