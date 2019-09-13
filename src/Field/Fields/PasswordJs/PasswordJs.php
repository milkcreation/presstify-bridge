<?php declare(strict_types=1);

namespace tiFy\Field\Fields\PasswordJs;

use tiFy\Contracts\Field\{FieldFactory as FieldFactoryContract, PasswordJs as PasswordJsContract};
use tiFy\Contracts\Encryption\Encrypter;
use tiFy\Field\FieldFactory;
use tiFy\Support\Proxy\Router as route;

class PasswordJs extends FieldFactory implements PasswordJsContract
{
    /**
     * Instance du contrôleur d'encryptage.
     * @var Encrypter
     */
    protected $encrypter;

    /**
     * Url de traitement.
     * @var string Url de traitement
     */
    protected $url = '';

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        $this->setUrl();
    }

    /**
     * {@inheritDoc}
     *
     * @return array {
     * @var array $attrs Attributs HTML du champ.
     * @var string $after Contenu placé après le champ.
     * @var string $before Contenu placé avant le champ.
     * @var string $name Clé d'indice de la valeur de soumission du champ.
     * @var string $value Valeur courante de soumission du champ.
     * @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     * @var array $container Liste des attributs de configuration du conteneur de champ.
     * @var bool $readonly Controleur en lecture seule (désactive aussi l'enregistrement et le générateur).
     * @var int $length .
     * @var bool hide Masquage de la valeur true (masquée)|false (visible en clair)
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'     => [],
            'after'     => '',
            'before'    => '',
            'name'      => '',
            'value'     => '',
            'viewer'    => [],
            'ajax'      => true,
            'container' => [
                'attrs' => [],
            ],
            'hide'      => true,
            'length'    => 32,
            'readonly'  => false,
        ];
    }

    /**
     * Récupération du controleur d'encryptage.
     *
     * @return Encrypter
     */
    public function getEncrypter()
    {
        if (is_null($this->encrypter)) {
            $this->encrypter = app('encrypter');
        }

        return $this->encrypter;
    }

    /**
     * @inheritDoc
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @inheritDoc
     */
    public function parse(): FieldFactoryContract
    {
        parent::parse();

        if ($container_class = $this->get('container.attrs.class')) {
            $this->set('container.attrs.class', "FieldPasswordJs {$container_class}");
        } else {
            $this->set('container.attrs.class', 'FieldPasswordJs');
        }

        $options = [];
        if ($ajax = $this->get('ajax')) {
            $defaults = [
                'url'  => $this->getUrl(),
                'type' => 'post',
                'data' => [],
            ];
            $options['ajax'] = is_array($ajax) ? array_merge($defaults, $ajax) : $defaults;
        }

        $this->set([
            'container.attrs.aria-hide'    => $this->get('hide') ? 'true' : 'false',
            'container.attrs.data-control' => 'password-js',
            'container.attrs.data-id'      => 'FieldPasswordJs--' . $this->getIndex(),
            'container.attrs.data-options' => $options
        ]);

        $this->set([
            'attrs.type' => $this->get('hide') ? 'password' : 'text',
            'attrs.size' => $this->get('attrs.size') ?: $this->get('length'),
        ]);

        if (!$this->has('attrs.autocomplete')) {
            $this->set('attrs.autocomplete', 'off');
        }

        if ($this->get('readonly')) {
            $this->push('attrs', 'readonly');
        }

        $this->set([
            'attrs.data-control' => 'password-js.input',
            'attrs.data-cypher'  => $cypher = $this->getEncrypter()->encrypt($this->getValue()),
            'attrs.value'        => $this->get('hide') ? $cypher : $this->get('attrs.value'),
        ]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setUrl(?string $url =  null): FieldFactoryContract
    {
        $this->url = is_null($url) ? route::xhr(md5($this->getAlias()), [$this, 'xhrResponse'])->getUrl() : $url;

        return $this;
    }

    /**
     * Récupération Ajax de la valeur décryptée.
     *
     * @return array
     */
    public function xhrResponse(): array
    {
        return [
            'success' => true,
            'data'    => $this->getEncrypter()->decrypt(request()->input('cypher')),
        ];
    }
}