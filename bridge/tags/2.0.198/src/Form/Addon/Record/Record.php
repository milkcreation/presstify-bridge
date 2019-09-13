<?php

/**
 * USAGE
 * Configuration des champs
    Standard
    ----------------------------------------
    'fields'    => array(
        [...]
        array(
            [...]
             'addons'        => array(
                'record'        => array(
                    // Active l'affichage de la colonne pour ce champ, le label du champ de formulaire est utilisé comme intitulé de colonne
                    'column'         => true,
                    // Active l'affichage de l'aperçu en ligne pour ce champ, le label du champ de formulaire est utilisé comme intitulé
                    'preview'        => true
                )
            )
        )
        [...]
    )
    Avancée
    ----------------------------------------
    'fields'    => array(
        [...]
        array(
            [...]
             'addons'        => array(
                'record'        => array(
                    // Active l'affichage de la colonne pour ce champ
                    'column'         => 'intitulé personnalisé',
                    // Active l'affichage de l'aperçu en ligne pour ce champ
                    'preview'        => 'intitulé personnalisé'
                )
            )
        )
        [...]
    )
 */


namespace tiFy\Form\Addon\Record;

use tiFy\Contracts\Db\DbFactory;
use tiFy\Contracts\Form\FormFactory;
use tiFy\Form\AddonController;
use tiFy\Template\Templates\ListTable\ListTable;
use tiFy\Template\Templates\ListTable\Contracts\Item;

class Record extends AddonController
{
    /**
     * Liste des options par défaut du formulaire associé.
     * @var array
     */
    protected $defaultFormOptions = [
        'cb'     => ListTable::class,
        'export' => false
    ];

    /**
     * Liste des options par défaut des champs du formulaire associé.
     * @var array
     */
    protected $defaultFieldOptions = [
        'record'   => true,
        'export'   => false,
        'column'   => false,
        'preview'  => false,
        'editable' => false,
    ];

    /**
     * Indicateur d'existance d'une instance
     */
    protected static $instance = false;

    /**
     * Indicateur d'activation de l'export.
     * @var bool
     */
    protected static $export = false;

    /**
     * Instance du controleur de base de données.
     * @var DbFactory
     */
    protected $db;

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        /*$this->events()
            ->listen('request.success', [$this, 'onRequestSuccess']);*/

        if (!$this->db = db('form.addon.record.db')) :
            $this->db = db()->register(
                'form.addon.record.db',
                [
                    'install'    => true,
                    'name'       => 'form_record',
                    'col_prefix' => 'record_',
                    'columns'    => [
                        'ID'      => [
                            'type'           => 'BIGINT',
                            'size'           => 20,
                            'unsigned'       => true,
                            'auto_increment' => true,
                            'prefix'         => false,
                        ],
                        'form_id' => [
                            'type'   => 'VARCHAR',
                            'size'   => 255,
                            'prefix' => false,
                        ],
                        'session' => [
                            'type' => 'VARCHAR',
                            'size' => 32,
                        ],
                        'status'  => [
                            'type'    => 'VARCHAR',
                            'size'    => 32,
                            'default' => 'publish',
                        ],
                        'date'    => [
                            'type'    => 'DATETIME',
                            'default' => '0000-00-00 00:00:00',
                        ],
                    ],
                    'keys'       => ['form_id' => 'form_id'],
                    'meta'       => true,
                ]
            );
        endif;

        if (! self::$instance) :
            self::$instance = true;

            template()->register(
                'form.addon.record.template',
                [
                    'admin_menu' => [
                        'menu_title' => __('Formulaires', 'tify'),
                        'menu_slug'  => 'form_addon_record',
                        'icon_url'   => 'dashicons-clipboard',
                    ],
                    'content' => function () {
                        return '';
                    }
                ]
            );

            $this->events()->listen('form.init', function (FormFactory $form) {
                $columns = ['cb'];
                foreach($this->fields() as $field) :
                    $columns[$field->getName()] = $field->getName();
                endforeach;

                template()->register(
                    "form.addon.record.template.{$this->form()->name()}",
                    new ListTable(
                        [
                            'admin_menu' => [
                                'parent_slug' => 'form_addon_record',
                                'menu_slug'   => 'form_addon_record',//"form_addon_record_{$this->form()->name()}",
                                'menu_title'  => $this->form()->getTitle(),
                                'position'    => $this->form()->index(),
                            ],
                            'params' => [
                                //'columns' => $columns
                            ],
                            'providers' => [
                                'db' => $this->db
                            ]
                        ]
                    )
                );
            });
        endif;

        /*
            $this->appAddAction('tify_templates_register');
            $this->appAddAction('tify_db_register');

            events()->listen(
                'wp.media.download.register',
                function ($abspath, MediaDownload $mediaDownload, $event) {
                    $authorize = request()->get('authorize');

                    if (get_transient($_REQUEST['authorize'])) :
                        $download->register($abspath);
                    endif;
                }
            );
        */

    }

    /**
     * Initialisation de l'addon.
     *
     * @return void
     */
    public function appBoot()
    {
        if (! self::$export && $this->getFormAttr('export', false)) :
            self::$export = true;
        endif;
    }

    /**
     * Définition d'interface d'administration
     *
     * @return void
     */
    public function tify_templates_register()
    {
        if (self::$export) :
            Templates::register(
                'tiFyCoreFormsAddonsRecordExport',
                [
                    'admin_menu' => [
                        'parent_slug' => 'tify_forms_record',
                        'menu_slug'   => 'tify_forms_record_export',
                        'menu_title'  => __('Exporter', 'tify'),
                        'position'    => 2,
                    ],
                    'cb'         => Export::class,
                    'db'         => 'tify_forms_record',
                ],
                'admin'
            );
        endif;
    }

    /**
     * Déclaration de la gestion des données en base.
     *
     * @param Db $db Classe de rappel de traitement des données en base.
     *
     * @return  void
     */
    public function tify_db_register($db)
    {
        $db->register(
            'tify_forms_record',
            self::$dbAttrs
        );
    }

    /**
     * Court-circuitage de l'issue d'un traitement de formulaire réussi.
     *
     * @param FactoryRequest $request Instance du contrôleur de traitement de la requête de soumission associée au formulaire.
     *
     * @return void
     */
    public function onRequestSuccess($handle)
    {
        $datas = [
            'form_id'        => $this->getForm()->getName(),
            'record_session' => $this->getForm()->getSession(),
            'record_status'  => 'publish',
            'record_date'    => current_time('mysql'),
            'item_meta'      => $this->getForm()->getFieldsValues(),
        ];

        // Définition de la base de données (front)
        if (! db()->get('tify_forms_record')) :
            db()->register('tify_forms_record', self::$dbAttrs);
        endif;

        db('tify_forms_record')->handle()->create($datas);
    }
}