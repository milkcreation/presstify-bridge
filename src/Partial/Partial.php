<?php declare(strict_types=1);

namespace tiFy\Partial;

use InvalidArgumentException;
use tiFy\Contracts\Partial\{
    Accordion,
    Breadcrumb,
    CookieNotice,
    CurtainMenu,
    Dropdown,
    Downloader,
    Holder,
    ImageLightbox,
    Modal,
    Notice,
    Pagination,
    Partial as PartialContract,
    PartialDriver,
    Pdfviewer,
    Progress,
    Sidebar,
    Slider,
    Spinner,
    Tab,
    Table,
    Tag
};
use tiFy\Support\Manager;

class Partial extends Manager implements PartialContract
{
    /**
     * Définition des éléments déclarées par défaut.
     * @var array
     */
    protected $defaults = [
        'accordion'      => Accordion::class,
        'breadcrumb'     => Breadcrumb::class,
        'cookie-notice'  => CookieNotice::class,
        'curtain-menu'   => CurtainMenu::class,
        'dropdown'       => Dropdown::class,
        'downloader'     => Downloader::class,
        'holder'         => Holder::class,
        'image-lightbox' => ImageLightbox::class,
        'modal'          => Modal::class,
        'notice'         => Notice::class,
        'pagination'     => Pagination::class,
        'pdfviewer'      => Pdfviewer::class,
        'progress'       => Progress::class,
        'sidebar'        => Sidebar::class,
        'slider'         => Slider::class,
        'spinner'        => Spinner::class,
        'tab'            => Tab::class,
        'table'          => Table::class,
        'tag'            => Tag::class,
    ];

    /**
     * Liste des éléments déclarées.
     * @var PartialDriver[]
     */
    protected $items = [];

    /**
     * Liste des indices courant des éléments déclarées par alias de qualification.
     * @var int[]
     */
    protected $indexes = [];

    /**
     * Instances des éléments par alias de qualification et indexés par identifiant de qualification.
     * @var PartialDriver[][]
     */
    protected $instances = [];

    /**
     * @inheritDoc
     */
    public function get(...$args): ?PartialDriver
    {
        $alias = $args[0] ?? null;
        if (!$alias || !isset($this->items[$alias])) {
            throw new InvalidArgumentException(
                sprintf(
                    __('Aucune instance de portion d\'affichage correspondant à l\'alias : %s', 'tify'),
                    $alias
                )
            );
        }

        $id = $args[1] ?? null;
        $attrs = $args[2] ?? [];

        if (is_array($id)) {
            $attrs = $id;
            $id = null;
        } else {
            $attrs = $attrs ?: [];
        }

        if ($id) {
            if (isset($this->instances[$alias][$id])) {
                return $this->instances[$alias][$id];
            }

            $this->indexes[$alias]++;
            $this->instances[$alias][$id] = clone $this->items[$alias];
            $partial = $this->instances[$alias][$id];
        } else {
            $this->indexes[$alias]++;
            $partial = clone $this->items[$alias];
        }

        return $partial
            ->setIndex($this->indexes[$alias])
            ->setId($id ?? $alias . $this->indexes[$alias])
            ->set($attrs)->parse();
    }

    /**
     * @inheritDoc
     */
    public function register($key, ...$args)
    {
        if (isset($args[0])) {
            return $this->set([$key => $args[0]]);
        }
        throw new InvalidArgumentException(
            sprintf(__('La déclaration de la portion d\'affichage [%s] n\'est pas conforme.', 'tify'), $key)
        );
    }

    /**
     * @inheritDoc
     */
    public function registerDefaults(): PartialContract
    {
        foreach ($this->defaults as $name => $alias) {
            $this->set($name, $this->getContainer()->get($alias));
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function resourcesDir(string $path = null): string
    {
        $path = $path ? '/' . ltrim($path, '/') : '';

        return file_exists(__DIR__ . "/Resources{$path}") ? __DIR__ . "/Resources{$path}" : '';
    }

    /**
     * @inheritDoc
     */
    public function resourcesUrl(string $path = null): string
    {
        $cinfo = class_info($this);
        $path = $path ? '/' . ltrim($path, '/') : '';

        return file_exists($cinfo->getDirname() . "/Resources{$path}") ? $cinfo->getUrl() . "/Resources{$path}" : '';
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidArgumentException
     */
    public function walk(&$item, $key = null): void
    {
        if ($item instanceof PartialDriver) {
            $item->prepare((string)$key, $this);

            $this->instances[$key] = [$item];
            $this->indexes[$key] = 0;
        } else {
            throw new InvalidArgumentException(
                sprintf(
                    __('La déclaration de la portion d\'affichage [%s] devrait être une instance de [%s]', 'tify'),
                    $key,
                    PartialDriver::class
                )
            );
        }
    }
}