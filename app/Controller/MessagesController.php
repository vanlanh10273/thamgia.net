<?php
    class MessagesController extends AppController{
        var $Helpers = array('Javascript', 'Ajax', 'Html');
        
        function beforeFilter(){
            parent::beforeFilter();
        }
        
        function inbox(){
            $this->isAuthenticated();
            
            $this->layout = 'no_column';
            
            $this->paginate = array(
                'Message' => array(
                    'limit' => DEFAULT_SIZE,
                    'order' => array('Message.created' => 'desc'),
                    'conditions' => array(
                                    'Message.receiver =' => $this->_usersUserID(),
                                    'Message.receiver_deleted' => false)
                )                                           
            );
            
            $dataPaginate = $this->paginate('Message');
            $data = array();
            $index = 0;
            
            foreach($dataPaginate as $message){
                $data[$index] = array();
                
                $options['conditions'] = array(
                    'User.id' => $message['Message']['sender']
                );
                $this->loadModel('User');
                $user = $this->User->find('first', $options);
                $data[$index]['id'] = $message['Message']['id'];
                $data[$index]['sender'] = $user['User']['fullname'];
                $data[$index]['viewed'] = $message['Message']['viewed'] ? 'viewed' : '';
                $data[$index]['content'] = substr($message['Message']['content'], 0, LIMIT_MESSEAGE_CONTENT_SIZE);
                $data[$index]['content'] .= strlen($message['Message']['content']) > LIMIT_MESSEAGE_CONTENT_SIZE ? '...' : '';
                $data[$index]['time_elapse'] = $this->timeToString(time()-strtotime($message['Message']['created'])).' ago';
                $index++;
            }
            $this->set('data', $data); 
        }
        
        function sendItems(){
            $this->isAuthenticated();
            
            $this->layout = 'no_column';
            
            $this->paginate = array(
                'Message' => array(
                    'limit' => DEFAULT_SIZE,
                    'order' => array('Message.created' => 'desc'),
                    'conditions' => array(
                                    'Message.sender =' => $this->_usersUserID(),
                                    'Message.sender_deleted' => false)
                )
            );
            
            $dataPaginate = $this->paginate('Message');
            $data = array();
            $index = 0;
            
            foreach($dataPaginate as $message){
                $data[$index] = array();
                
                $options['conditions'] = array(
                    'User.id' => $message['Message']['receiver']
                );
                $this->loadModel('User');
                $user = $this->User->find('first', $options);
                $data[$index]['id'] = $message['Message']['id'];
                $data[$index]['receiver'] = $user['User']['fullname'];
                $data[$index]['viewed'] = $message['Message']['viewed'] ? 'viewed' : '';
                $data[$index]['content'] = substr($message['Message']['content'], 0, LIMIT_MESSEAGE_CONTENT_SIZE);
                $data[$index]['content'] .= strlen($message['Message']['content']) > LIMIT_MESSEAGE_CONTENT_SIZE ? '...' : '';
                $data[$index]['time_elapse'] = $this->timeToString(time()-strtotime($message['Message']['created'])).' ago';
                $index++;
            }
            $this->set('data', $data);       
        }
        
        /**
        * Delete a message
        * 
        * @param mixed $user_id
        * @param mixed $id
        * @param mixed $sender: delete message by sender
        */
        function delete($user_id, $id, $sender = false){
            $this->isAuthenticated();
            
            // check own of message
            $options['conditions'] = array(
                    'Message.id' => $id
            );
            $message = $this->Message->find('first', $options);
            if (($message['Message']['sender'] != $this->_usersUserID()) && ($message['Message']['receiver'] != $this->_usersUserID()))
            {
                $this->redirect(array('controller' => 'Users' ,'action' => 'login'));
            }
            
            if ($user_id != $this->_usersUserID()){                                  
                $this->redirect(array('controller' => 'Users' ,'action' => 'login'));
            }
            
            $deleteMessage = false;
            if ($sender){
                if ($message['Message']['receiver_deleted']){
                    $deleteMessage = true;
                }else{
                    $message['Message']['sender_deleted'] = true;
                    $this->Message->save($message);
                }
            }else{
                if ($message['Message']['sender_deleted']){
                    $deleteMessage = true;
                }else{
                    $message['Message']['receiver_deleted'] = true;
                    $this->Message->save($message);
                }
            }
            
            if ($deleteMessage){
                $conditions = array(
                    'Message.id' => $id
                );            
                $this->Message->deleteAll($conditions, false);
            }
            
            $this->redirect(env('HTTP_REFERER'));
        }
        
        function getViewMessageBox($id, $markView = true){
            $this->isAuthenticated();
            $this->layout = "empty";
            
            // check own of message
            $options['conditions'] = array(
                    'Message.id' => $id
            );
            
            $message = $this->Message->find('first', $options);
            if (($message['Message']['sender'] != $this->_usersUserID()) && ($message['Message']['receiver'] != $this->_usersUserID())){
                $this->redirect(array('controller' => 'Users' ,'action' => 'login'));
            }
            
            // set this messeage to viewed
            if ($markView){
                $message['Message']['viewed'] = true;
                $this->Message->save($message);
            }
            
            $data = array();
            $data['id'] = $message['Message']['id'];
            $data['sender'] = $this->getUserNameById($message['Message']['sender']);
            $data['default_receiver'] = $message['Message']['receiver'] != $this->_usersUserID() ? $message['Message']['receiver'] : $message['Message']['sender'];
            $data['receiver'] = $this->getUserNameById($message['Message']['receiver']);
            $data['content'] = $message['Message']['content'];
            $data['time_elapse'] = $this->timeToString(time()-strtotime($message['Message']['created'])).' ago';
            $this->set('data', $data);
        }
        
        function getComposeMessageBox($defaultReceiver = null){
            $this->isAuthenticated();
            $this->layout = "ajax";
            
            $users = $this->User->find('all');
            $this->set('users', $users);
            $this->set('defaultReceiver', $defaultReceiver);
        }
        
        function sendMessage(){
            $this->isAuthenticated();
            $this->autoRender = false;
            if ($this->request->is('post')){
                $data = $this->request->data('Message');
                $message['Message'] = array();
                $message['Message']['sender'] = $this->_usersUserID();
                $message['Message']['receiver'] = $data['receiver'];
                $message['Message']['content'] = $data['content'];
                $message['Message']['created'] = date(TIME_FORMAT_MYSQL);
                $message['Message']['viewed'] = false;
                
                $options['conditions'] = array(
                    'User.id' => $data['receiver']
                );
                $to_user = $this->User->find('first', $options);
                
                $this->sendEmailMessage($to_user['User']['email'], $to_user['User']['fullname'], $this->_usersName(), $data['content']);
                $this->sendNotification($data['receiver'], $this->_usersName(), $this->_usersAvatar());

                $this->Message->save($message);
                
            }
            $this->redirect(env('HTTP_REFERER'));
        }
        
        function sendMessageToAllMember($event_id){
            $this->isAuthenticated();
            $this->autoRender = false;

            if ($this->request->is('post')){
                $this->loadModel('Participation');
                $optionsParticipation['conditions'] = array(
                    'Participation.event_id' => $event_id
                );
                $optionsParticipation['joins'] = array(
                    array(
                        'table' => 'users',
                        'alias' => 'User',
                        'type' => 'LEFT',
                        'conditions' => array(
                        'User.id = Participation.user_id',
                        )
                    )
                );
                $optionsParticipation['fields'] = array(
                        'User.fullname',
                        'User.email',
                        'Participation.user_id'
                );
                $participations = $this->Participation->find('all', $optionsParticipation);
                
                $data = $this->request->data('Message');
                $content = $data['content'];
                $sender_id = $this->_usersUserID();
                $sender_name = $this->_usersName();
                foreach($participations as $participation){
                    $message['Message'] = array();
                    $message['Message']['sender'] = $sender_id;
                    $message['Message']['receiver'] = $participation['Participation']['user_id'];
                    $message['Message']['content'] = $content;
                    $message['Message']['created'] = date(TIME_FORMAT_MYSQL);
                    $message['Message']['viewed'] = false;    
                    $this->sendEmailMessage($participation['User']['email'], $participation['User']['fullname'], $sender_name, $content);
                    $this->sendNotification($participation['Participation']['user_id'], $this->_usersName(), $this->_usersAvatar());
                    
                    $this->Message->saveAll($message);
                }    
            }
            $this->redirect(env('HTTP_REFERER'));
        }
        
        function sendMessageToAllMemberOfDailyCoupon($daily_coupon_id){
            $this->isAuthenticated();
            $this->autoRender = false;

            if ($this->request->is('post')){
                $this->loadModel('ParticipationDailyCoupon');
                $optionsParticipation['conditions'] = array(
                    'ParticipationDailyCoupon.daily_coupon_id' => $daily_coupon_id
                );
                $optionsParticipation['joins'] = array(
                    array(
                        'table' => 'users',
                        'alias' => 'User',
                        'type' => 'LEFT',
                        'conditions' => array(
                        'User.id = ParticipationDailyCoupon.user_id',
                        )
                    )
                );
                $optionsParticipation['fields'] = array(
                        'User.fullname',
                        'User.email',
                        'ParticipationDailyCoupon.user_id'
                );
                $participations = $this->ParticipationDailyCoupon->find('all', $optionsParticipation);
                
                $data = $this->request->data('Message');
                $content = $data['content'];
                $sender_id = $this->_usersUserID();
                $sender_name = $this->_usersName();
                foreach($participations as $participation){
                    $message['Message'] = array();
                    $message['Message']['sender'] = $sender_id;
                    $message['Message']['receiver'] = $participation['ParticipationDailyCoupon']['user_id'];
                    $message['Message']['content'] = $content;
                    $message['Message']['created'] = date(TIME_FORMAT_MYSQL);
                    $message['Message']['viewed'] = false;    
                    $this->sendEmailMessage($participation['User']['email'], $participation['User']['fullname'], $sender_name, $content);
                    $this->sendNotification($participation['ParticipationDailyCoupon']['user_id'], $this->_usersName(), $this->_usersAvatar());
                    
                    $this->Message->saveAll($message);
                }    
            }
            $this->redirect(env('HTTP_REFERER'));
        }
        
        function sendEmailMessage($email_address, $to_name, $from_name, $message){
            $this->loadModel('Email');
            $this->autoRender = false;
            $emailRecord['Email'] = array();
            $emailRecord['Email']['priority'] = PRIORITY_HIGH;
            
            $parameters = array();
            $parameters['subject'] = 'Thamgia.net - '. $from_name . ' gởi tin nhắn đến bạn';
            $parameters['to_email'] = $email_address;
            $parameters['template'] = 'message';
            
            $parameters['to_name'] = $to_name;
            $parameters['from_name'] = $from_name;
            
            
            $emailRecord['Email']['parameters'] = serialize($parameters);
            $emailRecord['Email']['message'] = $message;
            $this->Email->saveAll($emailRecord);
   
        }
        
        function sendNotification($user_id, $sender_name, $image_url){
            $this->autoRender = false;
            $this->loadModel('Notification');
            $linkNotification = Router::url(array(
                                            "controller"=>"Messages", 
                                            "action"=> "inbox"));
                                            
            $recordNotification['Notification'] = array();
            $recordNotification['Notification']['link'] = $linkNotification;
            $recordNotification['Notification']['notification'] = '<strong>'. $sender_name . '</strong> vừa gởi tin nhắn đến bạn';
            $recordNotification['Notification']['image_url'] = $image_url != null ? $image_url : NO_IMG_URL;
            $recordNotification['Notification']['user_id'] = $user_id;
            $recordNotification['Notification']['viewed'] = false;
            $recordNotification['Notification']['created'] = date(TIME_FORMAT_MYSQL);
            $this->Notification->saveAll($recordNotification);    
        }
        
        /**
        * This function is call through ajax
        * 
        * @param mixed $user_name
        */
        function checkUserName($user_name){
            $this->autoRender = false;
            $this->layout = 'ajax';
            if ($this->request->is('ajax')){
                $options['conditions'] = array(
                    'trim(User.fullname) like' => $user_name
                );
                $user = $this->User->find('first', $options);
                //var_dump($user);
                if ($user) echo json_encode((int)$user['User']['id']);
                else echo json_encode(0);                                                 
            }
            
        }
    }
    
    
?>
