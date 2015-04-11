<?php
    CakePlugin::load('Uploader');
    App::import('Vendor', 'Uploader.Uploader');
    class AdminController extends AppController{
        public $helpers = array('Fck', 'Html', 'QrCode');
        
        function beforeFilter(){
            parent::beforeFilter();
            $this->layout = "admin";
            
            $this->set('page_title', 'Admin');
            
            $this->Uploader = new Uploader(array('tempDir' => TMP, 'uploadDir' => FOLDER_UPLOAD_BANNER)); 
        }
        
        function approveEvents(){
            $this->isAdminAuthenticated();
            
            $this->set('page_title', 'Duyệt Sự Kiện');
            $this->loadModel('Event');
            
            $this->paginate = array(
                'Event' => array(
                    'limit' => DEFAULT_SIZE,
                    'order' => array('Event.created' => 'desc'),
                    'conditions' => array(
                                    'Event.approved =' => false,
                                    'Event.is_daily_coupon ='=> false)
                )
            );
            
            $dataPaginate = $this->paginate('Event');
            $data = array();
            $index = 0;

            foreach($dataPaginate as $event){
                $data[$index] = array();
                $options['conditions'] = array(
                        'City.id' => $event['Event']['city_id']
                    );
                $city = $this->City->find('first', $options);
                $options['conditions'] = array(
                    'Type.id' => $event['Event']['type_id']
                );
                $type = $this->Type->find('first', $options);
                
                $options['conditions'] = array(
                    'User.id' => $event['Event']['user_id']
                );
                $this->loadModel('User');
                $user = $this->User->find('first', $options);
                
                $data[$index]['title'] = $event['Event']['title'];
                $data[$index]['id'] = $event['Event']['id'];

                $createdDateObj = new DateTime($event['Event']['created']);
                
                $updatedDateObj = null;
                if ($event['Event']['updated'] != null){
                    $updatedDateObj = new DateTime($event['Event']['updated']);
                }
                
                
                $data[$index]['created'] = $createdDateObj->format(TIME_FORMAT_CLIENT);
                $data[$index]['city'] = $city['City']['name'];
                $data[$index]['type'] = $type['Type']['name'];
                $data[$index]['user']= $user['User']['fullname'];
                $data[$index]['updated'] = $updatedDateObj != null ? $updatedDateObj->format(TIME_FORMAT_CLIENT) : null;
                $index++;
            }
            $this->set('data', $data);
        } 
        
        function events(){
            $this->isAdminAuthenticated();
            
            $this->set('page_title', 'Duyệt Sự Kiện');
            $this->loadModel('Event');
            
            if (isset($this->request->query['city_id']))
                $city_id = $this->request->query['city_id'];
            else
                $city_id = 0;
            
            if (isset($this->request->query['title']))
                $title  = urldecode($this->request->query['title']);
            else
                $title = '';
            
            // condition
            $conditions = array();
            $conditions['Event.approved']  = true;
            $conditions['Event.is_daily_coupon']  = false;
            
            if ($city_id != 0)
                $conditions['Event.city_id'] = $city_id;
            if ($title != '')
                $conditions['Event.title LIKE ']  = '%'.$title.'%';
            
            $this->paginate = array(
                'Event' => array(
                    'limit' => DEFAULT_SIZE,
                    'order' => array('Event.created' => 'desc'),
                    'conditions' => $conditions
                )
            );
            
            $dataPaginate = $this->paginate('Event');
            $data = array();
            $index = 0;

            foreach($dataPaginate as $event){
                $data[$index] = array();
                $options['conditions'] = array(
                        'City.id' => $event['Event']['city_id']
                    );
                $city = $this->City->find('first', $options);
                $options['conditions'] = array(
                    'Type.id' => $event['Event']['type_id']
                );
                $type = $this->Type->find('first', $options);
                
                $options['conditions'] = array(
                    'User.id' => $event['Event']['user_id']
                );
                $this->loadModel('User');
                $user = $this->User->find('first', $options);
                
                $data[$index]['title'] = $event['Event']['title'];
                $data[$index]['id'] = $event['Event']['id'];

                $createdDateObj = new DateTime($event['Event']['created']);
                
                
                $data[$index]['created'] = $createdDateObj->format(TIME_FORMAT_CLIENT);
                $data[$index]['city'] = $city['City']['name'];
                $data[$index]['type'] = $type['Type']['name'];
                $data[$index]['user']= $user['User']['fullname'];
                
                $index++;
            }
            $this->set('data', $data);   
            $this->set('search_title', $title);
        }
        
        function highlights(){
            $this->isAdminAuthenticated();
            
            $this->set('page_title', 'Sự kiện nổi bật');    
            
            $this->paginate = array(
                'Highlight' => array(
                    'limit' => DEFAULT_SIZE,
                    'order' => array('Highlight.id' => 'desc')
                )
            );
            
            $dataPaginate = $this->paginate('Highlight');

            $this->set('data', $dataPaginate);
        }
        
        function addHighlight(){
            $this->isAdminAuthenticated();
            
            $this->set('page_title', 'Thêm sự kiện nổi bật');
            if ($this->request->is('post')){
                $error = null;
                $data  = $this->request->data('Highlight');
                $highLight['Highlight'] = array();
                $highLight['Highlight']['title'] = $data['title'];
                $highLight['Highlight']['start'] = date(TIME_FORMAT_MYSQL, $this->clientDateToTime($data['start'])); 
                $highLight['Highlight']['end'] = date(TIME_FORMAT_MYSQL, $this->clientDateToTime($data['end']));
                $highLight['Highlight']['start_event'] = date(TIME_FORMAT_MYSQL, $this->clientDateToTime($data['start_event']));
                $highLight['Highlight']['free'] = isset($data['free']) ? true : false;
                $highLight['Highlight']['event_url'] = $data['event_url'];
                $highLight['Highlight']['city_id'] = $data['city_id'];
                if ($data['image']['name']!= ''){
                    $arrFormData = array();
                    $arrFormData[0]=$data['image'];
                    $filesOk = $this->uploadFiles(FOLDER_UPLOAD_IMAGE_EVENT, $arrFormData);
                    
                    if (array_key_exists('urls', $filesOk)){
                        $highLight['Highlight']['image_url'] = $filesOk['urls'][0];
                    }else{
                        foreach($filesOk['errors'] as $itemError)
                            $error .=  $itemError.'<br />';
                    }
                }
                if ($error != null){ // has error
                    $this->set('data', $data);
                    $this->Session->setFlash($error, true);
                    
                }
                else{
                    $this->Highlight->save($highLight);
                    $this->redirect(array('controller'=>'Admin', 'action'=>'highlights'));
                }
            }
        }
        
        function editHighLight($id){
            $this->isAdminAuthenticated();
            $this->set('page_title', 'Thêm sự kiện nổi bật');
            
            $this->Highlight->id = $id;
            $highlight = $this->Highlight->read();
            
            if ($this->request->is("get")){
                $highlight['Highlight']['start'] = date(TIME_FORMAT_CLIENT, strtotime($highlight['Highlight']['start']));
                $highlight['Highlight']['end'] = date(TIME_FORMAT_CLIENT, strtotime($highlight['Highlight']['end']));
                $highlight['Highlight']['start_event'] = date(TIME_FORMAT_CLIENT, strtotime($highlight['Highlight']['start_event']));
                $this->set('data', $highlight['Highlight']);
            }else if ($this->request->is("post")){
                $error = null;
                $data  = $this->request->data('Highlight');
                $highLight['Highlight'] = array();
                $highLight['Highlight']['id'] = $id;
                $highLight['Highlight']['title'] = $data['title'];
                $highLight['Highlight']['start'] = date(TIME_FORMAT_MYSQL, $this->clientDateToTime($data['start'])); 
                $highLight['Highlight']['end'] = date(TIME_FORMAT_MYSQL, $this->clientDateToTime($data['end']));
                $highLight['Highlight']['start_event'] = date(TIME_FORMAT_MYSQL, $this->clientDateToTime($data['start_event']));
                $highLight['Highlight']['free'] = isset($data['free']) ? true : false;
                $highLight['Highlight']['event_url'] = $data['event_url'];
                $highLight['Highlight']['city_id'] = $data['city_id'];
                if ($data['image']['name']!= ''){
                    $arrFormData = array();
                    $arrFormData[0]=$data['image'];
                    $filesOk = $this->uploadFiles(FOLDER_UPLOAD_IMAGE_EVENT, $arrFormData);
                    
                    if (array_key_exists('urls', $filesOk)){
                        $highLight['Highlight']['image_url'] = $filesOk['urls'][0];
                    }else{
                        foreach($filesOk['errors'] as $itemError)
                            $error .=  $itemError.'<br />';
                    }
                }
                if ($error != null){ // has error
                    $this->set('data', $data);
                    $this->Session->setFlash($error, true);
                    
                }
                else{
                    $this->Highlight->save($highLight);
                    $this->redirect(array('controller'=>'Admin', 'action'=>'highlights'));
                }   
            }  
        }
        
        function deleteHighLight($id){
            $this->isAdminAuthenticated();
            $this->Highlight->delete($id, true);
            $this->redirect(array('controller'=>'Admin', 'action'=>'highlights'));   
        }
        
        function advertisements(){
            $this->isAdminAuthenticated();
            
            $this->loadModel('Side');
            $this->loadModel('Size');
            $this->loadModel('Advertisement');
            
            $this->set('page_title', 'Quảng cáo');
            
            $this->paginate = array(
                'Advertisement' => array(
                    'limit' => DEFAULT_SIZE,
                    'order' => array('Advertisement.id' => 'desc')
                )
            );
            
            $dataPaginate = $this->paginate('Advertisement');
            
            $data = array();
            $index = 0;

            foreach($dataPaginate as $advertisement){
                $data[$index]['Advertisement'] = array();
                $options['conditions'] = array(
                        'Side.id' => $advertisement['Advertisement']['side_id']
                );
                $side = $this->Side->find('first', $options);
    
                $options['conditions'] = array(
                    'Size.id' => $advertisement['Advertisement']['size_id']
                );
                $size = $this->Size->find('first', $options);
                
                $options['conditions'] = array(
                    'City.id' => $advertisement['Advertisement']['city_id']
                );
                $city = $this->City->find('first', $options);
                
                $data[$index]['Advertisement']['id'] = $advertisement['Advertisement']['id'];
                $data[$index]['Advertisement']['information'] = $advertisement['Advertisement']['information'];
                $data[$index]['Advertisement']['start'] = TimeFormat::ClientFormat($advertisement['Advertisement']['start']);
                $data[$index]['Advertisement']['end'] = TimeFormat::ClientFormat($advertisement['Advertisement']['end']);
                $data[$index]['Advertisement']['side'] = $side['Side']['name'];
                $data[$index]['Advertisement']['size'] = $size['Size']['name'];
                $data[$index]['Advertisement']['url'] = $advertisement['Advertisement']['url'];
                $data[$index]['Advertisement']['city'] = $city['City']['name'];
                $index++;
            }

            $this->set('data', $data);
            
        }
        
        function addAdvertisement(){
            $this->isAdminAuthenticated();
            $this->loadModel('Side');
            $this->loadModel('Size');
            $this->loadModel('Advertisement');
            
            $this->set('page_title', 'Thêm quảng cáo');    
            
            $sizes = $this->Size->find('all');
            $this->set('sizes', $sizes);
            
            $sides = $this->Side->find('all');
            $this->set('sides', $sides);
            
            if ($this->request->is('post')){
                $error = null;
                $data  = $this->request->data('Advertisement');
                
                $advertisement['Advertisement'] = array();
                $advertisement['Advertisement']['start'] = date(TIME_FORMAT_MYSQL, $this->clientDateToTime($data['start'])); 
                $advertisement['Advertisement']['end'] = date(TIME_FORMAT_MYSQL, $this->clientDateToTime($data['end']));    
                $advertisement['Advertisement']['side_id'] = $data['side_id'];
                $advertisement['Advertisement']['size_id'] = $data['size_id'];
                $advertisement['Advertisement']['information'] = $data['information'];
                $advertisement['Advertisement']['url'] = $data['url'];
                $advertisement['Advertisement']['city_id'] = $data['city_id'];
                if ($data['banner']['name'] != ''){
                    if ($dataUpload = $this->Uploader->upload($data['banner'], array('overwrite' => true))) {
                        // get size
                        $options['conditions'] = array(
                            'Size.id' => $data['size_id']
                        );
                        $currentSize = $this->Size->find('first', $options);
                        // Upload successful, do whatever
                        $resized_path = $this->Uploader->resize(array('width' => $currentSize['Size']['width'], 'height' => $currentSize['Size']['height']));
                        $this->Uploader->delete($dataUpload['path']);
                        $advertisement['Advertisement']['banner_url'] = preg_replace('/^\//', '', $resized_path);
                    }else{
                        $error .= 'Lỗi upload ảnh!<br />';
                    }        
                }else{
                    $error .= 'Vui lòng upload banner!';
                }
                
                if ($error != null){ // has error
                    $this->set('data', $data);
                    $this->set('error', $error);
                }
                else{
                    $this->Advertisement->save($advertisement);
                    $this->redirect(array('controller'=>'Admin', 'action'=>'advertisements'));
                }
            }
        }
        
        function editAdvertisement($id){
            $this->isAdminAuthenticated();
            $this->loadModel('Side');
            $this->loadModel('Size');
            $this->loadModel('Advertisement');
            
            $this->set('page_title', 'Chỉnh sửa quảng cáo');
            
            $sizes = $this->Size->find('all');
            $this->set('sizes', $sizes);
            
            $sides = $this->Side->find('all');
            $this->set('sides', $sides);
            
            $this->Advertisement->id = $id;
            $advertisement = $this->Advertisement->read();
            $old_banner_url = $advertisement['Advertisement']['banner_url'];
            
            if ($this->request->is("get")){
                $advertisement['Advertisement']['start'] = date(TIME_FORMAT_CLIENT, strtotime($advertisement['Advertisement']['start']));
                $advertisement['Advertisement']['end'] = date(TIME_FORMAT_CLIENT, strtotime($advertisement['Advertisement']['end']));
                $this->set('data', $advertisement['Advertisement']);
            }else if ($this->request->is("post")){
                $error = null;
                $data  = $this->request->data('Advertisement');
                
                $advertisement['Advertisement'] = array();
                $advertisement['Advertisement']['id'] = $id;
                $advertisement['Advertisement']['start'] = date(TIME_FORMAT_MYSQL, $this->clientDateToTime($data['start'])); 
                $advertisement['Advertisement']['end'] = date(TIME_FORMAT_MYSQL, $this->clientDateToTime($data['end']));    
                $advertisement['Advertisement']['side_id'] = $data['side_id'];
                $advertisement['Advertisement']['size_id'] = $data['size_id'];
                $advertisement['Advertisement']['information'] = $data['information'];
                $advertisement['Advertisement']['url'] = $data['url'];
                $advertisement['Advertisement']['city_id'] = $data['city_id'];
                if ($data['banner']['name'] != ''){
                    if ($dataUpload = $this->Uploader->upload($data['banner'], array('overwrite' => true))) {
                        // get size
                        $options['conditions'] = array(
                            'Size.id' => $data['size_id']
                        );
                        $currentSize = $this->Size->find('first', $options);
                        // Upload successful, do whatever
                        $resized_path = $this->Uploader->resize(array('width' => $currentSize['Size']['width'], 'height' => $currentSize['Size']['height']));
                        $this->Uploader->delete($dataUpload['path']);
                        $advertisement['Advertisement']['banner_url'] = preg_replace('/^\//', '', $resized_path);
                        $this->Uploader->delete($old_banner_url);
                    }else{
                        $error .= 'Lỗi upload ảnh!<br />';
                    }        
                }
                
                if ($error != null){ // has error
                    $data['banner_url'] = $old_banner_url;
                    $this->set('data', $data);
                    $this->set('error', $error);
                }
                else{
                    $this->Advertisement->save($advertisement);
                    $this->redirect(array('controller'=>'Admin', 'action'=>'advertisements'));
                }            
            }    
        }
        
        function deleteAdvertisement($id){
            $this->isAdminAuthenticated();
            $this->loadModel('Advertisement');
            $this->Advertisement->delete($id, true);
            $this->redirect(array('controller'=>'Admin', 'action'=>'advertisements'));   
        }
        
        function users(){
            $this->isAdminAuthenticated();
            $this->set('page_title', 'Users');
            
             if (isset($this->request->query['city_id']))
                $city_id = $this->request->query['city_id'];
            else
                $city_id = 0;
            
            if (isset($this->request->query['email']))
                $email  = urldecode($this->request->query['email']);
            else
                $email = '';
                
            if (isset($this->request->query['name']))
                $name  = urldecode($this->request->query['name']);
            else
                $name = '';
            
            // condition
            $conditions = array();
            
            if ($city_id != 0)
                $conditions['User.cityid'] = $city_id;
            if ($name != '')
                $conditions['User.fullname LIKE ']  = '%'.$name.'%';
            if ($email != '')
                $conditions['User.email LIKE ']  = '%'.$email.'%';
            
            $this->loadModel('Career');
            $this->loadModel('City');
            $this->loadModel('Role');
            
            $countUser = $this->User->find('count');
            $this->set('countUser', $countUser);
            
            $this->paginate = array(
                'User' => array(
                    'limit' => DEFAULT_SIZE,
                    'order' => array('User.id' => 'desc'),
                    'conditions' => $conditions
                )
            );
            
            $dataPaginate = $this->paginate('User');
            $data = array();
            $index = 0;
            
            foreach($dataPaginate as $user){
                $data[$index]['User'] = array();
                
                $options['conditions'] = array(
                        'Career.id' => $user['User']['careerid']
                );
                $career = $this->Career->find('first', $options);
                
                $options['conditions'] = array(
                    'City.id' => $user['User']['cityid']
                );
                $city = $this->City->find('first', $options);
                
                $options['conditions'] = array(
                    'Role.id' => $user['User']['level']
                );
                $role = $this->Role->find('first', $options);
                
                $data[$index]['User']['id'] = $user['User']['id'];
                $data[$index]['User']['city'] =  $city['City']['name'];
                $data[$index]['User']['career'] = $career['Career']['name'];
                $data[$index]['User']['level'] = $role['Role']['name'];
                $data[$index]['User']['email'] = $user['User']['email'];
                $data[$index]['User']['fullname'] = $user['User']['fullname'];
                $data[$index]['User']['avatar_url'] = $user['User']['avatar_url'];
                $index++;
            }
  
            $this->set('data', $data);
            $this->set('search_email', $email);
            $this->set('search_name', $name);
        }
        
        function addUser(){
            $this->isAdminAuthenticated();
            
            $this->loadModel('User');
            $this->loadModel('Role');
            $roles = $this->Role->find('all');
            $this->set('roles', $roles);   
            
            $this->loadModel('Career');
            $careers = $this->Career->find('all');
            $this->set('careers', $careers); 
            
             if ($this->request->is('post')){
                $error = null;
                $data  = $this->request->data('User');
                
                $options['conditions'] = array(
                        'User.email' => $data['email']
                );
                $countEmail = $this->User->find('count', $options);
                if ($countEmail > 0){
                    $error = '- Email '. $data['email']. ' đã có người khác sử dụng';
                    $data['email'] = '';
                }
                
                if ($error == null){
                    $arrUser['User'] = array();
                    $arrUser['User']['email'] = $data['email'];
                    $arrUser['User']['password'] = Security::hash(DEFAULT_PASSWORD, 'md5', true);
                    $arrUser['User']['fullname'] = $data['fullname'];
                    $arrUser['User']['cityid'] = $data['cityid'];
                    $arrUser['User']['careerid'] = $data['careerid'];
                    $arrUser['User']['level'] = $data['level'];
                    if ($data['avatar']['name'] != ''){
                        if ($dataUpload = $this->Uploader->upload($data['avatar'], array('overwrite' => true))) {
                            // Upload successful, do whatever
                            $resized_path = $this->Uploader->resize(array('width' => AVATAR_WIDTH, 'height' => AVATAR_HEIGHT));
                            $this->Uploader->delete($dataUpload['path']);
                            $arrUser['User']['avatar_url'] = preg_replace('/^\//', '', $resized_path);
                        }else{
                            $error .= 'Lỗi upload ảnh!<br />';
                        }        
                    }
                }
                
                if ($error != null){ // has error
                    $this->set('data', $data);
                    $this->set('error', $error);
                }
                else{
                    $this->User->save($arrUser);
                    $this->redirect(array('controller'=>'Admin', 'action'=>'users'));
                }
            }  
        }
        
        function editUser($user_id){
            $this->isAdminAuthenticated();
            
            $this->loadModel('User');
            $this->loadModel('Role');
            $roles = $this->Role->find('all');
            $this->set('roles', $roles);   
            
            $this->loadModel('Career');
            $careers = $this->Career->find('all');
            $this->set('careers', $careers);
            
            $this->User->id = $user_id;
            $user = $this->User->read();
            $old_avatar_url = $user['User']['avatar_url'];
            
             if ($this->request->is("get")){
                $this->set('data', $user['User']);
            }else if ($this->request->is("post")){
                $error = null;
                $data  = $this->request->data('User');
                if ($error == null){
                    $arrUser['User'] = array();
                    $arrUser['User']['email'] = $data['id'];
                    $arrUser['User']['email'] = $data['email'];
                    $arrUser['User']['password'] = Security::hash(DEFAULT_PASSWORD, 'md5', true);
                    $arrUser['User']['fullname'] = $data['fullname'];
                    $arrUser['User']['cityid'] = $data['cityid'];
                    $arrUser['User']['careerid'] = $data['careerid'];
                    $arrUser['User']['level'] = $data['level'];
                    if ($data['avatar']['name'] != ''){
                        if ($dataUpload = $this->Uploader->upload($data['avatar'], array('overwrite' => true))) {
                            // Upload successful, do whatever
                            $resized_path = $this->Uploader->resize(array('width' => AVATAR_WIDTH, 'height' => AVATAR_HEIGHT));
                            $this->Uploader->delete($dataUpload['path']);
                            $arrUser['User']['avatar_url'] = preg_replace('/^\//', '', $resized_path);
                        }else{
                            $error .= 'Lỗi upload ảnh!<br />';
                        }        
                    }
                }
                
                if ($error != null){ // has error
                    $this->set('data', $data);
                    $this->set('error', $error);
                }
                else{
                    $this->User->save($arrUser);
                    $this->redirect(array('controller'=>'Admin', 'action'=>'users'));
                }
            }
               
        }
        
        function deleteUser($user_id){
            $this->isAdminAuthenticated();
            $this->loadModel('User');
            $this->User->delete($user_id, true);
            $this->redirect(array('controller'=>'Admin', 'action'=>'users'));
        }
        
        function dailyCoupon(){
            $this->isAdminAuthenticated();
            $this->set('page_title', 'Daily Coupon');
            
            $this->loadModel('DailyCoupon');   
            
            $this->paginate = array(
                'DailyCoupon' => array(
                    'limit' => DEFAULT_SIZE,
                    'order' => array('DailyCoupon.created' => 'desc')
                )
            );
            
            $dataPaginate = $this->paginate('DailyCoupon');
            $data = array();
            $index = 0;
            
            foreach($dataPaginate as $dailyCoupon){
                $data[$index] = array();
                $options['conditions'] = array(
                        'City.id' => $dailyCoupon['DailyCoupon']['city_id']
                    );
                $city = $this->City->find('first', $options);
                
                $data[$index]['title'] = $dailyCoupon['DailyCoupon']['title'];
                $data[$index]['id'] = $dailyCoupon['DailyCoupon']['id'];

                $createdDateObj = new DateTime($dailyCoupon['DailyCoupon']['created']);
                
                
                $data[$index]['created'] = $createdDateObj->format(TIME_FORMAT_CLIENT);
                $data[$index]['city'] = $city['City']['name'];

                $index++;
            }
            $this->set('data', $data);   
        }
        
        function addDailyCoupon(){
            $this->isAdminAuthenticated();
            $this->Uploader = new Uploader(array('tempDir' => TMP, 'uploadDir' => FOLDER_UPLOAD_DAILY_COUPON)); 
            
            $this->set('page_title', 'Thêm daily coupon');
            $this->loadModel('DailyCoupon');  
            if ($this->request->is('post')){
                $error = null;
                $data  = $this->request->data('DailyCoupon');
                $dailyCoupon['DailyCoupon'] = array();
                $dailyCoupon['DailyCoupon']['title'] = $data['title'];
                $dailyCoupon['DailyCoupon']['start'] = date(TIME_FORMAT_MYSQL, $this->clientDateToTime($data['start'])); 
                $dailyCoupon['DailyCoupon']['end'] = date(TIME_FORMAT_MYSQL, $this->clientDateToTime($data['end']));
                $dailyCoupon['DailyCoupon']['discount'] = $data['discount'];
                $dailyCoupon['DailyCoupon']['hotline'] = $data['hotline'];
                $dailyCoupon['DailyCoupon']['city_id'] = $data['city_id'];
                $dailyCoupon['DailyCoupon']['description'] = $data['description'];
                $dailyCoupon['DailyCoupon']['address'] = $data['address'];
                $dailyCoupon['DailyCoupon']['summary'] = $data['summary'];
                $dailyCoupon['DailyCoupon']['user_id'] = $this->_usersUserID();
                $dailyCoupon['DailyCoupon']['free'] = false;
                $dailyCoupon['DailyCoupon']['code'] = $this->generateDailyCouponCode();
                if ($data['image']['name']!= ''){
                    if ($dataUpload = $this->Uploader->upload($data['image'], array('overwrite' => true, 'name' => $dailyCoupon['DailyCoupon']['code']))) {
                        // Upload successful, do whatever
                        $resized_path = $this->Uploader->resize(array('width' => IMG_WIDTH));
                        $resized_path_thumb = $this->Uploader->resize(array('width' => DAILYCOUPON_THUMB_WIDTH, 'height' => DAILYCOUPON_THUMB_HEIGHT, 'aspect' => false));
                        $this->Uploader->delete($dataUpload['path']);
                        $dailyCoupon['DailyCoupon']['image_url'] = preg_replace('/^\//', '', $resized_path);
                        $dailyCoupon['DailyCoupon']['image_thumb_url'] = preg_replace('/^\//', '', $resized_path_thumb);
                    }else{
                        $error .=  'Lỗi upload ảnh!'.'<br />';
                    }
                }
                if ($error != null){ // has error
                    $this->set('data', $data);
                    $this->Session->setFlash($error, true);
                }
                else{
                    $this->DailyCoupon->save($dailyCoupon);
                    $this->redirect(array('controller'=>'Admin', 'action'=>'dailyCoupon'));
                }
            }       
        }
        
        function editDailyCoupon($id){
            $this->isAdminAuthenticated();
            $this->set('page_title', 'Thêm sự kiện nổi bật');
            $this->loadModel('DailyCoupon');
            $this->DailyCoupon->id = $id;
            $dailyCoupon = $this->DailyCoupon->read();
            
            if ($this->request->is("get")){
                $dailyCoupon['DailyCoupon']['start'] = date(TIME_FORMAT_CLIENT, strtotime($dailyCoupon['DailyCoupon']['start']));
                $dailyCoupon['DailyCoupon']['end'] = date(TIME_FORMAT_CLIENT, strtotime($dailyCoupon['DailyCoupon']['end']));
                $this->set('data', $dailyCoupon['DailyCoupon']);
            }else if ($this->request->is("post")){
                $error = null;
                $this->Uploader = new Uploader(array('tempDir' => TMP, 'uploadDir' => FOLDER_UPLOAD_DAILY_COUPON)); 
                $data  = $this->request->data('DailyCoupon');
                $dailyCoupon['DailyCoupon'] = array();
                $dailyCoupon['DailyCoupon']['id'] = $id;
                $dailyCoupon['DailyCoupon']['title'] = $data['title'];
                $dailyCoupon['DailyCoupon']['start'] = date(TIME_FORMAT_MYSQL, $this->clientDateToTime($data['start'])); 
                $dailyCoupon['DailyCoupon']['end'] = date(TIME_FORMAT_MYSQL, $this->clientDateToTime($data['end']));
                $dailyCoupon['DailyCoupon']['discount'] = $data['discount'];
                $dailyCoupon['DailyCoupon']['hotline'] = $data['hotline'];
                $dailyCoupon['DailyCoupon']['city_id'] = $data['city_id'];
                $dailyCoupon['DailyCoupon']['description'] = $data['description'];
                $dailyCoupon['DailyCoupon']['summary'] = $data['summary'];
                $dailyCoupon['DailyCoupon']['address'] = $data['address'];
                if ($data['image']['name']!= ''){
                    if ($dataUpload = $this->Uploader->upload($data['image'], array('overwrite' => true, 'name' => $data['code']))) {
                        // Upload successful, do whatever
                        $resized_path = $this->Uploader->resize(array('width' => IMG_WIDTH));
                        $resized_path_thumb = $this->Uploader->resize(array('width' => DAILYCOUPON_THUMB_WIDTH, 'height' => DAILYCOUPON_THUMB_HEIGHT, 'aspect' => false));
                        $this->Uploader->delete($dataUpload['path']);
                        $this->Uploader->delete($data['image_url']);
                        $this->Uploader->delete($data['image_thumb_url']);
                        
                        $dailyCoupon['DailyCoupon']['image_url'] = preg_replace('/^\//', '', $resized_path);
                        $dailyCoupon['DailyCoupon']['image_thumb_url'] = preg_replace('/^\//', '', $resized_path_thumb);
                    }else{
                        $error .=  'Lỗi upload ảnh!'.'<br />';
                    }
                }
                if ($error != null){ // has error
                    $this->set('data', $data);
                    $this->Session->setFlash($error, true);
                    
                }
                else{
                    $this->DailyCoupon->save($dailyCoupon);
                    $this->redirect(array('controller'=>'Admin', 'action'=>'dailyCoupon'));
                }
            }     
        }
        
        function deleteDailyCoupon($id){
            $this->isAdminAuthenticated();
            $this->loadModel('DailyCoupon');
            $this->DailyCoupon->delete($id, true);
            $this->redirect(array('controller'=>'Admin', 'action'=>'dailyCoupon'));     
        }
           
        function sendEmails(){
            $this->isAdminAuthenticated();
            $this->set('page_title', 'Send Emails');   
            if ($this->request->is('post')){
                $this->loadModel('Email');
                $data  = $this->request->data('Email'); 
                $toEmails = str_replace(' ', '', $data['toEmails']);
                $toEmails = explode(',', $toEmails);
                
                $parameters = array();
                $parameters['subject'] = $data['subject'];
                $parameters['template'] = 'admin_send_email';
                
                
                foreach($toEmails as $emailAddress){
                    $parameters['to_email'] = $emailAddress;
                    $emailRecord['Email'] = array();
                    $emailRecord['Email']['priority'] = PRIORITY_HIGH;
                    $emailRecord['Email']['parameters'] = serialize($parameters);
                    $emailRecord['Email']['message'] = $data['content'];
                    $this->Email->saveAll($emailRecord);
                }
            } 
        }
        
        function generateDailyCouponCode(){
            $continue = false;
            $code = '';
            do{
                $code = strtotime("now");
                $code = str_replace(".", "", $code);
                $code = substr($code, strlen($code) - 9, 9);    
                
                $options['conditions'] = array(
                    'DailyCoupon.code' => $code
                );
                
                $count = $this->DailyCoupon->find('count', $options);
                $continue = $count > 0;
            }while($continue);
            
            return $code;
        }
    }
?>
