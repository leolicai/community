<?php
/**
 * IndexController.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 17/8/27
 * Version: 1.0
 */

namespace Admin\Controller;


use Admin\Exception\RuntimeException;
use Admin\Form\LoginForm;
use Admin\Service\AuthAdapter;


class IndexController extends AdminBaseController
{

    /**
     * Page switch
     */
    public function indexAction()
    {
        $authService = $this->appAdminAuthService();
        if ($authService->hasIdentity() && $authService->getIdentity()) {
            return $this->redirect()->toRoute('admin/dashboard', ['suffix' => '.html']);
        } else {
            return $this->redirect()->toRoute('admin/index', ['action' => 'login', 'suffix' => '.html']);
        }
    }


    /**
     * Page for logout
     */
    public function logoutAction()
    {
        $authService = $this->appAdminAuthService();
        if ($authService->hasIdentity()) {
            $authService->clearIdentity();
        }
        return $this->go('安全退出', '您的账号已经安全的退出系统, 再见!');
    }


    /**
     * Page for login
     */
    public function loginAction()
    {
        $authService = $this->appAdminAuthService();
        if ($authService->hasIdentity() || $authService->getIdentity()) {
            throw new RuntimeException('禁止重复登录', 1111);
        }

        $form = new LoginForm();
        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());
            if ($form->isValid()) {

                $data = $form->getData();

                $authAdapter = $authService->getAdapter();
                if (!$authAdapter instanceof AuthAdapter) {
                    throw new RuntimeException('系统未配置授权适配器', 1112);
                }

                $authAdapter->setEmail($data['email']);
                $authAdapter->setPasswd($data['password']);
                $authAdapter->setAdminerManager($this->appAdminAdminerManager());

                $result = $authService->authenticate();
                if ($result->getCode() != AuthAdapter::SUCCESS) {
                    $this->setResultData($result->getCode(), 'Failure');
                } else {
                    return $this->go('欢迎登录', '欢迎你再次登录管理中心!', $this->url()->fromRoute('admin'));
                }
            }
        }

        $this->addResultData('form', $form);
    }


    /**
     * Display information for all module request
     */
    public function messageAction()
    {
        $this->addResultData('topic', $this->params()->fromRoute('topic', '提示信息'));
        $this->addResultData('content', $this->params()->fromRoute('content', '...'));
        $this->addResultData('url', $this->params()->fromRoute('url', '#'));
        $this->addResultData('title', $this->params()->fromRoute('title', '返回'));
        $this->addResultData('delay', $this->params()->fromRoute('delay', 0));
    }

}