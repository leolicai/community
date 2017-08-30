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
use Admin\View\Helper\Pagination;
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

        $count = $adminerManager->getAdminerCount();

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
                $adminer->setAdminEmail($data['email']);
                $adminer->setAdminPasswd($data['password']);
                $adminer->setAdminName($data['name']);

                $groups = $adminer->getAdminGroups();
                $groups->add($defaultGroup);
                $adminer->setAdminGroups($groups);

                $adminerManager->getEntityManager()->persist($adminer);
                $adminerManager->getEntityManager()->flush();

                $this->go(
                    '成员已添加',
                    '新成员: ' . $data['name'] . ' 已经添加到系统中!',
                    $this->url()->fromRoute('admin/member'),
                    '查看成员列表',
                    3
                );

                return $this->layout()->setTerminal(true);
            }
        }

        $this->addResultData('form', $form);
        $this->addResultData('activeID', __METHOD__);
    }

}