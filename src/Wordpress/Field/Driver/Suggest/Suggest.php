<?php declare(strict_types=1);

namespace tiFy\Wordpress\Field\Driver\Suggest;

use Illuminate\Support\Collection;
use tiFy\Field\Driver\Suggest\Suggest as BaseSuggest;
use tiFy\Support\Proxy\Request;
use tiFy\Wordpress\{
    Contracts\Field\Suggest as SuggestContract,
    Query\QueryPost,
    Query\QueryTerm,
    Query\QueryUser
};

class Suggest extends BaseSuggest implements SuggestContract
{
    /**
     * @inheritDoc
     */
    public function xhrResponse(...$args): array
    {
        switch ('post') {
            case 'post' :
            default :
                return $this->xhrResponsePostQuery(...$args);
                break;
            case 'term' :
                return $this->xhrResponseTermQuery(...$args);
                break;
            case 'user' :
                return $this->xhrResponseUserQuery(...$args);
            case 'custom':
                return parent::xhrResponse(...$args);
                break;
        }
    }

    /**
     * @inheritDoc
     */
    public function xhrResponsePostQuery(...$args): array
    {
        $args = array_merge(
            ['post_type' => 'any'],
            Request::input('query_args', []),
            ['s' => Request::input('_term', '')]
        );

        $posts = QueryPost::queryFromArgs($args);

        $items = (new Collection($posts))->map(function (QueryPost &$item) {
            return [
                'alt'   => (string)$item->getId(),
                'label' => (string)$item->getTitle(),
                'value' => (string)$item->getTitle(true),
            ];
        })->all();

        return [
            'success' => true,
            'data'    => [
                'items' => $items,
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function xhrResponseTermQuery(...$args): array
    {
        $args = array_merge(Request::input('query_args', []), ['search' => Request::input('_term', '')]);

        $terms = QueryTerm::queryFromArgs($args);

        $items = (new Collection($terms))->map(function (QueryTerm &$item) {
            return [
                'alt'   => (string)$item->getId(),
                'label' => (string)$item->getName(),
                'value' => (string)$item->getName(),
            ];
        })->all();

        return [
            'success' => true,
            'data'    => [
                'items' => $items,
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function xhrResponseUserQuery(...$args): array
    {
        $args = array_merge(Request::input('query_args', []), ['search' => Request::input('_term', '')]);

        $terms = QueryUser::queryFromArgs($args);

        $items = (new Collection($terms))->map(function (QueryUser &$item) {
            return [
                'alt'   => (string)$item->getId(),
                'label' => (string)$item->getDisplayName(),
                'value' => (string)$item->getDisplayName(),
            ];
        })->all();

        return [
            'success' => true,
            'data'    => [
                'items' => $items,
            ]
        ];
    }
}