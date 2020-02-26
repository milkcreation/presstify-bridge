<?php declare(strict_types=1);

namespace tiFy\Session;

use Psr\Container\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Session\Session as BaseSession;
use tiFy\Contracts\Session\{FlashBag as FlashBagContract, Session as SessionContract, Store as StoreContract};

class Session extends BaseSession implements SessionContract
{
    /**
     * Instance du conteneur d'injection de dépendances.
     * @var Container|null
     */
    protected $container;

    /**
     * Instance du gestionnaire d'attributs de session ephémères.
     * @var FlashbagContract
     */
    protected $flashBag;

    /**
     * Liste des instances de gestionnaire de stockage de session déclarés.
     * @var StoreContract[]
     */
    protected $stores = [];

    /**
     * CONSTRUCTEUR.
     *
     * @param Container|null $container Instance du conteneur d'injection de dépendances.
     *
     * @return void
     */
    public function __construct(?Container $container = null)
    {
        $this->container = $container;

        parent::__construct();

        $this->registerBag($this->flashBag());
    }

    /**
     * @inheritDoc
     */
    public function flash($key = null, $value = null)
    {
        $flash = $this->getFlashBag();

        if (is_null($key)) {
            return $flash;
        } elseif (is_array($key)) {
            foreach($key as $k => $v) {
                $flash->add($k, $v);
            }

            return $this;
        } elseif (is_string($key)) {
            return $flash->get($key, is_array($value) ? $value : (array)$value);
        }

        return null;
    }

    /**
     * {@inheritDoc}
     *
     * @return FlashbagContract
     */
    public function flashBag(): FlashbagContract
    {
        if (is_null($this->flashBag)) {
            $this->flashBag = new FlashBag();
        }

        return $this->flashBag;
    }

    /**
     * @inheritDoc
     */
    public function getContainer(): ?Container
    {
        return $this->container;
    }

    /**
     * @inheritDoc
     *
     * @todo
     */
    public function reflash(?array $keys = null): SessionContract
    {
        return !is_null($keys) ? $this->flash($this->flash()->all()) : $this->flash($this->flash()->only($keys)) ;
    }

    /**
     * @inheritDoc
     */
    public function registerStore(string $name, ...$args): ?StoreContract
    {
        if (isset($this->stores[$name])) {
            return $this->stores[$name];
        }

        $store = ($container = $this->getContainer()) ? new Store($this) : $container->get('session.store');

        return $this->stores[$name] = $store->setName($name)->prepare();
    }

    /**
     * @inheritDoc
     */
    public function store(string $name): ?StoreContract
    {
        return $this->stores[$name] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setContainer(Container $container): SessionContract
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @inheritdoc
    public function getDb(): DbFactory
    {
        add_action('init', function () {
            // Initialisation de la base de données
            if (!empty($this->items)) :
                cron()->register('session.cleanup', [
                    'title'   => __('Nettoyage de sessions', 'tiFy'),
                    'desc'    => __('Suppression de la liste des sessions arrivée à expiration.', 'tiFy'),
                    'freq'    => 'twicedaily',
                    'command' => function () {
                        if (!defined('WP_SETUP_CONFIG') && !defined('WP_INSTALLING')) :
                            $this->getDb()->handle()->query(
                                $this->getDb()->handle()->prepare(
                                    "DELETE FROM " .
                                    $this->getDb()->getTableName() .
                                    " WHERE session_expiry < %d", time()
                                )
                            );
                        endif;
                    },
                ]);
            endif;
        }, 0);

        add_action('wp_footer', function () {
            if (config('user.session.debug', false)) :
                foreach ($this->items as $item) :
                    ?>
                    <div style="position:fixed;right:0;bottom:0;width:300px;">
                    <ul>
                        <li>name : <?php echo $item->getName(); ?></li>
                        <li>key : <?php echo $item->getSession('session_key'); ?></li>
                        <li>datatest : <?php echo $item->get('rand_test'); ?></li>
                    </ul>
                    </div><?php
                endforeach;
            endif;
        });

        if (is_null($this->db)) :
            if (!$this->db = db('session')) :
                $this->db = db()->register('session', [
                    'install'    => true,
                    'name'       => 'tify_session',
                    'primary'    => 'session_key',
                    'col_prefix' => 'session_',
                    'meta'       => false,
                    'columns'    => [
                        'id'     => [
                            'type'           => 'BIGINT',
                            'size'           => 20,
                            'unsigned'       => true,
                            'auto_increment' => true
                        ],
                        'name'   => [
                            'type'           => 'VARCHAR',
                            'size'           => 255,
                            'unsigned'       => false,
                            'auto_increment' => false
                        ],
                        'key'    => [
                            'type'           => 'CHAR',
                            'size'           => 32,
                            'unsigned'       => false,
                            'auto_increment' => false
                        ],
                        'value'  => [
                            'type' => 'LONGTEXT'
                        ],
                        'expiry' => [
                            'type'     => 'BIGINT',
                            'size'     => 20,
                            'unsigned' => true
                        ]
                    ],
                    'keys'       => ['session_id' => ['cols' => 'session_id', 'type' => 'UNIQUE']],
                ]);
            endif;
        endif;

        if (!$this->db instanceof DbFactory) :
            throw new \Exception(
                __('La table de base de données de stockage des sessions est indisponible.', 'tify'), 500
            );
        endif;

        return $this->db;
    }  */
}