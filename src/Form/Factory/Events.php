<?php declare(strict_types=1);

namespace tiFy\Form\Factory;

use tiFy\Contracts\Form\FactoryEvents;
use tiFy\Contracts\Form\FormFactory;

class Events implements FactoryEvents
{
    use ResolverTrait;

    /**
     * CONSTRUCTEUR.
     *
     * @param array $events Liste des événements associés au formulaire.
     * @param FormFactory $form Instance du contrôleur de formulaire.
     *
     * @return void
     */
    public function __construct($events, FormFactory $form)
    {
        $this->form = $form;

        foreach ($events as $name => $event) {
            if (is_callable($event)) {
                $listener = $event;
                $priority = 10;
            } elseif (isset($event['call']) && is_callable($event['call'])) {
                $listener = $event['call'];
                $priority = isset($event['priority']) ? $event['priority'] : 10;
            } else {
                continue;
            }

            $this->listen($name, $listener, $priority);
        }
    }

    /**
     * @inheritDoc
     */
    public function listen($name, $listener, $priority = 0)
    {
        events()->listen("form.factory.events.{$this->form()->name()}.{$name}", $listener, $priority);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function trigger($name, $args = [])
    {
        $name = "form.factory.events.{$this->form()->name()}.{$name}";

        return call_user_func_array([events(), 'trigger'], [$name, $args]);
    }
}