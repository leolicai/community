<?php
/**
 * AdminerController.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 2017/8/30
 * Version: 1.0
 */

namespace Admin\Controller;


use Admin\Entity\Adminer;
use Admin\Entity\Group;
use Admin\Exception\RuntimeException;
use Admin\Form\AdminerForm;
use Application\View\Helper\Pagination;
use Ramsey\Uuid\Uuid;


class AdminerController extends AdminBaseController
{

    /**
     * Display system administrators list
     */
    public function indexAction()
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

        $pageUrlTpl = $this->url()->fromRoute('admin/adminer', ['action' => 'index', 'key' => '%d']);
        $pagination->setPage($page)->setSize($size)->setCount($count)->setUrlTpl($pageUrlTpl);

        $entites = $adminerManager->getAdminersByLimitPage($page, $size);

        $this->addResultData('adminers', $entites);
        $this->addResultData('activeID', __METHOD__);
    }


    /**
     * Page for add new administrator
     */
    public function addAction()
    {
        $adminerManager = $this->appAdminAdminerManager();

        $form = new AdminerForm($adminerManager, null, [
            AdminerForm::FIELD_EMAIL,
            AdminerForm::FIELD_PASSWORD,
            AdminerForm::FIELD_NAME,
            AdminerForm::FIELD_EXPIRED]);

        if($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());
            if ($form->isValid()) {
                $data = $form->getData();

                $defaultGroup = $this->appAdminGroupManager()->getDefaultGroup();
                if (!$defaultGroup instanceof Group) {
                    throw new RuntimeException('Lost default group');
                }

                $adminer = new Adminer();
                $adminer->setAdminID(Uuid::uuid1()->toString());
                $adminer->setAdminEmail($data[AdminerForm::FIELD_EMAIL]);
                $adminer->setAdminPasswd($data[AdminerForm::FIELD_PASSWORD]);
                $adminer->setAdminName($data[AdminerForm::FIELD_NAME]);
                $adminer->setAdminExpired(new \DateTime($data[AdminerForm::FIELD_EXPIRED]));
                $adminer->setAdminActivated(Adminer::ACTIVATED_INVALID);
                $adminer->setAdminActiveCode(md5(time()));
                $adminer->setAdminLocked(Adminer::LOCKED_INVALID);
                $adminer->setAdminDefault(Adminer::DEFAULT_OTHER);
                $adminer->setAdminLevel(Adminer::LEVEL_DEFAULT);
                $adminer->setAdminCreated(new \DateTime());

                $groups = $adminer->getAdminGroups();
                $groups->add($defaultGroup);
                $adminer->setAdminGroups($groups);

                $adminerManager->saveModifiedAdminer($adminer);

                $this->go(
                    '管理员已添加',
                    '新管理员: ' . $data[AdminerForm::FIELD_NAME] . ' 已经添加!',
                    $this->url()->fromRoute('admin/adminer')
                );

                return $this->layout()->setTerminal(true);
            }
        }

        $this->addResultData('form', $form);
        $this->addResultData('activeID', __METHOD__);
    }


    /**
     * Active administrator account
     */
    public function activeAction()
    {
        $adminID = $this->params()->fromRoute('key', '');

        $adminerManager = $this->appAdminAdminerManager();

        $adminer = $adminerManager->getAdminerByID($adminID);
        if (!$adminer instanceof Adminer) {
            throw  new RuntimeException('Invalid administrator identity');
        }

        if (Adminer::DEFAULT_ADMIN == $adminer->getAdminDefault()) {
            throw  new RuntimeException('Disable configuration default administrator');
        }

        $adminer->setAdminActivated(Adminer::ACTIVATED_VALID);
        $adminer->setAdminActiveCode('');

        $adminerManager->saveModifiedAdminer($adminer);

        $this->go('已激活', '管理员账号已经被激活!', $this->url()->fromRoute('admin/adminer'));

        return $this->layout()->setTerminal(true);
    }

    /**
     * Lock administrator account
     */
    public function lockAction()
    {
        $adminID = $this->params()->fromRoute('key', '');

        $adminerManager = $this->appAdminAdminerManager();

        $adminer = $adminerManager->getAdminerByID($adminID);
        if (!$adminer instanceof Adminer) {
            throw  new RuntimeException('Invalid administrator identity');
        }

        if (Adminer::DEFAULT_ADMIN == $adminer->getAdminDefault()) {
            throw  new RuntimeException('Disable configuration default administrator');
        }

        $adminer->setAdminLocked(Adminer::LOCKED_VALID);

        $adminerManager->saveModifiedAdminer($adminer);

        $this->go('已锁定', '管理员账号已经被锁定!', $this->url()->fromRoute('admin/adminer'));

        return $this->layout()->setTerminal(true);
    }

    /**
     * UnLock administrator account
     */
    public function unlockAction()
    {
        $adminID = $this->params()->fromRoute('key', '');

        $adminerManager = $this->appAdminAdminerManager();

        $adminer = $adminerManager->getAdminerByID($adminID);
        if (!$adminer instanceof Adminer) {
            throw  new RuntimeException('Invalid administrator identity');
        }

        if (Adminer::DEFAULT_ADMIN == $adminer->getAdminDefault()) {
            throw  new RuntimeException('Disable configuration default administrator');
        }

        $adminer->setAdminLocked(Adminer::LOCKED_INVALID);

        $adminerManager->saveModifiedAdminer($adminer);

        $this->go('已解锁', '管理员账号已经解除锁定!', $this->url()->fromRoute('admin/adminer'));

        return $this->layout()->setTerminal(true);
    }


    /**
     * Page for edit administrator information
     */
    public function profileAction()
    {
        $adminID = $this->params()->fromRoute('key', '');

        $adminerManager = $this->appAdminAdminerManager();

        $adminer = $adminerManager->getAdminerByID($adminID);
        if (!$adminer instanceof Adminer) {
            throw  new RuntimeException('Invalid administrator identity');
        }

        if (Adminer::DEFAULT_ADMIN == $adminer->getAdminDefault()) {
            throw  new RuntimeException('Disable configuration default administrator');
        }

        $form = new AdminerForm($adminerManager, $adminer, [AdminerForm::FIELD_EMAIL, AdminerForm::FIELD_NAME]);

        if($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());
            if ($form->isValid()) {
                $data = $form->getData();

                $adminer->setAdminEmail($data[AdminerForm::FIELD_EMAIL]);
                $adminer->setAdminName($data[AdminerForm::FIELD_NAME]);

                $adminerManager->saveModifiedAdminer($adminer);

                $this->go(
                    '资料已更新',
                    '管理员: ' . $data[AdminerForm::FIELD_NAME] . ' 资料已经更新!',
                    $this->url()->fromRoute('admin/adminer')
                );

                return $this->layout()->setTerminal(true);
            }
        }

        $this->addResultData('form', $form);
        $this->addResultData('adminer', $adminer);
        $this->addResultData('activeID', __CLASS__);
    }


    /**
     * Page for edit administrator level
     */
    public function levelAction()
    {
        $adminID = $this->params()->fromRoute('key', '');

        $adminerManager = $this->appAdminAdminerManager();

        $adminer = $adminerManager->getAdminerByID($adminID);
        if (!$adminer instanceof Adminer) {
            throw  new RuntimeException('Invalid administrator identity');
        }

        if (Adminer::DEFAULT_ADMIN == $adminer->getAdminDefault()) {
            throw  new RuntimeException('Disable configuration default administrator');
        }

        $form = new AdminerForm($adminerManager, null, [AdminerForm::FIELD_LEVEL]);

        if($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());
            if ($form->isValid()) {
                $data = $form->getData();

                $adminer->setAdminLevel($data[AdminerForm::FIELD_LEVEL]);

                $adminerManager->saveModifiedAdminer($adminer);

                $this->go(
                    '等级已更新',
                    '管理员: ' . $adminer->getAdminName() . ' 等级已经更新!',
                    $this->url()->fromRoute('admin/adminer')
                );

                return $this->layout()->setTerminal(true);
            }
        }

        $this->addResultData('form', $form);
        $this->addResultData('adminer', $adminer);
        $this->addResultData('activeID', __CLASS__);
    }


    /**
     * Page for update administrator password
     */
    public function passwordAction()
    {
        $adminID = $this->params()->fromRoute('key', '');

        $adminerManager = $this->appAdminAdminerManager();

        $adminer = $adminerManager->getAdminerByID($adminID);
        if (!$adminer instanceof Adminer) {
            throw  new RuntimeException('Invalid administrator identity');
        }

        if (Adminer::DEFAULT_ADMIN == $adminer->getAdminDefault()) {
            throw  new RuntimeException('Disable configuration default administrator');
        }

        $form = new AdminerForm($adminerManager, null, [AdminerForm::FIELD_PASSWORD]);

        if($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());
            if ($form->isValid()) {
                $data = $form->getData();

                $adminer->setAdminPasswd($data[AdminerForm::FIELD_PASSWORD]);

                $adminerManager->saveModifiedAdminer($adminer);

                $this->go(
                    '密码已更新',
                    '管理员: ' . $adminer->getAdminName() . ' 的密码已经更新!',
                    $this->url()->fromRoute('admin/adminer')
                );

                return $this->layout()->setTerminal(true);
            }
        }

        $this->addResultData('form', $form);
        $this->addResultData('adminer', $adminer);
        $this->addResultData('activeID', __CLASS__);
    }


    /**
     * Page for edit administrator expired date
     */
    public function expiredAction()
    {
        $adminID = $this->params()->fromRoute('key', '');

        $adminerManager = $this->appAdminAdminerManager();

        $adminer = $adminerManager->getAdminerByID($adminID);
        if (!$adminer instanceof Adminer) {
            throw  new RuntimeException('Invalid administrator identity');
        }

        if (Adminer::DEFAULT_ADMIN == $adminer->getAdminDefault()) {
            throw  new RuntimeException('Disable configuration default administrator');
        }

        $form = new AdminerForm($adminerManager, null, [AdminerForm::FIELD_EXPIRED]);

        if($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());
            if ($form->isValid()) {
                $data = $form->getData();

                $adminer->setAdminExpired(new \DateTime($data[AdminerForm::FIELD_EXPIRED]));

                $adminerManager->saveModifiedAdminer($adminer);

                $this->go(
                    '失效时间已更新',
                    '管理员: ' . $adminer->getAdminName() . ' 的失效时间已经更新!',
                    $this->url()->fromRoute('admin/adminer')
                );

                return $this->layout()->setTerminal(true);
            }
        }

        $this->addResultData('form', $form);
        $this->addResultData('adminer', $adminer);
        $this->addResultData('activeID', __CLASS__);
    }


    /**
     * Page for dispatch administrator to groups
     */
    public function groupAction()
    {
        $adminID = $this->params()->fromRoute('key', '');

        $adminerManager = $this->appAdminAdminerManager();

        $adminer = $adminerManager->getAdminerByID($adminID);
        if (!$adminer instanceof Adminer) {
            throw  new RuntimeException('Invalid administrator identity');
        }

        if (Adminer::DEFAULT_ADMIN == $adminer->getAdminDefault()) {
            throw  new RuntimeException('Disable configuration default administrator');
        }

        $groupManager = $this->appAdminGroupManager();
        $groups = $groupManager->getAllGroups();

        if($this->getRequest()->isPost()) {

            $joined = (array)$this->params()->fromPost('joined', []);
            $unjoin = (array)$this->params()->fromPost('unjoin', []);

            $selected = [];
            foreach ($joined as $groupID) {
                $selected[$groupID] = $groupID;
            }
            foreach ($unjoin as $groupID) {
                $selected[$groupID] = $groupID;
            }
            //echo '<pre>'; print_r($this->params()->fromPost()); print_r($selected); echo '</pre>';

            $adminGroups = $adminer->getAdminGroups();
            foreach ($adminGroups as $group) {
                if (!in_array($group->getGroupID(), $selected)) {
                    $adminGroups->removeElement($group);
                } else {
                    unset($selected[$group->getGroupID()]);
                }
            }
            if (!empty($selected)) {
                foreach ($selected as $groupID) {
                    foreach($groups as $group) {
                        if ($group->getGroupID() == $groupID) {
                            $adminGroups->add($group);
                        }
                    }
                }
            }

            $defaultGroup = $groupManager->getDefaultGroup();
            if (!$adminGroups->contains($defaultGroup)) {
                $adminGroups->add($defaultGroup);
            }

            $adminer->setAdminGroups($adminGroups);

            $adminerManager->saveModifiedAdminer($adminer);

            $this->go(
                '分组已更新',
                '管理员: ' . $adminer->getAdminName() . ' 的分组信息已经更新!',
                $this->url()->fromRoute('admin/adminer')
            );

            return $this->layout()->setTerminal(true);
        }

        $this->addResultData('adminer', $adminer);
        $this->addResultData('groups', $groups);
    }



    /**
     *  ACL Registry
     *
     * @return array
     */
    public static function ComponentRegistry()
    {
        $item = self::BuildComponentInfo(__CLASS__, '管理员管理', 'admin/adminer', 1, 'user', 6);

        $item['component_actions']['index'] = self::BuildActionInfo('index', '管理员列表', 1, 'bars', 9);
        $item['component_actions']['add'] = self::BuildActionInfo('add', '新增管理员', 1, 'user-plus');

        $item['component_actions']['active'] = self::BuildActionInfo('active', '激活管理员账号');
        $item['component_actions']['lock'] = self::BuildActionInfo('lock', '锁定管理员账号');
        $item['component_actions']['unlock'] = self::BuildActionInfo('unlock', '解锁管理员账号');
        $item['component_actions']['profile'] = self::BuildActionInfo('profile', '修改管理员账号');
        $item['component_actions']['level'] = self::BuildActionInfo('level', '修改管理员等级');
        $item['component_actions']['password'] = self::BuildActionInfo('password', '修改管理员密码');
        $item['component_actions']['expired'] = self::BuildActionInfo('expired', '修改管理账号过期时间');
        $item['component_actions']['group'] = self::BuildActionInfo('group', '修改管理分组信息');

        return $item;
    }


}