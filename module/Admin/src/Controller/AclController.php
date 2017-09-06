<?php
/**
 * AclController.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 2017/9/5
 * Version: 1.0
 */

namespace Admin\Controller;


use Admin\Entity\Action;
use Admin\Entity\Group;
use Admin\Exception\InvalidArgumentException;
use Admin\Exception\RuntimeException;
use Application\View\Helper\Pagination;


class AclController extends AdminBaseController
{

    /**
     * Display page for group list
     */
    public function indexAction()
    {
        $size = 10;
        $page = (int)$this->params()->fromRoute('key', 1);
        if ($page < 1) { $page = 1; }

        $groupManager = $this->appAdminGroupManager();
        $count = $groupManager->getGroupsCount();

        $pagination = $this->appViewHelperManager(Pagination::class);
        if (!$pagination instanceof Pagination) {
            throw new RuntimeException('Invalid view helper pagination');
        }

        $pageUrlTpl = $this->url()->fromRoute('admin/acl', ['action' => 'index', 'key' => '%d']);
        $pagination->setPage($page)->setSize($size)->setCount($count)->setUrlTpl($pageUrlTpl);

        $entities = $groupManager->getGroupsByLimitPage($page, $size);

        $this->addResultData('groups', $entities);
        $this->addResultData('activeID', __METHOD__);
    }


    /**
     * Display page for a group ACL
     */
    public function groupAction()
    {
        $this->addResultData('activeID', __CLASS__);

        $key = $this->params()->fromRoute('key', '');
        $keys = explode('_', $key);
        $groupID = array_shift($keys);

        $groupManager = $this->appAdminGroupManager();
        $group = $groupManager->getGroupByID($groupID);

        if (!$group instanceof Group) {
            throw  new InvalidArgumentException('Invalid group identity');
        }

        if (Group::DEFAULT_GROUP == $group->getGroupDefault()) {
            throw  new RuntimeException('Disable configuration default group');
        }

        $this->addResultData('group', $group);

        $size = 2;
        $page = (int)array_shift($keys);
        if ($page < 1) { $page = 1; }

        $componentManager = $this->appAdminComponentManager();
        $count = $componentManager->getComponentsCount();

        $pagination = $this->appViewHelperManager(Pagination::class);
        if (!$pagination instanceof Pagination) {
            throw new RuntimeException('Invalid view helper pagination');
        }

        $pageUrlTpl = $this->url()->fromRoute('admin/acl', ['action' => 'group', 'key' => $groupID . '_%d']);
        $pagination->setPage($page)->setSize($size)->setCount($count)->setUrlTpl($pageUrlTpl);

        $entities = $componentManager->getComponentsByLimitPage($page, $size);

        $this->addResultData('components', $entities);
    }


    /**
     * Configuration ACL for a group
     * Only for ajax request
     */
    public function aclAction()
    {
        $this->setResultType(self::RESPONSE_JSON);

        $groupID = $this->params()->fromRoute('key', '');

        $groupManager = $this->appAdminGroupManager();
        $group = $groupManager->getGroupByID($groupID);

        if (!$group instanceof Group) {
            throw  new InvalidArgumentException('Invalid group identity');
        }

        if (Group::DEFAULT_GROUP == $group->getGroupDefault()) {
            throw  new RuntimeException('Disable configuration default group');
        }

        $actionID = $this->params()->fromPost('action_id', '');

        $componentManager = $this->appAdminComponentManager();
        $action = $componentManager->getAction($actionID);

        if (! $action instanceof Action) {
            throw new InvalidArgumentException('Invalid action identity');
        }

        $status = $this->params()->fromPost('status', '');
        $groupActions = $group->getGroupActions();
        if ('allow' == $status) {
            if (!$groupActions->contains($action)) {
                $groupActions->add($action);
                $group->setGroupActions($groupActions);
                $groupManager->saveModifiedGroup($group);
            }
        } else {
            if ($groupActions->contains($action)) {
                $groupActions->removeElement($action);
                $group->setGroupActions($groupActions);
                $groupManager->saveModifiedGroup($group);
            }
        }
    }


    /**
     *  ACL Registry
     *
     * @return array
     */
    public static function ComponentRegistry()
    {
        $item = self::BuildComponentInfo(__CLASS__, '系统权限管理', 'admin/acl', 1, 'cogs', 2);

        $item['component_actions']['index'] = self::BuildActionInfo('index', '查看授权列表', 1, 'users', 2);

        $item['component_actions']['group'] = self::BuildActionInfo('group', '查看权限列表');
        $item['component_actions']['acl'] = self::BuildActionInfo('acl', '配置权限');

        return $item;
    }



}