<?php declare(strict_types=1);

namespace tiFy\Asset;

use Psr\Container\ContainerInterface;
use Illuminate\Support\Collection;
use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;
use tiFy\Contracts\Asset\Asset as AssetContract;
use tiFy\Support\ParamsBag;

class Asset extends ParamsBag implements AssetContract
{
    /**
     * Instance du conteneur d'injection de dépendances.
     * @var ContainerInterface
     */
    protected $container;

    /**
     * CONSTRUCTEUR.
     *
     * @param ContainerInterface $container Instance du conteneur d'injection de dépendances.
     *
     * @return void
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function footer(): string
    {
        $js = '';
        if ($datas = (new Collection($this->get('data-js', [])))->where('footer', '===', true)
            ->pluck('value', 'key')) {
            foreach ($datas as $k => $v) {
                $js .= "tify['{$k}']=" . wp_json_encode($v) . ";";
            }
        }
        if ($inlineJs = (new Collection($this->get('inline-js.footer', [])))) {
            foreach ($inlineJs as $v) {
                $js .= $v . ";";
            }
        }
        return $js
            ? "<script type=\"text/javascript\">/* <![CDATA[ */" . ((new JS($js))->minify()) . "/* ]]> */</script>"
            : '';
    }

    /**
     * @inheritDoc
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @inheritDoc
     */
    public function header(): string
    {
        $output = '';

        if ($inlineCss = (new Collection($this->get('inline-css', [])))) {
            $css = '';
            foreach ($inlineCss as $v) {
                $css .= $v;
            }
            $output .= "<style type=\"text/css\">" . ((new CSS($css))->minify()) . "</style>";
        }

        $js = "let tify={};";
        if ($datas = (new Collection($this->get('data-js', [])))->where('footer', '===', false)
            ->pluck('value', 'key')) {
            foreach ($datas as $k => $v) {
                $js .= "tify['{$k}']=" . wp_json_encode($v) . ";";
            }
        }
        if ($inlineJs = (new Collection($this->get('inline-js.header', [])))) {
            foreach ($inlineJs as $v) {
                $js .= $v . ";";
            }
        }

        return $output . "<script type=\"text/javascript\">/* <![CDATA[ */" . ((new JS($js))->minify()) .
            "/* ]]> */</script>";
    }

    /**
     * @inheritDoc
     */
    public function normalize(string $string): string
    {
        return html_entity_decode(rtrim(trim($string), ';'), ENT_QUOTES, 'UTF-8');
    }

    /**
     * @inheritDoc
     */
    public function setDataJs(string $key, $value, bool $footer = false): AssetContract
    {
        if (is_array($value)) {
            foreach ($value as $k => &$v) {
                if (is_scalar($v)) {
                    $v = (is_bool($v) || is_int($v)) ? $v : $this->normalize(strval($v));
                }
            }
        } elseif (is_scalar($value)) {
            $value = (is_bool($value) || is_int($value)) ? $value : $this->normalize(strval($value));
        }
        $this->push('data-js', compact('footer', 'key', 'value'));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setInlineCss(string $css): AssetContract
    {
        return $this->push('inline-css', $this->normalize($css));
    }

    /**
     * @inheritDoc
     */
    public function setInlineJs(string $js, bool $footer = false): AssetContract
    {
        return $this->push('inline-js' . ($footer ? '.footer' : '.header'), $this->normalize($js));
    }

    /**
     * @inheritDoc
     */
    public function url(string $path = ''): string
    {
        return url()->root('/vendor/presstify/framework/assets' . ($path ? '/' . ltrim($path, '/') : $path));
    }
}
