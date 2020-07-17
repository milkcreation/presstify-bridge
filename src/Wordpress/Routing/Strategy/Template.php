<?php declare(strict_types=1);

namespace tiFy\Wordpress\Routing\Strategy;

use League\Route\Route;
use Psr\Http\Message\{ResponseInterface as PsrResponse, ServerRequestInterface as PsrRequest};
use Symfony\Component\HttpFoundation\Response as SfResponse;
use tiFy\Http\Response;
use tiFy\Support\Proxy\Router;
use tiFy\Routing\Strategy\App as AppStrategy;
use tiFy\Wordpress\Contracts\Routing\Route as RouteContract;
use Wp_Query;
use Zend\Diactoros\Response as ZendResponse;

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
            add_action('pre_get_posts', function (WP_Query &$wp_query) {
                if ($wp_query->is_main_query() && ! $wp_query->is_admin) {
                    foreach ($this->cTags as $ct) {
                        $wp_query->{$ct} = false;
                    }
                    $wp_query->query_vars = $wp_query->fill_query_vars([]);
                    $wp_query->is_route = true;

                    add_action('wp', function () {
                        global $wp_query;
                        $wp_query->is_404 = false;
                        status_header(200);
                    });
                }
            }, 0);
        }

        $controller = $route->getCallable($this->getContainer());

        $args = array_values($route->getVars());
        array_push($args, $request);

        add_action('template_redirect', function () use ($controller, $args) {
            $resolved = $controller(...$args);

            if ($resolved instanceof PsrResponse) {
                $response = Response::createFromPsr($resolved);
            } elseif ($resolved instanceof SfResponse) {
                $response = $resolved;
            } else {
                $response = Response::create((string)$resolved);
            }

            if (!$response->headers->has('content-type')) {
                $response->headers->set('content-type', 'text/html');
            }

            Router::emit($response);
            exit;
        }, 50);

        return $this->applyDefaultResponseHeaders((new ZendResponse())->withStatus(100));
    }
}