<?php
/**
 * MenuManager.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 2017/8/29
 * Version: 1.0
 */

namespace Admin\Service;


use Admin\Entity\Adminer;
use Admin\Entity\Component;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\View\Helper\Url;

class MenuManager
{
    /**
     * @var Url
     */
    private $urlHelper;

    /**
     * @var AuthService
     */
    private $authService;

    /**
     * @var AdminerManager
     */
    private $adminerManager;

    /**
     * @var GroupManager
     */
    private $groupManager;

    /**
     * @var ComponentManager
     */
    private $componentManager;

    /**
     * @var array
     */
    private $topRightItems;

    /**
     * @var array
     */
    private $sideTreeItems;



    /**
     * Quick create menu link item array
     *
     * @param string $id
     * @param string $icon
     * @param string $label
     * @param string $link
     * @param string $title
     * @param string $type item|divider
     * @return array
     */
    public static function CreateMenuItem($id, $icon, $label, $link = '', $title = '', $type = 'item')
    {
        return [
            'id' => $id,
            'icon' => $icon,
            'label' => $label,
            'link' => $link,
            'title' => empty($title) ? $label : $title,
            'type' => $type
        ];
    }




    public function __construct(
        Url $url,
        AuthService $authService,
        AdminerManager $adminerManager,
        GroupManager $groupManager,
        ComponentManager $componentManager)
    {
        $this->urlHelper = $url;
        $this->authService = $authService;
        $this->adminerManager = $adminerManager;
        $this->groupManager = $groupManager;
        $this->componentManager = $componentManager;

        $this->initTopRightItems();

        $this->initSideTreeItems();
    }

    /**
     * Init top right menu items
     */
    public function initTopRightItems()
    {
        $this->topRightItems = [];

        $identity = $this->authService->getIdentity();
        if (empty($identity)) {
            return;
        }

        $adminer = $this->adminerManager->getAdminerByID($identity);
        if (!$adminer instanceof Adminer) {
            return;
        }

        $url = $this->urlHelper;

        $item = self::CreateMenuItem('profile_menu', 'user', $adminer->getAdminName());
        $item['dropdown'] = [
            self::CreateMenuItem('summary', 'user', '我的信息', $url('admin/profile', ['suffix'=>'.html']), $adminer->getAdminName()),
            self::CreateMenuItem('password', 'hashtag', '修改密码', $url('admin/profile', ['action' => 'password', 'suffix'=>'.html'])),
            self::CreateMenuItem('profile_detail', 'edit', '修改资料', $url('admin/profile', ['action' => 'update', 'suffix'=>'.html'])),
            self::CreateMenuItem('', '', '', '', '', 'divider'),
            self::CreateMenuItem('profile_logout', 'sign-out', '退出登录', $url('admin/index', ['action' => 'logout', 'suffix' => '.html'])),
        ];

        $logoutItem = self::CreateMenuItem('logout', 'sign-out', '退出登录', $url('admin/index', ['action' => 'logout', 'suffix' => '.html']));

        $this->setTopRightItems([$item, $logoutItem]);
    }

    /**
     * @return array
     */
    public function getTopRightItems()
    {
        return $this->topRightItems;
    }

    /**
     * @param array $topRightItems
     */
    public function setTopRightItems($topRightItems)
    {
        $this->topRightItems = $topRightItems;
    }


    /**
     * Init side tree items
     */
    public function initSideTreeItems()
    {
        $this->sideTreeItems = [];
        $url = $this->urlHelper;

        $identity = $this->authService->getIdentity();
        if (empty($identity)) {
            return;
        }

        $adminer = $this->adminerManager->getAdminerByID($identity);
        if (!$adminer instanceof Adminer) {
            return;
        }

        $actions = new ArrayCollection();
        if (Adminer::DEFAULT_ADMIN == $adminer->getAdminDefault()) {
            $actions = $this->componentManager->getMenuActions();
        } else {
            $groups = $adminer->getAdminGroups();
            foreach($groups as $group) {
                $groupActions = $group->getGroupActions();
                foreach ($groupActions as $groupAction) {
                    $actions->add($groupAction);
                }
            }
        }

        $menus = [];
        $menuComponents = new ArrayCollection();

        foreach ($actions as $action) {

            $component = $action->getActionComponent();
            if (!$menuComponents->contains($component)) {
                $menuComponents->add($component);
            }

            $subMenuID = $component->getComponentClass() . '::' . str_replace('-', '', lcfirst(ucwords($action->getActionMethod(), '-'))) . 'Action';
            $item = self::CreateMenuItem(
                $subMenuID,
                $action->getActionIcon(),
                $action->getActionName(),
                $url($component->getComponentRoute(), ['action' => $action->getActionMethod(), 'suffix' => '.html'])
            );
            $menus[$component->getComponentClass()]['dropdown'][] = $item;
        }

        $i = 1;
        $items = [];
        foreach ($menuComponents as $component) {
            if ($component instanceof Component) {
                $item = self::CreateMenuItem(
                    $component->getComponentClass(),
                    $component->getComponentIcon(),
                    $component->getComponentName(),
                    $url($component->getComponentRoute(), ['suffix' => '.html'])
                );
                if (array_key_exists($component->getComponentClass(), $menus)) {
                    $item['dropdown'] = $menus[$component->getComponentClass()]['dropdown'];
                }
                $index = $component->getComponentRank() * 10 + $i++;
                $items[$index] = $item;
            }
        }

        krsort($items); // Sort

        $dashboard = self::CreateMenuItem('dashboard', 'dashboard', '管理中心', $url('admin/dashboard', ['suffix' => '.html']));
        array_unshift($items, $dashboard);

        $this->sideTreeItems = $items;
    }

    /**
     * @return array
     */
    public function getSideTreeItems()
    {
        return $this->sideTreeItems;
    }



}