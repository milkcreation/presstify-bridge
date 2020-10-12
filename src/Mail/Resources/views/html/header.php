<?php
/**
 * @var tiFy\Contracts\Mail\MailView $this
 */
?>
<?php if ($logo = $this->get('infos.logo')) : ?>
    <tr class="rowHeaderContent">
        <td>
            <?php echo partial('tag', [
                'attrs' => [
                    'class'  => 'BodyHeader-logo',
                    'src'    => $logo['src'] ?? '',
                    'width'  => $logo['width'] ?? 200,
                    'height' => $logo['height'] ?? 40,
                    'alt'    => $logo['alt'] ?? __('Logo', 'tify'),
                    'border' => 0,
                ],
                'tag'   => 'img',
            ]); ?>
        </td>
    </tr>
<?php endif;