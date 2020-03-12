<?php declare(strict_types=1);

namespace tiFy\Partial\Driver\Progress;

use tiFy\Contracts\Partial\{PartialDriver as PartialDriverContract, Progress as ProgressContract};
use tiFy\Partial\PartialDriver;

class Progress extends PartialDriver implements ProgressContract
{
    /**
     * {@inheritDoc}
     *
     * @return array {
     * @var array $attrs Attributs HTML du champ.
     * @var string $after Contenu placé après le champ.
     * @var string $before Contenu placé avant le champ.
     * @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     * }
     */
    public function defaults(): array
    {
        return [
            'before'      => '',
            'after'       => '',
            'attrs'       => [],
            'viewer'      => [],
            // @todo 'infos'       => true,
            'label'       => '',
            'max'         => 100,
            'meter-bar'   => true,
            'meter-label' => true,
            'value'       => 0,
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): PartialDriverContract
    {
        parent::parse();

        $meterBar = $this->get('meter-bar');
        if ($meterBar !== false) {
            if (!is_array($meterBar)) {
                $this->set('meter-bar', []);
            }
            $this->set([
                'meter-bar.attrs.data-control' => 'progress.meter.bar',
            ]);
        }

        $meterLabel = $this->get('meter-label');
        if ($meterLabel !== false) {
            $defaults = [
                'content' => $this->get('label', ''),
                'tag'     => 'span',
            ];

            $this->set('meter-label', is_array($meterLabel) ? array_merge($defaults, $meterLabel) : $defaults);

            $this->set([
                'meter-label.attrs.data-control' => 'progress.meter.label',
            ]);
        }

        $this->set('meter', !!($meterBar || $meterLabel));

        /* @todo
        $infos = $this->get('infos');
        if ($infos !== false) {
            if (!is_array($infos)) {
                $this->set('infos', []);
            }
            $this->set([
                'infos.attrs.data-control' => 'progress.infos',
            ]);
        }*/

        $this->set([
            'attrs.data-control' => $this->get('attrs.data-control', 'progress'),
            'attrs.data-options' => [
                'label' => !!$this->get('label', '') ? 'fixed' : 'auto',
                'max'   => $this->get('max', 100),
                'value' => $this->get('value', 0),
            ],
        ]);

        return $this;
    }
}