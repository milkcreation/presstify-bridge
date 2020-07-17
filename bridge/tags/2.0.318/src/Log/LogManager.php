<?php declare(strict_types=1);

namespace tiFy\Log;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use tiFy\Contracts\Container\Container;
use tiFy\Contracts\Log\{
    Logger as LoggerContract,
    LogManager as LogManagerContract
};
use tiFy\Support\Manager;

class LogManager extends Manager implements LogManagerContract
{
    /**
     * @inheritDoc
     */
    public function alert($message, array $context = []): void
    {
        $this->channel()->alert($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        $this->set('default', []);
    }

    /**
     * @inheritDoc
     */
    public function critical($message, array $context = []): void
    {
        $this->channel()->critical($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function channel(string $channel = null): ?LoggerContract
    {
        return $this->get($channel ?? 'default');
    }

    /**
     * @inheritDoc
     */
    public function debug($message, array $context = []): void
    {
        $this->channel()->debug($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function emergency($message, array $context = []): void
    {
        $this->channel()->emergency($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function error($message, array $context = []): void
    {
        $this->channel()->error($message, $context);
    }

    /**
     * @inheritDoc
     *
     * @return LoggerContract|null
     */
    public function get(...$args): ?LoggerContract
    {
        if (isset($args[0])) {
            return $this->items[$args[0]] ?? null;
        }
        return null;
    }

    /**
     * @inheritDoc
     *
     * @return Container|null
     */
    public function getContainer(): ?ContainerInterface
    {
        return parent::getContainer();
    }

    /**
     * @inheritDoc
     */
    public function info($message, array $context = []): void
    {
        $this->channel()->info($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function log($level, $message, array $context = []): void
    {
        $this->channel()->log($level, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function notice($message, array $context = []): void
    {
        $this->channel()->notice($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function register($key, ...$args): LogManagerContract
    {
        if (isset($args[0])) {
            return $this->set([$key => $args[0]]);
        }
        throw new InvalidArgumentException(
            __('La déclaration du controleur de journalisation n\'est pas conforme.', 'tify')
        );
    }

    /**
     * @inheritDoc
     */
    public function success(string $message, array $context = []): void
    {
        $this->channel()->success($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function registerChannel(string $name, array $params = []): ?LoggerContract
    {
        return $this->set($name, $params)->channel($name);
    }

    /**
     * @inheritDoc
     */
    public function warning($message, array $context = []): void
    {
        $this->channel()->warning($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function walk(&$logger, $key = null): void
    {
        if(!$logger instanceof LoggerContract) {
            $params = $logger;
            $logger = (new Logger((string)$key))->setContainer($this->getContainer());
            $logger->setParams($params);
        }

        $this->items[$key] = $logger;
    }
}