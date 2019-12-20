<?php declare(strict_types=1);

namespace tiFy\Wordpress\Cookie;

use tiFy\Contracts\Cookie\{Cookie as CookieContract};
use WP_Site;

class Cookie
{
    /**
     * Instance du gestionnaire de routage.
     * @var CookieContract
     */
    protected $manager;

    /**
     * CONSTRUCTEUR.
     *
     * @param CookieContract $manager Instance du gestionnaire de cookie.
     *
     * @return void
     */
    public function __construct(CookieContract $manager)
    {
        $this->manager = $manager;

        if (is_multisite() && $site = WP_Site::get_instance(get_current_blog_id())) {
            if (!config()->get('cookie.domain')) {
                $this->manager->setDomain($site->domain);
            }
            if (!config()->get('cookie.path')) {
                $this->manager->setPath($site->path);
            }
            if (!config()->has('cookie.salt')) {
                $this->manager->setSalt('_' . md5($this->manager->getDomain() . $this->manager->getPath() .COOKIEHASH));
            }

        } else {
            if (!config()->has('cookie.salt')) {
                $this->manager->setSalt('_' . COOKIEHASH);
            }
        }


        if ($cookies = config('cookie.cookies', [])) {
            foreach (config('cookie.cookies') as $k => $v) {
                is_numeric($k) ? $this->manager->instance($v) : $this->manager->instance($k, $v);
            }
        }
    }
}