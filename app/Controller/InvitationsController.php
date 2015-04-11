<?php
    class InvitationsController extends AppController{
        function beforeFilter(){
            parent::beforeFilter();
            $this->loadModel('User');
        }
        
        function getMaxUsers(){
            $this->autoRender = false;
            $maxUser = 4;
            return  $this->User->GetRandomUsers($maxUser);
        }
        
        function getInvitation($event_id){
            $this->layout = 'ajax';
            $data = $this->User->GetRandomUsers(1);
            $this->set('user', $data[0]);
            $this->set('event_id', $event_id); 
        }
        
        function sendEmailInvitation(){
            $this->loadModel('Email');
            $this->autoRender = false;
            
            if ($this->request->is('post')){
                $event_id = $_POST['event_id'];
                $email = $_POST['email'];
                
                // get event detail
                $this->loadModel('Event');
                $options['joins'] = array(
                        array(
                                'table' => 'users',
                                'alias' => 'User',
                                'type' => 'LEFT',
                                'conditions' => array(
                                'User.id = Event.user_id',
                                )
                        ),
                        array(
                                'table' => 'cities',
                                'alias' => 'City',
                                'type' => 'LEFT',
                                'conditions' => array(
                                'City.id = Event.city_id',
                                )
                        )
                );
                $options['conditions'] = array(
                    'Event.id' => $event_id
                );
                
                $options['fields'] = array(
                    'User.fullname',
                    'User.id',
                    'Event.id',
                    'Event.type_id',
                    'Event.city_id',
                    'Event.title',
                    'Event.start',
                    'Event.end',
                    'Event.address',
                    'Event.hotline',
                    'Event.free',
                    'Event.fee',
                    'Event.image_url',
                    'Event.description',
                    'Event.created',
                    'Event.views',
                    'City.name'
                );
                
                $event = $this->Event->find('first', $options);
                $dayOfWeek = array();
                $dayOfWeek[1] = "Thứ 2";
                $dayOfWeek[2] = "Thứ 3";
                $dayOfWeek[3] = "Thứ 4";
                $dayOfWeek[4] = "Thứ 5";
                $dayOfWeek[5] = "Thứ 6";
                $dayOfWeek[6] = "Thứ 7";
                $dayOfWeek[7] = "Chủ nhật";
                
                $dataEvent = array();
                $dataEvent['title'] = $event['Event']['title'];
                $startTimesObj = new DateTime($event['Event']['start']);
                $dataEvent['start'] = 'Từ: '.$dayOfWeek[$startTimesObj->format('N')] . ', '. $startTimesObj->format('d'). ' Tháng '. $startTimesObj->format('m'). ' ' . $startTimesObj->format('Y'). ' '. $startTimesObj->format('H').':'. $startTimesObj->format('i');
                
                $endTimesObj = new DateTime($event['Event']['end']);
                $dataEvent['end'] = 'Đến: '.$dayOfWeek[$endTimesObj->format('N')] . ', '. $endTimesObj->format('d'). ' Tháng '. $endTimesObj->format('m'). ' ' . $endTimesObj->format('Y'). ' '. $endTimesObj->format('H').':'. $endTimesObj->format('i'); 
                $dataEvent['address'] = $event['Event']['address'].' - '.$event['City']['name'];
                
                // store email
                $emailRecord['Email'] = array();
                $emailRecord['Email']['priority'] = PRIORITY_NORMAL;
                
                $parameters = array();
                $parameters['subject'] = $this->_usersName(). ' vừa mời bạn tham gia một sự kiện tại thamgia.net';
                $parameters['to_email'] = $email;
                $parameters['template'] = 'inviter';
                
                $parameters['event_id'] = $event_id;
                $parameters['user_name'] = $this->_usersName();
                $parameters['event_title'] = $dataEvent['title'];
                $parameters['event_time'] = $dataEvent['start'] . ' - ' . $dataEvent['end'];
                $parameters['event_address'] = $dataEvent['address'];
                
                $emailRecord['Email']['parameters'] = serialize($parameters);
                $this->Email->save($emailRecord);
            }
        }
        
    }
?>
