<?php declare(strict_types=1);

namespace tiFy\Api\Recaptcha\Field;

use tiFy\Api\Recaptcha\Contracts\{FieldRecaptcha, Recaptcha as RecaptchaContract};
use tiFy\Contracts\Field\FieldDriver as FieldDriverContract;
use tiFy\Field\{FieldDriver, FieldView};
use tiFy\Support\Proxy\View;

class Recaptcha extends FieldDriver implements FieldRecaptcha
{
    /**
     * Instance du controleur de champ reCaptcha.
     * @var RecaptchaContract
     */
    protected $recaptcha;

    /**
     * {@inheritDoc}
     *
     * @see https://developers.google.com/recaptcha/docs/display
     *
     * @return array {
     *      @var string $before Contenu placé avant le champ.
     *      @var string $after Contenu placé après le champ.
     *      @var string $name Clé d'indice de la valeur de soumission du champ.
     *      @var string $value Valeur courante de soumission du champ.
     *      @var array $attrs Attributs HTML du champ.
     *      @var array $viewer Liste des attributs de configuration du controleur de gabarit d'affichage.
     *      @var string $theme Couleur d'affichage du captcha. light|dark.
     *      @var string $sitekey Clé publique. Optionnel si l'API $recaptcha est active.
     *      @var string $secretkey Clé publique. Optionnel si l'API $recaptcha est active.
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
            'sitekey'   => '',
            'secretkey' => '',
            'theme'     => 'light',
            'tabindex'  => 0
        ];
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        $this->recaptcha->addWidgetRender($this->get('attrs.id'), [
            'sitekey' => $this->get('sitekey'),
            'theme'   => $this->get('theme')
        ]);

        return parent::render();
    }

    /**
     * @inheritDoc
     */
    public function parse(): FieldDriverContract
    {
        parent::parse();

        $this->recaptcha = app('api.recaptcha');

        if (!$this->get('attrs.id')) {
            $this->set('attrs.id', 'Field-recapcha--' . $this->getIndex());
        }

        $this->set('attrs.data-tabindex', $this->get('tabindex'));

        if (!$this->get('sitekey')) {
            $this->set('sitekey', $this->recaptcha->getSiteKey());
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function viewer(?string $view = null, array $data = [])
    {
        if (!$this->viewer) {
            $this->viewer = View::getPlatesEngine([
                'directory' => class_info($this)->getDirname() . '/views/',
                'factory' => FieldView::class,
                'field'   => $this
            ]);
        }

        return parent::viewer($view, $data);
    }
}