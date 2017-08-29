<?php
/**
 * Module.php
 *
 * @author: Leo <camworkster@gmail.com>
 * @version: 1.0
 */

namespace Application;


use Zend\Mvc\MvcEvent;


class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }


    /**
     *
     * @param MvcEvent $event
     */
    public function onBootstrap(MvcEvent $event)
    {
        // Register listener
        $sharedEventManager = $event->getApplication()->getEventManager()->getSharedManager();
        $sharedEventManager->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH, [$this, 'onDispatchListener'], 100);
        //$sharedEventManager->attach(\Zend\Mvc\Application::class, MvcEvent::EVENT_DISPATCH, [$this, 'onGlobalDispatchListener'], 0);
        $sharedEventManager->attach('Zend\Mvc\Application', MvcEvent::EVENT_DISPATCH, [$this, 'onGlobalDispatchListener'], 0);
    }


    /**
     * @param MvcEvent $event
     */
    public function onDispatchListener(MvcEvent $event)
    {
        //$serviceManager = $event->getApplication()->getServiceManager();
        //$logger = $serviceManager->get('AppLogger');
        //$logger->info('This is application module dispatch listener');
    }


    /**
     * Application global listener
     *
     * @param MvcEvent $event
     * @return mixed
     */
    public function onGlobalDispatchListener(MvcEvent $event)
    {
        $serviceManager = $event->getApplication()->getServiceManager();
        $logger = $serviceManager->get('AppLogger');
        //$logger->info('The is a global render listener');

        $resultData = $event->getTarget()->getResultData();
        $appConfig = $serviceManager->get('ApplicationConfig');
        $resultData['env'] = isset($appConfig['application']['env']) ? $appConfig['application']['env'] : 'development';

        //$logger->debug(json_encode($resultData));

        $request = $event->getRequest();
        if($request instanceof \Zend\Http\Request) {
            $headerAccept = $request->getHeader('Accept');
            if ($headerAccept) {

                $fieldValue = $headerAccept->getFieldValue();
                //$logger->debug('Accept string:' . $fieldValue);

                $fieldValues = explode(',', $fieldValue);
                $firstFieldValue = array_shift($fieldValues);

                $acceptValue = @strtolower(str_replace(' ', '', $firstFieldValue));
                //$logger->debug("accept response type:" . $acceptValue);


                if ('application/json' == $acceptValue) {
                    $headerContentType = new \Zend\Http\Header\ContentType();
                    $headerContentType->setMediaType('application/json');
                    $headerContentType->setCharset('UTF-8');

                    $responseHeaders = new \Zend\Http\Headers();
                    $responseHeaders->addHeader($headerContentType);

                    $response = $event->getResponse();
                    if (!$response instanceof \Zend\Http\Response) {
                        $response = new \Zend\Http\Response();
                    }
                    $response->setHeaders($responseHeaders);
                    $response->setContent(json_encode($resultData, JSON_UNESCAPED_UNICODE));

                    $event->setResult($response);
                    return ;
                }

                if ('text/html' == $acceptValue || 'text/plain' == $acceptValue) {
                    $logger->info("set var to view: " . $resultData['message']);
                    $event->getViewModel()->setVariables($resultData);
                    $logger->info('root template: ' . $event->getViewModel()->getTemplate());
                    foreach($event->getViewModel()->getChildren() as $child) {
                        if ($child instanceof \Zend\View\Model\ViewModel) {
                            $child->setVariables($resultData);
                            $logger->info('child template: ' . $child->getTemplate() . PHP_EOL. json_encode($resultData));
                        }
                    }

                    return ;
                }
            }
        }

        return $event->setResult($event->getResponse());
    }

}