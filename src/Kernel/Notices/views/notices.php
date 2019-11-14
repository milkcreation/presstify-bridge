<?php
/**
 * @var tiFy\Contracts\View\ViewController $this
 */
?>
<ol class="Notices-items Notices-items--<?php echo $this->get('code'); ?>">
<?php foreach($this->get('messages', []) as $key => $message) : ?>
    <li class="Notices-item Notices-item--<?php echo $this->get('code'); ?>">
        <?php echo $message; ?>
    </li>
<?php endforeach; ?>
</ol>