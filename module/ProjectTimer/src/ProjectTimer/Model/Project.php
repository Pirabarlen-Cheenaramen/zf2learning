<?php
namespace ProjectTimer\Model;

class Project
{
    public $idproject
    public $projectname;

    public function exchangeArray($data)
    {
        $this->idproject=(!empty($data['idproject'])) ? $data['idproject'] : null;
        $this->projectname=(!empty($data['projectname'])) ? $data['projectname] : null;
    }
}
?>
