<?php
 namespace ProjectTimer\Form;

 use Zend\Form\Form;

 class ProjectTimerForm extends Form
 {
     public function __construct($name = null)
     {
         // we want to ignore the name passed
         parent::__construct('projecttimer');

         $this->add(array(
             'name' => 'id',
             'type' => 'Hidden',
         ));
         $this->add(array(
             'name' => 'projectname',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Project Name ',
             ),
         ));
         $this->add(array(
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'value' => 'Go',
                 'id' => 'submitbutton',
             ),
         ));
     }
 }
?>
