<?php
/**
 * AdminBaseController.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 17/8/27
 * Version: 1.0
 */

namespace Admin\Controller;


use Admin\Service\AdminerManager;
use Admin\Service\AuthService;
use Application\Controller\AppBaseController;
use Zend\Mvc\MvcEvent;


/**
 * Class AdminBaseController
 * @package Admin\Controller
 *
 * @method \Admin\Controller\Plugin\AppAdminMessagePlugin appAdminMessage()
 */
class AdminBaseController extends AppBaseController
{

    public function onDispatch(MvcEvent $e)
    {
        $result = parent::onDispatch($e);
        $e->getViewModel()->setTemplate('layout/admin_layout');
        return $result;
    }

    /**
     * @return AdminerManager
     */
    protected function appAdminAdminerManager()
    {
        return $this->appServiceManager(AdminerManager::class);
    }

    /**
     * @return AuthService
     */
    protected function appAdminAuthService()
    {
        return $this->appServiceManager(AuthService::class);
    }


    /**
     * Display information for option
     *
     * @param string $topic
     * @param string $content
     * @param string $href
     * @param string $title
     * @param int $delay
     * @return mixed
     */
    protected function go($topic = 'Message', $content = '...', $href = '', $title = '返回', $delay = 3)
    {
        return $this->forward()->dispatch(
            IndexController::class,
            [
                'controller' => IndexController::class,
                'action' => 'message',
                'topic' => $topic,
                'content' => $content,
                'url' => $href,
                'title' => $title,
                'delay' => $delay,
            ]
        );

        //return $this->appAdminMessage()->show($topic, $content, $href, $title, $delay);
    }
}