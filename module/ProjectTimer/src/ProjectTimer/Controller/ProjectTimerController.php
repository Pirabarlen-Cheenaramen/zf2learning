<?php
namespace ProjectTimer\Controller;

 use Zend\Mvc\Controller\AbstractActionController;
 use Zend\View\Model\ViewModel;
 use ProjectTimer\Model\Project;
 use ProjectTimer\Model\ProjectTimer;
 use ProjectTimer\Form\ProjectTimerForm;
 use ProjectTimer\Form\ProjectForm;
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
       $form = new ProjectForm();
         $form->get('submit')->setValue('Add');
         $request = $this->getRequest();
         if ($request->isPost()) {
             $project = new Project();
             $form->setInputFilter($project->getInputFilter());
             $form->setData($request->getPost());
             //if(!$form->isValid())
             //    {
             //        print_r($form->getMessages()); die;//error messages
             //        //$form->getErrorMessages(); //any custom error messages
             //   }
            //--print_r($form);
             if ($form->isValid()) {
                 $project->exchangeArray($form->getData());
                 $this->getProjectTable()->saveProject($project);

                 // Redirect to list of albums
                 return $this->redirect()->toRoute('projecttimer');
             }
         }
         return array('form' => $form);
     }

    public function stopAction()
    {
        $dStop= new \DateTime("NOW");
        $id = (int) $this->params()->fromRoute('id', 0);
        $projecttimer= $this->getProjectTimerTable()->getProjectTimerByStop($id);
        $projecttimer->stop_time=$dStop->format('Y-m-d H:i:s');
        $this->getProjectTimerTable()->saveProjectTimer($projecttimer);
        return $this->redirect()->toRoute('projecttimer', array(
            'action' => 'index'
        ));
    }

    public function startAction()
    {
        $dStart= new \DateTime("NOW");
        $id = (int) $this->params()->fromRoute('id', 0);
        $projecttimer= new ProjectTimer();
        $data['project_id']=$id;
        $data['start_timyye']=$dStart->format('Y-m-d H:i:s');
        $projecttimer->exchangeArray(array(
            'project_id'=>$id,
            'start_time'=> $dStart->format('Y-m-d H:i:s'),
        ));
        $this->getProjectTimerTable()->saveProjectTimer($projecttimer);
        return $this->redirect()->toRoute('projecttimer', array(
            'action' => 'index'
        ));
    }

     public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        print_r($id);
        if (!$id) {
            return $this->redirect()->toRoute('projecttimer', array(
                'action' => 'add'
            ));
         }

        // Get the Project with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $project = $this->getProjectTable()->getProject($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('projecttimer', array(
                'action' => 'index'
            ));
        }

        $form  = new ProjectTimerForm();
        $form->bind($project);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($project->getInputFilter());
            print_r($request->getPost()); die;
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getProjectTable()->saveProject($project);
                // Redirect to list of projects
               return $this->redirect()->toRoute('projecttimer');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
       );
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
