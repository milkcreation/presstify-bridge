<?php declare(strict_types=1);

namespace tiFy\Wordpress\PageHook;

use tiFy\Contracts\Metabox\MetaboxDriver as MetaboxDriverContract;
use tiFy\Metabox\MetaboxDriver;
use tiFy\Wordpress\Contracts\PageHook;
use tiFy\Wordpress\Proxy\PageHook as ProxyPageHook;

class PageHookMetabox extends MetaboxDriver
{
    /**
     * Instance du gestionnaire de page d'accroche.
     * @var PageHook|null
     */
    protected $pageHook;

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'items' => [],
            'title' => __('Pages d\'accroche', 'tify'),
        ]);
    }

    /**
     * @inheritDoc
     */
    public function parse(): MetaboxDriverContract
    {
        parent::parse();

        if (!$this->get('items')) {
            $this->set([
                'items' => $this->pageHook() ? $this->pageHook()->collect()->where('admin', true)->all() : [],
            ]);
        }

        return $this;
    }

    /**
     * Récupération du gestionnaire de pages d'accroche.
     *
     * @return PageHook
     */
    public function pageHook(): ?PageHook
    {
        return $this->pageHook ?: ProxyPageHook::getInstance();
    }

    /**
     * Définition du gestionnaire de pages d'accroche.
     *
     * @param PageHook $pageHook
     *
     * @return $this
     */
    public function setPageHook(PageHook $pageHook): self
    {
        $this->pageHook = $pageHook;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function viewer(?string $view = null, array $data = [])
    {
        if (!$this->viewer && !$this->get('viewer.directory')) {
            $this->set('viewer.directory', class_info($this->pageHook())->getDirname() . '/Resources/views/metabox');
        }

        return func_num_args() === 0 ? parent::viewer() : parent::viewer($view, $data);
    }
}