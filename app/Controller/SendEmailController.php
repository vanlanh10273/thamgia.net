<?php
    class SendEmailController extends AppController{
        function beforeFilter(){
            $this->loadModel('Email');
            $this->loadModel('AccountEmail');
        }
        
        /*function index(){
            $this->autoRender = false;
            
            // get queues emails to send
            $options['limit'] = 50;
            $options['order'] = array('Email.id ASC');
            $queueEmails = $this->Email->find('all', $options);
            
            
            
            foreach($queueEmails as $recordEmail){
                $parameters = unserialize($recordEmail['Email']['parameters']);
                $this->SendMail($parameters, $recordEmail['Email']['message']);
                $this->Email->delete($recordEmail['Email']['id']);
            }
        }*/
        
        function sendPriority($priority, $max = 50){
            $this->autoRender = false;
            
            // get queues emails to send
            $options['limit'] = $max;
            $options['order'] = array('Email.id ASC');
            $options['conditions'] = array('Email.priority' => $priority);
            $queueEmails = $this->Email->find('all', $options);
            $currentEmailId = 0;
            if ($queueEmails){
                // setup email to send
                $email = new CakeEmail();
                $accountEmail = $this->getConfigEmail();
                if ($accountEmail){
                    $email->config($accountEmail['AccountEmail']);
                    $quantity = $accountEmail['AccountEmail']['quantity'];
                    
                    foreach($queueEmails as $recordEmail){
                        $parameters = unserialize($recordEmail['Email']['parameters']);
                        try{
                            $currentEmailId = $recordEmail['Email']['id'];
                            $this->SendMail($email, $parameters, $recordEmail['Email']['message']);
                            $this->Email->delete($recordEmail['Email']['id']);
                            $quantity++;
                            
                            if ($quantity > EMAIL_QUOTA){ // switch account email
                                $accountEmail['AccountEmail']['quantity'] = $quantity;
                                $this->AccountEmail->save($accountEmail);
                                $accountEmail = $this->getConfigEmail();
                                $quantity = $accountEmail['AccountEmail']['quantity'];
                                $email->config($accountEmail['AccountEmail']);
                            }
                        }catch (Exception $ex){                            
                            $errorMsg = $ex->getMessage();
                            try{
                                if (strpos($errorMsg, EMAIL_ERROR_NO_DESTINATION) !== false)
                                    throw new Exception("Error email");
                                    
                                if (strpos($errorMsg, EMAIL_ERROR_IVALID) !== false)
                                    throw new Exception("Error email");
                                
                                // disable account email
                                $accountEmail['AccountEmail']['active'] = false;
                                $this->AccountEmail->save($accountEmail);
                                $accountEmail = $this->getConfigEmail();
                                $quantity = $accountEmail['AccountEmail']['quantity'];
                                $email->config($accountEmail['AccountEmail']);    
                                
                            }catch (Exception $emailEx){
                                $this->Email->delete($currentEmailId);
                            }
                            // delete email error
                            if ($ex->getMessage() == EMAIL_ERROR_NO_DESTINATION){
                                
                            }else{
                                   
                            }                                
                        }
                    }   
                    
                    // save quantity of account email
                    $accountEmail['AccountEmail']['quantity'] = $quantity;
                    $this->AccountEmail->save($accountEmail);
                }
            }
        }
        
        /**
        * Function to send email when import contact
        * 
        * @param mixed $parameters is array with 6 elements
        * event_id
        * user_name
        * event_time
        * event_title
        * event_address
        * to_email
        * @param mixed $message
        */
        function SendMail($email, $parameters, $message = null){
            $this->autoRender = false;
            if ($message != null)
                $parameters['message'] = $message;
            
            //$email = new CakeEmail('default');
            $email->emailFormat('html');
            $email->template($parameters['template']);
            $email->subject($parameters['subject']);
            $email->from(array(EMAIL_SENDING => EMAIL_DISPLAY_SENDING));
            $email->to($parameters['to_email']);
            $email->viewVars($parameters);
            $email->send('');
        }
        
        function getConfigEmail(){
            $this->autoRender = false;
            $options['order'] = array('AccountEmail.Id ASC');
            $options['conditions'] = array('AccountEmail.quantity <' => EMAIL_QUOTA, 'AccountEmail.active' => true);
            $accountEmail = array();
            
            $accountEmail = $this->AccountEmail->find('first', $options);
            if (!$accountEmail){
                $this->AccountEmail->updateAll(array('AccountEmail.quantity' => 0), array('AccountEmail.active' => true));
                $accountEmail = $this->AccountEmail->find('first', $options);
            }
            
            return $accountEmail;
        }
    
        function testSendMail($accountEmailId){
            $this->autoRender = false;
            $options['order'] = array('AccountEmail.Id ASC');
            $options['conditions'] = array('AccountEmail.id ' => $accountEmailId);
            $accountEmail = $this->AccountEmail->find('first', $options);
            try{
                $email = new CakeEmail();
                $email->config($accountEmail['AccountEmail']);
                
                $email->emailFormat('html');
                $email->template('dangky');
                $email->from(array(EMAIL_SENDING => EMAIL_DISPLAY_SENDING));
                $email->to('ngocvinhxt@gmail.com');
                $email->viewVars(array('fullname' => 'test',
                                        'email' => 'inet130hamnghi@gmail.com',
                                        'password' => 'test'));
                $email->subject('Thamgia.net - Test send email '. uniqid());
                $email->send('My message');
                echo "Success!";
            }catch(Exception $ex){
                echo $ex->getMessage();
            }
        }
        
        function testEmailRecord($accountEmailId, $emailRecordId){
            $this->autoRender = false;
            $options['conditions'] = array('AccountEmail.id ' => $accountEmailId);
            $accountEmail = $this->AccountEmail->find('first', $options);
            try{
                $email = new CakeEmail();
                $email->config($accountEmail['AccountEmail']);
                
                $email->emailFormat('html');
                $email->template('dangky');
                
                // get a email to send
                $options['conditions'] = array('Email.id' => $emailRecordId);
                $recordEmail = $this->Email->find('first', $options);
                $parameters = unserialize($recordEmail['Email']['parameters']);
                $this->SendMail($email, $parameters, $recordEmail['Email']['message']);
                $this->Email->delete($recordEmail['Email']['id']);
                
                echo "Success!";
            }catch(Exception $ex){
                if ($ex->getMessage() == EMAIL_ERROR_NO_DESTINATION)
                    $this->Email->delete($emailRecordId);
                
                echo $ex->getMessage();
            }    
        }
    }
?>
