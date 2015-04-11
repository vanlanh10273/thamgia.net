<?php
    class ParticipationDailyCouponsController extends AppController{
        public $name = "ParticipationDailyCoupons";
        
        function beforeFilter(){
            parent::beforeFilter();
        }
        
        function participate(){
            $this->isAuthenticated();
            
            if ($this->request->is('post')){
                $participation = $this->request->data('ParticipationDailyCoupon');
                $arrParticipation['ParticipationDailyCoupon'] = array();
                $arrParticipation['ParticipationDailyCoupon']['daily_coupon_id'] = $participation['daily_coupon_id'];
                $arrParticipation['ParticipationDailyCoupon']['user_id'] = $this->_usersUserID();
                $arrParticipation['ParticipationDailyCoupon']['mobile'] = $participation['mobile'];
                $arrParticipation['ParticipationDailyCoupon']['note'] = $participation['note'];
                $arrParticipation['ParticipationDailyCoupon']['created'] = date(TIME_FORMAT_MYSQL);
                $voucherCode = $this->generateCode();
                $arrParticipation['ParticipationDailyCoupon']['code'] = $voucherCode;
                $this->ParticipationDailyCoupon->save($arrParticipation);
                
                $this->loadModel("DailyCoupon");
                $options['joins'] = array(
                        array(
                                'table' => 'users',
                                'alias' => 'User',
                                'type' => 'LEFT',
                                'conditions' => array(
                                'User.id = DailyCoupon.user_id',
                                )
                        )
                );
                $options['conditions'] = array(
                    'DailyCoupon.id' => $participation['daily_coupon_id']
                );
                
                $options['fields'] = array(
                    'User.id',
                    'User.fullname',
                    'User.email',
                    'DailyCoupon.id',
                    'DailyCoupon.title'
                );
                
                $event = $this->DailyCoupon->find('first', $options);
                
                
                $this->sendEmailRegisterVoucher($this->_usersEmail(), $this->_usersName(), $event['DailyCoupon']['id'], $voucherCode);                        
                
                $this->sendEmailOwner($event['User']['email'], $event['User']['fullname'], $event['DailyCoupon']['title'], $this->_usersUserID(), $this->_usersName());
                $this->sendNotificationOwner($event['User']['id'], $event['DailyCoupon']['id'], $event['DailyCoupon']['title'], $this->_usersAvatar(), $this->_usersName());
                
                // log activity
                $this->logActivity($this->_usersUserID() ,$event['DailyCoupon']['id'], "vừa nhận voucher tại" ,true);
                
                $this->redirect(array('controller' => 'DailyCoupons', 'action'=> 'detail', 'slug' => Link::seoTitle($event['DailyCoupon']['title']), 'id'=> $event['DailyCoupon']['id'] ));
            }else{
                $this->redirect(array('controller'=>'home', 'action'=>'index'));
            }
        }
        
        /**
        * Quan ly dang ky tham gia su kien
        * 
        * @param mixed $event_id
        */
        function participationsOfDailyCoupon($daily_coupon_id){
            $this->isAuthenticated();
            $this->layout = "no_column";
            // count number of participation
            $options['conditions'] = array(
                    'ParticipationDailyCoupon.daily_coupon_id' => $daily_coupon_id
            );
            
            $number_of_participation = $this->ParticipationDailyCoupon->find('count', $options);
            $this->set('number_of_participation', $number_of_participation);
            
            // get event title
            $options['conditions'] = array(
                    'DailyCoupon.id' => $daily_coupon_id
            );
            $this->loadModel("DailyCoupon");
            $dailyCoupon = $this->DailyCoupon->find('first', $options);
            $this->set('daily_coupon_title', $dailyCoupon['DailyCoupon']['title']);
            $this->set('daily_coupon_id', $dailyCoupon['DailyCoupon']['id']);
            

            $this->paginate = array(
                'ParticipationDailyCoupon' => array(
                    'limit' => DEFAULT_SIZE,
                    'order' => array('ParticipationDailyCoupon.created' => 'desc'),
                    'conditions' => array(
                                    'ParticipationDailyCoupon.daily_coupon_id =' => $daily_coupon_id)
                )
            );
            
            $dataPaginate = $this->paginate('ParticipationDailyCoupon');
            $data = array();
            $index = 0;
            foreach($dataPaginate as $participation){
                $data[$index] = array();
                
                // load a user
                $options['conditions'] = array(
                    'User.id' => $participation['ParticipationDailyCoupon']['user_id']
                );
                $user = $this->User->find('first', $options);
                
                $createDateObj = new DateTime($participation['ParticipationDailyCoupon']['created']);
                
                $data[$index]['created'] = $createDateObj->format(TIME_FORMAT_CLIENT);
                $data[$index]['user_name'] = $user['User']['fullname'];
                $data[$index]['user_id'] = $participation['ParticipationDailyCoupon']['user_id'];
                $data[$index]['mobile'] = $participation['ParticipationDailyCoupon']['mobile'];
                $data[$index]['note'] = $participation['ParticipationDailyCoupon']['note'];
                $data[$index]['daily_coupon_id'] = $participation['ParticipationDailyCoupon']['daily_coupon_id'];
                $index++;
            }
            $this->set('data', $data);     
        }
        
        function getParticipations($daily_coupon_id){
            $this->autoRender = false;
            $options['conditions'] = array(
                    'ParticipationDailyCoupon.daily_coupon_id' => $daily_coupon_id
            );
            
            $options['joins'] = array(
                        array(
                                'table' => 'users',
                                'alias' => 'User',
                                'type' => 'LEFT',
                                'conditions' => array(
                                'User.id = ParticipationDailyCoupon.user_id',
                                )
                        )
            );
            $options['fields'] = array(
                    'User.fullname',
                    'User.avatar_url',
            );
            
            $participations = $this->ParticipationDailyCoupon->find('all', $options);
            return $participations;
        }
        
        function sendEmailRegisterVoucher($email_address, $user_name,  $daily_coupon_id, $voucher_code){
            // event information
            $this->loadModel('DailyCoupon');
            $options['joins'] = array(
                    array(
                            'table' => 'cities',
                            'alias' => 'City',
                            'type' => 'LEFT',
                            'conditions' => array(
                            'City.id = DailyCoupon.city_id',
                            )
                    ),
            );
            $options['conditions'] = array(
                'DailyCoupon.id' => $daily_coupon_id
            );
            
            $options['fields'] = array(
                'DailyCoupon.id',
                'DailyCoupon.city_id',
                'DailyCoupon.title',
                'DailyCoupon.start',
                'DailyCoupon.end',
                'DailyCoupon.address',
                'DailyCoupon.discount',
                'City.name'
            );
            $dailyCoupon = $this->DailyCoupon->find('first', $options); 
            $dailyCouponTitle = $dailyCoupon['DailyCoupon']['title'];
            
            
            $startTimesObj = new DateTime($dailyCoupon['DailyCoupon']['start']);
            $dailyCouponStart = $startTimesObj->format('d-m-Y');
            
            $endTimesObj = new DateTime($dailyCoupon['DailyCoupon']['end']);
            $dailyCouponEnd = $endTimesObj->format('d-m-Y');
            $dailyCouponAddress =  $dailyCoupon['Event']['address'].' - '.$dailyCoupon['City']['name'];
            $dailyCouponDiscount = $dailyCoupon['DailyCoupon']['discount'];
            
            
            // create email record
            $this->loadModel('Email');
            $this->autoRender = false;
            $emailRecord['Email'] = array();
            $emailRecord['Email']['priority'] = PRIORITY_HIGH;
            
            $parameters = array();
            $parameters['subject'] = 'Thamgia.net - Chào mừng '. $user_name. ' tham gia sự kiện';
            $parameters['to_email'] = $email_address;
            $parameters['template'] = 'participation_user_voucher';
            
            $parameters['event_title'] = $dailyCouponTitle;
            $parameters['user_name'] = $user_name;
            $parameters['event_id'] = $daily_coupon_id;
            $parameters['vocher_code'] = $voucher_code;
            $parameters['event_start'] = $dailyCouponStart;
            $parameters['event_end'] = $dailyCouponEnd;
            $parameters['event_address'] = $dailyCouponAddress;
            $parameters['event_fee'] = $dailyCouponDiscount;
            $emailRecord['Email']['parameters'] = serialize($parameters);
            $this->Email->saveAll($emailRecord);
        }
        
        function sendEmailOwner($email_address, $owner_name, $event_title, $user_id, $user_name){
            $this->loadModel('Email');
            $this->autoRender = false;
            $emailRecord['Email'] = array();
            $emailRecord['Email']['priority'] = PRIORITY_HIGH;
            
            $parameters = array();
            $parameters['subject'] = 'Thamgia.net - '.$user_name.' vừa tham gia daily coupon';
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
                                            "controller"=>"ParticipationDailyCoupons", 
                                            "action"=> "participationsOfDailyCoupon",
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
        
        function generateCode(){
            $continue = false;
            $code = '';
            do{
                $code = strtotime("now");
                $code = str_replace(".", "", $code);
                $code = substr($code, strlen($code) - 9, 9);    
                
                $options['conditions'] = array(
                    'ParticipationDailyCoupon.code' => $code
                );
                
                $count = $this->ParticipationDailyCoupon->find('count', $options);
                $continue = $count > 0;
            }while($continue);
            
            return $code;
        }
    }
?>
