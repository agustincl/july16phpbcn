<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Album;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Validator\AbstractValidator;

class Module
{   
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function onBootstrap(MvcEvent $e)
    {
        date_default_timezone_set('Europe/Madrid');
    
        $serviceManager = $e->getApplication()->getServiceManager();
        $translator = $serviceManager->get('translator');
    
        $locale = 'es_ES';
        // $locale = 'en_US';
    
        $translator->setLocale(\Locale::acceptFromHttp($locale));
        AbstractValidator::setDefaultTranslator($translator);
    }
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(               
                'Album\Model\AlbumMapper' => function ($sm) {                        
                        $adapterMaster 	= $sm->get('dbMasterAdapter');
                        $adapterSlave 	= $sm->get('dbSlaveAdapter');
                        $mapper = new \Album\Model\AlbumMapper($adapterMaster, $adapterSlave);
                        return $mapper;
                },  
            ),
        );
    }
}
