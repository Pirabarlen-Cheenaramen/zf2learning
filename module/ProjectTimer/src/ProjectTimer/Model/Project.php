<?php
namespace ProjectTimer\Model;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Project implements InputFilterAwareInterface
{
    public $idproject;
    public $projectname;
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->idproject=(!empty($data['idproject'])) ? $data['idproject'] : null;
        $this->projectname=(!empty($data['projectname'])) ? $data['projectname'] : null;
    }
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
                         $inputFilter->add(array(
                 'name'     => 'id',
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'Int'),
                 ),
             ));

            $inputFilter->add(array(
                'name'     => 'projectname',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));

        $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }
}
?>
