<?php

namespace tiFy\Mail;

use tiFy\Contracts\Cron\CronJob;
use tiFy\Contracts\Mail\Mailer;
use tiFy\Contracts\Mail\MailQueue as MailQueueContract;

class MailQueue implements MailQueueContract
{
    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        db()->register('mail.queue', [
            'name'          => 'mail_queue',
            'install'       => true,
            'col_prefix'    => 'mq_',
            'meta'          => true,
            'columns'       => [
                'id'                => [
                    'type'              => 'BIGINT',
                    'size'              => 20,
                    'unsigned'          => true,
                    'auto_increment'    => true
                ],
                'session_id'        => [
                    'type'              => 'VARCHAR',
                    'size'              => 32,
                    'default'           => null
                ],
                'date_created'      => [
                    'type'              => 'DATETIME',
                    'default'           => '0000-00-00 00:00:00'
                ],
                'date_created_gmt'  => [
                    'type'              => 'DATETIME',
                    'default'           => '0000-00-00 00:00:00'
                ],
                'sending'           => [
                    'type'              => 'VARCHAR',
                    'size'              => 10,
                ],
                'params'            => [
                    'type'              => 'LONGTEXT'
                ]
            ]
        ]);

        cron()->register('mail.queue', [
            'title'         => __('File d\'expédition des emails', 'tify'),
            'description'   => __('Expédition des emails en partance de la file d\'attente.', 'tify'),
            'freq'    => [
                'id'            => 'every_minute',
                'interval'      => 60,
                'display'       => __('Chaque minute', 'tify')
            ],
            'command'        => function ($args, CronJob $job) {
                if ($queue = db('mail.queue')) :
                    if (
                        $emails = $queue->select()->rows(
                            [
                                'sending'   => [
                                    'value'     => (new \DateTime())->getTimestamp(),
                                    'compare'   => '<='
                                ],
                                'orderby'   => 'sending',
                                'order'     => 'ASC'
                            ]
                        )
                    ) :
                        foreach ($emails as $email) :
                            $params = unserialize(base64_decode($email->mq_params));
                            $queue->handle()->delete_by_id($email->mq_id);

                            /** @var Mailer $mailer */
                            $mailer = app()->get('mailer');
                            $mailer->send($params);

                            $job->log()->notice(__('Email expédié avec succès', 'tify'));
                        endforeach;
                    endif;
                endif;
            }
        ]);
    }

    /**
     * Ajout d'un élément dans la file d'attente
     *
     * @param array $params Paramètre d'expédition du mail.
     * @param string $sending Date de programmation d'envoi du mail au format timestamp.
     * @param array $item_meta Données complémentaires d'envoi du mail.
     *
     * @return int
     */
    public function add($params, $date = 'now', $item_meta = [])
    {
        if ($queue = db('mail.queue')) :
            $id = 0;
            $session_id = uniqid('tFymq_', true);
            $date_created = (new \DateTime(null, new \DateTimeZone(get_option('timezone_string'))))->format('Y-m-d H:i:s');
            $date_created_gmt = (new \DateTime())->format('Y-m-d H:i:s');
            $sending = (new \DateTime($date, new \DateTimeZone(get_option('timezone_string'))))->getTimestamp();
            $params = base64_encode(serialize($params));
            $data = compact(['id', 'session_id', 'date_created', 'date_created_gmt', 'sending', 'params', 'item_meta']);

            return ($insert_id = $queue->handle()->create($data))
                ? $queue->select()->cell_by_id($id, 'session_id')
                : 0;
        endif;

        return 0;
    }
}