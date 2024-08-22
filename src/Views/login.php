<?= $this->extend(service('settings')->get('Auth.views')['action_halberd_layout']) ?>

<?= $this->section('main') ?>

            <p><?= lang('Halberd.confirmCode') ?></p>

<?= $this->endSection() ?>
