<?php
namespace ProjectTimer;

 use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
 use Zend\ModuleManager\Feature\ConfigProviderInterface;
 use ProjectTimer\Model\ProjectTimerTable;
 use ProjectTimer\Model\ProjectTable;
 use Zend\Db\ResultSet\ResultSet;
 use Zend\Db\TableGateway\TableGateway;
 use Zend\Mvc\ModuleRouteListener;
 use Zend\Mvc\MvcEvent;
 use ProjectTimer\Model\ProjectTimer;
 use ProjectTimer\Model\Project;
 use ProjectTimer\Form\ProjectTimerForm;
 class Module implements AutoloaderProviderInterface, ConfigProviderInterface
 {
     public function getAutoloaderConfig()
     {
        /* return array(
             'Zend\Loader\ClassMapAutoloader' => array(
                 __DIR__ . '/autoload_classmap.php',
             ),
             'Zend\Loader\StandardAutoloader' => array(
                 'namespaces' => array(
                     __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                 ),
             ),
         );*/
     }

     public function getConfig()
     {
         return include __DIR__ . '/../../config/module.config.php';
     }
    public function getServiceConfig()
     {
         return array(
             'factories' => array(
                 'ProjectTimer\Model\ProjectTimerTable' =>  function($sm) {
                     $tableGateway = $sm->get('ProjectTimerTableGateway');
                     $table = new ProjectTimerTable($tableGateway);
                     return $table;
                 },
                 'ProjectTimerTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                     $resultSetPrototype->setArrayObjectPrototype(new ProjectTimer());
                     return new TableGateway('project_timer', $dbAdapter, null, $resultSetPrototype);
                 },

                'ProjectTimer\Model\ProjectTable' =>  function($sm) {
                     $tableGateway = $sm->get('ProjectTableGateway');
                     $table = new ProjectTable($tableGateway);
                     return $table;
                 },
                 'ProjectTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                     $resultSetPrototype->setArrayObjectPrototype(new Project());
                     return new TableGateway('project', $dbAdapter, null, $resultSetPrototype);
                 },
             ), //sub array
         ); //main array


     }

 }
?>
