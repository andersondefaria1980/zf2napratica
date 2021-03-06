<?php

return array(
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
    'db' => array(
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=zf2napratica;host=localhost:8889',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    'acl' => array(
        'roles' => array(
            'visitante' => null,
            'redator' => 'visitante',
            'admin' => 'redator'
        ),
        'resources' => array(
            'Application\Controller\Index.index',
            'Application\Controller\Index.comments',
            'Admin\Controller\Index.save',
            'Admin\Controller\Index.delete',
            'Admin\Controller\Auth.index',
            'Admin\Controller\Auth.login',
            'Admin\Controller\Auth.logout',
        ),
        'privilege' => array(
            'visitante' => array(
                'allow' => array(
                    'Application\Controller\Index.index',
                    'Application\Controller\Index.comments',
                    'Admin\Controller\Auth.index',
                    'Admin\Controller\Auth.login',
                    'Admin\Controller\Auth.logout',
                    
                )
            ),
            'redator' => array(
                'allow' => array(
                    'Admin\Controller\Index.save',
                )
            ),
            'admin' => array(
                'allow' => array(
                    'Admin\Controller\Index.delete',
                )
            ),
        ),
    ),
    'cache' => array(
        'adapter' => 'memory',
        'ttl' => 0,
        'plugins' => array(
            'exception_handler' => array('throw_exceptions' => false),
            'Serializer'
        ),
    ),
    'doctrine' => array(
    'connection' => array(
      'driver'   => 'pdo_mysql',
      'host'     => 'localhost',
      'port'     => '3306',
      'dbname'   => 'zf2napratica'
    )
  ),
);