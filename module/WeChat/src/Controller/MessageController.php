<?php
/**
 * MessageController.php
 *
 * @author: Leo <camworkster@gmail.com>
 * @version: 1.0
 */

namespace WeChat\Controller;


use Zend\View\Model\ViewModel;

class MessageController extends WeChatBaseController
{

    public function indexAction()
    {
        return $this->getResponse();
    }


    public function receivedAction()
    {
        $this->setMediaType(self::MEDIA_TYPE_PLAIN);

        return new ViewModel();
    }

}