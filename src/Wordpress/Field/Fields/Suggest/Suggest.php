<?php declare(strict_types=1);

namespace tiFy\Wordpress\Field\Fields\Suggest;

use tiFy\Field\Fields\Suggest\Suggest as BaseSuggest;
use tiFy\Support\Proxy\Request as req;
use tiFy\Wordpress\{
    Contracts\Field\Suggest as SuggestContract,
    Query\QueryPost,
    Query\QueryPosts,
    Query\QueryTerm,
    Query\QueryTerms,
    Query\QueryUser,
    Query\QueryUsers
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
        $args = array_merge([
            'post_type' => 'any',
        ], req::input('query_args', []), ['s' => req::input('_term', '')]);

        $posts = QueryPosts::createFromArgs($args) ?: [];

        $items = collect($posts)->map(function (QueryPost &$item) {
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
        $args = array_merge(req::input('query_args', []), ['search' => req::input('_term', '')]);

        $terms = QueryTerms::createFromArgs($args) ?: [];

        $items = collect($terms)->map(function (QueryTerm &$item) {
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
        $args = array_merge(req::input('query_args', []), ['search' => req::input('_term', '')]);

        $terms = QueryUsers::createFromArgs($args) ?: [];

        $items = collect($terms)->map(function (QueryUser &$item) {
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
            ],
        ];
    }
}