<?php
namespace ProjectTimer\Model;

 use Zend\Db\TableGateway\TableGateway;

 class ProjectTimerTable
 {
     protected $tableGateway;

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
     }

     public function fetchAll()
     {
         $resultSet = $this->tableGateway->select();
         return $resultSet;
     }

     public function getProjectTimer($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('project_id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }

     public function getProjectTimerByStop($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('project_id' => $id, 'stop_time'=>null));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }


     public function saveProjectTimer(ProjectTimer $projecttimer)
     {
         $data = array(
             'start_time' => $projecttimer->start_time,
             'stop_time'  => $projecttimer->stop_time,
         );

         $id = (int) $projecttimer->project_id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->getProjectTimer($id)) {
                 $this->tableGateway->update($data, array('project_id' => $id));
             } else {
                 throw new \Exception('ProjectTimer id does not exist');
             }
         }
     }

     public function deleteProjectTimer($id)
     {
         $this->tableGateway->delete(array('project_id' => (int) $id));
     }
 }
?>
