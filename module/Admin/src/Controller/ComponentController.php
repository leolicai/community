<?php
/**
 * ComponentController.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 2017/9/1
 * Version: 1.0
 */

namespace Admin\Controller;


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

        $entites = $componentManager->getComponentsByLimitPage($page, $size);

        $this->addResultData('components', $entites);
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
     *  ACL 登记
     *
     * @return array
     */
    public static function ComponentRegistry()
    {
        $item = self::BuildComponentInfo(__CLASS__, '系统组件管理', 'admin/component', 1, 'cubes');

        $item['component_actions']['index'] = self::BuildActionInfo('index', '系统组件列表', 1, 'bars');
        $item['component_actions']['async'] = self::BuildActionInfo('async', '同步系统组件');

        return $item;
    }


}