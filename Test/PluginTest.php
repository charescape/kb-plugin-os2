<?php

require_once 'tests/units/Base.php';

use Kanboard\Plugin\Aliyun\Plugin;

class PluginTest extends Base
{
    /**
     * @var Plugin
     */
    protected $plugin;

    public function setUp()
    {
        parent::setUp();
        $this->plugin = new Plugin($this->container);
    }

}
