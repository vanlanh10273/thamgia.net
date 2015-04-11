<?php
    class ErrorsController extends AppController{
        function beforeFilter(){
            parent::beforeFilter();    
        }
        
        function error404(){
            $this->layout = "default";
        }
    }
?>
