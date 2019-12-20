<?php declare(strict_types=1);

namespace tiFy\Partial\Partials\Notice;

use Closure;
use tiFy\Contracts\Partial\{Notice as NoticeContract, PartialFactory as PartialFactoryContract};
use tiFy\Partial\PartialFactory;

class Notice extends PartialFactory implements NoticeContract
{
    /**
     * {@inheritDoc}
     *
     * @return array {
     *      @var array $attrs Attributs HTML du champ.
     *      @var string $after Contenu placé après le champ.
     *      @var string $before Contenu placé avant le champ.
     *      @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     *      @var string|callable $content Texte du message de notification. défaut 'Lorem ipsum dolor site amet'.
     *      @var bool $dismiss Affichage du bouton de masquage de la notification.
     *      @var int $timeout Délai d'expiration d'affichage du message. Exprimé en secondes.
     *      @var string $type Type de notification info|warning|success|error. défaut info.
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'         => [],
            'after'         => '',
            'before'        => '',
            'viewer'        => [],
            'content' => 'Lorem ipsum dolor site amet',
            'dismiss' => false,
            'timeout' => 0,
            'type'    => 'info'
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): PartialFactoryContract
    {
        parent::parse();

        $this->set('attrs.data-control', 'notice');
        $this->set('attrs.data-timeout', $this->get('timeout', 0));

        $this->set('attrs.aria-type', $this->get('type'));

        $content = $this->get('content', '');
        $this->set('content', $content instanceof Closure ? call_user_func($content) : $content);

        if($dismiss = $this->get('dismiss')) {
            if (!is_array($dismiss)) {
                $dismiss = [];
            }

            $this->set('dismiss', partial('tag', array_merge([
                'tag'     => 'button',
                'attrs'   => [
                    'data-toggle' => 'notice.dismiss'
                ],
                'content' => '&times;'
            ], $dismiss)));
        } else {
            $this->set('dismiss', '');
        }

        return $this;
    }
}