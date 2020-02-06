<?php declare(strict_types=1);

namespace tiFy\Log;

use Monolog\{Formatter\LineFormatter, Handler\RotatingFileHandler, Logger as MonologLogger};
use Psr\Container\ContainerInterface as Container;
use tiFy\Contracts\{Kernel\Path as PathContract, Log\Logger as LoggerContract, Support\ParamsBag as ParamBagContract};
use tiFy\Support\{DateTime, ParamsBag};

class Logger extends MonologLogger implements LoggerContract
{
    /**
     * Instance du conteneur d'injection de dépendance.
     * @var Container|null
     */
    protected $container;

    /**
     * Instance des paramètres de configuration.
     * @var ParamBagContract|null
     */
    protected $params;

    /**
     * @inheritDoc
     */
    public function __construct(string $name, array $handlers = [], array $processors = [])
    {
        parent::__construct($name, $handlers, $processors);

        $this->params = new ParamsBag();
    }

    /**
     * @inheritDoc
     */
    public function addRecord($level, $message, array $context = []): bool
    {
        if (!$this->getHandlers()) {
            $this->setDefaultHandler();
        }

        return parent::addRecord($level, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function addSuccess(string $message, array $context = []): bool
    {
        return $this->addNotice($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function getContainer(): ?Container
    {
        return $this->container;
    }

    /**
     * @inheritDoc
     */
    public function params($key = null, $default = null)
    {
        if (is_string($key)) {
            return $this->params->get($key, $default);
        } elseif (is_array($key)) {
            return $this->params->set($key);
        } else {
            return $this->params;
        }
    }

    /**
     * @inheritDoc
     */
    public function setContainer(Container $container): LoggerContract
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setParams(array $params): LoggerContract
    {
        $this->params->set($params);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function success(string $message, array $context = []): bool
    {
        return $this->addSuccess($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function setDefaultHandler()
    {
        if ($this->getContainer() && $this->getContainer()->has('path')) {
            /** @var PathContract $path */
            $path = $this->getContainer()->get('path');

            $handler = (new RotatingFileHandler(
                $this->params('filename', $path->getLogPath($this->getName() . '.log')),
                $this->params('rotate', 10),
                $this->params('level', self::DEBUG)
            ))->setFormatter(new LineFormatter(
                    $this->params('format', null), $this->params('date_format', null))
            );

            $this->setTimezone($this->params('timezone', DateTime::getGlobalTimeZone()));

            $this->pushHandler($handler);
        }
    }
}