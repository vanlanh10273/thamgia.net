<?php
    App::uses('ExceptionRenderer', 'Error');
    
    class AppExceptionRenderer extends ExceptionRenderer {
        public function missingController($error) {
            //$this->controller->header('HTTP/1.1 404 Not Found');
            /*$this->controller->set('title_for_layout', 'Not Found');*/
            $this->controller->redirect(array('controller' => 'Errors', 'action' => 'error404'));
        }
        public function missingAction($error) {
            $this->missingController($error);
        }
    }
?>
