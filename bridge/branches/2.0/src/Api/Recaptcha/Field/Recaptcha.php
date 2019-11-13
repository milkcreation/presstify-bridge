<?php declare(strict_types=1);

namespace tiFy\Api\Recaptcha\Field;

use tiFy\Api\Recaptcha\Contracts\{FieldRecaptcha, Recaptcha as RecaptchaContract};
use tiFy\Contracts\Field\FieldFactory as FieldFactoryContract;
use tiFy\Field\{FieldFactory, FieldView};

class Recaptcha extends FieldFactory implements FieldRecaptcha
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
    public function display(): string
    {
        $this->recaptcha->addWidgetRender($this->get('attrs.id'), [
            'sitekey' => $this->get('sitekey'),
            'theme'   => $this->get('theme')
        ]);

        return parent::display();
    }

    /**
     * @inheritDoc
     */
    public function parse(): FieldFactoryContract
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
            $cinfo = class_info(Recaptcha::class);
            $default_dir = $cinfo->getDirname() . '/views/';
            $this->viewer = view()
                ->setDirectory(is_dir($default_dir) ? $default_dir : null)
                ->setController(FieldView::class)
                ->setOverrideDir(
                    (($override_dir = $this->get('viewer.override_dir')) && is_dir($override_dir))
                        ? $override_dir
                        : (is_dir($default_dir) ? $default_dir : $cinfo->getDirname())
                )
                ->setParam('field', $this);
        }

        if (func_num_args() === 0) {
            return $this->viewer;
        }

        return $this->viewer->make("_override::{$view}", $data);
    }
}