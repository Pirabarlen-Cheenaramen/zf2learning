<?php
namespace ProjectTimer\Controller;

 use Zend\Mvc\Controller\AbstractActionController;
 use Zend\View\Model\ViewModel;

 class ProjectTimerController extends AbstractActionController
 {
    protected $projecttimerTable;
    protected $projectTable;

     public function indexAction()
     {
        return new ViewModel(array(
             'projecttimer' => $this->getProjectTimerTable()->fetchAll(),
         ));
     }

     public function addAction()
     {
     }

     public function editAction()
     {
     }

     public function deleteAction()
     {
     }
    public function getProjectTimerTable()
    {
         if (!$this->projecttimerTable) {
             $sm = $this->getServiceLocator();
             $this->projecttimerTable = $sm->get('ProjectTimer\Model\ProjectTimerTable');
         }
         return $this->projecttimerTable;
    }

    public function getProjectTable()
    {
         if (!$this->projectTable) {
             $sm = $this->getServiceLocator();
             $this->projectTable = $sm->get('ProjectTimer\Model\ProjectTable');
         }
         return $this->projectTable;
    }


 }
?>
