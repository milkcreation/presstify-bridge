<?php declare(strict_types=1);

namespace tiFy\Form\Addon\Mailer;

use tiFy\Contracts\Metabox\MetaboxDriver as MetaboxDriverContract;
use tiFy\Form\AddonMetaboxDriver;

class MailerConfirmationMetabox extends AddonMetaboxDriver
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
                'confirmation' => get_option($this->optionNames['confirmation'], 'off') ?: 'off',
                'sender'       => array_merge([
                    'email' => get_option('admin_email'),
                    'name'  => '',
                ], get_option($this->optionNames['sender']) ?: []),
            ],
        ]);

        return (string)$this->viewer('confirmation', $this->all());
    }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'title' => __('Confirmation', 'tify'),
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