<?php declare(strict_types=1);

namespace tiFy\Metabox;

use tiFy\Contracts\{
    Metabox\MetaboxContext as MetaboxContextContract,
    Metabox\MetaboxManager,
    View\ViewEngine
};
use tiFy\Support\ParamsBag;

class MetaboxContext extends ParamsBag implements MetaboxContextContract
{
    /**
     * Nom de qualification.
     * @var string
     */
    protected $name;

    /**
     * Instance du gestionnaire de metaboxes.
     * @var MetaboxManager|null
     */
    protected $manager;

    /**
     * Instance du gestionnaire de gabarit d'affichage.
     * @var ViewEngine|null
     */
    protected $viewer;

    /**
     * Résolution de sortie de la classe sous forme de chaîne de caractères.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [
            'items' => []
        ];
    }

    /**
     * @inheritDoc
     */
    public function manager(): ?MetaboxManager
    {
        return $this->manager;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        $this->set([
            'items' => $this->manager()->getRenderItems($this->name)
        ]);

        return (string)$this->viewer('index', $this->parse()->all());
    }

    /**
     * @inheritDoc
     */
    public function setManager(MetaboxManager $manager): MetaboxContextContract
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name): MetaboxContextContract
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function viewer(?string $view = null, array $data = [])
    {
        if (!$this->viewer) {
            $defaultDir = $this->manager()->resourcesDir("/views/contexts/{$this->name}");
            $fallbackDir = $this->get('viewer.override_dir') ? : $defaultDir;

            $this->viewer = view()
                ->setDirectory($defaultDir)
                ->setOverrideDir($fallbackDir);
        }

        if (func_num_args() === 0) {
            return $this->viewer;
        }

        return $this->viewer->make("_override::{$view}", $data);
    }
}