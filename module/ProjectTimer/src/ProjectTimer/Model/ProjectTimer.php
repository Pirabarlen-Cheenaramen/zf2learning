<?php
namespace ProjectTimer\Model;

class ProjectTimer
{
    public $project_id
    public $start_time;
    public $stop_time;

    public function exchangeArray($data)
    {
        $this->project_id=(!empty($data['project_id'])) ? $data['project_id'] : null;
        $this->start_time=(!empty($data['start_time'])) ? $data['start_time] : null;
        $this->stop_time=(!empty($data['stop_time'])) ? $data['stop_time'] : null;
    }
}
?>
