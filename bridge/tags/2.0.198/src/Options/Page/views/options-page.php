<?php
/**
 * @var tiFy\Options\Page\OptionsPageView $this.
 */
?>

<div class="wrap">
    <h2><?php echo $this->get('page_title', ''); ?></h2>

    <form method="post" action="options.php">
        <?php $this->insert($this->get('template', 'standard'), $this->all()); ?>
    </form>
</div>
