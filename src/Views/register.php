<?= $this->extend(service('settings')->get('Auth.views')['action_halberd_layout']) ?>

<?= $this->section('main') ?>

            <p><?= lang('Halberd.googleApp') ?></p>

            <p><?= $qrcode ?></p>

            <p><?= lang('Halberd.problems', ['placeholder' => $secret]) ?></p>

<?= $this->endSection() ?>
