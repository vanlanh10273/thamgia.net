<?php
    CakePlugin::load('Uploader');
    App::import('Vendor', 'Uploader.Uploader');
     class EventsController extends AppController{
         public $helpers = array('Fck', 'Html', 'QrCode');
         /*public $components = array('RequestHandler');*/
         public $name = "Events";
         
         function beforeFilter(){
            parent::beforeFilter();
            $this->Uploader = new Uploader(array('tempDir' => TMP, 'uploadDir' => FOLDER_UPLOAD_IMAGE_EVENT));
        }
        
        function getByType($slug_city, $city_id, $slug_type ,$type_id){
            
            $this->storeCityToSession($city_id, $this->getCityNameById($city_id));

            $this->layout = "event";
            $options['conditions'] = array(
                        'City.id' => $city_id
                    );
            $city = $this->City->find('first', $options);
            $options['conditions'] = array(
                    'Type.id' => $type_id
                );
            $type = $this->Type->find('first', $options);
            
            
            $this->set('type_id', $type_id);
            $this->set('type_name', $type['Type']['name']);


         }
         
        function add(){
            $this->isAuthenticated();
            $this->layout = "no_daily_coupon";
            $this->loadModel('Career');
            $careers = $this->Career->find('all');
            $this->set('careers', $careers);
            
            if ($this->request->is('post')){
                $error = null;
                $data = $this->request->data('Event');
                try{
                    $arrEvent['Event'] = array();
                    $arrEvent['Event']['title'] = $data['title'];
                    $arrEvent['Event']['address'] = $data['address'];
                    $arrEvent['Event']['city_id'] = $data['city_id'];
                    $arrEvent['Event']['type_id'] = $data['type_id'];
                    $arrEvent['Event']['career_id'] = $data['career_id'];
                    
                    $startEvent = $this->clientDateToTime($data['start']);
                    $endEvent = $this->clientDateToTime($data['end']);
                    if ($startEvent >= $endEvent){
                        $data['end'] = '';
                        throw new Exception("Vui lòng chọn lại thời gian kết thúc sự kiện!");
                    }
                    $arrEvent['Event']['start'] =  date(TIME_FORMAT_MYSQL, $startEvent); 
                    $arrEvent['Event']['end'] = date(TIME_FORMAT_MYSQL, $endEvent);
                    $arrEvent['Event']['free'] = $data['free'];
                    if (!$data['free']){
                        if ($data['fee'] != DEFAULT_FEE)
                            $arrEvent['Event']['fee'] = $data['fee'];   
                    }
                    $arrEvent['Event']['hotline'] = $data['hotline'];
                    $arrEvent['Event']['description'] = $data['description'];
                    $arrEvent['Event']['created'] = date(TIME_FORMAT_MYSQL);
                    $arrEvent['Event']['updated'] = $arrEvent['Event']['created'];
                    $arrEvent['Event']['user_id'] = $data['user_id'];
                    $arrEvent['Event']['code'] = $this->generateCode();
                    $arrEvent['Event']['approved'] = false;
                    if ($data['image']['name'] != ''){
                         if ($dataUpload = $this->Uploader->upload($data['image'], array('overwrite' => true, 'name' => $this->generateCode()))) {
                            // Upload successful, do whatever
                            $resized_path = $this->Uploader->resize(array('width' => IMG_WIDTH));
                            $resized_path_thumb = $this->Uploader->resize(array('width' => DAILYCOUPON_THUMB_WIDTH, 'height' => DAILYCOUPON_THUMB_HEIGHT, 'aspect' => false));
                            $resized_path_list = $this->Uploader->resize(array('width' => IMG_LIST_WIDTH));
                            $this->Uploader->delete($dataUpload['path']);
                            $arrEvent['Event']['image_url'] = preg_replace('/^\//', '', $resized_path);
                            $arrEvent['Event']['image_thumb_url'] = preg_replace('/^\//', '', $resized_path_thumb);
                            $arrEvent['Event']['image_list_url'] = preg_replace('/^\//', '', $resized_path_list);
                        }else{
                            /*$error .= 'Lỗi upload ảnh!<br />';      */
                            throw new  Exception("Lỗi upload ảnh!");
                        }
                    }
                    
                   
                    $this->Event->save($arrEvent);
                    $this->sendMailAddEvent($this->_usersEmail(), $this->Event->id, $this->_usersName(), $data['title']);
                    
                    $this->redirect(array('controller'=>'Events', 'action'=>'finishAddEvent'));
                   
                }catch(Exception $ex){
                    $this->set('data', $data);
                    $this->set('error', $ex->getMessage());
                }
            }
            else{
                //$this->redirect(array('controller'=>'home', 'action'=>'index'));
            }
        }
        
        function addedEvents($user_id){
            $this->isAuthenticated();
            
            $this->layout = "no_column";
            
            $this->paginate = array(
                'Event' => array(
                    'limit' => DEFAULT_SIZE,
                    'order' => array('Event.created' => 'desc'),
                    'conditions' => array(
                                    'Event.user_id =' => $user_id)
                )
            );
            
            $dataPaginate = $this->paginate('Event');
            $data = array();
            $index = 0;

            foreach($dataPaginate as $event){
                $data[$index] = array();
                
                $data[$index]['title'] = $event['Event']['title'];
                $data[$index]['id'] = $event['Event']['id'];

                $createdDateObj = new DateTime($event['Event']['created']);
                if ($event['Event']['updated'] != null){
                    $updatedDateObj = new DateTime($event['Event']['updated'] );    
                    $data[$index]['updated'] = $updatedDateObj->format(TIME_FORMAT_CLIENT);
                }else
                    $data[$index]['updated'] = '';
                
                
                $data[$index]['created'] = $createdDateObj->format(TIME_FORMAT_CLIENT);
                $data[$index]['approved'] = $event['Event']['approved'] ? 'approved' : 'pending';
                $data[$index]['code'] = $event['Event']['code'];
                $options['conditions'] = array(
                    'Participation.event_id' => $event['Event']['id']
                );
                $this->loadModel('Participation');
                $data[$index]['members'] = $this->Participation->find('count', $options);
                $index++;
            }
            $this->set('data', $data); 
            $this->set('user_id', $user_id);
            if($user_id == $this->_usersUserID())
                $this->set('owner', true);
            else
                $this->set('owner', false);
        }
        
        function detail($slug, $id = null){
            $this->isAuthEventDetail($id);
            
            $this->layout = "event_detail";
            if ($id){
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
                        ),
                        array(
                                'table' => 'types',
                                'alias' => 'Type',
                                'type' => 'LEFT',
                                'conditions' => array(
                                'Type.id = Event.type_id',
                                )
                        )
                );
                $options['conditions'] = array(
                    'Event.id' => $id
                );
                
                $options['fields'] = array(
                    'User.fullname',
                    'User.avatar_url',
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
                    'Event.is_daily_coupon',
                    'Type.name',
                    'City.name',
                    'Event.thanks'
                );
                
                $event = $this->Event->find('first', $options); 
                
                
                if($event){
                    // store city_id and city_name to session
                    $this->storeCityToSession($event['Event']['city_id'], $event['City']['name']);
                    // save view
                    $event['Event']['views'] = $event['Event']['views'] + 1;
                    $this->Event->save($event);
                    
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
                    $data['title'] = $event['Event']['title'];
                    $startTimesObj = new DateTime($event['Event']['start']);
                    $data['start'] = 'Từ: '.$dayOfWeek[$startTimesObj->format('N')] . ', '. $startTimesObj->format('d'). ' Tháng '. $startTimesObj->format('m'). ' ' . $startTimesObj->format('Y'). ' '. $startTimesObj->format('H').':'. $startTimesObj->format('i');
                    
                    $endTimesObj = new DateTime($event['Event']['end']);
                    $data['end'] = 'Đến: '.$dayOfWeek[$endTimesObj->format('N')] . ', '. $endTimesObj->format('d'). ' Tháng '. $endTimesObj->format('m'). ' ' . $endTimesObj->format('Y'). ' '. $endTimesObj->format('H').':'. $endTimesObj->format('i');
                    
                    $createTimeObj = new DateTime($event['Event']['created']);
                    
                    $data['created'] = $this->timeToString(time()-strtotime($event['Event']['created'])).' trước'; 
                    
                    
                    // get type of event
                    $data['type'] = !$event['Event']['is_daily_coupon'] ? $event['Type']['name'] : 'Daily Coupon' ;
                    
                    // get address of event
                    $data['address'] = $event['Event']['address'].' - '.$event['City']['name'];
                    
                    // get hotline
                    $data['hotline'] = $event['Event']['hotline'];
                    
                    // get fee
                    if ($event['Event']['free']){
                        $data['fee'] = 'Miễn phí';
                    }else{
                        $data['fee'] = $event['Event']['fee'] ;
                    }
                    
                    // get image
                    if (isset($event['Event']['image_url'])){
                        $data['image'] = $event['Event']['image_url'];
                    }else{
                        $data['image'] = NO_IMG_URL;
                    }
                    
                    // get event description
                    $data['description'] = isset($event['Event']['description'])?$event['Event']['description']: '';
                    
                    $data['user_name'] = $event['User']['fullname'];
                    $data['owner_id'] = $event['User']['id'];
                    
                    $data['type_id'] = $event['Event']['type_id'];
                    $data['type_name'] = $event['Type']['name'];
                    $data['city_id'] = $event['Event']['city_id'];
                    $data['city_name'] = $event['City']['name'];
                    $data['is_daily_coupon'] = $event['Event']['is_daily_coupon'];
                    $data['avatar_url'] = $event['User']['avatar_url'];
                    $data['thanks'] = $event['Event']['thanks'];  
                    
                    // get summary iformation for event
                    $options = array();
                    $options['conditions'] = array(
                        'Participation.event_id' => $id
                    );
                    $this->loadModel('Participation');
                    $totalParticipation = $this->Participation->find('count', $options);
                    
                    
                    $participated = false;
                    if ($this->_isLogin()){
                        $options = array();
                        $options['conditions'] = array(
                            'Participation.event_id' => $id,
                            'Participation.user_id' => $this->_usersUserID()
                        );
                        $participated = $this->Participation->find('count', $options) > 0;
                    }
                    
                    // get string time for time count down
                    $timeCounDown = $startTimesObj->format('F') . ' ' . $startTimesObj->format('d') . ' ' . $startTimesObj->format('Y') . ' '. $startTimesObj->format('H'). ':'. $startTimesObj->format('i') . ':' . $startTimesObj->format('s');
                    
                    $this->set('data', $data);    
                    $this->set('hasEvent', true);
                    $this->set('totalParticipation', $totalParticipation);
                    $this->set('participated', $participated);
                    $this->set('timeCountDown', $timeCounDown);
                    $this->set('title',  $event['Event']['title']. ' - ' .'thamgia.net');
                    $this->set('type_id', $event['Event']['type_id']);
                    $this->set('type_name', $event['Type']['name']);
                    
                    // get event status
                    $current = new DateTime();
                    $event_status = STATUS_UP_COMING;
                    if ( ($event['Event']['start'] <= $current->format(TIME_FORMAT_MYSQL)) && ($event['Event']['end'] > $current->format(TIME_FORMAT_MYSQL) )){
                        $event_status = STATUS_ON_GOING;
                    }else if ( ($event['Event']['start'] < $current->format(TIME_FORMAT_MYSQL)) && ($event['Event']['end'] < $current->format(TIME_FORMAT_MYSQL) )){
                        $event_status = STATUS_END;                        
                    }
                    $this->set('eventStatus', $event_status);
                    
                    // set keyword and description
                    $hasTitles = explode(" ", $event['Event']['title']);
                    $metaKeywords = "";
                    $countWords = count($hasTitles);
                    for($i=1; $i< $countWords; $i+=2){
                        $metaKeywords = $metaKeywords . " " . $hasTitles[$i-1] . " " . $hasTitles[$i]. " , ";
                    }
                    $metaKeywords .= " sự kiện, khóa học, hội thảo, event";
                    
                    $this->set('meta_keywords', $metaKeywords);
                    $this->set('meta_description', $event['Event']['title'] . '-' . DEFAULT_META_DESCRIPTION . $this->Session->read(CITY_NAME));
                    
                }else{
                    $this->redirect(array('controller'=>'home', 'action'=>'index'));
                }                
            }else{
                $this->redirect(array('controller'=>'home', 'action'=>'index'));
            }
        }
        
        function edit($id = null){
            $this->isAuthEvent($id);
            $this->layout = "no_daily_coupon";
            
            $this->loadModel('Career');
            $careers = $this->Career->find('all');
            $this->set('careers', $careers);
            
            $this->Event->id = $id;
            $event = $this->Event->read();
            $old_img_url = $event['Event']['image_url'];
            $old_img_list_url = $event['Event']['image_list_url'];
            $old_img_thumb_url = $event['Event']['image_thumb_url'];
            
            if ($this->request->is("get")){
                // load list selection
                $event['Event']['start'] = date(TIME_FORMAT_CLIENT, strtotime($event['Event']['start']));
                $event['Event']['end'] = date(TIME_FORMAT_CLIENT, strtotime($event['Event']['end']));
                $this->set('data', $event['Event']);
            }
            else if ($this->request->is("post")){
                $error = null;
                $data = $this->request->data('Event');
                try{
                    $arrEvent['Event'] = array();
                    $arrEvent['Event']['id'] = $id;
                    $arrEvent['Event']['title'] = $data['title'];
                    $arrEvent['Event']['address'] = $data['address'];
                    $arrEvent['Event']['city_id'] = $data['city_id'];
                    $arrEvent['Event']['type_id'] = $data['type_id'];
                    $arrEvent['Event']['career_id'] = $data['career_id'];          
                    
                    $startEvent = $this->clientDateToTime($data['start']);
                    $endEvent = $this->clientDateToTime($data['end']);
                    if ($startEvent >= $endEvent){
                        $data['end'] = '';
                        throw new Exception("Vui lòng chọn lại thời gian kết thúc sự kiện!");
                    }
                    $arrEvent['Event']['start'] =  date(TIME_FORMAT_MYSQL, $startEvent); 
                    $arrEvent['Event']['end'] = date(TIME_FORMAT_MYSQL, $endEvent);
                    
                    $arrEvent['Event']['free'] = $data['free'];
                    if (!$data['free']){
                        if ($data['fee'] != DEFAULT_FEE)
                            $arrEvent['Event']['fee'] = $data['fee'];   
                    }
                    $arrEvent['Event']['hotline'] = $data['hotline'];
                    $arrEvent['Event']['description'] = $data['description'];
                    $arrEvent['Event']['updated'] = date(TIME_FORMAT_MYSQL);
                    
                    if ($data['image']['name'] != ''){
                        if ($dataUpload = $this->Uploader->upload($data['image'], array('overwrite' => true, 'name' => $this->generateCode()))) {
                            // Upload successful, do whatever
                            $resized_path = $this->Uploader->resize(array('width' => IMG_WIDTH));
                            $resized_path_thumb = $this->Uploader->resize(array('width' => DAILYCOUPON_THUMB_WIDTH, 'height' => DAILYCOUPON_THUMB_HEIGHT, 'aspect' => false));
                            $resized_path_list = $this->Uploader->resize(array('width' => IMG_LIST_WIDTH));
                            $this->Uploader->delete($dataUpload['path']);
                            $arrEvent['Event']['image_url'] = preg_replace('/^\//', '', $resized_path);
                            $arrEvent['Event']['image_thumb_url'] = preg_replace('/^\//', '', $resized_path_thumb);
                            $arrEvent['Event']['image_list_url'] = preg_replace('/^\//', '', $resized_path_list);
                            
                            $this->Uploader->delete($old_img_url);
                            $this->Uploader->delete($old_img_thumb_url);
                            $this->Uploader->delete($old_img_list_url);
                            
                        }else{         
                            throw new Exception('Lỗi upload ảnh!');
                        }    
                    }
                    
                    // change event to not approved
                    //if (!$this->_isAdmin())
                    $arrEvent['Event']['approved'] = false;
                  
                    $this->Event->save($arrEvent);
                    $this->redirect(array('controller'=>'Events', 'action'=>'addedEvents', $this->_usersUserID()));
                }catch (Exception $ex){
                        $data['id'] = $event['Event']['id'];
                        $data['image_url'] = $event['Event']['image_url'];
                        $this->set('data', $data);
                        $this->set('error', $ex->getMessage());    
                }
                    
            }
            
        }
        
        function delete($id = null){
            $this->isAuthEvent($id);
            
            // delete Participation
            $this->loadModel('Participation');
            $options['conditions'] = array(
                'Participation.event_id' => $id
            );
            $participations = $this->Participation->find('all', $options);
            foreach($participations as $participation){
                $this->Participation->delete($participation['Participation']['id']);
            }
            
            // delete likes
            $this->loadModel('Like');
            $options['conditions'] = array(
                'Like.event_id' => $id
            );
            $likes = $this->Like->find('all', $options);
            foreach($likes as $like){
                $this->Like->delete($like['Like']['id']);
            }
            
            // delete comments
            $this->loadModel('Comment');
            $options['conditions'] = array(
                'Comment.event_id' => $id
            );
            $comments = $this->Comment->find('all', $options);
            foreach($comments as $comment){
                $this->Comment->delete($comment['Comment']['id']);
            }
            
            // delete Event
            $this->Event->delete($id, true);
            if ($this->_isAdmin())
                $this->redirect(array('controller'=>'Admin', 'action'=>'approveEvents'));
            else
                $this->redirect(array('controller'=>'Events', 'action'=>'addedEvents'));
                
        }
        
        function approve($id){
            if ($id){
                $this->isAdminAuthenticated();
                
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
                    'Event.id' => $id
                );
                
                $options['fields'] = array(
                    'User.fullname',
                    'User.email',
                    'User.id',
                    'Event.id',
                    'Event.title',
                    'Event.approved',
                    'Event.city_id'
                );
                
                $event = $this->Event->find('first', $options);
                if (!$event['Event']['approved']){
                    $event['Event']['approved'] = true;
                    $this->Event->save($event);
                    $this->sendMailApprovedEvent($event['User']['email'], $event['Event']['id'], $event['User']['fullname'], $event['Event']['title']);
                    $this->logActivity($event['User']['id'], $event['Event']['id'], "vừa đăng sự kiện", false, $event['Event']['city_id']);
                };
                $this->redirect(array('controller'=>'admin', 'action'=>'approveEvents'));    
            }else{
                $this->flash('Event_ID không đúng!');
                $this->redirect(array('controller'=>'admin', 'action'=>'approveEvents'));    
            }

        }
        
        function isAuthEvent($id){
            $this->isAuthenticated();
            
            if ($id){
                $this->Event->id = $id;
                $event = $this->Event->read();
                $hasPermission = false;
                if (($this->_usersUserID() == $event['Event']['user_id']) ||  $this->_isAdmin()){
                    $hasPermission = true;
                }
                if (!$hasPermission){
                    $this->redirect(array('controller'=>'home', 'action'=>'index'));    
                }
            }else{
                $this->redirect(array('controller'=>'home', 'action'=>'index'));    
            }
        }
        
        function isAuthEventDetail($id){
            if ($id){
                $this->Event->id = $id;
                $event = $this->Event->read();
                
                $hasPermission = false;
                if (($this->_usersUserID() == $event['Event']['user_id']) ||  $this->_isAdmin()){
                    $hasPermission = true;
                }
                
                if (!$hasPermission){
                    if (!$event['Event']['approved'])
                        $this->redirect(array('controller'=>'home', 'action'=>'index'));    
                }
            }else{
                $this->redirect(array('controller'=>'home', 'action'=>'index'));    
            }    
        }
        
        function generateCode(){
            $continue = false;
            $code = '';
            do{
                $code = strtotime("now");
                $code = str_replace(".", "", $code);
                $code = substr($code, strlen($code) - 9, 9);    
                
                $options['conditions'] = array(
                    'Event.code' => $code
                );
                
                $count = $this->Event->find('count', $options);
                $continue = $count > 0;
            }while($continue);
            
            return $code;
        }
        
        
        
        function getEventsToday($city_id){
            $numberOfEvents = 5;
            $current = new DateTime();
            $current = new DateTime($current->format('Y-m-d'));
            $nextDay = new DateTime('+ 1 day');
            $nextDay = new DateTime($nextDay->format('Y-m-d'));
            $options['conditions'] = array(
                'Event.start >' => $current->format(TIME_FORMAT_MYSQL),
                'Event.start <' => $nextDay->format(TIME_FORMAT_MYSQL),
                'Event.city_id' => $city_id,
                'Event.approved ='=>true
            ); 
            $options['order'] = array('Event.start' => 'asc');
            $options['fields'] = array('Event.id','Event.title', 'Event.image_thumb_url', 'Event.image_url', 'Event.start');
            $events = $this->Event->find('all', $options);
            $data = array();
            if (count($events) > $numberOfEvents){
                
                $arrKey = array_rand($events, $numberOfEvents);
                $index=0;
                foreach($arrKey as $key){
                    $data[$index] = $events[$key];
                    $index++;
                }
                $data;
            } else{
                $data = $events;
            }
            
            for($i=0; $i<count($data); $i++){
                $data[$i]['Event']['start'] = $this->dateToVieFormat(new DateTime($data[$i]['Event']['start']));
            }
            return $data;
        }
        
        function getEventsInterested($city_id){
            $current = new DateTime();
            $options['conditions'] = array(
                'Event.start >' => $current->format(TIME_FORMAT_MYSQL),
                'Event.city_id' => $city_id,
                'Event.approved ='=>true
            );
            $options['limit'] = 5;
            $options['order'] = array('Event.views' => 'desc');
            $events = $this->Event->find('all', $options);
            
            for($i=0; $i<count($events); $i++){
                $events[$i]['Event']['start'] = $this->dateToVieFormat(new DateTime($events[$i]['Event']['start']));
            }
            return $events;
        }
        
        function getEventsLatest($city_id){
            $options['conditions'] = array(
                'Event.city_id' => $city_id,
                'Event.approved ='=>true
            );
            $options['limit'] = 5;
            $options['order'] = array('Event.created' => 'desc');
            $events = $this->Event->find('all', $options);
            
            for($i=0; $i<count($events); $i++){
                $events[$i]['Event']['start'] = $this->dateToVieFormat(new DateTime($events[$i]['Event']['start']));
            }
            return $events;    
        }
        
        function getEventsOther($city_id, $type_id, $event_id){
            $options['conditions'] = array(
                'Event.city_id =' => $city_id,
                'Event.type_id =' => $type_id,
                'Event.id !='=>$event_id,
                'Event.approved ='=>true,
                'Event.start >' => date(TIME_FORMAT_MYSQL)
            );
            $options['limit'] = 6;
            $options['order'] = array('Event.start' => 'asc');
            $events = $this->Event->find('all', $options);
            
            for($i=0; $i<count($events); $i++){
                $events[$i]['Event']['start'] = $this->dateToVieFormat(new DateTime($events[$i]['Event']['start']));
            }
            return $events;    
        }
        
        function search($city_id, $type_id, $from, $to, $title){
            $this->layout = "event";
            $this->set('type_id', $type_id);
            $search = array();
            //$search['free'] = $free;
            $search['from_date'] = $from;
            $search['to_date'] = $to;
            $search['title'] = $title;
            $this->set('search', $search);
                  
        }
        
        function sendMailApprovedEvent($email_address, $event_id, $user_name, $event_title){
            $this->loadModel('Email');
            $this->autoRender = false;
            $emailRecord['Email'] = array();
            $emailRecord['Email']['priority'] = PRIORITY_HIGH;
            
            $parameters = array();
            $parameters['subject'] = 'Thamgia.net - Duyệt sự kiện: ' . $event_title;
            $parameters['to_email'] = $email_address;
            $parameters['template'] = 'approve';
            
            $parameters['event_id'] = $event_id;
            $parameters['user_name'] = $user_name;
            $parameters['event_title'] = $event_title;
            
            $emailRecord['Email']['parameters'] = serialize($parameters);
            $this->Email->save($emailRecord);
        }
        
        function sendMailAddEvent($email_address, $event_id, $user_name, $event_title){
            $this->loadModel('Email');
            $this->autoRender = false;
            $emailRecord['Email'] = array();
            $emailRecord['Email']['priority'] = PRIORITY_HIGH;
            
            $parameters = array();
            $parameters['subject'] = 'Thamgia.net - '. $user_name .' vừa đăng sự kiện';
            $parameters['to_email'] = $email_address;
            $parameters['template'] = 'add_event';
            
            $parameters['event_id'] = $event_id;
            $parameters['user_name'] = $user_name;
            $parameters['event_title'] = $event_title;
            
            
            $emailRecord['Email']['parameters'] = serialize($parameters);
            
            $this->Email->save($emailRecord);
            
        }
        
        function shareFacebook($event_id, $event_title){
            $this->layout = "ajax";
            $this->set('event_id', $event_id);
            $this->set('event_title', $event_title);
        }
        
        function finishAddEvent(){
            $this->isAuthenticated();
            $this->layout = "no_column";
        }
        
        function getVoucherInformation($event_id){
            $this->autoRender = false;
            $this->isAuthenticated();
               
            $options['joins'] = array(
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
                'Event.id',
                'Event.city_id',
                'Event.title',
                'Event.start',
                'Event.end',
                'Event.address',
                'Event.free',
                'Event.fee',
                'Event.summary',
                'City.name'
            );
            
            $event = $this->Event->find('first', $options);
            
            $data = array();
            $data['title'] = $event['Event']['title'];
            $data['id'] = $event['Event']['id'];
            $data['fee'] = $event['Event']['fee'];
            $startTimesObj = new DateTime($event['Event']['start']);
            $data['start'] =  $startTimesObj->format('d/m/y'); 
            
            $endTimesObj = new DateTime($event['Event']['end']);
            $data['end'] =  $endTimesObj->format('d/m/y');
            
            $data['address'] = $event['Event']['address'].', '.$event['City']['name'];
            $data['code'] = General::generateVoucherCode(); 
            $data['summary'] = $event['Event']['summary'];
            
            return $data;
        }
        
        function getPdfVoucher($event_id){
            $this->isAuthenticated();
                           
            $options['joins'] = array(
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
                'Event.id',
                'Event.type_id',
                'Event.city_id',
                'Event.title',
                'Event.start',
                'Event.end',
                'Event.address',
                'Event.fee',
                'City.name',
                'Event.summary'
            );
            
            $event = $this->Event->find('first', $options);
            
            $data = array();
            $data['title'] = $event['Event']['title'];
            $data['fee'] = $event['Event']['fee'];
            $startTimesObj = new DateTime($event['Event']['start']);
            $data['start'] =  $startTimesObj->format('d/m/y'); 
            
            $endTimesObj = new DateTime($event['Event']['end']);
            $data['end'] =  $endTimesObj->format('d/m/y');
            
            $data['address'] = $event['Event']['address'].', '.$event['City']['name'];
            $data['code'] = General::generateVoucherCode();
            $data['summary'] = $event['Event']['summary'];
            $data['fee'] = $event['Event']['fee'];
            
            $this->set('event_title', $event['Event']['title']);
            $this->set('event_start', $data['start']);
            $this->set('event_end', $data['end']);
            $this->set('voucher_code', $data['code']);
            $this->set('event_address', $data['address']);
            $this->set('event_summary', $data['summary']);
            $this->set('event_fee', $data['fee']);
        }
        
        function printVoucher($event_id){
            $this->layout = 'ajax';
            $this->isAuthenticated();
                           
            $options['joins'] = array(
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
                'Event.id',
                'Event.type_id',
                'Event.city_id',
                'Event.title',
                'Event.start',
                'Event.end',
                'Event.address',
                'Event.free',
                'Event.fee',
                'City.name',
                'Event.summary'
            );
            
            $event = $this->Event->find('first', $options);
            
            $data = array();
            $data['title'] = $event['Event']['title'];
            $data['fee'] = $event['Event']['fee'];
            $startTimesObj = new DateTime($event['Event']['start']);
            $data['start'] =  $startTimesObj->format('d/m/y'); 
            
            $endTimesObj = new DateTime($event['Event']['end']);
            $data['end'] =  $endTimesObj->format('d/m/y');
            
            $data['address'] = $event['Event']['address'].', '.$event['City']['name'];
            $data['code'] = General::generateVoucherCode();
            
            $this->set('event_title', $event['Event']['title']);
            $this->set('event_start', $data['start']);
            $this->set('event_end', $data['end']);
            $this->set('voucher_code', $data['code']);
            $this->set('event_address', $data['address']);
            $this->set('event_summary', $event['Event']['summary']);
            $this->set('event_fee', $event['Event']['fee']);
        }
        
        function getDailyCoupon($city_id){
            $current = new DateTime();
            $current = new DateTime($current->format('Y-m-d'));
            $options['conditions'] = array(
                'Event.start <=' => $current->format(TIME_FORMAT_MYSQL),
                'Event.end >=' => $current->format(TIME_FORMAT_MYSQL),
                'Event.city_id' => $city_id,
                'Event.is_daily_coupon ='=>true
            );
            $options['fields'] = array(
                'Event.id',
                'Event.image_url',
                'Event.summary',
                'Event.title',
                'Event.fee',
                'Event.end'
            ); 
            $events = $this->Event->find('all', $options);
            shuffle($events);
            
            for($index=0; $index< count($events); $index++){
                $events[$index]['Event']['end'] = $this->dateToVieFormat(new DateTime($events[$index]['Event']['end']));
            }
            return $events;
        }
        
        function postDailyCoupon(){
            $this->autoRender = false;
            if ($this->request->is('post')){
                $data  = $this->request->data('DailyCoupon');
                $this->loadModel('User');
                $options['conditions'] = array('User.level' => LEVEL_ADMIN);
                $users = $this->User->find('all', $options);
                
                $this->loadModel('Email');
                
                $arrEmail = array();
                $emailRecord = array();
                $emailRecord['priority'] = PRIORITY_HIGH;
                $emailRecord['message'] = $data['content'];
                // setup email for admin
                $parameters = array();
                $parameters['subject'] = 'Thamgia.net - '. $data['user_name'] .' vừa đăng ký daily coupon';
                $parameters['template'] = 'admin_daily_coupon';
                $parameters['email'] = $data['email'];
                $parameters['mobile'] = $data['mobile'];
                $parameters['user_name'] = $data['user_name'];
                $parameters['group'] = $data['group'];
                $parameters['address'] = $data['address'] . " - " . $data['city'];
                
                
                foreach($users as $user){
                    $parameters['to_email'] = $user['User']['email'];
                    $parameters['admin_name'] = $user['User']['fullname'];
                    $emailRecord['parameters'] = serialize($parameters);
                    array_push($arrEmail, $emailRecord);
                }
                
                // email for user post daily coupon
                $parameters = array();
                $parameters['subject'] = 'Thamgia.net - Thông báo đăng ký daily coupon';
                $parameters['template'] = 'daily_coupon';
                $parameters['to_email'] = $data['email'];
                $parameters['user_name'] = $data['user_name'];
                $emailRecord['parameters'] = serialize($parameters);
                array_push($arrEmail, $emailRecord);
                
                // store email to queue
                $this->Email->saveAll($arrEmail);
            }
        }
        
        function getEvents($city_id, $limit = 6, $type_id = 0, $user_id = 0, $from = 0, $current_month){
            $this->autoRender = false;


            $options['limit'] = $limit;
            $conditions = array();
            $conditions['Event.city_id'] = $city_id;
            $conditions['Event.approved'] = true;
            $conditions['Event.is_daily_coupon'] = false;
            if ($current_month != 0){
                $firstDayOfMonth = new DateTime();
                $firstDayOfMonth->setTimestamp($current_month);

                $lastDayOfMonth = new DateTime();
                $lastDayOfMonth->setTimestamp($current_month);
                $lastDayOfMonth->modify('+1 month');

                $conditions['Event.start >='] = $firstDayOfMonth->format(TIME_FORMAT_MYSQL);
                $conditions['Event.start <'] = $lastDayOfMonth->format(TIME_FORMAT_MYSQL);

            }else{
                $conditions['Event.start >='] = date(TIME_FORMAT_MYSQL);
            }

            if ($type_id != 0){
                $conditions['Event.type_id'] = $type_id;
            }
            
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
                    ),
                    array(
                            'table' => 'pins_users',
                            'alias' => 'PinsUser',
                            'type' => 'LEFT',
                            'conditions' => array(
                                'PinsUser.event_id = Event.id',
                                'PinsUser.user_id' => $user_id
                            )
                    ),
                    array(
                            'table' => 'thanks_events',
                            'alias' => 'ThanksEvent',
                            'type' => 'LEFT',
                            'conditions' => array(
                                'ThanksEvent.event_id = Event.id',
                                'ThanksEvent.user_id' => $user_id
                            )
                    )
                );
            // order  load event promotion
            if ($type_id == TYPE_PROMOTION)
                $options['order'] = array('PinsUser.id' => 'desc' ,'Event.end' => 'asc');
            else
                $options['order'] = array('PinsUser.id' => 'desc' ,'Event.start' => 'asc');

            $options['fields'] = array('User.fullname', 'Event.title', 'Event.views'
                                        ,'Event.address', 'Event.image_url', 'Event.image_list_url' 
                                        ,'Event.start', 'Event.title', 'Event.id', 'Event.user_id'
                                        ,'PinsUser.id', 'PinsUser.event_id', 'Event.thanks', 'ThanksEvent.id');
            $data = $this->Event->find('all', $options);
            return $data;
        }
        
        function getSearchEvents($city_id, $limit = 6, $type_id = 0,  $from_date, $to_date, $title ,$user_id = 0, $from = 0){
            $this->autoRender = false;
            $options['limit'] = $limit;
            $conditions = array();
            $conditions['Event.city_id'] = $city_id;
            $conditions['Event.approved'] = true;
            $conditions['Event.is_daily_coupon'] = false;
            //$conditions['Event.free'] = $free;
            
            if ($type_id != 0)
                $conditions['Event.type_id ='] = $type_id;
            
            
            if ($from_date != '0'){
                $from_date = str_replace('-', '/', $from_date);
                $from_date .= ' 00:00';
                $conditions['Event.start >='] =  date(TIME_FORMAT_MYSQL, $this->clientDateToTime($from_date));
            }   
            if ($to_date != '0'){
                $to_date = str_replace('-', '/', $to_date);
                $to_date .= ' 23:59';
                $conditions['Event.start <='] =  date(TIME_FORMAT_MYSQL, $this->clientDateToTime($to_date));
            }
            
            if ($title != '0'){
                $conditions['Event.title LIKE ']  = '%'.$title.'%';
            }
            
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
                    ),
                    array(
                            'table' => 'pins_users',
                            'alias' => 'PinsUser',
                            'type' => 'LEFT',
                            'conditions' => array(
                                'PinsUser.event_id = Event.id',
                                'PinsUser.user_id' => $user_id
                            )
                    )
                );


            $options['fields'] = array('User.fullname', 'Event.title', 'Event.views'
                                        ,'Event.address', 'Event.image_url','Event.image_list_url'
                                        ,'Event.start', 'Event.title', 'Event.id', 'Event.user_id'
                                        ,'PinsUser.id', 'PinsUser.event_id', 'Event.thanks');
            $data = $this->Event->find('all', $options);
            return $data;
        }
        
        function moreEvents(){
            $this->autoRender = true;
            $city_id =  isset($this->request->query['city_id']) ? $this->request->query['city_id'] : 0;
            $limit = isset($this->request->query['limit']) ? $this->request->query['limit'] : 6;
            $type_id = isset($this->request->query['type_id']) ? $this->request->query['type_id'] : 0;
            $user_id = isset($this->request->query['user_id']) ? $this->request->query['user_id'] : 0;
            $month = isset($this->request->query['month']) ? $this->request->query['month'] : 0;
            $from = isset($this->request->query['from'])? $this->request->query['from']  + 1 : 0;
            $data = $this->getEvents($city_id, $limit, $type_id, $user_id, $from, $month);
            $this->layout = "ajax";
            $this->autoRender = true;
            $this->set('data', $data);
            $this->set('from', $from);
        }
        
        function moreSearchEvents(){
            $this->autoRender = true;
            $city_id =  isset($this->request->query['city_id']) ? $this->request->query['city_id'] : 0;
            $limit = isset($this->request->query['limit']) ? $this->request->query['limit'] : 6;
            $type_id = isset($this->request->query['type_id']) ? $this->request->query['type_id'] : 0;
            $user_id = isset($this->request->query['user_id']) ? $this->request->query['user_id'] : 0;
            $from = isset($this->request->query['from'])? $this->request->query['from']  + 1 : 0;
            //$free = isset($this->request->query['free']) ? $this->request->query['free'] : true;
            $from_date = isset($this->request->query['from_date']) ? $this->request->query['from_date'] : '0';
            $to_date = isset($this->request->query['to_date']) ? $this->request->query['to_date'] : '0';
            $title = isset($this->request->query['title']) ? $this->request->query['title'] : '0';
            $data = $this->getSearchEvents($city_id, $limit, $type_id, $from_date, $to_date, $title, $user_id ,$from  );
            $this->layout = "ajax";
            $this->autoRender = true;
            $this->set('data', $data);
            $this->set('from', $from);
            $this->render("more_events");
        }


                                     
     } 
?>
