<?php declare(strict_types=1);

namespace tiFy\View\Factory;

use Exception;
use Illuminate\Support\Arr;
use League\Plates\{Engine as BasePlatesEngine, Template\Folder, Template\Template as BasePlatesTemplate};
use tiFy\Contracts\View\{
    PlatesFactory as PlatesFactoryContract,
    PlatesEngine as PlatesEngineContract
};
use Throwable;
use tiFy\Support\HtmlAttrs;

class PlatesFactory extends BasePlatesTemplate implements PlatesFactoryContract
{
    /**
     * Instance du gestionnaire de gabarits.
     * @var PlatesEngineContract
     */
    protected $engine;

    /**
     * CONSTRUCTEUR.
     *
     * @param BasePlatesEngine $engine
     * @param string $name
     *
     * @return void
     */
    public function __construct(BasePlatesEngine $engine, $name)
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

        return is_null($folder) ? $this->engine()->getDirectory() : $folder->getPath();
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, $default = '')
    {
        return Arr::get($this->data, $key, $default);
    }

    /**
     * @inheritDoc
     */
    public function engine(): PlatesEngineContract
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
    public function htmlAttrs(array $attrs, bool $linearized = true)
    {
        return HtmlAttrs::createFromAttrs($attrs, $linearized);
    }

    /**
     * @inheritDoc
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
    public function pull(string $key, $defaults = null)
    {
        return Arr::pull($this->data, $key, $defaults);
    }

    /**
     * @inheritDoc
     */
    public function reset(string $name): PlatesFactoryContract
    {
        $this->start($name);
        $this->stop();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function share($key, $value = null): PlatesFactoryContract
    {
        $this->engine()->addData(is_array($key) ? $key : [$key => $value]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function set($key, $value): PlatesFactoryContract
    {
       Arr::set($this->data, $key, $value);

       return $this;
    }
}