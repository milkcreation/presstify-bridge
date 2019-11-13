<?php declare(strict_types=1);

namespace tiFy\View;

use Exception;
use Illuminate\Support\Arr;
use League\Plates\{
    Template\Folder,
    Template\Template
};
use tiFy\Contracts\View\{
    ViewController as ViewControllerContract,
    ViewEngine
};
use Throwable;
use tiFy\Support\HtmlAttrs;

class ViewController extends Template implements ViewControllerContract
{
    /**
     * Instance du controleur de gestion des gabarits.
     * @var ViewEngine
     */
    protected $engine;

    /**
     * CONSTRUCTEUR.
     *
     * @param ViewEngine $engine
     * @param string $name
     *
     * @return void
     */
    public function __construct(ViewEngine $engine, $name)
    {
        parent::__construct($engine, $name);

        $this->boot();
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function boot(): void {}

    /**
     * @inheritDoc
     */
    public function dirname(): string
    {
        /** @var Folder $folder */
        $folder = $this->name->getFolder();

        return (is_null($folder))
            ? $this->engine->getDirectory()
            : $folder->getPath();
    }

    /**
     * {@inheritDoc}
     */
    public function fetch($name, array $data = [])
    {
        try {
            return $this->engine->render(
                ($this->engine->getFolders()->exists('_override') ? '_override::' : '') . $name,
                $data
            );
        } catch(Exception $e) {
            return $e->getMessage();
        } catch(Throwable $e) {
            return $e->getMessage();
        }
    }

    /**
     * @inheritDoc
     */
    public function get($key, $default = '')
    {
        return Arr::get($this->data, $key, $default);
    }

    /**
     * @inheritDoc
     */
    public function engine(): ViewEngine
    {
        return $this->engine;
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return Arr::has($this->data, $key);
    }

    /**
     * @inheritDoc
     */
    public function htmlAttrs($attrs, $linearized = true)
    {
        return HtmlAttrs::createFromAttrs($attrs, $linearized);
    }

    /**
     * {@inheritDoc}
     */
    public function insert($name, array $data = []): void
    {
        try {
            echo $this->fetch($name, $data);
        } catch (Exception $e) {
            echo $e->getMessage();
        } catch (Throwable $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @inheritDoc
     */
    public function pull($key, $defaults = null)
    {
        return Arr::pull($this->data, $key);
    }

    /**
     * @inheritDoc
     */
    public function reset($name): ViewControllerContract
    {
        $this->start($name);
        $this->stop();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function share($key, $value = null): ViewControllerContract
    {
        $this->engine->addData(is_array($key) ? $key : [$key => $value]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function set($key, $value): ViewControllerContract
    {
       Arr::set($this->data, $key, $value);

       return $this;
    }
}