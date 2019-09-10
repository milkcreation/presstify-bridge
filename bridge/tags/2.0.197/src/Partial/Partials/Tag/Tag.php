<?php declare(strict_types=1);

namespace tiFy\Partial\Partials\Tag;

use tiFy\Contracts\Partial\{PartialFactory as PartialFactoryContract, Tag as TagContract};
use tiFy\Partial\PartialFactory;
use tiFy\Support\Str;

class Tag extends PartialFactory implements TagContract
{
    /**
     * Liste des champs connu de type singleton
     * @see http://html-css-js.com/html/tags
     * @var string[]
     */
    protected $singleton = [
        'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source'
    ];

    /**
     * {@inheritDoc}
     *
     * @return array {
     *      @var array $attrs Attributs HTML du champ.
     *      @var string $after Contenu placé après le champ.
     *      @var string $before Contenu placé avant le champ.
     *      @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     *      @var string $tag Balise HTML div|span|a|... défaut div.
     *      @var string|callable $content Contenu de la balise HTML.
     *      @var boolean $singleton Activation de balise de type singleton. ex <{tag}/>. Usage avancé, cet attributon
     *                              se fait automatiquement pour les balises connues.
     * }
     */
    public function defaults(): array
    {
        return [
            'before'    => '',
            'after'     => '',
            'attrs'     => [],
            'viewer'    => [],
            'tag'       => 'div',
            'content'   => '',
            'singleton' => false,
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): PartialFactoryContract
    {
        parent::parse();

        if (in_array($this->get('tag'), $this->singleton)) {
            $this->set('singleton', true);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return $this
     */
    public function parseDefaults(): PartialFactoryContract
    {
        if (!$this->get('attrs.id')) {
            $this->forget('attrs.id');
        }

        $base = Str::ucfirst($this->getAlias());
        $default_class = "{$base} {$base}--" . $this->get('tag') . " {$base}--" . $this->getIndex();
        if (!$this->has('attrs.class')) {
            $this->set('attrs.class', $default_class);
        } else {
            $this->set('attrs.class', sprintf($this->get('attrs.class', ''), $default_class));
        }
        if (!$this->get('attrs.class')) {
            $this->forget('attrs.class');
        }

        $this->parseViewer();

        return $this;
    }
}