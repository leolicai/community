<?php
/**
 * IndexController.php
 *
 * @author: Leo <camworkster@gmail.com>
 * @version: 1.0
 */

namespace WeChat\Controller;


class IndexController extends WeChatBaseController
{

    public function indexAction()
    {
        return $this->getResponse();
    }
}