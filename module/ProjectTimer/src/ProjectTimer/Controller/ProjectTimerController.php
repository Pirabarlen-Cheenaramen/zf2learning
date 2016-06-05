<?php
namespace ProjectTimer\Controller;

 use Zend\Mvc\Controller\AbstractActionController;
 use Zend\View\Model\ViewModel;
 use ProjectTimer\Model\Project;
 use ProjectTimer\Form\ProjectTimerForm;
 class ProjectTimerController extends AbstractActionController
 {
    protected $projecttimerTable;
    protected $projectTable;
    protected $projectTableHashKey;
    protected $projectTimerTableHashKey;
    public $timeSummary;
    public $projectHashKey;
    //projectStatus 0 means closed; 1 means still openned [generated in getTimeSummary]
    public $projectStatus;
    public function indexAction()
     {
        return new ViewModel(array(
             'projecttimertable' => $this->getProjectTimerTable()->fetchAll(),
             'projecttable' => $this->getProjectTable()->fetchAll(),
            'timesummary' => $this->getTimeSummary(),
            'projecthashkey' => $this->getProjectsHashKey(),
            'projectstatus' => $this->projectStatus,
         ));
     }

     public function addAction()
     {
       $form = new ProjectTimerForm();
         $form->get('submit')->setValue('Add');

         $request = $this->getRequest();
         if ($request->isPost()) {
             $project = new Project();
             $form->setInputFilter($project->getInputFilter());
             $form->setData($request->getPost());

             if ($form->isValid()) {
                 $project->exchangeArray($form->getData());
                 $this->getProjectTable()->saveProject($project);

                 // Redirect to list of albums
                 return $this->redirect()->toRoute('projecttimer');
             }
         }
         return array('form' => $form);
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

    //get a project id, calculate its hours
    public function getTotalHours($id)
    {

    }

    public function getProjectName($id)
    {
        if (! $this->projectTableHashKey[$id] ) { return null; }
        return $this->projectTableHashKey[$id];
    }

    private function getProjectsHashKey()
    {
            $this->projectTableHashKey=null;
            $projects= $this->getProjectTable()->fetchAll();

            foreach( $projects as $project )
            {
                $this->projectTableHashKey[$project->idproject] = $project->projectname;
            }
        return $this->projectTableHashKey;
    }

    private function getTimeSummary()
    {
        if(!$this->timeSummary)
        {
            $projectTime=$this->getProjectTimerTable()->fetchAll();
            foreach( $projectTime as $pt )
            {
                if (!$pt->start_time)
                { $projectStatus[$pt]->project_id=0; continue; }
                if($pt->start_time && !$pt->stop_time)
                {
                    $dStop=new \DateTime("NOW");
                    $this->projectStatus[$pt->project_id]=1;
                }
                else
                {
                    $dStop=new \DateTime($pt->stop_time);
                }
                $dStart=new \DateTime($pt->start_time);
                $tStart=$dStart->getTimestamp();
                $tStop=$dStop->getTimestamp();
                $hours=($tStop-$tStart)/3600;
                //!empty($this->timeSummary[$pt->project_id])? $this->timeSummary[$pt->project_id]=0 : $this->timeSummary[$pt->project_id]+=$hours;
                if (isset($this->timeSummary[$pt->project_id]))
                {
                    $this->timeSummary[$pt->project_id]+=$hours;
                }
                else
                {
                    $this->timeSummary[$pt->project_id]=$hours;
                }
            }
        }
        return $this->timeSummary;
    }
    private function getProjectTimerHashKey()
    {
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
