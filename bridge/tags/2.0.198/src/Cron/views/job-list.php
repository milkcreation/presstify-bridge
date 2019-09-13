<?php
/**
 * Administration des tâches planifiées
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Contracts\View\ViewController $this
 * @var tiFy\Contracts\Cron\CronJob[] $jobs
 */
?>
<div class="wrap">
    <h2><?php _e('Tâches planifiées', 'tiFy');?></h2>

    <ul>
        <?php foreach ($jobs as $name => $item) :?>
            <li style="margin:0 0 20px;">
                <h3 style="margin:0 0 5px;">
                    <?php echo $item->getTitle(); ?>
                </h3>

                <div>
                    <em><?php echo $item->getDescription();?></em>
                </div>

                <div>
                    <?php
                    printf(
                        __('<b>Dernière exécution :</b> %s', 'tify'),
                        !$item->getLastDate()
                        ? __('jamais', 'tify')
                        : mysql2date(
                            sprintf(
                                __('%s à %s', 'tify'),
                                get_option('date_format'),
                                get_option('time_format')
                            ),
                            $item->getLastDate()->format('Y-m-d H:i:s'),
                            true
                        )
                    );
                    ?>
                </div>

                <div>
                    <?php
                    printf(
                        __('<b>Prochaine exécution :</b> %s', 'tify'),
                        mysql2date(
                            sprintf(
                                __('%s à %s', 'tify'),
                                get_option('date_format'),
                                get_option('time_format')
                            ),
                            $item->getNextDate()->format('Y-m-d H:i:s'),
                            true
                        )
                    );
                    ?>
                </div>
            </li>
        <?php endforeach;?>
    </ul>

</div>
<?php
