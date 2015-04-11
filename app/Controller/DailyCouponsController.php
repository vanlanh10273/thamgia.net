<?php
class DailyCouponsController extends AppController{
    public $helpers = array('Html');
    
    function beforeFilter(){
        parent::beforeFilter();
        $cityName = $this->Session->read(CITY_NAME);
        $this->set('title', 'Daily Coupon - Chương trình nhận voucher tại thamgia.net');
        $this->set('meta_description', 'Daily Coupon - chương trình khuyến mãi nhận voucher giảm giá chỉ dành cho thành viên thamgia.net tại '. $cityName);
        $this->set('meta_keywords', 'Khuyến mãi '. $cityName .', Giảm giá '. $cityName .', khuyen mai '.$cityName.', sự kiện '.$cityName.', hội thảo '.$cityName );
    }
    
    function index($slug, $city_id){
        $this->layout = 'event';
        // store city_id to cookie
        $this->storeCityToSession($city_id, $this->getCityNameById($city_id));    
    }
     
    function getDailyCoupon($city_id){
        $current = new DateTime();
        $current = new DateTime($current->format('Y-m-d'));
        $options['conditions'] = array(
            'DailyCoupon.start <=' => $current->format(TIME_FORMAT_MYSQL),
            'DailyCoupon.end >=' => $current->format(TIME_FORMAT_MYSQL),
            'DailyCoupon.city_id' => $city_id
        );
        $options['fields'] = array(
            'DailyCoupon.id',
            'DailyCoupon.image_thumb_url',
            'DailyCoupon.summary',
            'DailyCoupon.title',
            'DailyCoupon.discount',
            'DailyCoupon.end'
        ); 
        $events = $this->DailyCoupon->find('all', $options);
        shuffle($events);

        for($index=0; $index< count($events); $index++){
            $events[$index]['DailyCoupon']['end'] = $this->dateToVieFormat(new DateTime($events[$index]['DailyCoupon']['end']));
        }
        return $events;
    }
    
    function getListDailyCoupons($city_id, $limit = 6, $from){
        $this->autoRender = false;
        $current = new DateTime();
        $current = new DateTime($current->format('Y-m-d'));
        $options['limit'] = $limit;
        $conditions = array();
        $conditions['DailyCoupon.city_id'] = $city_id;
        $conditions['DailyCoupon.start <='] = $current->format(TIME_FORMAT_MYSQL);
        $conditions['DailyCoupon.end >='] = $current->format(TIME_FORMAT_MYSQL);
        
        if ($from != 0){
            $options['limit'] = $from . ',' . $limit;
        }
        
        $options['conditions'] = $conditions;
        
        
        $options['joins'] = array(
                array(
                            'table' => 'users',
                            'alias' => 'User',
                            'type' => 'LEFT',
                            'conditions' => array(
                            'User.id = DailyCoupon.user_id',
                            )
                    ),
                array(
                        'table' => 'cities',
                        'alias' => 'City',
                        'type' => 'LEFT',
                        'conditions' => array(
                        'City.id = DailyCoupon.city_id',
                        )
                )
            );
        $options['order'] = array('DailyCoupon.start' => 'asc');
        $options['fields'] = array('User.fullname', 'DailyCoupon.title', 'DailyCoupon.views'
                                    ,'DailyCoupon.address', 'DailyCoupon.image_url'
                                    ,'DailyCoupon.start', 'DailyCoupon.title', 'DailyCoupon.id', 'DailyCoupon.user_id',
                                    'DailyCoupon.thanks','DailyCoupon.discount');
        $data = $this->DailyCoupon->find('all', $options);
        return $data;    
    }
    
    function moreDailyCoupons(){
            $this->autoRender = true;
            $city_id =  isset($this->request->query['city_id']) ? $this->request->query['city_id'] : 0;
            $limit = isset($this->request->query['limit']) ? $this->request->query['limit'] : 6;
            $from = isset($this->request->query['from'])? $this->request->query['from']  + 1 : 0;
            $data = $this->getListDailyCoupons($city_id, $limit, $from);
            $this->layout = "ajax";
            $this->autoRender = true;
            $this->set('data', $data);
            $this->set('from', $from);
        }
    
    function countDailyCoupon($city_id){
        $current = new DateTime();
        $current = new DateTime($current->format('Y-m-d'));
        $options['conditions'] = array(
            'DailyCoupon.start <=' => $current->format(TIME_FORMAT_MYSQL),
            'DailyCoupon.end >=' => $current->format(TIME_FORMAT_MYSQL),
            'DailyCoupon.city_id' => $city_id
        );
        $dailyCoupon = $this->DailyCoupon->find('count', $options);
        return $dailyCoupon;
    }
    
    function detail($slug, $id = null){
        //$this->isAuthEventDetail($id);
        
        $this->layout = "daily_coupon_detail";
        if ($id){
            $options['joins'] = array(
                array(
                        'table' => 'users',
                        'alias' => 'User',
                        'type' => 'LEFT',
                        'conditions' => array(
                        'User.id = DailyCoupon.user_id',
                        )
                ),
                array(
                        'table' => 'cities',
                        'alias' => 'City',
                        'type' => 'LEFT',
                        'conditions' => array(
                        'City.id = DailyCoupon.city_id',
                        )
                )
            );
            $options['conditions'] = array(
                'DailyCoupon.id' => $id
            );
            
            $options['fields'] = array(
                'User.fullname',
                'User.avatar_url',
                'User.id',
                'DailyCoupon.id',
                'DailyCoupon.city_id',
                'DailyCoupon.title',
                'DailyCoupon.start',
                'DailyCoupon.end',
                'DailyCoupon.address',
                'DailyCoupon.hotline',
                'DailyCoupon.discount',
                'DailyCoupon.image_url',
                'DailyCoupon.description',
                'DailyCoupon.created',
                'DailyCoupon.discount',
                'DailyCoupon.thanks',
                'DailyCoupon.views',
                'City.name'
            );
            
            $event = $this->DailyCoupon->find('first', $options); 
            
            
            if($event){
                // store city_id and city_name to session
                $this->storeCityToSession($event['DailyCoupon']['city_id'], $event['City']['name']);
                
                // save view
                $event['DailyCoupon']['views'] = $event['DailyCoupon']['views'] + 1;
                $this->DailyCoupon->save($event);
                
                $dayOfWeek = array();
                $dayOfWeek[1] = "Thứ 2";
                $dayOfWeek[2] = "Thứ 3";
                $dayOfWeek[3] = "Thứ 4";
                $dayOfWeek[4] = "Thứ 5";
                $dayOfWeek[5] = "Thứ 6";
                $dayOfWeek[6] = "Thứ 7";
                $dayOfWeek[7] = "Chủ nhật";
                
                $data = array();
                $data['id']  = $id;
                $data['title'] = $event['DailyCoupon']['title'];
                $startTimesObj = new DateTime($event['DailyCoupon']['start']);
                $data['start'] = 'Từ: '.$dayOfWeek[$startTimesObj->format('N')] . ', '. $startTimesObj->format('d'). ' Tháng '. $startTimesObj->format('m'). ' ' . $startTimesObj->format('Y'). ' '. $startTimesObj->format('H').':'. $startTimesObj->format('i');
                
                $endTimesObj = new DateTime($event['DailyCoupon']['end']);
                $data['end'] = 'Đến: '.$dayOfWeek[$endTimesObj->format('N')] . ', '. $endTimesObj->format('d'). ' Tháng '. $endTimesObj->format('m'). ' ' . $endTimesObj->format('Y'). ' '. $endTimesObj->format('H').':'. $endTimesObj->format('i');
                
                $createTimeObj = new DateTime($event['DailyCoupon']['created']);
                
                $data['created'] = $this->timeToString(time()-strtotime($event['DailyCoupon']['created'])).' trước'; 
                $data['discount'] = $event['DailyCoupon']['discount'];
                
                
                // get address of event
                $data['address'] = $event['DailyCoupon']['address'].' - '.$event['City']['name'];
                
                // get hotline
                $data['hotline'] = $event['DailyCoupon']['hotline'];
                
                
                // get image
                if (isset($event['DailyCoupon']['image_url'])){
                    $data['image'] = $event['DailyCoupon']['image_url'];
                }else{
                    $data['image'] = NO_IMG_URL;
                }
                
                // get event description
                $data['description'] = isset($event['DailyCoupon']['description'])?$event['DailyCoupon']['description']: '';
                
                $data['user_name'] = $event['User']['fullname'];
                $data['owner_id'] = $event['User']['id'];
                
                $data['city_id'] = $event['DailyCoupon']['city_id'];
                $data['city_name'] = $event['City']['name'];
                $data['avatar_url'] = $event['User']['avatar_url'];
                $data['thanks'] = $event['DailyCoupon']['thanks'];
                
                // get summary iformation for event
                $options = array();
                $options['conditions'] = array(
                    'ParticipationDailyCoupon.daily_coupon_id' => $id
                );
                $this->loadModel('ParticipationDailyCoupon');
                $totalParticipation = $this->ParticipationDailyCoupon->find('count', $options);
                
                
                $participated = false;
                if ($this->_isLogin()){
                    $options = array();
                    $options['conditions'] = array(
                        'ParticipationDailyCoupon.daily_coupon_id' => $id,
                        'ParticipationDailyCoupon.user_id' => $this->_usersUserID()
                    );
                    $participated = $this->ParticipationDailyCoupon->find('count', $options) > 0;
                }
                
                // get string time for time count down
                $timeCounDown = $endTimesObj->format('F') . ' ' . $endTimesObj->format('d') . ' ' . $endTimesObj->format('Y') . ' '. $endTimesObj->format('H'). ':'. $endTimesObj->format('i') . ':' . $endTimesObj->format('s');
                
                $this->set('data', $data);    
                $this->set('hasEvent', true);
                $this->set('totalParticipation', $totalParticipation);
                $this->set('participated', $participated);
                $this->set('timeCountDown', $timeCounDown);
                $this->set('title',  $event['DailyCoupon']['title']. ' - ' .'thamgia.net');
                
                // get event status
                $current = new DateTime();
                $event_status = STATUS_UP_COMING;
                if ( ($event['DailyCoupon']['start'] <= $current->format(TIME_FORMAT_MYSQL)) && ($event['DailyCoupon']['end'] > $current->format(TIME_FORMAT_MYSQL) )){
                    $event_status = STATUS_ON_GOING;
                }else if ( ($event['DailyCoupon']['start'] < $current->format(TIME_FORMAT_MYSQL)) && ($event['DailyCoupon']['end'] < $current->format(TIME_FORMAT_MYSQL) )){
                    $event_status = STATUS_END;                        
                }
                $this->set('eventStatus', $event_status);
                
                
            }else{
                $this->redirect(array('controller'=>'home', 'action'=>'index'));
            }                
        }else{
            $this->redirect(array('controller'=>'home', 'action'=>'index'));
        }
    }
    
    function getVoucherInformation($daily_coupon_id){
            $this->autoRender = false;
            $options['joins'] = array(
                array(
                        'table' => 'cities',
                        'alias' => 'City',
                        'type' => 'LEFT',
                        'conditions' => array(
                        'City.id = DailyCoupon.city_id',
                        )
                )
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
                'DailyCoupon.summary',
                'City.name'
            );
            
            $dailyCoupon = $this->DailyCoupon->find('first', $options);
            
            $data = array();
            $data['title'] = $dailyCoupon['DailyCoupon']['title'];
            $data['id'] = $dailyCoupon['DailyCoupon']['id'];
            $data['discount'] = $dailyCoupon['DailyCoupon']['discount'];
            $startTimesObj = new DateTime($dailyCoupon['DailyCoupon']['start']);
            $data['start'] =  $startTimesObj->format('d/m/y'); 
            
            $endTimesObj = new DateTime($dailyCoupon['DailyCoupon']['end']);
            $data['end'] =  $endTimesObj->format('d/m/y');
            
            $data['address'] = $dailyCoupon['DailyCoupon']['address'].', '.$dailyCoupon['City']['name'];
            //$data['code'] = General::generateVoucherCode(); 
            $data['summary'] = $dailyCoupon['DailyCoupon']['summary'];
            
            // get code of voucher
            $options = array();
            $options['conditions'] = array(
                'ParticipationDailyCoupon.daily_coupon_id' => $daily_coupon_id,
                'ParticipationDailyCoupon.user_id' => $this->_usersUserID()
            );
            
            $options['fields'] = array(
                'ParticipationDailyCoupon.code'
            );
            $this->loadModel('ParticipationDailyCoupon');
            $participationDailyCoupon = $this->ParticipationDailyCoupon->find('first', $options);
            $data['code'] = $participationDailyCoupon['ParticipationDailyCoupon']['code'];
            
            return $data;
        }
        
    function getPdfVoucher($daily_coupon_id){
        $this->isAuthenticated();
                       
        $data = $this->getVoucherInformation($daily_coupon_id);
        $this->autoRender = true;
        $this->set('daily_coupon_title', $data['title']);
        $this->set('daily_coupon_start', $data['start']);
        $this->set('daily_coupon_end', $data['end']);
        $this->set('voucher_code', $data['code']);
        $this->set('daily_coupon_address', $data['address']);
        $this->set('daily_coupon_summary', $data['summary']);
        $this->set('daily_coupon_discount', $data['discount']);
    }
        
    function printVoucher($daily_coupon_id){
        $this->layout = 'ajax';
        $this->isAuthenticated();
                       
        $data = $this->getVoucherInformation($daily_coupon_id);
        $this->autoRender = true;
        
        $this->set('daily_coupon_title', $data['title']);
        $this->set('daily_coupon_start', $data['start']);
        $this->set('daily_coupon_end', $data['end']);
        $this->set('voucher_code', $data['code']);
        $this->set('daily_coupon_address', $data['address']);
        $this->set('daily_coupon_summary', $data['summary']);
        $this->set('daily_coupon_discount', $data['discount']);
    }
        
    
    function migrate(){
        $this->autoRender = false;
        $this->loadModel("Event");
        $options['conditions'] = array('Event.is_daily_coupon' => true);
        $arrDailyCoupons = $this->Event->find('all', $options);
        foreach($arrDailyCoupons as $event){
            $dailyCoupon = array();
            $dailyCoupon['DailyCoupon']['title'] = $event['Event']['title'];
            $dailyCoupon['DailyCoupon']['address'] = $event['Event']['address'];
            $dailyCoupon['DailyCoupon']['city_id'] = $event['Event']['city_id'];
            $dailyCoupon['DailyCoupon']['start'] = $event['Event']['start'];
            $dailyCoupon['DailyCoupon']['end'] = $event['Event']['end'];
            $dailyCoupon['DailyCoupon']['discount'] = $event['Event']['fee'];
            $dailyCoupon['DailyCoupon']['hotline'] = $event['Event']['hotline'];
            $dailyCoupon['DailyCoupon']['description'] = $event['Event']['description'];
            $dailyCoupon['DailyCoupon']['image_url'] = $event['Event']['image_url'];
            $dailyCoupon['DailyCoupon']['image_thumb_url'] = $event['Event']['image_url'];
            $dailyCoupon['DailyCoupon']['user_id'] = $event['Event']['user_id'];
            $dailyCoupon['DailyCoupon']['created'] = $event['Event']['created'];
            $dailyCoupon['DailyCoupon']['updated'] = $event['Event']['updated'];
            $dailyCoupon['DailyCoupon']['code'] = $event['Event']['code'];
            $dailyCoupon['DailyCoupon']['thanks'] = $event['Event']['thanks'];
            $this->DailyCoupon->saveAll($dailyCoupon);
        }
    }
}
?>
