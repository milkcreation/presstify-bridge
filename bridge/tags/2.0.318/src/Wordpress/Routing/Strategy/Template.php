<?php declare(strict_types=1);

namespace tiFy\Wordpress\Routing\Strategy;

use Laminas\Diactoros\Response as LaminasResponse;
use League\Route\Route;
use Psr\Http\Message\{ResponseInterface as PsrResponse, ServerRequestInterface as PsrRequest};
use Symfony\Component\HttpFoundation\Response as SfResponse;
use tiFy\Http\Response;
use tiFy\Support\Proxy\Router;
use tiFy\Routing\Strategy\AppStrategy;
use tiFy\Wordpress\Contracts\Routing\Route as RouteContract;
use tiFy\Wordpress\Proxy\PageHook;
use Wp_Query;

class Template extends AppStrategy
{
    /**
     * Indicateur de contexte d'affichage de page de Wordpress.
     * @var string[]
     */
    protected $cTags = [
        'is_single',
        'is_preview',
        'is_page',
        'is_archive',
        'is_date',
        'is_year',
        'is_month',
        'is_day',
        'is_time',
        'is_author',
        'is_category',
        'is_tag',
        'is_tax',
        'is_search',
        'is_feed',
        'is_comment_feed',
        'is_trackback',
        'is_home',
        'is_404',
        'is_embed',
        'is_paged',
        'is_admin',
        'is_attachment',
        'is_singular',
        'is_robots',
        'is_posts_page',
        'is_post_type_archive'
    ];

    /**
     * @inheritDoc
     */
    public function invokeRouteCallable(Route $route, PsrRequest $request): PsrResponse
    {
        /** @var RouteContract $route */
        $route->setCurrent();

        if (!$route->isWpQuery()) {
            add_action('pre_get_posts', function (WP_Query $wp_query) {
                if ($wp_query->is_main_query() && ! $wp_query->is_admin) {
                    foreach ($this->cTags as $ct) {
                        $wp_query->{$ct} = false;
                    }
                    $wp_query->query_vars = $wp_query->fill_query_vars([]);
                    $wp_query->is_route = true;
                    unset($wp_query->query);
                }
            }, 0);

            add_action('wp', function () {
                global $wp_query;

                if ($wp_query->is_main_query() && ! $wp_query->is_admin) {
                    $wp_query->is_404 = false;
                    $wp_query->query = [];

                    status_header(200);
                }
            });

            add_filter('posts_pre_query', function (?array $posts, WP_Query $wp_query) {
                if ($wp_query->is_main_query() && ! $wp_query->is_admin && !PageHook::current()) {
                    return [];
                }
                return $posts;
            }, 10, 2);
        }

        add_action('template_redirect', function () use ($route, $request) {
            if ( is_robots() || is_favicon() || is_feed() || is_trackback()) {
                return;
            }

            $controller = $route->getCallable($this->getContainer());

            $args = array_values($route->getVars());
            array_push($args, $request);
            $response = $controller(...$args);

            if ($response instanceof SfResponse) {
                $response = Response::convertToPsr($response);
            } elseif (!$response instanceof PsrResponse) {
                $response = is_string($response) ? Response::create($response)->psr() : (new Response())->psr();
            }

            Router::emit($this->applyDefaultResponseHeaders($response));
            exit;
        }, 50);

        return $this->applyDefaultResponseHeaders((new LaminasResponse())->withStatus(100));
    }
}