<?php declare(strict_types=1);

namespace tiFy\Form\Addon\Record;

use Illuminate\Database\Schema\Blueprint;
use tiFy\Form\Addon\Record\ListTable\{Model as ListTableModel, Factory as ListTableFactory};
use tiFy\Form\AddonFactory;
use tiFy\Support\{DateTime, Str};
use tiFy\Support\Proxy\{Database, Schema, Template};

/**
 * USAGE :
 * ---------------------------------------------------------------------------------------------------------------------
 * Configuration standard des champs :
 * 'fields'    => [
 *      {...}
 *      [
 *          {...}
 *          'addons'        => [
 *              //
 *              'record'        => [
 *                  // Active l'affichage de la colonne pour ce champ. true par défaut.
 *                  // Par défaut le label du champ de formulaire est utilisé en tant qu'intitulé de colonne.
 *                  // Utiliser une chaîne de caractère pour personnaliser l'intitulé.
 *                  // Utiliser un tableau pour personnaliser la colonne
 *                  // @see \tiFy\Template\Templates\ListTable\Factory.
 *                  // @var $column boolean|string|array
 *                  'column'         => true,
 *                  // Active l'affichage de l'aperçu en ligne pour ce champ. true par défaut.
 *                  // Par défaut le label du champ de formulaire est utilisé en tant qu'intitulé de qualification.
 *                  // Utiliser une chaîne de caractère pour personnaliser.
 *                  'preview'        => true
 *                  // Active l'enregistrement du champ. true par défaut.
 *                  // Par défaut l'identifiant du champ de formulaire est utilisé en tant qu'indice de qualification.
 *                  // Utiliser une chaîne de caractère pour personnaliser.
 *                  'save'        => true
 *              ]
 *              {...}
 *          ]
 *      ]
 *      {...}
 * ];
 */
class Record extends AddonFactory
{
    /**
     * Indicateur d'existance d'une instance.
     * @var boolean
     */
    protected static $instance = false;

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        Database::addConnection(
            array_merge(
                Database::getConnection()->getConfig(), ['strict' => false]
            ),
            'form.addon.record'
        );

        if (is_multisite()) {
            global $wpdb;

            Database::getConnection('form.addon.record')->setTablePrefix($wpdb->prefix);
        }


        $schema = Schema::connexion('form.addon.record');

        if (!$schema->hasTable('tify_forms_record')) {
            $schema->create('tify_forms_record', function (Blueprint $table) {
                $table->bigIncrements('ID');
                $table->string('form_id', 255);
                $table->string('session', 32);
                $table->string('status', 32)->default('publish');
                $table->dateTime('created_date')->default('0000-00-00 00:00:00');
                $table->index('form_id', 'form_id');
            });
        }

        if (!$schema->hasTable('tify_forms_recordmeta')) {
            $schema->create('tify_forms_recordmeta', function (Blueprint $table) {
                $table->bigIncrements('meta_id');
                $table->bigInteger('tify_forms_record_id')->default(0);
                $table->string('meta_key', 255)->nullable();
                $table->longText('meta_value')->nullable();
                $table->index('tify_forms_record_id', 'tify_forms_record_id');
                $table->index('meta_key', 'meta_key');
            });
        }

        if (! self::$instance) {
            self::$instance = true;

            add_action('admin_menu', function () {
                add_menu_page(
                    __('Formulaires', 'tify'),
                    __('Formulaires', 'tify'),
                    null,
                    'form_addon_record',
                    '',
                    'dashicons-clipboard'
                );
            });
        }

        $this->form()->events()->listen('form.prepared', function () {
            $columns = [
                '__record' => [
                    'content' => function ($item) {
                        return (string) $this->form()->viewer(
                            'addon/record/list-table/col-details',
                            compact('item')
                        );
                    },
                    'title'   => __('Informations d\'enregistrement', 'tify')
                ]
            ];

            foreach ($this->form()->fields() as $field) {
                if ($column  = $field->getAddonOption('record.column')) {
                    if (is_string($column)) {
                        $column = ['title' => $column];
                    } elseif (!is_array($column)) {
                        $column = [];
                    }

                    $slug = $field->getSlug();
                    $columns[$slug] = array_merge([
                        'title'     => $field->getTitle(),
                        'content'   => function ($item) use ($slug) {
                            return $item->{$slug} ?? '';
                        }
                    ], $column);
                }
            }

            Template::set(
                'FormAddonRecord'. Str::studly($this->form()->name()),
                (new ListTableFactory())->setAddon($this)->set([
                    'labels'    => [
                        'gender'   => $this->form()->label()->gender(),
                        'singular' => $this->form()->label()->singular(),
                        'plural'   => $this->form()->label()->plural(),
                    ],
                    'params'    => [
                        'bulk-actions' => false,
                        'columns'      => $columns,
                        'query_args'   => [
                            'order' => 'DESC'
                        ],
                        'row-actions'  => false,
                        'search'       => false,
                        'view-filters' => false,
                        'wordpress'    => [
                            'admin_menu' => [
                                'parent_slug' => 'form_addon_record'
                            ],
                        ],
                    ],
                    'providers'    => [
                        'db'    => (new ListTableModel())->setAddon($this)
                    ]
                ])
            );
        });

        $this->form()->events()
            ->listen('request.proceed',  function () {
                $this->form()->events('addon.record.save');
            })
            ->listen('addon.record.save', [$this, 'save']);
    }

    /**
     * @inheritDoc
     */
    public function defaultsFieldOptions(): array
    {
        return [
            //'export'   => false,
            //'editable' => false,
            'column'   => true,
            'preview'  => true,
            'save'     => true,
        ];
    }

    /**
     * Sauvegarde des données de formulaire.
     *
     * @return void
     */
    public function save()
    {
        $datas = [
            'form_id'      => $this->form()->name(),
            'session'      => $this->form()->session()->create(),
            'status'       => 'publish',
            'created_date' => DateTime::now()->toDateTimeString(),
        ];

        if ($id = RecordModel::insertGetId($datas)) {
            $record = RecordModel::find($id);

            foreach ($this->form()->fields() as $field) {
                if ($column = $field->getAddonOption('record.save')) {
                    $record->saveMeta($field->getSlug(), wp_unslash($field->getValues()));
                }
            }
        }
    }
}