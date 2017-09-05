<?php
/**
 * AclController.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 2017/9/5
 * Version: 1.0
 */

namespace Admin\Controller;


use Admin\Entity\Group;
use Admin\Exception\InvalidArgumentException;
use Admin\Exception\RuntimeException;
use Application\View\Helper\Pagination;


class AclController extends AdminBaseController
{

    /**
     * Display page for group list
     */
    public function groupAction()
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

        $pageUrlTpl = $this->url()->fromRoute('admin/acl', ['action' => 'group', 'key' => '%d']);
        $pagination->setPage($page)->setSize($size)->setCount($count)->setUrlTpl($pageUrlTpl);

        $entities = $groupManager->getGroupsByLimitPage($page, $size);

        $this->addResultData('groups', $entities);
        $this->addResultData('activeID', __METHOD__);
    }

    /**
     * Display page for administrator list
     */
    public function adminerAction()
    {
        $size = 10;
        $page = (int)$this->params()->fromRoute('key', 1);
        if ($page < 1) { $page = 1; }

        $adminerManager = $this->appAdminAdminerManager();
        $count = $adminerManager->getAdminersCount();

        $pagination = $this->appViewHelperManager(Pagination::class);
        if (!$pagination instanceof Pagination) {
            throw new RuntimeException('Invalid view helper pagination');
        }

        $pageUrlTpl = $this->url()->fromRoute('admin/acl', ['action' => 'adminer', 'key' => '%d']);
        $pagination->setPage($page)->setSize($size)->setCount($count)->setUrlTpl($pageUrlTpl);

        $entities = $adminerManager->getAdminersByLimitPage($page, $size);

        $this->addResultData('adminers', $entities);
        $this->addResultData('activeID', __METHOD__);
    }

    /**
     * Display page for a group ACL
     */
    public function groupaclAction()
    {
        $this->addResultData('activeID', __CLASS__);

        $groupID = $this->params()->fromRoute('key', '');

        $groupManager = $this->appAdminGroupManager();
        $group = $groupManager->getGroupByID($groupID);

        if (!$group instanceof Group) {
            throw  new InvalidArgumentException('Invalid group identity');
        }

        if (Group::DEFAULT_GROUP == $group->getGroupDefault()) {
            throw  new RuntimeException('Disable configuration default group');
        }

        $this->addResultData('group', $group);

        $groupAcls = $group->getGroupAcls();
        $acls = [];
        foreach($groupAcls as $acl) {
            $acls[$acl->getAclAction()->getActionID()] = $acl->getAclStatus();
        }
        $this->addResultData('acl', $acls);

        $size = 2;
        $page = (int)$this->params()->fromRoute('key', 1);
        if ($page < 1) { $page = 1; }

        $componentManager = $this->appAdminComponentManager();
        $count = $componentManager->getComponentsCount();

        $pagination = $this->appViewHelperManager(Pagination::class);
        if (!$pagination instanceof Pagination) {
            throw new RuntimeException('Invalid view helper pagination');
        }

        $pageUrlTpl = $this->url()->fromRoute('admin/acl', ['action' => 'groupacl', 'key' => '%d']);
        $pagination->setPage($page)->setSize($size)->setCount($count)->setUrlTpl($pageUrlTpl);

        $entities = $componentManager->getComponentsByLimitPage($page, $size);

        $this->addResultData('components', $entities);
    }

    /**
     * Display page for a administrator ACL
     */
    public function admineraclAction()
    {
        //todo
    }

    /**
     * Configuration ACL for a group
     */
    public function groupacledAction()
    {
        //todo
    }

    /**
     * Configuration ACL for a administrator
     */
    public function admineracledAction()
    {
        //todo
    }


    /**
     *  ACL Registry
     *
     * @return array
     */
    public static function ComponentRegistry()
    {
        $item = self::BuildComponentInfo(__CLASS__, '系统权限管理', 'admin/acl', 1, 'cogs', 6);

        $item['component_actions']['group'] = self::BuildActionInfo('group', '查看分组列表', 1, 'users', 2);
        $item['component_actions']['adminer'] = self::BuildActionInfo('adminer', '查看管理员列表', 1, 'user', 1);

        $item['component_actions']['groupacl'] = self::BuildActionInfo('groupacl', '查看分组权限列表');
        $item['component_actions']['admineracl'] = self::BuildActionInfo('admineracl', '查看管理员权限列表');
        $item['component_actions']['groupacled'] = self::BuildActionInfo('groupacled', '配置分组权限');
        $item['component_actions']['admineracled'] = self::BuildActionInfo('admineracled', '配置管理员权限');

        return $item;
    }



}