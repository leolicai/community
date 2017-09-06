<?php
/**
 * ComponentController.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 2017/9/1
 * Version: 1.0
 */

namespace Admin\Controller;


use Admin\Entity\Action;
use Admin\Entity\Component;
use Admin\Exception\InvalidArgumentException;
use Admin\Exception\RuntimeException;
use Application\View\Helper\Pagination;



class ComponentController extends AdminBaseController
{
    /**
     * Display components list
     */
    public function indexAction()
    {
        $size = 10;
        $page = (int)$this->params()->fromRoute('key', 1);
        if ($page < 1) { $page = 1; }

        $componentManager = $this->appAdminComponentManager();
        $count = $componentManager->getComponentsCount();

        $pagination = $this->appViewHelperManager(Pagination::class);
        if (!$pagination instanceof Pagination) {
            throw new RuntimeException('Invalid view helper pagination');
        }

        $pageUrlTpl = $this->url()->fromRoute('admin/component', ['action' => 'index', 'key' => '%d']);
        $pagination->setPage($page)->setSize($size)->setCount($count)->setUrlTpl($pageUrlTpl);

        $entities = $componentManager->getComponentsByLimitPage($page, $size);

        $this->addResultData('components', $entities);
        $this->addResultData('activeID', __METHOD__);

    }


    /**
     * Page for component detail information
     */
    public function detailAction()
    {
        $componentClass = base64_decode($this->params()->fromRoute('key', ''));

        $componentManager = $this->appAdminComponentManager();
        $component = $componentManager->getComponentByClass($componentClass);

        if (!$component instanceof Component) {
            throw new InvalidArgumentException('Invalid component identity');
        }

        $this->addResultData('component', $component);
    }

    /**
     * Remove a component from registered components
     * Only for ajax request
     */
    public function deleteAction()
    {
        $this->setResultType(self::RESPONSE_JSON);

        $componentClass = base64_decode($this->params()->fromRoute('key', ''));

        $componentManager = $this->appAdminComponentManager();
        $component = $componentManager->getComponentByClass($componentClass);

        if (!$component instanceof Component) {
            throw new InvalidArgumentException('Invalid component identity');
        }

        $componentManager->removeComponent($component);
    }


    /**
     * Remove a action from registered actions
     * Only for ajax request
     */
    public function removeAction()
    {
        $this->setResultType(self::RESPONSE_JSON);

        $actionID = $this->params()->fromRoute('key', '');

        $componentManager = $this->appAdminComponentManager();

        $action = $componentManager->getAction($actionID);

        if (! $action instanceof Action) {
            throw new InvalidArgumentException('Invalid action identity');
        }

        $componentManager->removeAction($action);
    }


    /**
     * Async register system all component
     */
    public function asyncAction()
    {
        ignore_user_abort(true);
        set_time_limit(0);

        $controllers = $this->appConfig()->get('controllers.factories');
        $items = [];
        foreach($controllers as $controllerClassName => $factory) {
            if (0 !== strpos($controllerClassName, __NAMESPACE__)) {
                continue;
            }

            if (method_exists($controllerClassName, 'ComponentRegistry')) {
                $items[$controllerClassName] = $controllerClassName::ComponentRegistry();
            }
        }
        //echo '<p>Origin</p><pre>'; print_r($items); echo '</pre><hr>';

        $componentManager = $this->appAdminComponentManager();
        $componentManager->async($items);

        if (!$this->getRequest()->isXmlHttpRequest()) {
            echo json_encode($this->getResultData(), JSON_UNESCAPED_UNICODE);
            return $this->getResponse();
        }
    }




    /**
     *  ACL Registry
     *
     * @return array
     */
    public static function ComponentRegistry()
    {
        $item = self::BuildComponentInfo(__CLASS__, '系统组件管理', 'admin/component', 1, 'cubes', 1);

        $item['component_actions']['index'] = self::BuildActionInfo('index', '查看系统组件列表', 1, 'bars');

        $item['component_actions']['detail'] = self::BuildActionInfo('detail', '查看组件详情');
        $item['component_actions']['delete'] = self::BuildActionInfo('delete', '删除组件');
        $item['component_actions']['remove'] = self::BuildActionInfo('remove', '删除组件功能');
        $item['component_actions']['async'] = self::BuildActionInfo('async', '同步系统组件');

        return $item;
    }


}