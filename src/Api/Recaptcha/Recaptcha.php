<?php declare(strict_types=1);

namespace tiFy\Api\Recaptcha;

use ReCaptcha\{ReCaptcha as ReCaptchaSdk, Response as ReCaptchaResponse, RequestMethod\SocketPost as ReCaptchaSocket};
use RuntimeException;
use tiFy\Api\Recaptcha\Field\Recaptcha as RecaptchaField;
use tiFy\Api\Recaptcha\Contracts\Recaptcha as RecaptchaContract;
use tiFy\Support\Proxy\{Field, Request};

class Recaptcha extends ReCaptchaSdk implements RecaptchaContract
{
    /**
     * Instance déclarée.
     * @var static
     */
    protected static $instance;

    /**
     * Liste des attributs de configuration.
     * @var array {
     *      @var string $secretkey Clé secrète, requise pour la communication entre le site et Google.
     *                             Veillez a ne surtout jamais divulger cette clé.
     *      @var string $sitekey Clé du site, utilisée dans le code HTML
     * }
     */
    protected $attributes = [];

    /**
     * Liste des widgets déclarés.
     * @var array
     */
    protected $widgets = [];

    /**
     * CONSTRUCTEUR.
     *
     * @param array $attrs Liste des attributs de configuration.
     *
     * @return void
     */
    protected function __construct(array $attrs = [])
    {
        try {
            parent::__construct($attrs['secretkey'], (ini_get('allow_url_fopen') ? null : new ReCaptchaSocket()));

            $this->attributes = $attrs;

            Field::set('recaptcha', new RecaptchaField());

            add_action('wp_print_footer_scripts', function () {
                if ($this->widgets) {
                    $js = "function onloadCallback () {";
                    foreach ($this->widgets as $id => $params) {
                        $js .= "let el=document.getElementById('{$id}');";
                        $js .= "if(typeof(el)!='undefined' && el!=null){";
                        $js .= "grecaptcha.render('{$id}', " . json_encode($params) . ");";
                        $js .= "};";
                    }
                    $js .= "};";
                    echo '<script type="text/javascript">' . $js . '</script>';
                    echo '<script type="text/javascript"
                                  src="https://www.google.com/recaptcha/api.js?hl=' . $this->getLanguage() . '&onload=onloadCallback&render=explicit"
                                  async defer></script>';
                }
            });

        } catch (RuntimeException $e) {
            wp_die($e->getMessage(), __('Api reCaptcha : Erreur de configuration', 'tify'), $e->getCode());
        }
    }

    /**
     * @inheritDoc
     */
    public static function instance(array $attrs = []): RecaptchaContract
    {
        return self::$instance = !is_null(self::$instance)
            ? self::$instance : new static(array_merge(['secretkey' => '', 'sitekey'   => ''], $attrs));
    }

    /**
     * @inheritDoc
     */
    public function addWidgetRender(string $id, array $params = []): RecaptchaContract
    {
        $this->widgets[$id] = $params;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getLanguage(): string
    {
        global $locale;

        switch ($locale) {
            default :
                list($lang, $indice) = preg_split('/_/', $locale, 2);
                break;
            case 'zh_CN':
                $lang = 'zh-CN';
                break;
            case 'zh_TW':
                $lang = 'zh-TW';
                break;
            case 'en_GB' :
                $lang = 'en-GB';
                break;
            case 'fr_CA' :
                $lang = 'fr-CA';
                break;
            case 'de_AT' :
                $lang = 'de-AT';
                break;
            case 'de_CH' :
                $lang = 'de-CH';
                break;
            case 'pt_BR' :
                $lang = 'pt-BR';
                break;
            case 'pt_PT' :
                $lang = 'pt-PT';
                break;
            case 'es_AR' :
            case 'es_CL' :
            case 'es_CO' :
            case 'es_MX' :
            case 'es_PE' :
            case 'es_PR' :
            case 'es_VE' :
                $lang = 'es-419';
                break;
        }

        return $lang;
    }

    /**
     * @inheritDoc
     */
    public function getSiteKey(): ?string
    {
        return $this->attributes['sitekey'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function response(): ReCaptchaResponse
    {
        return $this->verify(Request::input('g-recaptcha-response'), Request::server('REMOTE_ADDR'));
    }

    /**
     * @inheritDoc
     */
    public function validation(): bool
    {
        return $this->response()->isSuccess();
    }
}