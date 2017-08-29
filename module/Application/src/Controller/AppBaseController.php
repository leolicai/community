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

    /**
     * @var array
     */
    private $resultData;

    public function __construct()
    {
        $this->resultData = [];
        $this->resultData['data'] = new \stdClass();

        $this->setResultData();
    }


    /**
     * @param null $service_name
     * @return mixed|\Zend\ServiceManager\ServiceLocatorInterface
     */
    protected function appServiceManager($service_name = null)
    {
        $manager = $this->getEvent()->getApplication()->getServiceManager();
        if (null === $manager) {
            return $manager;
        }
        return $manager->get($service_name);
    }


    /**
     * Add data to result object
     *
     * @param string $key
     * @param null|string|array|\stdClass $data
     */
    protected function addResultData($key, $data = null)
    {
        $this->resultData['data']->{$key} = $data;
    }


    /**
     * Init the result object
     *
     * @param int $code
     * @param string $message
     */
    protected function setResultData($code = 0, $message = 'Success')
    {
        $this->resultData['code'] = (int)$code;
        $this->resultData['message'] = (string)$message;
    }

    /**
     * @return array
     */
    public function getResultData()
    {
        return $this->resultData;
    }
}