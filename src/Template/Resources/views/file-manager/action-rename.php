<?php
/**
 * Gestionnaire de fichiers > Formulaire de renommage d'un élément.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\FileManager\View $this
 * @var tiFy\Template\Templates\FileManager\Contracts\FileInfo $file
 */
?>
<div class="FileManager-action FileManager-action--toggleable FileManager-action--rename"
     data-control="file-manager.action.rename"
>
    <h3 class="FileManager-title"><?php _e('Renommer', 'tify'); ?></h3>

    <div class="FileManager-actionNotices"></div>

    <form class="FileManager-actionForm" method="post" action="" data-control="file-manager.action.rename.form">
        <div class="FileManager-actionFormFields">
            <?php echo field('hidden', ['name' => 'path', 'value' => $file->getRelPath()]); ?>

            <div class="FileManager-actionFormField FileManager-actionFormField--name">
                <?php echo field('text', [
                    'name'  => 'name',
                    'value' => $file->getBasename($file->getExtension() ? ".{$file->getExtension()}" : ''),
                    'attrs' => [
                        'placeholder' => __('Saisissez le nouveau nom ...', 'tify')
                    ]
                ]); ?>
                <?php if ($file->getExtension()) : ?>
                    <div class="FileManager-actionFormExt">
                        <?php echo ".{$file->getExtension()}"; ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($file->isFile()) : ?>
                <?php echo field('checkbox', [
                    'after'   => (string)field('label', [
                        'attrs'   => [
                            'for' => 'FileManager-actionFormRename--keep',
                        ],
                        'content' => 'Conserver l\'extension du fichier'
                    ]),
                    'attrs'   => [
                        'id'          => 'FileManager-actionFormRename--keep',
                        'style'       => 'display:inline-block',
                        'data-toggle' => '.FileManager-actionFormExt'
                    ],
                    'checked' => true,
                    'name'    => 'keep',
                    'value'   => 'on'
                ]); ?>
            <?php else : ?>
                <?php echo field('hidden', [
                    'name'  => 'keep',
                    'value' => 'off'
                ]); ?>
            <?php endif; ?>
        </div>

        <div class="FileManager-actionFormButtons">
            <?php echo field('button', [
                'attrs'   => [
                    'class' => 'FileManager-button FileManager-button--valid FileManager-actionButton'
                ],
                'type'    => 'submit',
                'content' => __('Valider', 'tify')
            ]); ?>

            <?php echo field('button', [
                'attrs'   => [
                    'class'        => 'FileManager-button FileManager-button--cancel FileManager-actionButton',
                    'data-control' => 'file-manager.action.toggle',
                    'data-action'  => 'rename',
                    'data-reset'   => 'true'
                ],
                'type'    => 'button',
                'content' => __('Annuler', 'tify')
            ]); ?>
        </div>
    </form>

    <a href="#"
       class="FileManager-actionClose"
       data-control="file-manager.action.toggle"
       data-action="rename">
        <?php echo $this->getIcon('close'); ?>
    </a>
</div>