<?php
/**
 * @var tiFy\Partial\PartialView $this
 */
?>
<?php echo $this->before(); ?>
<div <?php $this->attrs(); ?>>
    <div data-control="pdfviewer.spinner">
        <?php echo is_string($this->get('spinner')) ? $this->get('spinner') : $this->fetch('spinner', $this->all()); ?>
    </div>

    <div data-control="pdfviewer.content">
        <?php if ($header = $this->get('content.header')) : ?>
            <div data-control="pdfviewer.content.header">
                <?php echo is_string($header) ? $header : $this->fetch('content-header', $this->all()); ?>
            </div>
        <?php endif; ?>

        <div data-control="pdfviewer.content.body"><?php $this->insert('content-body', $this->all()); ?></div>

        <?php if ($footer = $this->get('content.footer')) : ?>
            <div data-control="pdfviewer.content.footer">
                <?php echo is_string($footer) ? $footer : $this->fetch('content-footer', $this->all()); ?>
            </div>
        <?php endif; ?>
    </div>

    <div data-control="pdfviewer.nav">
        <?php if ($first = $this->get('nav.first')) : ?>
            <span data-control="pdfviewer.nav.first">
                <?php echo is_string($first) ? $first : $this->fetch('nav-first', $this->all()); ?>
            </span>
        <?php endif; ?>

        <?php if ($prev = $this->get('nav.prev')) : ?>
            <span data-control="pdfviewer.nav.prev">
                <?php echo is_string($prev) ? $prev : $this->fetch('nav-prev', $this->all()); ?>
            </span>
        <?php endif; ?>

        <?php if ($status = $this->get('nav.status')) : ?>
            <span data-control="pdfviewer.nav.status">
                <?php echo is_string($status) ? $status : $this->fetch('nav-status', $this->all()); ?>
            </span>
        <?php endif; ?>

        <?php if ($next = $this->get('nav.next')) : ?>
            <span data-control="pdfviewer.nav.next">
                <?php echo is_string($next) ? $next : $this->fetch('nav-next', $this->all()); ?>
            </span>
        <?php endif; ?>

        <?php if ($last = $this->get('nav.last')) : ?>
            <span data-control="pdfviewer.nav.last">
                <?php echo is_string($last) ? $last : $this->fetch('nav-last', $this->all()); ?>
            </span>
        <?php endif; ?>
    </div>
</div>
<?php echo $this->after();