<?php

namespace Core\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Helper que verifica partial no chache
 * 
 * @category Application
 * @package View\Helper
 * @author  Elton Minetto <eminetto@coderockr.com>
 */
class CachedPartial extends AbstractHelper implements ServiceLocatorAwareInterface
{
    /**
     * Set the service locator.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return CustomHelper
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }
    /**
     * Get the service locator.
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
    public function __invoke($partial)
    {
        
        $helperPluginManager = $this->getServiceLocator();
        $serviceManager = $helperPluginManager->getServiceLocator();
        
        $cache = $serviceManager->get('Cache');
        
        $cachedKey = 'partial_'.$partial;
        //echo $cachedKey; exit;
        if(!$template = $cache->getItem($cachedKey)){
            echo 'Não existe:  '. $partial;
            $template = $partial;
            $cache->addItem($cachedKey, $template);
        }else{
            echo 'Existe: '. $cache->getItem($cachedKey); //exit;   
        }
        //return $template;
        //return $this->view->render($template);
    }
}