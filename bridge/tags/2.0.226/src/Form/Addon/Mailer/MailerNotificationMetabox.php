<?php declare(strict_types=1);

namespace tiFy\Form\Addon\Mailer;

use tiFy\Contracts\Metabox\MetaboxDriver as MetaboxDriverContract;
use tiFy\Form\AddonMetaboxDriver;

class MailerNotificationMetabox extends AddonMetaboxDriver
{
    /**
     * Liste des noms d'enregistement des options.
     * @var array
     */
    protected $optionNames = [];

    /**
     * @inheritDoc
     */
    public function content(): string
    {
        $this->set([
            'option_names'  => $this->optionNames,
            'option_values' => [
                'notification' => get_option($this->optionNames['notification'], 'off') ?: 'off',
                'recipients'   => get_option($this->optionNames['recipients']) ?: [],
            ],
        ]);

        return (string)$this->viewer('notification', $this->all());
    }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'title' => __('Notification', 'tify'),
        ]);
    }

    /**
     * @inheritDoc
     */
    public function parse(): MetaboxDriverContract
    {
        parent::parse();

        $this->optionNames = $this->addon()->params('option_names', []);

        return $this;
    }
}