<?php
/**
 * Module.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 17/8/27
 * Version: 1.0
 */

namespace Admin;


use Admin\Controller\IndexController;
use Zend\Mvc\MvcEvent;
use Zend\Session\SessionManager;


class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }


    /**
     * @param MvcEvent $event
     */
    public function onBootstrap(MvcEvent $event)
    {
        $sharedEventManager = $event->getApplication()->getEventManager()->getSharedManager();
        $sharedEventManager->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH, [$this, 'onDispatchListener'], 100);
    }


    /**
     * @param MvcEvent $event
     */
    public function onDispatchListener(MvcEvent $event)
    {

        $serviceManager = $event->getApplication()->getServiceManager();
        $serviceManager->get(SessionManager::class); //Init session manager

        //$logger = $serviceManager->get('AppLogger');

        $viewModel = $event->getViewModel();

        $controller = $event->getRouteMatch()->getParam('controller', null);
        if($controller == IndexController::class) { // Allow all access
            $viewModel->setTemplate('layout/admin_simple');
            return ;
        }

        // Set module default template
        //$viewModel->setTemplate('layout/admin_layout');
    }


}