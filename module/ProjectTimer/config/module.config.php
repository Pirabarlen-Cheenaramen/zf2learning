<?php
return array(
     'controllers' => array(
         'invokables' => array(
             'ProjectTimer\Controller\ProjectTimer' => 'ProjectTimer\Controller\ProjectTimerController',
         ),
     ),
     'router' => array(
         'routes' => array(
             'projecttimer' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/projecttimer[/:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'ProjectTimer\Controller\ProjectTimer',
                         'action'     => 'index',
                     ),
                 ),
             ),
         ),
     ),
     'view_manager' => array(
         'template_path_stack' => array(
             'projecttimer' => __DIR__ . '/../view',
         ),
     ),
 );
?>
