<?php declare(strict_types=1);

namespace tiFy\Metabox\Contexts;

use tiFy\Contracts\Metabox\MetaboxDriver;
use tiFy\Metabox\MetaboxContext;

class TabContext extends MetaboxContext
{
    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'rotation' => ['left', 'top', 'default', 'pills']
        ]);
    }

    /**
     * @inheritDoc
     */
    public function parse()
    {
        parent::parse();

        if ($items = $this->get('items', [])) {
            array_walk($items, function (MetaboxDriver &$item, $key){
                $item = [
                    'name'     => $key,
                    'title'    => $item->title(),
                    'parent'   => $item['parent'],
                    'content'  => "<div class=\"MetaboxTab-content\">{$item->content()}</div>",
                    'position' => $item['position']
                ];
            });

            $this->set('items', $items);
        }

        return $this;
    }
}