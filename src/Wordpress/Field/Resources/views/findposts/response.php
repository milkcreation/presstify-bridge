<?php
/**
 * Field Findposts - Réponse AJAX.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Field\FieldView $this
 * @var string $alt
 * @var array $posts
 * @var array $post
 */
?>
<table class="widefat">
    <thead>
        <tr>
            <th class="found-radio"><br /></th>
            <th><?php _e('Title'); ?></th>
            <th class="no-break"><?php _e('Type'); ?></th>
            <th class="no-break"><?php _e('Date'); ?></th>
            <th class="no-break"><?php _e('Status'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($posts as $post) : ?>
            <tr class="<?php echo trim('found-posts ' . $alt); ?>">
                <td class="found-radio">
                    <input type="radio" id="found-<?php echo $post['ID']; ?>" name="found_post_id" value="<?php echo esc_attr($post['ID']); ?>">
                </td>
                <td>
                    <label for="found-<?php echo $post['ID']; ?>"><?php echo esc_html($post['_post_title']); ?></label>
                </td>
                <td class="no-break"><?php echo esc_html($post_types[$post['post_type']]->labels->singular_name); ?></td>
                <td class="no-break"><?php echo esc_html($post['_post_date']); ?></td>
                <td class="no-break"><?php echo esc_html($post['_post_status']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>