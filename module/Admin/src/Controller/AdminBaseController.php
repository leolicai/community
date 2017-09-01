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
use Admin\Service\ComponentManager;
use Admin\Service\GroupManager;
use Application\Controller\AppBaseController;


/**
 * Class AdminBaseController
 * @package Admin\Controller
 */
class AdminBaseController extends AppBaseController
{

    /**
     * @return ComponentManager
     */
    protected function appAdminComponentManager()
    {
        return $this->appServiceManager(ComponentManager::class);
    }

    /**
     * @return GroupManager
     */
    protected function appAdminGroupManager()
    {
        return $this->appServiceManager(GroupManager::class);
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

        $this->addResultData('topic', $topic);
        $this->addResultData('content', $content);
        $this->addResultData('url', $href);
        $this->addResultData('title', $title);
        $this->addResultData('delay', $delay);

        return $this->forward()->dispatch(
            IndexController::class,
            ['controller' => IndexController::class, 'action' => 'message']
        );
    }



    /**
     * @param string $controller_class
     * @param string $controller_name
     * @param string $route
     * @param int $menu
     * @param string $icon
     * @param int $rank
     * @return array
     */
    protected static function BuildComponentInfo($controller_class, $controller_name, $route, $menu = 0, $icon = 'list', $rank = 1)
    {
        if (empty($icon)) { $icon = 'list'; }
        return [
            'component_class' => $controller_class,
            'component_name' => $controller_name,
            'component_route' => $route,
            'component_menu' => $menu,
            'component_icon' => $icon,
            'component_rank' => $rank,
            'component_actions' => [],
        ];
    }


    /**
     * @param string $action_method
     * @param string $action_name
     * @param int $menu
     * @param string $icon
     * @param int $rank
     * @return array
     */
    protected static function BuildActionInfo($action_method, $action_name, $menu = 0, $icon = 'caret-right', $rank = 1)
    {
        if (empty($icon)) { $icon = 'caret-right'; }
        return [
            'action_method' => $action_method,
            'action_name' => $action_name,
            'action_menu' => $menu,
            'action_icon' => $icon,
            'action_rank' => $rank,
        ];
    }

}