<?php
/**
 * Module.php
 *
 * @author: Leo <camworkster@gmail.com>
 * @version: 1.0
 */

namespace WeChat;


class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

}