<?php
helper('html');
helper('form');
?>
<?= doctype() ?>
<html lang="<?= service('request')->getLocale() ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title><?= lang('TOTP.title2FA') ?></title>

    <?= link_tag('css/totp.css') ?>
</head>

<body>
    <h1><?= lang('TOTP.title2FA') ?></h1>

<?php if (session('error')) : ?>
    <p><?= session('error') ?></p>
<?php endif ?>

    <p><?= lang(isset($qrcode) ? 'TOTP.googleApp' : 'TOTP.confirmCode') ?></p>

<?php if(isset($qrcode)): ?>
    <p><svg version="1.1" viewBox="-4 -4 45 45"><path d="<?= gzuncompress(base64_decode($qrcode)) ?>" /></svg></p>

    <p><?= lang('TOTP.problems', ['placeholder' => $secret]) ?></p>
<?php endif ?>

    <?= form_open(url_to('auth-action-verify')) ?>
        <?= form_input([
            'type' => 'number',
            'name' => 'token',
            'placeholder' => '000000',
            'inputmode' => 'numeric',
            'pattern' => '[0-9]{6}',
            'required' => true,
        ]) ?>
        <?= form_submit('', lang('Auth.confirm')) ?>
    <?= form_close() ?>
</body>
</html>
