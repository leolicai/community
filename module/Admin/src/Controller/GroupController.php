<?php
/**
 * GroupController.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 2017/8/31
 * Version: 1.0
 */

namespace Admin\Controller;


use Admin\Entity\Group;
use Admin\Exception\RuntimeException;
use Admin\Form\GroupForm;
use Application\View\Helper\Pagination;
use Ramsey\Uuid\Uuid;


class GroupController extends AdminBaseController
{

    /**
     * Display all group list
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

        $pageUrlTpl = $this->url()->fromRoute('admin/group', ['action' => 'index', 'key' => '%d']);
        $pagination->setPage($page)->setSize($size)->setCount($count)->setUrlTpl($pageUrlTpl);

        $entites = $groupManager->getGroupsByLimitPage($page, $size);

        $this->addResultData('groups', $entites);
        $this->addResultData('activeID', __METHOD__);
    }


    /**
     * Page for add new group to system
     */
    public function addAction()
    {
        $groupManager = $this->appAdminGroupManager();

        $form = new GroupForm($groupManager);

        if($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());
            if ($form->isValid()) {
                $data = $form->getData();

                $group = new Group();
                $group->setGroupID(Uuid::uuid1()->toString());
                $group->setGroupName($data[GroupForm::FIELD_NAME]);
                $group->setGroupCreated(new \DateTime());

                $groupManager->saveModifiedGroup($group);

                $this->go(
                    '分组已添加',
                    '新分组: ' . $group->getGroupName() . ' 已经添加!',
                    $this->url()->fromRoute('admin/group')
                );

                return $this->layout()->setTerminal(true);
            }
        }

        $this->addResultData('form', $form);
        $this->addResultData('activeID', __METHOD__);
    }


    /**
     * Page for edit group information
     */
    public function editAction()
    {
        $groupID = $this->params()->fromRoute('key', '');

        $groupManager = $this->appAdminGroupManager();
        $group = $groupManager->getGroupByID($groupID);

        if (!$group instanceof Group) {
            throw  new RuntimeException('Invalid group identity');
        }

        if (Group::DEFAULT_GROUP == $group->getGroupDefault()) {
            throw  new RuntimeException('Disable configuration default group');
        }

        $form = new GroupForm($groupManager, $group);

        if($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());
            if ($form->isValid()) {
                $data = $form->getData();

                $group->setGroupName($data[GroupForm::FIELD_NAME]);

                $groupManager->saveModifiedGroup($group);

                $this->go(
                    '资料已更新',
                    '分组: ' . $group->getGroupName() . ' 资料已经更新!',
                    $this->url()->fromRoute('admin/group')
                );

                return $this->layout()->setTerminal(true);
            }
        }

        $this->addResultData('form', $form);
        $this->addResultData('group', $group);
        $this->addResultData('activeID', __CLASS__);
    }


    /**
     * Delete group information
     */
    public function deleteAction()
    {
        $groupID = $this->params()->fromRoute('key', '');

        $groupManager = $this->appAdminGroupManager();
        $group = $groupManager->getGroupByID($groupID);

        if (!$group instanceof Group) {
            throw  new RuntimeException('Invalid group identity');
        }

        if (Group::DEFAULT_GROUP == $group->getGroupDefault()) {
            throw  new RuntimeException('Disable configuration default group');
        }

        $groupManager->removeGroup($group);

        $this->go('已删除', '分组已经删除!', $this->url()->fromRoute('admin/group'));
        return $this->layout()->setTerminal(true);
    }

}