<?php
CakePlugin::load('Uploader');
App::import('Vendor', 'Uploader.Uploader');
class UsersController extends AppController{
    public $components = array('Session', 'RequestHandler');
    
    function beforeFilter(){
        parent::beforeFilter();
        $this->Uploader = new Uploader(array('tempDir' => TMP, 'uploadDir' => FOLDER_UPLOAD_IMAGE_EVENT));
        $this->loadModel('Event');
        $this->loadModel('Participation');
        $this->loadModel('Message');
    } 
    /**
     * Dang nhap
     */    
    function login(){
        $this->redirectLogined();
        $this->layout = "no_column";
        
        if ($this->request->is('post')){
            $user = $this->request->data('User');
            $options['conditions'] = array(
                'User.email' => $user['email'],
                'User.password' => Security::hash($user['password'], 'md5', true)
            );
            
            $userAuth = $this->User->find('first', $options);
            if ($userAuth){
                $this->Session->write(AUTH_USER_ID, $userAuth['User']['id']);
                $this->Session->write(AUTH_USER_FULLNAME, $userAuth['User']['fullname']);
                $this->Session->write(AUTH_USER_LEVEL, $userAuth['User']['level']);
                $this->Session->write(AUTH_USER_EMAIL, $userAuth['User']['email']);
                $this->Session->write(AUTH_USER_AVATAR_URL, $userAuth['User']['avatar_url']);
                
                $this->redirect(env('HTTP_REFERER'));
            }else{
                $this->Session->setFlash(__('Đăng nhập không thành công', true));
                $this->redirect(array('plugin'=>null,'controller'=>'Users','action'=>'login'));
            }
        }    
    }
    
    /**
     * Dang xuat
     */ 
    function logout(){
        $this->Session->delete(AUTH_USER_ID);
        $this->Session->delete(AUTH_USER_FULLNAME);
        $this->Session->delete(AUTH_USER_LEVEL);
        $this->Session->delete(AUTH_USER_EMAIL);
        $this->redirect(env('HTTP_REFERER'));
    }
    
    function redirectLogined(){
        if ($this->_isLogin())
           $this->redirect(array('plugin'=>null,'controller'=>'Home','action'=>'index')); 
    }
    
    function editProfile(){
        $this->layout = "no_column";
        $this->isAuthenticated();
        $options['conditions'] = array(
            'User.id' => $this->_usersUserID()
        );
        
        $options['fields'] = array(
            'User.fullname',
            'User.cityid',
            'User.careerid',
            'User.email',
            'User.avatar_url'
        );
        $user = $this->User->find('first', $options);
        $this->set('data', $user);
        
        $this->loadModel('Career');
        $careers = $this->Career->find('all');
        $this->set('careers', $careers);
        $old_img_url = $user['User']['avatar_url'];
        if ($this->request->is('post')){
            $error = '';
            $data= $this->request->data('User');
            $user['User'] = array();
            $user['User']['id'] = $this->_usersUserID();
            $user['User']['fullname'] = $data['fullname'];
            $user['User']['cityid'] = $data['cityid'];
            $user['User']['careerid'] = $data['careerid'];
            $user['User']['email'] = $data['email'];
            if ($data['avatar']['name'] != ''){
                    if ($dataUpload = $this->Uploader->upload($data['avatar'], array('overwrite' => true))) {
                        // Upload successful, do whatever
                        $resized_path = $this->Uploader->resize(array('width' => AVATAR_WIDTH, 'height' => AVATAR_HEIGHT));
                        $this->Uploader->delete($dataUpload['path']);
                        $user['User']['avatar_url'] = preg_replace('/^\//', '', $resized_path);
                        $this->Uploader->delete($old_img_url);
                        
                    }else{
                        $error = 'Lỗi upload ảnh!<br />';
                    }    
            }
            if ($error != null){
                $this->set('error', $error);
                $user['User']['avatar_url'] = $old_img_url;
                $this->set('data', $user);
            }else{
                $this->User->save($user);
                $this->redirect(array('controller' => 'Users', 'action' => 'profile', $this->_usersUserID()));    
            }
        }
        
    }
    
    function profile($id){
        $this->layout = "no_column";
        if ($id){
            // set id of user
            $this->set('user_id', $id);
            if ($id == $this->_usersUserID())
                $this->set('owner', true);
            else
                $this->set('owner', false);
                
        }    
    }
    
    function changePassword(){
        $this->layout = "no_column";
        $this->isAuthenticated();
        if ($this->request->is('post')){
            $user = $this->request->data('User');
            // check password
            $options['conditions'] = array(
                'User.id' => $this->_usersUserID(),
                'User.password' => Security::hash($user['current_password'], 'md5', true)
            );
            
            $userAuth = $this->User->find('first', $options);
            if ($userAuth){
                $userAuth['User']['password'] = Security::hash($user['password'], 'md5', true);
                $this->User->save($userAuth);
                $this->redirect(array('plugin'=>null,'controller'=>'Users','action'=>'profile', $this->_usersUserID()));
            }else{
                $this->Session->setFlash(__('Mật khẩu hiện tại không đúng', true));
                $this->redirect(array('plugin'=>null,'controller'=>'Users','action'=>'changePassword'));
            }
        }
    }
    
    function search(){
        $this->layout = 'ajax';
        $users=$this->User->find('all', array(
                                                'conditions' => array('User.fullname LIKE ' => '%'.$this->params['url']['q'].'%')
                                                ));
        $this->set('users',$users);
    }
    
    function forgotPassword(){
        $this->layout = 'no_column';
        $this->redirectLogined();
        if ($this->request->is('post')){
            $data = $this->request->data('User');
            $options['conditions'] = array(
                'User.email' => $data['email']
            );    
            $user = $this->User->find('first', $options);
            if ($user){
                $temp_pass = $this->createTempPassword(8);
                $user['User']['temp_password'] = $temp_pass;
                $this->User->save($user);
                
                $this->sendEmailForgotPassword($user['User']['id'], $user['User']['fullname'], $user['User']['email'], $temp_pass);
                
                $this->redirect(array("controller" => "users", "action" => "requestForgotPassword"));
            }else{
                $this->Session->setFlash('Email này không tồn tại!');
            }
            
        }
    }
    
    function resetPassword($user_id, $temp_password){
        $options['conditions'] = array(
                'User.id' => $user_id,
                'User.temp_password' => $temp_password
        );    
        $user = $this->User->find('first', $options); 
        if ($user){
            $new_password = $this->createTempPassword(6);
            $user['User']['temp_password'] = '';
            $user['User']['password'] = Security::hash($new_password, 'md5', true);
            $this->User->save($user);
            $this->sendEmailResetPassword($user['User']['fullname'], $user['User']['email'], $new_password);
            $this->redirect(array('controller' => 'Users', 'action' => 'finishResetPassword'));
        }else{
            $this->redirect(array('controller' => 'home', 'action' => 'index'));
        }
    }
    
    function requestForgotPassword(){
        $this->layout = 'no_column';
    }
    
    function finishResetPassword(){
        $this->layout = 'no_column';
    }
    
    function createTempPassword($len) {
        $this->autoRender = false;
        $pass = '';
        $lchar = 0;
        $char = 0;
        for($i = 0; $i < $len; $i++) {
            while($char == $lchar) {
            $char = rand(48, 109);
            if($char > 57) $char += 7;
                if($char > 90) $char += 6;  
            }
            $pass .= chr($char);
            $lchar = $char;
        } return $pass;
    } 
    
    function sendEmailForgotPassword($user_id, $user_name, $user_email, $temp_password){
        $this->loadModel('Email');
        $this->autoRender = false;
        $emailRecord['Email'] = array();
        $emailRecord['Email']['priority'] = PRIORITY_HIGH;
        
        $parameters = array();
        $parameters['subject'] = 'Thamgia.net - Yêu cầu đặt lại mật khẩu';
        $parameters['to_email'] = $user_email;
        $parameters['template'] = 'forgot_password';
        
        $parameters['user_name'] = $user_name;
        $parameters['user_id'] = $user_id;
        $parameters['temp_password'] = $temp_password;
        
        
        $emailRecord['Email']['parameters'] = serialize($parameters);
        
        $this->Email->save($emailRecord);   
    }
    
    function sendEmailResetPassword($user_name, $user_email, $new_password){
        $this->loadModel('Email');
        $this->autoRender = false;
        $emailRecord['Email'] = array();
        $emailRecord['Email']['priority'] = PRIORITY_HIGH;
        
        $parameters = array();
        $parameters['subject'] = 'Thamgia.net - Mật khẩu mới';
        $parameters['to_email'] = $user_email;
        $parameters['template'] = 'reset_password';
        
        $parameters['user_name'] = $user_name;
        $parameters['user_email'] = $user_email;
        $parameters['new_password'] = $new_password;
        
        
        $emailRecord['Email']['parameters'] = serialize($parameters);
        
        $this->Email->save($emailRecord);    
    }
    
    function getNewMember(){
        $this->autoRender = false;
        $options['limit'] = 4;
        $options['order'] = array('User.id' => 'desc');
        $options['fields'] = array('User.id', 'User.fullname', 'User.avatar_url', 'User.created');
        $data = $this->User->find('all', $options);
        return $data;
    }
    
    function getUser($id){
        $this->autoRender = false;
        $options['joins'] = array(
            array(
                    'table' => 'cities',
                    'alias' => 'City',
                    'type' => 'LEFT',
                    'conditions' => array(
                    'City.id = User.cityid',
                    )
            ),
            array(
                    'table' => 'types',
                    'alias' => 'Type',
                    'type' => 'LEFT',
                    'conditions' => array(
                    'Type.id = User.careerid',
                    )
            ),
            array(
                    'table' => 'careers',
                    'alias' => 'Career',
                    'type' => 'LEFT',
                    'conditions' => array(
                    'Career.id = User.careerid',
                    )
            )
        );
        $options['conditions'] = array('User.id' => $id);
        $options['fields'] = array('User.fullname', 'User.id', 'User.email', 'Type.name', 'City.name', 'User.avatar_url', 'Career.name');
        $data = $this->User->find('first', $options);
        return $data;
    }
    
    function getUserInformation($user_id){
        $this->autoRender = false;
        $data = array();
        $options['conditions'] = array(
            'Event.user_id' => $user_id
        );
        
        $data['count_added_events'] = $this->Event->find('count', $options);

        // participated events
        $options['conditions'] = array(
            'Participation.user_id' => $user_id
        );
        $data['count_participated_events'] = $this->Participation->find('count', $options);

        // participated events
        $options['conditions'] = array(
            'Message.receiver' => $user_id,
            'Message.viewed' => false,
            'Message.receiver_deleted' => false
        );
        $data['count_inbox_items'] = $this->Message->find('count', $options);
        
        return $data;
    }
}
?>