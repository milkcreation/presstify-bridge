<?php
/**
 * @var tiFy\Contracts\View\PlatesFactory $this
 */
?>
<div>
    <?php settings_fields($this->get('name')); ?>
    <?php do_settings_sections($this->get('name')); ?>
</div>

<div class="submit">
    <?php submit_button(); ?>
</div>