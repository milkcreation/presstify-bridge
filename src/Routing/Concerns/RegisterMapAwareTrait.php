<?php declare(strict_types=1);

namespace tiFy\Routing\Concerns;

use Exception;
use League\Route\Strategy\StrategyInterface;
use tiFy\Contracts\Routing\{RegisterMapAwareTrait as RegisterMapAwareTraitContract, Route as RouteContract};

/**
 * Trait RegisterMapAwareTrait
 * @package tiFy\Routing\Concerns
 *
 * @mixin ContainerAwareTrait
 */
trait RegisterMapAwareTrait
{
    /**
     * {@inheritdoc}
     *
     * @return RegisterMapAwareTrait
     */
    public function register(string $name, array $attrs): RegisterMapAwareTraitContract
    {
        $attrs = array_merge([
            'method' => 'GET',
            'path'   => '/',
            'cb'     => ''
        ], $attrs);

        /**
         * @var string $method . GET|POST|PUT|PATCH|DELETE|HEAD|OPTIONS
         * @var string $path
         * @var callable $cb
         */
        extract($attrs);

        $scheme = $scheme ?? request()->getScheme();
        $host = $host ?? request()->getHost();
        $strategy = $strategy ?? null;

        /** @var RouteContract $route */
        $route = $this->map($method, $path, $cb);

        $route->setName($name)
            ->setScheme($scheme)
            ->setHost($host);

        if (is_string($strategy)) {
            try {
                $strategy = $this->getContainer()->get("router.strategy.{$strategy}");
            } catch (Exception $e) {
                $strategy = null;
            }
        }

        if ($strategy instanceof StrategyInterface) {
            $strategy->setContainer($this->getContainer());
            $route->setStrategy($strategy);
        }
        return $this;
    }
}