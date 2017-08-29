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


/**
 * Class AdminBaseController
 * @package Admin\Controller
 *
 * @method \Admin\Controller\Plugin\AppAdminMessagePlugin appAdminMessage()
 */
class AdminBaseController extends AppBaseController
{

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
     * @param string $url
     * @param string $title
     * @param int $delay
     * @return mixed
     */
    protected function go($topic = 'Message', $content = '...', $url = '/', $title = '返回', $delay = 3)
    {
        return $this->appAdminMessage()->show($topic, $content, $url, $title, $delay);
    }
}