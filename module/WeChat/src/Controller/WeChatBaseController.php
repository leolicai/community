<?php
/**
 * WeChatBaseController.php
 *
 * @author: Leo <camworkster@gmail.com>
 * @version: 1.0
 */

namespace WeChat\Controller;


use Application\Controller\AppBaseController;
use Zend\Http\Header\ContentType;
use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;


class WeChatBaseController extends AppBaseController
{

    const MEDIA_TYPE_JSON = 'application/json';
    const MEDIA_TYPE_HTML = 'text/html';
    const MEDIA_TYPE_XML = 'application/xml';
    const MEDIA_TYPE_PLAIN = 'text/plain';

    const MEDIA_CHARSET = 'UTF-8';

    protected $mediaType = '';


    /**
     * @param string $type
     */
    protected function setMediaType($type)
    {
        $this->mediaType = $type;
    }


    public function onDispatch(MvcEvent $e)
    {
        $viewModel = parent::onDispatch($e);

        $response = $this->getResponse();

        $headerContentType = new ContentType();
        $headerContentType->setCharset(self::MEDIA_CHARSET);

        if ($viewModel instanceof JsonModel) {

            $headerContentType->setMediaType(self::MEDIA_TYPE_JSON);

            $params = $viewModel->getVariables();
            $response->setContent(json_encode($params, JSON_UNESCAPED_UNICODE));

            if ($response instanceof Response) {
                $response->getHeaders()->addHeader($headerContentType);
            }

            return $response;
        }

        if ($viewModel instanceof ViewModel) {

            $viewModel->setTerminal(true);

            if (self::MEDIA_TYPE_XML == $this->mediaType) {
                $headerContentType->setMediaType(self::MEDIA_TYPE_XML);
            } else if(self::MEDIA_TYPE_PLAIN == $this->mediaType) {
                $headerContentType->setMediaType(self::MEDIA_TYPE_PLAIN);
            } else {
                $headerContentType->setMediaType(self::MEDIA_TYPE_HTML);
            }

            if ($response instanceof Response) {
                $response->getHeaders()->addHeader($headerContentType);
            }
        }

        return $viewModel;
    }

}