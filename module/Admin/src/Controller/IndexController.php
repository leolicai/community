<?php
/**
 * IndexController.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 17/8/27
 * Version: 1.0
 */

namespace Admin\Controller;


use Zend\View\Model\ViewModel;


class IndexController extends AdminBaseController
{

    public function indexAction()
    {

        return new ViewModel();
    }


    public function loginAction()
    {
        return new ViewModel();
    }

}