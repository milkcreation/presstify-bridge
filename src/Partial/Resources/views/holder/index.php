<?php
/**
 * @var tiFy\Partial\PartialView $this
 */
?>
<?php $this->before(); ?>
<div <?php $this->attrs(); ?>>
    <span class="Holder-pad"
          style="padding-top:<?php echo ceil((100/$this->get('width'))* $this->get('height')); ?>%"
    ></span>

    <div class="Holder-content">
        <?php
        if ($content = $this->get('content')) :
            echo $this->get('content');
        else :
            $this->insert('default', $this->all());
        endif;
        ?>
    </div>
</div>
<?php $this->after();