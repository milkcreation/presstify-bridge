<?php
/**
 * Affichage de l'email en mode déboguage.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Mail\MailerMessageView $this
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->get('charset'); ?>"/>
    <title><?php _e('Mail - Mode de déboguage') ?></title>
</head>

<body style="width:100%;margin:0;padding:0;background:#FFF;color:#000;font-family:Arial, Helvetica, sans-serif;font-size:12px;"
      link="#0000FF"
      alink="#FF0000"
      vlink="#800080"
      bgcolor="#FFFFFF"
      text="#000000"
      yahoo="fix"
>

<table cellspacing="0" border="0" bgcolor="#EEEEEE" width="100%" align="center"
       style="border-bottom:solid 1px #AAA;">
    <tbody>
    <tr>
        <td style="line-height:1.1em;padding:3px 10px;color:#000;font-size:13px">
            <h3 style="margin-bottom:10px;">
                <?php echo $this->get('subject', ''); ?>
            </h3>

            <hr style="display:block;margin:10px 0 5px;background-color:#CCC; height:1px; border:none;">
        </td>
    </tr>

    <?php if ($headers = $this->get('headers', [])) : ?>
        <?php foreach ($this->get('headers', []) as $header) : ?>
            <tr>
                <td style="line-height:1.1em;padding:3px 10px;color:#000;font-size:13px">
                    <?php echo $this->e($header); ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td>
            <hr style="display:block;margin:5px 0;background-color:#CCC; height:1px; border:none;">
            </td>
        </tr>
    <?php endif; ?>

    <?php if ($to = $this->get('to', [])) : ?>
        <tr>
            <td style="line-height:1.1em;padding:3px 10px;color:#000;font-size:13px">
                <?php printf('To: %s', $this->e(join(', ', $this->linearizeContacts($to)))); ?>
            </td>
        </tr>
    <?php endif; ?>

    <?php if ($cc = $this->get('cc', [])) : ?>
        <tr>
            <td style="line-height:1.1em;padding:3px 10px;color:#000;font-size:13px">
                <?php printf('Cc: %s', $this->e(join(', ', $this->linearizeContacts($cc)))); ?>
            </td>
        </tr>
    <?php endif; ?>

    <?php if ($bcc = $this->get('bcc', [])) : ?>
        <tr>
            <td style="line-height:1.1em;padding:3px 10px;color:#000;font-size:13px">
                <?php printf('Bcc: %s', $this->e(join(', ', $this->linearizeContacts($bcc)))); ?>
            </td>
        </tr>
    <?php endif; ?>

    <?php if ($replyTo = $this->get('reply-to', [])) : ?>
        <tr>
            <td style="line-height:1.1em;padding:3px 10px;color:#000;font-size:13px">
                <?php printf('ReplyTo: %s', $this->e(join(', ', $this->linearizeContacts($replyTo)))); ?>
            </td>
        </tr>
    <?php endif; ?>

    </tbody>
</table>

<?php if (in_array($this->get('content_type'), ['text/html', 'multipart/alternative'])) : ?>
<table cellspacing="0" border="0" width="600" align="center" style="margin:30px auto;">
    <tbody>
    <tr>
        <td style="text-align:center;font-size:18px;font-family:courier">
            -------------- VERSION HTML --------------
        </td>
    </tr>
    </tbody>
</table>

<iframe
    id="iframe"
    width="700"
    height="500"
    frameborder="0"
    marginheight="0"
    marginwidth="0"
    style="display: block; margin:0 auto;"
></iframe>

<?php endif;?>

<?php if (in_array($this->get('content_type'), ['text/plain', 'multipart/alternative'])) : ?>
<table cellspacing="0" border="0" width="600" align="center" style="margin:30px auto;">
    <tbody>
    <tr>
        <td style="text-align:center;font-size:18px;font-family:courier">
            -------------- VERSION TEXTE --------------
        </td>
    </tr>
    </tbody>
</table>

<table cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF" width="600" align="center">
    <tr>
        <td width="600">
            <div>
                <?php echo nl2br($this->get('plain')); ?>
            </div>
        </td>
    </tr>
</table>
<?php endif;?>

<br>
<br>

<script type="text/javascript">
    let iframe = document.getElementById('iframe'),
        doc = iframe.contentWindow.document,
        resizeIframe = function (obj){
            obj.style.height = 0;
            obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
        };

    doc.open();
    doc.write(decodeURIComponent("<?php echo rawurlencode($this->get("html")); ?>"));
    doc.close();
    resizeIframe(iframe);
</script>
</body>
</html>