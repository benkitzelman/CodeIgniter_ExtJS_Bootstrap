<?php
class mmrGroupUser_B extends Doctrine_Record 
{
    public function setTableDefinition() 
    {
        $this->hasColumn('user_id', 'string', 30, array('primary' => true));
        $this->hasColumn('group_id', 'string', 30, array('primary' => true));
    }
}
