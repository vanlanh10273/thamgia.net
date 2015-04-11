<?php
    define('STEP_1', 'step_1');
    define('STEP_2', 'step_2');
    CakePlugin::load('Uploader');
    App::import('Vendor', 'Uploader.Uploader');
    class SignupController extends AppController{
        
        var $uses = array('User');
        public $helpers = array('Html', 'Form', 'Session');
        public $components = array('Session',  'Email');
     
        public function beforeFilter() {
            parent::beforeFilter();
            $this->layout = "signup";
        }
        
        function captcha_image(){
            App::import('Vendor', 'captcha/captcha');
            $captcha = new captcha();
            $captcha->show_captcha();
        }
        
        public function account(){
            if(!$this->_isLogin()){
                if ($this->request->is('post')){
                    // check account information
                    $hasError = false;
                    $error = '';
                    
                    $options['conditions'] = array(
                        'User.email' => $this->request->data['Account']['email']
                    );
                    $countEmail = $this->User->find('count', $options);
                    if ($countEmail > 0){
                        $hasError = true;
                        $error = '- Email '. $this->request->data['Account']['email']. ' đã có người khác sử dụng';
                        $this->request->data['Account']['email'] = '';
                    }
                    
                    if (strtoupper($this->request->data['Account']['captcha_code']) != strtoupper($this->Session->read('captcha'))){
                        if ($hasError)
                            $error .= '<br />'.'- Vui lòng gõ lại mã bảo vệ!';
                        else
                            $error = '- Vui lòng gõ lại mã bảo vệ!';
                        
                        $hasError = true;
                    }

                    if (!$hasError){
                        $account = $this->request->data('Account');
                        $this->Session->write(STEP_1, $account);
                        $this->redirect(array('action'=>'profile')); 
                    }
                    else{
                        $this->set('email', $this->request->data['Account']['email']);
                        $this->set('error', $error);
                    }
                }    
            }else{
                // redirect to home if user is authenticated
                $this->redirect(array('controller'=>'home', 'action'=>'index'));
            }
            
        }
        
        public function profile(){
            $this->Uploader = new Uploader(array('tempDir' => TMP, 'uploadDir' => FOLDER_UPLOAD_AVATAR));
            if ($this->Session->check(STEP_1)){  // check step 1
                if ($this->request->is('post')){
                    $profile = $this->request->data('Profile');
                    $error = null;
                    if ($profile['avatar']['name'] != ''){
                        if ($dataUpload = $this->Uploader->upload($profile['avatar'], array('overwrite' => true))) {
                        // Upload successful, do whatever
                            $resized_path = $this->Uploader->resize(array('width' => AVATAR_WIDTH, 'height' => AVATAR_HEIGHT));
                            $this->Uploader->delete($dataUpload['path']);
                            $profile['avatar_url'] = preg_replace('/^\//', '', $resized_path);
                        }else{
                            $error = 'Lỗi upload ảnh!<br />';
                        }     
                    }else{
                        $profile['avatar_url'] = '';
                    }
                    
                    if ($error == null){
                        $this->Session->write(STEP_2, $profile);
                        $this->redirect(array('action' => 'finish'));  // redirect to step 1        
                    }else{
                        $this->loadModel("City");
                        $this->loadModel('Career');
                        $cities = $this->City->find('all');
                        $careers = $this->Career->find('all');
                        $this->set('cities', $cities);
                        $this->set('careers', $careers);       
                        $this->set('data', $profile);
                        $this->set('error', $error);
                    }
                }
                else{
                    $this->loadModel("City");
                    $this->loadModel('Career');
                    $cities = $this->City->find('all');
                    $careers = $this->Career->find('all');
                    $this->set('cities', $cities);
                    $this->set('careers', $careers);
                }   
            }
            else{
                $this->redirect(array('action' => 'account'));  // redirect to step 1
            }
                
        }
        
        public function finish(){
            if ($this->Session->check(STEP_2)){
                try{
                    // create account to datatabase
                    $arrUser['User'] = array();
                    $account = $this->Session->read(STEP_1);
                    $profile = $this->Session->read(STEP_2);
                    $dob = null;
                    if (is_int($profile['year']) && is_int($profile['month']) && is_int($profile['day'])){
                        $dob =  mktime(0,0,0, $profile['month'], $profile['day'], $profile['year']);
                    }
                    
                    $arrUser['User']['email'] = $account['email'];
                    $arrUser['User']['password'] = Security::hash($account['password'], 'md5', true);
                    $arrUser['User']['fullname'] = $profile['name'];
                    $arrUser['User']['cityid'] = $profile['city'];
                    $arrUser['User']['avatar_url'] = $profile['avatar_url'];
                    $arrUser['User']['careerid'] = $profile['career'];
                    $arrUser['User']['level'] = LEVEL_USER;
                    if (!is_null($dob)){
                        $arrUser['User']['dob'] = date(TIME_FORMAT_MYSQL, $dob);
                    }
                    $this->User->save($arrUser);
                    $this->sendMailRegistry($account['email'], $profile['name'], $account['password']);
                    
                    $this->Session->delete(STEP_1);
                    $this->Session->delete(STEP_2);    
                }catch(Exception $ex){
                    $this->Session->setFlash(__($e->getMessage(), true));
                    $this->redirect(array('action' => 'account'));  // redirect to step 2           
                }
            }
            else{
                $this->redirect(array('action' => 'profile'));  // redirect to step 2    
            }
        }
        
        public function validateCaptcha(){
            $this->autoRender = false;
            $captcha_code = $this->request->query['captcha_code'];
            if (strtoupper($captcha_code) != strtoupper($this->Session->read('captcha'))){
                return json_encode(false);                                    
            }else{
                return json_encode(true);
            }    
        }
        
        function sendMailRegistry($email_address, $fullname, $password){
            $this->loadModel('Email');
            //$this->autoRender = false;
            $emailRecord['Email'] = array();
            $emailRecord['Email']['priority'] = PRIORITY_HIGH;
            
            $parameters = array();
            $parameters['subject'] = 'Chúc mừng '. $fullname.' đã hòa mạng thamgia.net';
            $parameters['to_email'] = $email_address;
            $parameters['template'] = 'dangky';
            
            $parameters['fullname'] = $fullname;
            $parameters['email'] = $email_address;
            $parameters['password'] = $password;
            
            $emailRecord['Email']['parameters'] = serialize($parameters);
            $this->Email->save($emailRecord);
            
        }
    }  
?>
