<?php
/**
 * AppAdminMessagePlugin.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 2017/8/28
 * Version: 1.0
 */

namespace Admin\Controller\Plugin;


use Admin\Controller\IndexController;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;


/**
 * Display page information message plugin
 *
 * Class AppAdminMessagePlugin
 * @package Admin\Controller\Plugin
 */
class AppAdminMessagePlugin extends AbstractPlugin
{
    public function show($topic = 'Message', $content = '...', $url = '', $title = '', $delay = null)
    {

        return $this->getController()->forward()->dispatch(
            IndexController::class,
            [
                'controller' => IndexController::class,
                'action' => 'message',
                'topic' => $topic,
                'content' => $content,
                'url' => $url,
                'title' => $title,
                'delay' => $delay,
            ]
        );
    }

}