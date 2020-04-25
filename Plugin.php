<?php

namespace Kanboard\Plugin\AliyunObjectStorageService;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;

defined('ALIYUN_OSS_KEY') or define('ALIYUN_OSS_KEY', ''); // aliyun_oss_accesskey_id
defined('ALIYUN_OSS_SECRET') or define('ALIYUN_OSS_SECRET', ''); // aliyun_oss_accesskey_secret
defined('ALIYUN_OSS_BUCKET') or define('ALIYUN_OSS_BUCKET', ''); // aliyun_oss_bucket
defined('ALIYUN_OSS_ENDPOINT') or define('ALIYUN_OSS_ENDPOINT', ''); // aliyun_oss_endpoint

class Plugin extends Base
{
    public function initialize()
    {
        if ($this->isConfigured()) {
            $this->container['objectStorage'] = function () {
                return new ObjectStorage(
                    $this->getAliyunAccessKey(),
                    $this->getAliyunSecretKey(),
                    $this->getAliyunRegion(),
                    $this->getAliyunBucket()
                );
            };
        }

        $this->template->hook->attach('template:config:integrations', 'aliyunObjectStorageService:config');
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__ . '/Locale');
    }

    public function getPluginName()
    {
        return 'Aliyun Object Storage Service';
    }

    public function getPluginDescription()
    {
        return t('This plugin stores files to Aliyun Object Storage Service');
    }

    public function getPluginAuthor()
    {
        return 'charescape@outlook.com';
    }

    public function getPluginVersion()
    {
        return '1.0.0';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/charescape/kb-plugin-os2';
    }

    public function getCompatibleVersion()
    {
        return '>=1.0.37';
    }

    public function isConfigured()
    {
        if (
            !$this->getAliyunAccessKey()
            || !$this->getAliyunSecretKey()
            || !$this->getAliyunRegion()
            || !$this->getAliyunBucket()
        ) {
            $this->logger->info('Plugin Aliyun Object Storage Service not configured!');
            return false;
        }

        return true;
    }

    public function getAliyunAccessKey()
    {
        if (!empty(ALIYUN_OSS_KEY)) {
            return ALIYUN_OSS_KEY;
        }

        return $this->configModel->get('aliyun_oss_accesskey_id');
    }

    public function getAliyunSecretKey()
    {
        if (!empty(ALIYUN_OSS_SECRET)) {
            return ALIYUN_OSS_SECRET;
        }

        return $this->configModel->get('aliyun_oss_accesskey_secret');
    }

    public function getAliyunRegion()
    {
        if (!empty(ALIYUN_OSS_ENDPOINT)) {
            return ALIYUN_OSS_ENDPOINT;
        }

        return $this->configModel->get('aliyun_oss_endpoint');
    }

    public function getAliyunBucket()
    {
        if (!empty(ALIYUN_OSS_BUCKET)) {
            return ALIYUN_OSS_BUCKET;
        }

        return $this->configModel->get('aliyun_oss_bucket');
    }
}
