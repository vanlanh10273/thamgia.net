<?php
class AccountEmailsController extends AppController{
    function beforeFilter(){
        parent::beforeFilter();
        $this->layout = "admin";
    }
    
    function index(){
        $this->isAdminAuthenticated();
        
         $this->set('page_title', 'Tài khoản email');
         $this->paginate = array(
                'AccountEmail' => array(
                    'limit' => DEFAULT_SIZE,
                    'order' => array('AccountEmail.id' => 'desc'),
                    'conditions' => array('AccountEmail.active =' => true),
                    'fields' => array('AccountEmail.id', 'AccountEmail.username', 'AccountEmail.quantity')
                )
         );
            
         $dataPaginate = $this->paginate('AccountEmail');
         $this->set('data', $dataPaginate);
    }
    
    function add(){
        $this->isAdminAuthenticated();
        $this->set('page_title', 'Tài khoản email');
        if ($this->request->is('post')){
            $data  = $this->request->data('AccountEmail');
            try{
                $arrAccountEmail['AccountEmail'] = array();
                $arrAccountEmail['AccountEmail'] = $data;
                $this->AccountEmail->save($arrAccountEmail);
                $this->redirect(array('controller'=>'AccountEmails', 'action'=>'index'));    
            }catch (Exception $ex){
                $this->set('data', $data);
            }  
            
        }      
    }
    
    function edit($id){
        $this->isAdminAuthenticated();
        $this->set('page_title', 'Tài khoản email');
        $this->AccountEmail->id = $id;
        $accountEmail = $this->AccountEmail->read();
        if ($this->request->is("get")){
            $this->set('data', $accountEmail['AccountEmail']);
        }else if ($this->request->is("post")){
            $data  = $this->request->data('AccountEmail');
            try{
                $arrAccountEmail['AccountEmail'] = array();
                $arrAccountEmail['AccountEmail'] = $data;
                $arrAccountEmail['AccountEmail']['id'] = $id;
                $this->AccountEmail->save($arrAccountEmail);
                $this->redirect(array('controller'=>'AccountEmails', 'action'=>'index'));    
            }catch (Exception $ex){
                $this->set('data', $data);
            }        
        }
               
    }
    
    function delete($id){
        $this->isAdminAuthenticated();
        $this->AccountEmail->delete($id, true);
        $this->redirect(array('controller'=>'AccountEmails', 'action'=>'index'));    
    }
    
    function inactive(){
        $this->isAdminAuthenticated();
        
         $this->set('page_title', 'Email lỗi');
         $this->paginate = array(
                'AccountEmail' => array(
                    'limit' => DEFAULT_SIZE,
                    'order' => array('AccountEmail.id' => 'desc'),
                    'conditions' => array('AccountEmail.active =' => false),
                    'fields' => array('AccountEmail.id', 'AccountEmail.username', 'AccountEmail.quantity')
                )
         );
            
         $dataPaginate = $this->paginate('AccountEmail');
         $this->set('data', $dataPaginate);    
    }
    
    function setActive($id){
        $this->AccountEmail->id = $id;
        $accountEmail = $this->AccountEmail->read();
        $accountEmail['AccountEmail']['active'] = true;
        $accountEmail['AccountEmail']['quantity'] = 0;
        $this->AccountEmail->save($accountEmail);
        $this->redirect(array('controller'=>'AccountEmails', 'action'=>'inactive'));              
    }
}
?>
