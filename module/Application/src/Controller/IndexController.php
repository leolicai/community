<?php
/**
 * IndexController.php
 *
 * @author: Leo <camworkster@gmail.com>
 * @version: 1.0
 */

namespace Application\Controller;


use Zend\View\Model\ViewModel;


class IndexController extends AppBaseController
{

    public function indexAction()
    {
        return new ViewModel();
    }

}