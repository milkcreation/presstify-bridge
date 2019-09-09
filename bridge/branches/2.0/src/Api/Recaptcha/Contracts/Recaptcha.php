<?php

namespace tiFy\Api\Recaptcha\Contracts;

use ReCaptcha\ReCaptcha as ReCaptchaSdk;
use ReCaptcha\Response;

interface Recaptcha
{
    /**
     * Déclaration d'un widget de rendu.
     *
     * @param string $id Identifiant de qualification HTML de l'élément porteur.
     * @param array $params Liste des paramètres.
     *
     * @return $this
     */
    public function addWidgetRender($id, $params = []);

    /**
     * Instanciation statique du contrôleur.
     *
     * @param array $attrs Liste des attributs de configuration.
     *
     * @return static
     */
    public static function create($attrs = []);

    /**
     * Récupération de la langue.
     *
     * @return string
     */
    public function getLanguage();

    /**
     * Récupération de la clé publique.
     *
     * @return string
     */
    public function getSiteKey();

    /**
     * Récupération de  la réponse à l'issue de la soumission.
     *
     * @return Response
     */
    public function validation();

    /**
     * Calls the reCAPTCHA siteverify API to verify whether the user passes
     * CAPTCHA test and additionally runs any specified additional checks
     *
     * @param string $response The user response token provided by reCAPTCHA, verifying the user on your site.
     * @param string $remoteIp The end user's IP address.
     *
     * @return Response Response from the service.
     */
    public function verify($response, $remoteIp = null);

    /**
     * Provide a hostname to match against in verify()
     * This should be without a protocol or trailing slash, e.g. www.google.com
     *
     * @param string $hostname Expected hostname
     *
     * @return ReCaptchaSdk Current instance for fluent interface
     */
    public function setExpectedHostname($hostname);

    /**
     * Provide an APK package name to match against in verify()
     *
     * @param string $apkPackageName Expected APK package name
     *
     * @return ReCaptchaSdk Current instance for fluent interface
     */
    public function setExpectedApkPackageName($apkPackageName);

    /**
     * Provide an action to match against in verify()
     * This should be set per page.
     *
     * @param string $action Expected action
     *
     * @return ReCaptchaSdk Current instance for fluent interface
     */
    public function setExpectedAction($action);

    /**
     * Provide a threshold to meet or exceed in verify()
     * Threshold should be a float between 0 and 1 which will be tested as response >= threshold.
     *
     * @param float $threshold Expected threshold
     *
     * @return ReCaptchaSdk Current instance for fluent interface
     */
    public function setScoreThreshold($threshold);

    /**
     * Provide a timeout in seconds to test against the challenge timestamp in verify()
     *
     * @param int $timeoutSeconds Expected hostname
     *
     * @return ReCaptchaSdk Current instance for fluent interface
     */
    public function setChallengeTimeout($timeoutSeconds);
}