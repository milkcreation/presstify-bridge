<?php
/**
 * @var tiFy\Contracts\Mail\MailView $this
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->driver()->getCharset(); ?>"/>
    <title><?php echo $this->driver()->getSubject(); ?></title>

    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
            color: #000;
            font-family: sans-serif;
            font-size: 14px;
            line-height: 180%;
            text-align: left;
        }

        img {
            border: 0 none;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        a img {
            border: 0 none;
        }

        .imageFix {
            display: block;
        }

        table, td {
            border-collapse: collapse;
        }

        #bodyTable {
            height: 100% !important;
            margin: 0;
            padding: 0;
            width: 100% !important;
        }
    </style>

    <?php if ($css = $this->get('css')) : ?>
        <style type="text/css"><?php echo $css; ?></style>
    <?php endif; ?>
</head>

<body topmargin="0" leftmargin="0" marginwidth="0" marginheight="0" offset="0">
<center>
    <table id="bodyTable" border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
        <tr>
            <td align="center" valign="top">
                <div id="body_style" style="padding:15px">
                    <?php echo $this->section('header'); ?>
                    <?php echo $this->section('content'); ?>
                    <?php echo $this->section('footer'); ?>
                </div>
            </td>
        </tr>
    </table>
</center>
</body>
</html>