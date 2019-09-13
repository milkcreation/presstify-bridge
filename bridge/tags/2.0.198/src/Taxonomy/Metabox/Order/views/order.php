<?php
/**
 * @var tiFy\Contracts\View\ViewController $this
 * @var WP_Term $term
 */
?>

<table class="form-table">
    <tbody>
    <tr>
        <th>
            <label>
                <?php _e( 'Choix de l\'ordre', 'tify'); ?>
            </label>
            <em style="display:block;color:#999;font-size:11px;font-weight:normal;">
                <?php _e( '(-1 pour masquer l\'élément)', 'tify'); ?>
            </em>
        </th>
        <td>
            <?php
            echo field(
                'number',
                [
                    'name' => '_order',
                    'value' => (int) get_term_meta($term->term_id, '_order', true),
                    'attrs' => [
                        'min' => -1
                    ]
                ]
            );
            ?>
        </td>
    </tr>
    </tbody>
</table>
