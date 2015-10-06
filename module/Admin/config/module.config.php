<?php
namespace Admin;

// module/Admin/conﬁg/module.config.php:
return array(
    'controllers' => array( //add module controllers
        'invokables' => array(
            'Admin\Controller\Index' => 'Admin\Controller\IndexController',
            'Admin\Controller\Auth' => 'Admin\Controller\AuthController',

        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'cache' => 'Doctrine\Common\Cache\ArrayCache',
            'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Model')
          ),
    ),
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/admin',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                        'module'        => 'admin'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                        'child_routes' => array( //permite mandar dados pela url 
                            'wildcard' => array(
                                'type' => 'Wildcard'
                            ),
                        ),
                    ),
                    
                ),
            ),
        ),
    ),
    'view_manager' => array( //the module can have a specific layout
        // 'template_map' => array(
        //     'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
        // ),
        'template_path_stack' => array(
            'admin' => __DIR__ . '/../view',
        ),
    ),
    // 'db' => array( //module can have a specific db configuration
    //     'driver' => 'PDO_SQLite',
    //     'dsn' => 'sqlite:' . __DIR__ .'/../data/admin.db',
    //     'driver_options' => array(
    //         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    //     )
    // )
    

//    'service_manager' => array(
//        'factories' => array(
//            'Session' => function($sm) {
//                return new Zend\Session\Container('ZF2napratica');
//            },
//            'Admin\Service\Auth' => function($sm) {
//                $dbAdapter = $sm->get('DbAdapter');
//                return new Admin\Service\Auth($dbAdapter);
//            },
//        )    
//    )
    
    'service_manager' => array(
        'factories' => array(
            'Session' => function($sm) {
                return new Zend\Session\Container('ZF2napratica');
            },
            'Admin\Service\Auth' => function($sm) {
                $dbAdapter = $sm->get('DbAdapter');
                return new Admin\Service\Auth($dbAdapter);
            },
            'Cache' => function($sm) {
                //$config = include __DIR__ . '/../../../config/application.config.php';
                $config = $sm->get('Config');
                $cache = \Zend\Cache\StorageFactory::factory(
                    array(
                        'adapter' => $config['cache']['adapter'],
                        'ttl' => $config['cache']['ttl'],
                        'plugins' => $config['cache']['plugins']
                    )
                );

                return $cache;
            },
            'Doctrine\ORM\EntityManager' => function($sm) {
                $config = $sm->get('Configuration');
                //$config = $sm->get('Config'); mesma coisa
                
                $doctrineConfig = new \Doctrine\ORM\Configuration();
                $cache = new $config['doctrine']['driver']['cache'];
                $doctrineConfig->setQueryCacheImpl($cache);
                $doctrineConfig->setProxyDir('/tmp');
                $doctrineConfig->setProxyNamespace('EntityProxy');
                $doctrineConfig->setAutoGenerateProxyClasses(true);
                
                $driver = new \Doctrine\ORM\Mapping\Driver\AnnotationDriver(
                    new \Doctrine\Common\Annotations\AnnotationReader(),
                    array($config['doctrine']['driver']['paths'])
                );
                $doctrineConfig->setMetadataDriverImpl($driver);
                $doctrineConfig->setMetadataCacheImpl($cache);
                \Doctrine\Common\Annotations\AnnotationRegistry::registerFile(
                    getenv('PROJECT_ROOT'). '/vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php'
                );
                $em = \Doctrine\ORM\EntityManager::create(
                    $config['doctrine']['connection'],
                    $doctrineConfig
                );
                return $em;

            },
        )    
    ),
                
);