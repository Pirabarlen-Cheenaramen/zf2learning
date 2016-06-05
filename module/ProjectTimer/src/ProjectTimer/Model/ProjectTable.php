<?php
namespace ProjectTimer\Model;

 use Zend\Db\TableGateway\TableGateway;

 class ProjectTable
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

     public function getProject($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('idproject' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }

     public function saveProject(Project $project)
     {
         $data = array(
             'projectname'  => $project->projectname,
         );

         $id = (int) $project->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->getProject($id)) {
                 $this->tableGateway->update($data, array('idproject' => $id));
             } else {
                 throw new \Exception('Project id does not exist');
             }
         }
     }

     public function deleteProject($id)
     {
         $this->tableGateway->delete(array('idproject' => (int) $id));
     }
 }
?>
