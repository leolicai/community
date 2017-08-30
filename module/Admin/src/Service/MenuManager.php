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
     * @var array
     */
    private $topRightItems;


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




    public function __construct(Url $url, AuthService $authService, AdminerManager $adminerManager)
    {
        $this->urlHelper = $url;
        $this->authService = $authService;
        $this->adminerManager = $adminerManager;

        $this->initTopRightItems();
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




}