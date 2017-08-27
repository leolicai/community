<?php
/**
 * AppBaseController.php
 *
 * @author: Leo <camworkster@gmail.com>
 * @version: 1.0
 */

namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;


/**
 * Class AppBaseController
 * @package Application\Controller
 *
 * @method \Application\Controller\Plugin\AppLoggerPlugin appLogger()
 * @method \Application\Controller\Plugin\AppConfigPlugin appConfig()
 */
class AppBaseController extends AbstractActionController
{

}