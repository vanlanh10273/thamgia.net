<?php
    class ParticipationsController extends AppController{
        
        function beforeFilter(){
            parent::beforeFilter();
        }
        
        function participate(){
            $this->isAuthenticated();
            
            if ($this->request->is('post')){
                $participation = $this->request->data('Participation');
                $arrParticipation['Participation'] = array();
                $arrParticipation['Participation']['event_id'] = $participation['event_id'];
                $arrParticipation['Participation']['user_id'] = $this->_usersUserID();
                $arrParticipation['Participation']['mobile'] = $participation['mobile'];
                $arrParticipation['Participation']['note'] = $participation['note'];
                $arrParticipation['Participation']['created'] = date(TIME_FORMAT_MYSQL);
                $this->Participation->save($arrParticipation);
                
                $options['joins'] = array(
                        array(
                                'table' => 'users',
                                'alias' => 'User',
                                'type' => 'LEFT',
                                'conditions' => array(
                                'User.id = Event.user_id',
                                )
                        )
                );
                $options['conditions'] = array(
                    'Event.id' => $participation['event_id']
                );
                
                $options['fields'] = array(
                    'User.id',
                    'User.fullname',
                    'User.email',
                    'Event.id',
                    'Event.title',
                    'Event.type_id'
                );
                
                $event = $this->Event->find('first', $options);
                
                if ($event['Event']['type_id'] == TYPE_DAILY_COUPON){
                    $this->sendEmailRegisterVoucher($this->_usersEmail(), $this->_usersName(), $event['Event']['id']);                        
                }else $this->sendEmailRegister($this->_usersEmail(), $this->_usersName(), $event['Event']['title'], $event['Event']['id']);
                
                $this->sendEmailOwner($event['User']['email'], $event['User']['fullname'], $event['Event']['title'], $this->_usersUserID(), $this->_usersName());
                $this->sendNotificationOwner($event['User']['id'], $event['Event']['id'], $event['Event']['title'], $this->_usersAvatar(), $this->_usersName());
                
                // log activity
                $this->logActivity($this->_usersUserID(),  $participation['event_id'], "vừa tham gia sự kiện");
                
                $this->redirect(array('controller' => 'Events', 'action'=> 'detail', 'slug' => Link::seoTitle($event['Event']['title']), 'id'=> $event['Event']['id'] ));
            }else{
                $this->redirect(array('controller'=>'home', 'action'=>'index'));
            }
        }
        
        function participatedEvents($user_id){
            $this->isAuthenticated();
            
            
            $this->layout = "no_column";
            $this->paginate = array(
                'Participation' => array(
                    'limit' => DEFAULT_SIZE,
                    'order' => array('Participation.created' => 'desc'),
                    'conditions' => array(
                                    'Participation.user_id =' => $user_id)
                )
            );
            
            $dataPaginate = $this->paginate('Participation');
            $data = array();
            $index = 0;
            $nowDateObj = new DateTime();
            foreach($dataPaginate as $participation){
                $data[$index] = array();
                
                // load an event
                $options['conditions'] = array(
                    'Event.id' => $participation['Participation']['event_id']
                );
                $event = $this->Event->find('first', $options);
                
                // get status of event
                $status = '';
                $startDateObj = new DateTime($event['Event']['start']);
                $endDateObj = new DateTime($event['Event']['end']);
                if ($startDateObj > $nowDateObj)
                    $status = STATUS_UP_COMING;
                else if (($startDateObj <= $nowDateObj) && ($endDateObj > $nowDateObj ))
                    $status = STATUS_ON_GOING;
                else if ($endDateObj <= $nowDateObj)
                    $status = STATUS_END;
                $createDateObj = new DateTime($participation['Participation']['created']);
                
                
                $data[$index]['code'] = $event['Event']['code'];
                $data[$index]['title'] = $event['Event']['title'];
                $data[$index]['status'] = $status;
                $data[$index]['start'] = $startDateObj->format(TIME_FORMAT_CLIENT);
                $data[$index]['end'] = $endDateObj->format(TIME_FORMAT_CLIENT);
                $data[$index]['created'] = $createDateObj->format(TIME_FORMAT_CLIENT);
                $data[$index]['event_id'] = $participation['Participation']['event_id'];
                $index++;
            }
            $this->set('data', $data); 
            $this->set('user_id', $user_id);
            if($user_id == $this->_usersUserID())
                $this->set('owner', true);
            else
                $this->set('owner', false);
            
        }
        
        /**
        * Quan ly dang ky tham gia su kien
        * 
        * @param mixed $event_id
        */
        function participationsOfEvent($event_id){
            $this->isAuthenticated();
            $this->layout = "no_column";
            // count number of participation
            $options['conditions'] = array(
                    'Participation.event_id' => $event_id
            );
            
            $number_of_participation = $this->Participation->find('count', $options);
            $this->set('number_of_participation', $number_of_participation);
            
            // get event title
            $options['conditions'] = array(
                    'Event.id' => $event_id
            );
            $event = $this->Event->find('first', $options);
            $this->set('event_title', $event['Event']['title']);
            $this->set('event_id', $event['Event']['id']);
            

            $this->paginate = array(
                'Participation' => array(
                    'limit' => DEFAULT_SIZE,
                    'order' => array('Participation.created' => 'desc'),
                    'conditions' => array(
                                    'Participation.event_id =' => $event_id)
                )
            );
            
            $dataPaginate = $this->paginate('Participation');
            $data = array();
            $index = 0;
            foreach($dataPaginate as $participation){
                $data[$index] = array();
                
                // load a user
                $options['conditions'] = array(
                    'User.id' => $participation['Participation']['user_id']
                );
                $user = $this->User->find('first', $options);
                
                $createDateObj = new DateTime($participation['Participation']['created']);
                
                $data[$index]['created'] = $createDateObj->format(TIME_FORMAT_CLIENT);
                $data[$index]['user_name'] = $user['User']['fullname'];
                $data[$index]['user_id'] = $participation['Participation']['user_id'];
                $data[$index]['mobile'] = $participation['Participation']['mobile'];
                $data[$index]['note'] = $participation['Participation']['note'];
                $data[$index]['event_id'] = $participation['Participation']['event_id'];
                $index++;
            }
            $this->set('data', $data);     
        }
        
        function delete($user_id, $event_id){
            $this->isAuthenticated();
            
            if ($user_id != $this->_usersUserID()){                                  
                $this->redirect(array('controller' => 'Users' ,'action' => 'login'));
            }
            
            $conditions = array(
                    'Participation.event_id' => $event_id,
                    'Participation.user_id' => $user_id
            );        
            $this->Participation->deleteAll($conditions, false);
            $this->redirect(array('controller'=>'Participations','action'=>'participatedEvents'));
        }
        
        function getParticipationsOfEvents($event_id){
            $this->autoRender = false;
            $options['conditions'] = array(
                    'Participation.event_id' => $event_id
            );
            
            $options['joins'] = array(
                        array(
                                'table' => 'users',
                                'alias' => 'User',
                                'type' => 'LEFT',
                                'conditions' => array(
                                'User.id = Participation.user_id',
                                )
                        )
            );
            $options['fields'] = array(
                    'User.fullname',
                    'User.avatar_url',
            );
            
            $participations = $this->Participation->find('all', $options);
            return $participations;
        }
        
        function sendEmailRegister($email_address, $user_name, $event_title, $event_id){
            $this->loadModel('Email');
            $this->autoRender = false;
            $emailRecord['Email'] = array();
            $emailRecord['Email']['priority'] = PRIORITY_HIGH;
            
            $parameters = array();
            $parameters['subject'] = 'Thamgia.net - Chào mừng '. $user_name. ' tham gia sự kiện';
            $parameters['to_email'] = $email_address;
            $parameters['template'] = 'participation_user';
            
            $parameters['event_title'] = $event_title;
            $parameters['user_name'] = $user_name;
            $parameters['event_id'] = $event_id;
            
            
            $emailRecord['Email']['parameters'] = serialize($parameters);
            $this->Email->saveAll($emailRecord);
        }
        
        function sendEmailRegisterVoucher($email_address, $user_name,  $event_id){
            // event information
            $this->loadModel('Event');
            $options['joins'] = array(
                    array(
                            'table' => 'cities',
                            'alias' => 'City',
                            'type' => 'LEFT',
                            'conditions' => array(
                            'City.id = Event.city_id',
                            )
                    ),
            );
            $options['conditions'] = array(
                'Event.id' => $event_id
            );
            
            $options['fields'] = array(
                'Event.id',
                'Event.type_id',
                'Event.city_id',
                'Event.title',
                'Event.start',
                'Event.end',
                'Event.address',
                'Event.fee',
                'City.name'
            );
            $event = $this->Event->find('first', $options); 
            $event_title = $event['Event']['title'];
            $voucher_code = General::generateVoucherCode();
            
            $startTimesObj = new DateTime($event['Event']['start']);
            $event_start = $startTimesObj->format('d-m-Y');
            
            $endTimesObj = new DateTime($event['Event']['end']);
            $event_end = $endTimesObj->format('d-m-Y');
            $event_address =  $event['Event']['address'].' - '.$event['City']['name'];
            $event_fee = $event['Event']['fee'];
            
            
            // create email record
            $this->loadModel('Email');
            $this->autoRender = false;
            $emailRecord['Email'] = array();
            $emailRecord['Email']['priority'] = PRIORITY_HIGH;
            
            $parameters = array();
            $parameters['subject'] = 'Thamgia.net - Chào mừng '. $user_name. ' tham gia sự kiện';
            $parameters['to_email'] = $email_address;
            $parameters['template'] = 'participation_user_voucher';
            
            $parameters['event_title'] = $event_title;
            $parameters['user_name'] = $user_name;
            $parameters['event_id'] = $event_id;
            $parameters['vocher_code'] = $voucher_code;
            $parameters['event_start'] = $event_start;
            $parameters['event_end'] = $event_end;
            $parameters['event_address'] = $event_address;
            $parameters['event_fee'] = $event_fee;
            $emailRecord['Email']['parameters'] = serialize($parameters);
            $this->Email->saveAll($emailRecord);
        }
        
        
        function sendEmailOwner($email_address, $owner_name, $event_title, $user_id, $user_name){
            $this->loadModel('Email');
            $this->autoRender = false;
            $emailRecord['Email'] = array();
            $emailRecord['Email']['priority'] = PRIORITY_HIGH;
            
            $parameters = array();
            $parameters['subject'] = 'Thamgia.net - '.$user_name.' vừa tham gia sự kiện';
            $parameters['to_email'] = $email_address;
            $parameters['template'] = 'participation_owner';
            
            $parameters['event_title'] = $event_title;
            $parameters['user_name'] = $user_name;
            $parameters['user_id'] = $user_id;
            $parameters['owner_name'] = $owner_name;
            
            
            $emailRecord['Email']['parameters'] = serialize($parameters);
            $this->Email->saveAll($emailRecord);    
        }
        
        function sendNotificationOwner($user_id,  $event_id, $event_title, $image_url, $member_name){
            $this->autoRender = false;
            $this->loadModel('Notification');
            $linkNotification = Router::url(array(
                                            "controller"=>"Participations", 
                                            "action"=> "participationsOfEvent",
                                            $event_id)); 
                                            
            $recordNotification['Notification'] = array();
            $recordNotification['Notification']['link'] = $linkNotification;
            $recordNotification['Notification']['notification'] = '<strong>'. $member_name . '</strong> vừa tham gia sự kiện '. $event_title;
            $recordNotification['Notification']['image_url'] = $image_url != null ? $image_url : NO_IMG_URL;
            $recordNotification['Notification']['user_id'] = $user_id;
            $recordNotification['Notification']['viewed'] = false;
            $recordNotification['Notification']['created'] = date(TIME_FORMAT_MYSQL);
            
            $this->Notification->save($recordNotification);
        }
    }
?>
