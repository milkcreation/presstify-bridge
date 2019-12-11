<?php declare(strict_types=1);

namespace tiFy\Api\Recaptcha\Contracts;

use ReCaptcha\{ReCaptcha as ReCaptchaSdk, Response as ReCaptchaResponse};

/**
 * @mixin ReCaptchaSdk
 */
interface Recaptcha
{
    /**
     * Création|Récupération de l'instance déclarée.
     *
     * @param array $attrs Liste des attributs de configuration.
     *
     * @return static
     */
    public static function instance(array $attrs = []): Recaptcha;

    /**
     * Déclaration d'un widget de rendu.
     *
     * @param string $id Identifiant de qualification HTML de l'élément porteur.
     * @param array $params Liste des paramètres.
     *
     * @return static
     */
    public function addWidgetRender(string $id, array $params = []): Recaptcha;

    /**
     * Récupération de la langue.
     *
     * @return string
     */
    public function getLanguage(): string;

    /**
     * Récupération de la clé publique.
     *
     * @return string|null
     */
    public function getSiteKey(): ?string;

    /**
     * Récupération de la réponse à l'issue de la soumission.
     *
     * @return ReCaptchaResponse
     */
    public function response(): ReCaptchaResponse;

    /**
     * Récupération de  la réponse à l'issue de la soumission.
     *
     * @return boolean
     */
    public function validation(): bool;
}