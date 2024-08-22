<?= $this->extend(config('Halberd')->views['layout']) ?>

<?= $this->section('main') ?>

            <p><?= lang('Halberd.googleApp') ?></p>

            <p><?= $qrcode ?></p>

            <p><?= lang('Halberd.problems', ['placeholder' => $secret]) ?></p>

<?= $this->endSection() ?>
