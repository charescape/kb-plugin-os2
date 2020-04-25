<h3>
    <i class="fa fa-cloud" aria-hidden="true"></i>
    Aliyun Object Storage Service
</h3>
<div class="panel">
    <?= $this->form->label(t('Aliyun OSS endpoint'), 'aliyun_oss_endpoint') ?>
    <?= $this->form->text('aliyun_oss_endpoint', $values) ?>

    <?= $this->form->label(t('Aliyun OSS Bucket'), 'aliyun_oss_bucket') ?>
    <?= $this->form->text('aliyun_oss_bucket', $values) ?>

    <?= $this->form->label(t('Aliyun Access Key ID'), 'aliyun_oss_akey_id') ?>
    <?= $this->form->password('aliyun_oss_akey_id', $values) ?>

    <?= $this->form->label(t('Aliyun Access Key Secret'), 'aliyun_oss_akey_secret') ?>
    <?= $this->form->password('aliyun_oss_akey_secret', $values) ?>

    <p class="form-help"><a href="https://github.com/charescape/kb-plugin-os2" target="_blank"><?= t('Help on Aliyun Object Storage Service integration') ?></a></p>

    <div class="form-actions">
        <button class="btn btn-blue"><?= t('Save') ?></button>
    </div>
</div>
