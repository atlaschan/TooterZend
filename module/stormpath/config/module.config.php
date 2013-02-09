<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'stormpath\Controller\account' => 'stormpath\Controller\IndexController', //it has defined the controller name mapping used in router.
			'stormpath\Controller\password' => 'stormpath\Controller\PasswordController',
			'stormpath\Controller\profile' => 'stormpath\Controller\ProfileController',
			'stormpath\Controller\signup' => 'stormpath\Controller\SignUpController',
			'stormpath\Controller\toot' => 'stormpath\Controller\TootController',
        ),
    ),
	
	/**
	* Zend asks controller action method name to be camel casing for multiple words, however
	* it enforces lower case in the url recognition. Instead, it provides the mapping from camel casing to 
	* all lower cases with dashes separating the word.
	* For example, for signUp, the action name is signUpAction, but the url and view name should be sign-up and sign-up.phtml
	* Record this for understanding convenience.
	*/
	
	
    'router' => array(
        'routes' => array(
			'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'stormpath\Controller\account',
                        'action'     => 'login',
                    ),
                ),
            ),
			
			'login' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/login',
                    'defaults' => array(
                        'controller' => 'stormpath\Controller\account',
                        'action'     => 'login',
                    ),
                ),
            ),
			
			'logout' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/logout',
                    'defaults' => array(
                        'controller' => 'stormpath\Controller\account',
                        'action'     => 'logout',
                    ),
                ),
            ),
			
			'profile' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/profile',
                    'defaults' => array(
                        'controller' => 'stormpath\Controller\profile',
                        'action'     => 'profile',
                    ),
                ),
            ),
			
			'resetPassword' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/reset-password',
                    'defaults' => array(
                        'controller' => 'stormpath\Controller\password',
                        'action'     => 'resetPassword',
                    ),
                ),
            ),
			
			'resetPasswordMsg' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/reset-password-msg',
                    'defaults' => array(
                        'controller' => 'stormpath\Controller\password',
                        'action'     => 'resetPasswordMsg',
                    ),
                ),
            ),
			
			'signUp' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/sign-up',
                    'defaults' => array(
                        'controller' => 'stormpath\Controller\signup',
                        'action'     => 'signUp',
                    ),
                ),
            ),
			
			'tooter' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/tooter',
                    'defaults' => array(
                        'controller' => 'stormpath\Controller\toot',
                        'action'     => 'tooter',
                    ),
                ),
            ),
			
			'changePassword' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/changePassword',
                    'defaults' => array(
                        'controller' => 'stormpath\Controller\password',
                        'action'     => 'changePassword',
                    ),
                ),
            ),
			
			/*
			'view' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/view/:key/:sequence',
					'constraints' => array(
                        'key' => '[0-9]{10,13}',
						'sequence' => '[0-9]{1,2}',
                    ),
                    'defaults' => array(
                        'controller' => 'stormpath\Controller\stormpath',
                        'action'     => 'view',
                    ),
                ),
            ),*/
			
        ),
    ),
	
    'view_manager' => array(
        'template_path_stack' => array(
            'stormpath' => __DIR__ . '/../view',
        ),
    ),
);
