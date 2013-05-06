<?php
//
// A CodeIgniter override: The framework should automatically handle the detection of this
// override, and the instantiation and calling of this class. 
//
class My_Loader extends CI_Loader {
    function doctrine($table=FALSE) {
        $ci =&get_instance();
        
        if (!defined('DOCTRINE_PATH')) {
            require APPPATH.'config/doctrine.php';
        }
        
        if (!isset($this->doctrine_manager)) {
            $this->doctrine_manager = Doctrine_Manager::getInstance();
        }
        
        if ($table !== FALSE) {
            if (isset($ci->{$table})) {
                return $ci->{$table};
            }
            
            Doctrine::loadModels(MODELS_PATH);
            
            $ci->{$table} = Doctrine::getTable($table);
            return $ci->{$table};
        }
        
        return $this->doctrine_manager;
    }
}