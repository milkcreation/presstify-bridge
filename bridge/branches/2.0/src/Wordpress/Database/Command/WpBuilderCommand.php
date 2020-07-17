<?php declare(strict_types=1);

namespace tiFy\Wordpress\Database\Command;

use Exception;
use Illuminate\Database\Eloquent\{Builder, Collection as BaseCollection, Model as BaseModel};
use Symfony\Component\Console\{Input\InputInterface, Input\InputOption, Output\OutputInterface};
use tiFy\Contracts\Log\Logger as LoggerContract;
use tiFy\Console\Command as BaseCommand;
use tiFy\Support\{DateTime, MessagesBag, ParamsBag, Proxy\Log};

class WpBuilderCommand extends BaseCommand
{
    /**
     * Instance du contructeur de récupération des éléments.
     * @var BaseModel|Builder
     */
    protected $builder;

    /**
     * Nombre d'éléments par portion de traitement.
     * @var int
     */
    protected $chunk = 100;

    /**
     * Liste des clés primaires de limitation de la requête de récupération des éléments.
     * @var int[]|array
     */
    protected $contraintIds = [];

    /**
     * Compteur d'occurence.
     * @var int
     */
    protected $counter = 0;

    /**
     * Format d'affichage de la date.
     * @var string
     */
    protected $dateFormat = 'd/m/Y H:i:s';

    /**
     * Instance de la liste des données associés à l'élément courant.
     * @var ParamsBag
     */
    protected $itemDatas;

    /**
     * Initialisation de la journalisation.
     * @var bool
     */
    protected $logger = true;

    /**
     * Données d'enregistrement de l'élément.
     * @var MessagesBag
     */
    protected $message;

    /**
     * Nom de la classe du modèle associé.
     * @var string
     */
    protected $modelClassname;

    /**
     * Enregistrement de démarrage du traitement.
     * @var int
     */
    protected $offset = 0;

    /**
     * Clé primaire.
     *
     * @var string|null
     */
    protected $primaryKey;

    /**
     * Liste des arguments de requête complémentaires de récupération des éléments.
     * @var array
     */
    protected $queryArgs = [];

    /**
     * Nombre total d'enregistrements.
     * @var int
     */
    protected $total = 0;

    /**
     * CONSTRUCTEUR.
     *
     * @param string|null $name
     *
     * @return void
     */
    public function __construct(?string $name = null)
    {
        parent::__construct($name);

        $this
            ->addOption('url', null, InputOption::VALUE_OPTIONAL, __('Url du site', 'tify'), '')
            ->addOption(
                'id',
                null,
                InputOption::VALUE_OPTIONAL,
                __('Identifiant(s) de qualification (séparateur virgule)', 'tify'),
                0
            )
            ->addOption(
                'offset', null, InputOption::VALUE_OPTIONAL, __('Numéro d\'enregistrement de démarrage', 'tify'), 0
            )
            // @todo
            ->addOption(
                'length', null, InputOption::VALUE_OPTIONAL, __('Nombre d\'enregistrements à traiter', 'tify'), -1
            );
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->handleBefore();

        if ($ids = $input->getOption('id')) {
            $ids = array_map('intval', explode(',', $ids));
            foreach ($ids as $id) {
                $this->setContraintId($id);
            }
        }

        if ($offset = (int)$input->getOption('offset') ?: 0) {
            $this->setOffset($offset);
        }

        if (($length = (int)$input->getOption('length')) && ($length > 0)) {
            $maxId = $this->fetchQuery()->orderBy($this->getPrimaryKey(), 'asc')
                ->skip($this->getOffset())->take($length)->select($this->getPrimaryKey())->get()->last()
                ->{$this->getPrimaryKey()};

            $this->total = $length;
        } else {
            $maxId = $this->fetchQuery()->orderBy($this->getPrimaryKey(), 'asc')
                ->select($this->getPrimaryKey())->get()->last()
                ->{$this->getPrimaryKey()};
            $this->total = $this->countQuery()->count() - $this->getOffset();
        }

        $this->counter = 0;

        $start = DateTime::now(DateTime::getGlobalTimeZone());

        $output->writeln([
            '=====================================================================================================',
            sprintf(__('Début de la tâche : %s', 'tify'), $this->getDescription()),
            '=====================================================================================================',
            sprintf(__('Démarrage des opérations : %s', 'tify'), $start->format($this->dateFormat)),
            sprintf(__('Nombre d\'élements à traiter : %d', 'tify'), $this->total),
            ''
        ]);

        $this->fetchQuery()->skip($this->getOffset())->where('id', '<=', $maxId)
            ->chunkById($this->getChunk(), function (BaseCollection $items) use ($output) {
                $this->handleItems($items, $output);
            });

        $this->handleAfter();

        $end = DateTime::now(DateTime::getGlobalTimeZone());

        $output->writeln([
            '',
            '=====================================================================================================',
            sprintf(__('Fin de la tâche : %s', 'tify'), $this->getDescription()),
            '=====================================================================================================',
            sprintf(__('Démarrage des opérations : %s', 'tify'), $start->format($this->dateFormat)),
            sprintf(__('Achévement des opérations : %s', 'tify'), $end->format($this->dateFormat)),
            '=====================================================================================================',
        ]);
    }

    /**
     * Requête de récupération du compte du nombre d'éléments.
     *
     * @return Builder
     */
    public function countQuery(): Builder
    {
        return $this->fetchQuery();
    }

    /**
     * Requête de récupération de la liste des éléments.
     *
     * @return Builder
     */
    public function fetchQuery(): Builder
    {
        $builder = $this->getBuilder();

        $query = $builder;

        if ($ids = $this->getContraintIds()) {
            $query->whereIn($this->getPrimaryKey(), $ids);
        } else {
            $query->where($this->getQueryArgs());
        }

        return $query;
    }

    /**
     * Récupération de l'instance du modèle.
     *
     * @return Builder
     */
    public function getBuilder(): ?Builder
    {
        $classname = $this->modelClassname;

        try {
            $instance = new $classname();
            if ($instance instanceof BaseModel) {
                $builder = $instance->newQuery();
            }
        } catch (Exception $e) {
            $builder = null;
        }

        return isset($builder) && $builder instanceof Builder ? $builder : null;
    }

    /**
     * Récupération du nombre d'élements traités par lot.
     *
     * @return int
     */
    public function getChunk(): int
    {
        return $this->chunk;
    }

    /**
     * Récupération de la liste des identifiants de qualification de contrainte de la requête de récupération des
     * éléments.
     *
     * @return array
     */
    public function getContraintIds(): array
    {
        return $this->contraintIds;
    }

    /**
     * Récupération du compteur d'élément.
     *
     * @return string
     */
    public function getCounter()
    {
        return "{$this->counter}/$this->total";
    }

    /**
     * Récupération de la liste des argument de requête de récupération des éléments.
     *
     * @return array
     */
    public function getQueryArgs(): array
    {
        return $this->queryArgs;
    }

    /**
     * Récupération de l'enregistrement de démarrage.
     *
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * Récupération de la clé primaire.
     *
     * @return string
     */
    public function getPrimaryKey(): string
    {
        if (is_null($this->primaryKey)) {
            $this->primaryKey = (($builder = $this->getBuilder()) && $model = $builder->getModel())
                ? $model->getKeyName() : 'id';
        }

        return $this->primaryKey;
    }

    /**
     * Pré-traitement de la tâche.
     *
     * @return void
     */
    public function handleBefore(): void
    {
    }

    /**
     * Post-traitement de tâche.
     *
     * @return void
     */
    public function handleAfter(): void
    {
    }

    /**
     * Traitement des résultats de requête.
     *
     * @param BaseCollection|BaseModel[] $items
     * @param OutputInterface $output
     *
     * @return void
     *
     * @throws Exception
     */
    public function handleItems(BaseCollection $items, OutputInterface $output): void
    {
        foreach ($items as $item) {
            $this->itemDatas()->clear();

            $this->counter++;

            $this->handleItemBefore($item);

            try {
                $this->handleItem($item);
            } catch (Exception $e) {
                $this->message()->error($e->getMessage());
            }

            $this->handleItemAfter($item);

            $this->handleMessages($output);
        }
    }

    /**
     * Traitement d'un élément.
     *
     * @param BaseModel|object $item
     *
     * @return void
     *
     * @throws Exception
     */
    public function handleItem(object $item): void
    {
    }

    /**
     * Post-traitement de la tâche d'un élément.
     *
     * @param BaseModel|object $item Instance de l'élément.
     *
     * @return void
     */
    public function handleItemAfter(object $item): void
    {
    }

    /**
     * Pré-traitement de l'import d'un élément.
     *
     * @param BaseModel|object $item Instance de l'élément.
     *
     * @return void
     */
    public function handleItemBefore(object $item): void
    {
    }

    /**
     * Traitement des messages de notification.
     *
     * @param OutputInterface $output
     * @param bool $forget Suppression des messages
     *
     * @return void
     */
    public function handleMessages(OutputInterface $output, bool $forget = true): void
    {
        foreach ($this->message()->all() as $level => $messages) {
            foreach($messages as $message) {
                $this->log($level, $message);
            }

            $output->writeln($messages);
        }

        if ($forget = true) {
            $this->message()->flush();
        }
    }

    /**
     * Définition de paramètres(s)|Récupération de paramètres|Récupération de l'instance des paramètres de l'élément
     * courant.
     *
     * @param array|string|null $key Liste des définitions|Indice de qualification du paramètre (Syntaxe à point)|null.
     * @param mixed $default Valeur de retour par défaut lors de la récupération d'une donnée.
     *
     * @return mixed|ParamsBag
     */
    public function itemDatas($key = null, $default = null)
    {
        if (!$this->itemDatas instanceof ParamsBag) {
            $this->itemDatas = new ParamsBag();
        }

        if (is_string($key)) {
            return $this->itemDatas->get($key, $default);
        } elseif (is_array($key)) {
            return $this->itemDatas->set($key);
        } else {
            return $this->itemDatas;
        }
    }

    /**
     * Jornalisation.
     *
     * @param $level
     * @param string $message
     * @param array $context
     *
     * @return string|LoggerContract|null
     */
    public function log($level = null, string $message = '', array $context = [])
    {
        if ($this->logger === false) {
            return null;
        } elseif (!$this->logger instanceof LoggerContract) {
            $this->logger = Log::registerChannel(
                '[command]' . preg_replace('/\:/', '@', $this->getName()),
                is_array($this->logger) ? $this->logger : []
            );
        }

        if (is_null($level)) {
            return $this->logger ?: null;
        } else {
            $this->logger->log($level, $message, $context);
        }

        return null;
    }

    /**
     * Ajout d'un message ou récupération de l'instance du gestionnaire de message.
     *
     * @param string|int|null $level
     * @param string $message
     * @param array|null $data
     * @param string $code
     *
     * @return MessagesBag|string|null
     */
    public function message($level = null, string $message = null, ?array $data = [], ?string $code = null)
    {
        if (is_null($this->message)) {
            $this->message = new MessagesBag();
        }

        if (is_null($level)) {
            return $this->message;
        } else {
            return $this->message->add($level, $message, $data, $code);
        }
    }

    /**
     * Définition du nombre d'élements traités par lot.
     *
     * @param int $chunk
     *
     * @return static
     */
    public function setChunk(int $chunk = 100): self
    {
        $this->chunk = $chunk;

        return $this;
    }

    /**
     * Définition d'un identifiant de qualification de contrainte de requête de récupération des éléments.
     *
     * @param int $id
     *
     * @return static
     */
    public function setContraintId(int $id = 0): self
    {
        $this->contraintIds[] = $id;

        return $this;
    }

    /**
     * Définition de la classe du modèle.
     *
     * @param Builder|BaseModel $builder
     *
     * @return static
     */
    public function setBuilder(Builder $builder): self
    {
        $this->builder = $builder;

        return $this;
    }

    /**
     * Définition de la classe du modèle.
     *
     * @param string $classname
     *
     * @return static
     */
    public function setModelClassname(string $classname): self
    {
        $this->modelClassname = $classname;

        return $this;
    }

    /**
     * Définition de l'enregistrement de démarrage.
     *
     * @param int $offset
     *
     * @return static
     */
    public function setOffset(int $offset = 0): self
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * Définition de la clé primaire de traitement des requêtes.
     *
     * @param string $key
     *
     * @return static
     */
    public function setPrimaryKey(string $key): self
    {
        $this->primaryKey = $key;

        return $this;
    }

    /**
     * Définition des arguments de requête de récupération des éléments.
     *
     * @param array $args
     *
     * @return static
     */
    public function setQueryArgs(array $args = []): self
    {
        $this->queryArgs = $args;

        return $this;
    }
}