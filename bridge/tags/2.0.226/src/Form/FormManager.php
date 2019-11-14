<?php declare(strict_types=1);

namespace tiFy\Form;

use Closure;
use Exception;
use tiFy\Contracts\Form\{
    AddonFactory as AddonFactoryContract,
    FormManager as FormManagerContract,
    FormFactory,
};
use tiFy\Support\Manager;

class FormManager extends Manager implements FormManagerContract
{
    /**
     * Liste des formulaires déclarés.
     * @var FormFactory[]
     */
    protected $items = [];

    /**
     * Formulaire courant.
     * @var FormFactory
     */
    protected $current;

    /**
     * @inheritDoc
     */
    public function addonRegister(string $name, $controller = null): FormManagerContract
    {
        $this->getContainer()->add("form.addon.{$name}", function () use ($name, $controller) : AddonFactoryContract {
            if (is_object($controller) || $controller instanceof Closure) {
                $addon = call_user_func($controller);
            } elseif (class_exists($controller)) {
                $addon = new $controller();
            } else {
                $addon = new AddonFactory();
            }

            return $addon->setName($name);
        });

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function buttonRegister($name, $controller): FormManagerContract
    {
        app()->add("form.button.{$name}", function ($name, $attrs, FormFactory $form) use ($controller) {
            if (is_object($controller) || $controller instanceof Closure) {
                return call_user_func_array($controller, [$name, $attrs, $form]);
            } elseif (class_exists($controller)) {
                return new $controller($name, $attrs, $form);
            } else {
                return app()->get('form.button', [$name, $attrs, $form]);
            }
        });

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function current($form = null): ?FormFactory
    {
        if (is_null($form)) {
            return $this->current;
        } elseif (is_string($form)) {
            $form = $this->get($form);
        }

        if ( ! $form instanceof FormFactory) {
            return null;
        }

        $this->current = $form;

        $this->current->onSetCurrent();

        return $this->current;
    }

    /**
     * @inheritDoc
     */
    public function fieldRegister($name, $controller): FormManagerContract
    {
        app()->add("form.field.{$name}", function ($name, $attrs, FormFactory $form) use ($controller) {
            if (is_object($controller) || $controller instanceof Closure) {
                return call_user_func_array($controller, [$name, $attrs, $form]);
            } elseif (class_exists($controller)) {
                return new $controller($name, $attrs, $form);
            } else {
                return app()->get('form.field', [$name, $attrs, $form]);
            }
        });

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function index($name): ?int
    {
        $index = array_search($name, array_keys($this->items));

        return ($index !== false) ? $index : null;
    }

    /**
     * @inheritDoc
     */
    public function register($name, ...$args): FormManagerContract
    {
        return $this->set([$name => $args[0] ?? []]);
    }

    /**
     * @inheritDoc
     */
    public function reset(): FormManagerContract
    {
        if ($this->current instanceof FormFactory) {
            $this->current->onResetCurrent();
        }
        $this->current = null;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function resourcesDir($path = ''): string
    {
        $path = $path ? '/' . ltrim($path, '/') : '';

        return (file_exists(__DIR__ . "/Resources{$path}"))
            ? __DIR__ . "/Resources{$path}"
            : '';
    }

    /**
     * @inheritDoc
     */
    public function resourcesUrl($path = ''): string
    {
        $cinfo = class_info($this);
        $path  = $path ? '/' . ltrim($path, '/') : '';

        return (file_exists($cinfo->getDirname() . "/Resources{$path}"))
            ? $cinfo->getUrl() . "/Resources{$path}"
            : '';
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function walk(&$item, $name = null): void
    {
        $name  = strval($name);
        $attrs = [];
        if (is_string($item)) {
            $item = new $item();
        } elseif (is_array($item)) {
            $attrs = $item;
            if (isset($attrs['controller'])) {
                $controller = $attrs['controller'];
                unset($attrs['controller']);

                $item = new $controller();
            } else {
                $item = $this->getContainer()->get('form.factory');
            }
        }
        if ( ! $item instanceof FormFactory) {
            throw new Exception(sprintf(
                __('Déclaration de %s en erreur, le formulaire devrait être une instance de %s'),
                $name,
                FormFactory::class
            ));
        } else {
            $item->set($attrs)->setInstance($name, $this);
        }
    }
}