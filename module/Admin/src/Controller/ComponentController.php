<?php
/**
 * ComponentController.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 2017/9/1
 * Version: 1.0
 */

namespace Admin\Controller;


class ComponentController extends AdminBaseController
{

    public function indexAction()
    {
        //todo
    }


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
                $items[] = $controllerClassName::ComponentRegistry();
            }
        }
        //echo '<p>Origin</p><pre>'; print_r($items); echo '</pre><hr>';

        $componentManager = $this->appAdminComponentManager();
        $componentManager->async($items);

        $this->layout()->setTerminal(true);

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

        $item['actions']['index'] = self::BuildActionInfo('index', '系统组件列表', 1, 'bars');
        $item['actions']['async'] = self::BuildActionInfo('async', '同步系统组件');

        return $item;
    }


}