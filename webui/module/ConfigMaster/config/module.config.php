<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'ConfigMaster\Controller\Index' => 'ConfigMaster\Controller\IndexController',
            'ConfigMaster\Controller\User' => 'ConfigMaster\Controller\UserController',
            'ConfigMaster\Controller\Config' => 'ConfigMaster\Controller\ConfigController',
            'ConfigMaster\Controller\Product' => 'ConfigMaster\Controller\ProductController',
            'ConfigMaster\Controller\Channel' => 'ConfigMaster\Controller\ChannelController',
            'ConfigMaster\Controller\Country' => 'ConfigMaster\Controller\CountryController'
        )
    ),

    'router' => array(
        'routes' => array(
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'dashboard' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/dashboard',
                    'defaults' => array(
                        '__NAMESPACE__' => 'ConfigMaster\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action[/:id]]][/]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '\w+'
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    
    'view_manager' => array(
        'template_path_stack' => array(
            'config' => __DIR__ . '/../view'
        )
    )
);