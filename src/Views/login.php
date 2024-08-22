<?= $this->extend(service('settings')->get('Auth.views')['layout']) ?>

<?= $this->section('main') ?>

            <p><?= lang('Halberd.confirmCode') ?></p>

<?= $this->endSection() ?>
