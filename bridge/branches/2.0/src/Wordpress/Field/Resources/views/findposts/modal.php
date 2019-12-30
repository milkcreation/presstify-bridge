<?php
/**
 * Field Findposts - Fenêtre modale.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Field\FieldView $this
 */
?>
<div id="ajax-response"></div>

<div id="find-posts" class="find-box tiFyFindPosts-box" style="display: none;">
    <div id="find-posts-head" class="find-box-head">
        <?php _e('Attach to existing content'); ?>
        <button type="button" id="find-posts-close">
            <span class="screen-reader-text"><?php _e('Close media attachment panel'); ?></span>
        </button>
    </div>

    <div class="find-box-inside">
        <div class="find-box-search">
            <?php if ($found_action) : ?>
                <input type="hidden" name="found_action" value="<?php echo esc_attr($found_action); ?>"/>
            <?php endif; ?>
            <?php if ($query_args) : ?>
                <input type="hidden" name="query_args"
                       value="<?php echo urlencode(json_encode($query_args)); ?>"/>
            <?php endif; ?>
            <input type="hidden" name="affected" id="affected" value=""/>
            <?php wp_nonce_field('FieldFindposts', '_ajax_nonce', false); ?>
            <label class="screen-reader-text" for="find-posts-input"><?php _e('Search'); ?></label>
            <input type="text" id="find-posts-input" name="ps" value=""/>

            &nbsp;&nbsp;<?php _e('Type :', 'tify'); ?>
            <select id="find-posts-post_type" name="post_type">
                <option value="any"><?php _e('Tous', 'tify'); ?></option>
                <?php foreach ($post_types as $post_type) : ?>
                    <option value="<?php echo $post_type; ?>"><?php echo get_post_type_object($post_type)->label; ?></option>
                <?php endforeach; ?>
            </select>

            <span class="spinner"></span>
            <input type="button" id="find-posts-search" value="<?php esc_attr_e('Search'); ?>" class="button"/>
            <div class="clear"></div>
        </div>
        <div id="find-posts-response"></div>
    </div>
    <div class="find-box-buttons">
        <?php submit_button(__('Select'), 'primary alignright', 'find-posts-submit', false); ?>
        <div class="clear"></div>
    </div>
</div>
