<?php declare(strict_types=1);

namespace tiFy\Wordpress\User\Signin;

use tiFy\User\Signin\SigninFactory as tiFySigninFactory;
use WP_Error;

class SigninFactory extends tiFySigninFactory
{
    /**
     * @inheritdoc
     */
    public function boot(): void
    {
        add_action('init', [$this, 'handle']);

        events()->listen('user.signin.handle.login', [$this, 'handleLogin'], -999999);

        events()->listen('user.signin.handle.logout', [$this, 'handleLogout'], -999999);
    }

    /**
     * @inheritdoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'redirect_url'       => site_url('/'),
            'notices'            => [
                'empty_username'        => __('L\'identifiant doit être renseigné.', 'tify'),
                'empty_password'        => __('Le mot de passe doit être renseigné.', 'tify'),
                'invalid_username'      => __('L\'identifiant n\'est pas valide.', 'tify'),
                'incorrect_password'    => __('Le mot de passe ne correspond pas à l’identifiant fourni.', 'tify'),
                'authentication_failed' => __('Les informations de connexion fournies sont invalides.', 'tify'),
                'role_not_allowed'      => __('Votre utilisateur n\'est pas autorisé à se connecter depuis cette' .
                    ' interface.', 'tify'),
            ]
        ]);
    }

    /**
     * Traitement de l'authentification.
     *
     * @return void
     */
    public function handleLogin(): void
    {
        check_admin_referer('Signin-login-' . $this->getName());

        add_filter('authenticate', function ($user) {
            if (!is_wp_error($user) && !$this->hasRole($user->roles)) {
                return new WP_Error('role_not_allowed');
            } else {
                return $user;
            }
        }, 25);

        $secure_cookie = '';

        if (($log = request()->post('log', false)) && !force_ssl_admin()) {
            $user_name = sanitize_user($log);
            if ($user = get_user_by('login', $user_name)) {
                if (get_user_option('use_ssl', $user->ID)) {
                    $secure_cookie = true;
                    force_ssl_admin(true);
                }
            }
        }

        $reauth = !request()->get('reauth') ? false : true;
        $user = wp_signon([], $secure_cookie);

        if (!request()->cookie(LOGGED_IN_COOKIE)) {
            if (headers_sent()) {
                $user = new WP_Error('test_cookie', sprintf(
                    __('<strong>ERROR</strong>: Cookies are blocked due to unexpected output. ' .
                        'For help, please see<a href="%1$s">this documentation</a> or try the ' .
                        '<a href="%2$s">support forums</a>.'
                    ),
                    __('https://codex.wordpress.org/Cookies'),
                    __('https://wordpress.org/support/')
                ));
            } elseif (request()->post('testcookie') && !request()->cookie(TEST_COOKIE)) {
                $user = new WP_Error('test_cookie', sprintf(
                    __('<strong>ERROR</strong>: Cookies are blocked or not supported by your browser. ' .
                        'You must <a href="%s">enable cookies</a> to use WordPress.'
                    ),
                    __('https://codex.wordpress.org/Cookies')
                ));
            }
        }

        if (!is_wp_error($user) && !$reauth) {
            $redirect_url = request()->get('redirect_to');

            if ($redirect_url = $this->getAuthRedirectUrl($redirect_url)) {
                if ($secure_cookie && false !== strpos($redirect_url, 'wp-admin')) {
                    $redirect_url = preg_replace('|^http://|', 'https://', $redirect_url);
                }
            } else {
                $redirect_url = admin_url();
            }

            wp_safe_redirect($redirect_url);
            exit;
        } elseif (is_wp_error($user)) {
            if ($user->get_error_codes()) {
                foreach ($user->get_error_codes() as $code) {
                    if (!$messages = $user->get_error_messages()) {
                        $this->addNotice('error', $code, $code);
                    } else {
                        foreach ($messages as $message) {
                            $this->addNotice('error', $message, $code);
                        }
                    }
                }
            } else {
                $this->addNotice('error', __('Erreur lors de la tentative d\'authentification', 'tify'));
            }
        }
    }

    /**
     * Traitement de la connection.
     *
     * @return void
     */
    public function handleLogout(): void
    {
        check_admin_referer('signin-logout-' . $this->getName());

        wp_logout();

        $redirect_url = request()->get('redirect_to');

        if (!$redirect_url = $this->getLogoutRedirectUrl($redirect_url)) {
            $redirect_url = remove_query_arg([
                'action',
                '_wpnonce',
                'signin',
            ], set_url_scheme('//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
        }

        $redirect_url = add_query_arg('loggedout', true, $redirect_url);

        wp_safe_redirect($redirect_url);
        exit;
    }
}