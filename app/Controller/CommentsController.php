<?php
    CakePlugin::load('Uploader');
    App::import('Vendor', 'Uploader.Uploader');
    class CommentsController extends AppController{
        //var $helpers = array('Ajax');
        function beforeFilter(){
            $this->loadModel('ThanksComment');
            parent::beforeFilter();
        }
        
        function sendComment($event_id){
            $comment = $_POST['comment'];
            $isDailyCoupon = isset($_POST['is_daily_coupon'])? $_POST['is_daily_coupon'] : false;
            $arrComment['Comment'] = array();
            $arrComment['Comment']['event_id'] = $event_id;
            $arrComment['Comment']['user_id'] = $this->_usersUserID();
            $arrComment['Comment']['created'] = date(TIME_FORMAT_MYSQL);
            $arrComment['Comment']['comment'] = $comment;
            $arrComment['Comment']['is_daily_coupon'] = $isDailyCoupon;
            $this->Comment->save($arrComment);
            //$this->sendMailNotificationComment($event_id, $this->_usersUserID(), $this->_usersName() , $comment);
            
            // log activity
            $this->logActivity($this->_usersUserID(), $event_id, "vừa bình luận sự kiện");
            
            $this->redirect(array('controller'=>'Comments', 'action'=>'getComments', $event_id));
        }
        
        function getComments($event_id){
            $this->layout = 'empty';    
            $isDailyCoupon = isset($_GET['is_daily_coupon']) ? $_GET['is_daily_coupon'] : false;
            $options['joins'] = array(
                    array(
                            'table' => 'users',
                            'alias' => 'User',
                            'type' => 'LEFT',
                            'conditions' => array(
                            'User.id = Comment.user_id',
                            )
                    )
            );
            $options['conditions'] = array(
                'Comment.event_id' => $event_id,
                'Comment.is_daily_coupon' => $isDailyCoupon
            );
            $options['fields'] = array(
                'User.id',
                'User.fullname',
                'User.avatar_url',
                'Comment.comment',
                'Comment.created',
                'Comment.id'
            );
            $options['order'] = array(
                'Comment.created ASC'
            );
            $data = $this->Comment->find('all', $options);
            
            // get user thanks
            $numberItem = count($data);
            
            $optionsThanks['joins'] = array(
                array(
                        'table' => 'users',
                        'alias' => 'User',
                        'type' => 'LEFT',
                        'conditions' => array(
                        'User.id = ThanksComment.user_id',
                        )
                )
            );
            $optionsThanks['fields'] = array(
                'User.fullname'
            );
            
            
            for ($i=0; $i < $numberItem; $i++){
                $optionsThanks['conditions'] = array(
                    'ThanksComment.comment_id' => $data[$i]['Comment']['id']
                );                
                $dataThanks = $this->ThanksComment->find('all', $optionsThanks);
                $userThanks = "";
                foreach($dataThanks as $item){
                    $userThanks .= $item['User']['fullname'] . " <br /> ";
                }
                $data[$i]['Comment']['usersThanks'] = $userThanks;
                $data[$i]['Comment']['thanks'] = count($dataThanks);
            }
            
            $this->set('data', $data);
        }
        
        function getAComment($comment_id){
            $this->layout = 'empty';    
            $options['joins'] = array(
                    array(
                            'table' => 'users',
                            'alias' => 'User',
                            'type' => 'LEFT',
                            'conditions' => array(
                            'User.id = Comment.user_id',
                            )
                    )
            );
            $options['conditions'] = array(
                'Comment.id' => $comment_id
            );
            $options['fields'] = array(
                'User.id',
                'User.fullname',
                'User.avatar_url',
                'Comment.comment',
                'Comment.created',
                'Comment.id'
            ); 
            $data = $this->Comment->find('first', $options);
            
            // get user thanks
            $numberItem = count($data);
            
            $optionsThanks['joins'] = array(
                array(
                        'table' => 'users',
                        'alias' => 'User',
                        'type' => 'LEFT',
                        'conditions' => array(
                        'User.id = ThanksComment.user_id',
                        )
                )
            );
            $optionsThanks['fields'] = array(
                'User.fullname'
            );
     
            $optionsThanks['conditions'] = array(
                'ThanksComment.comment_id' => $data['Comment']['id']
            );                
            $dataThanks = $this->ThanksComment->find('all', $optionsThanks);
            $userThanks = "";
            foreach($dataThanks as $item){
                $userThanks .= $item['User']['fullname'] . " <br /> ";
            }
            $data['Comment']['usersThanks'] = $userThanks;
            $data['Comment']['thanks'] = count($dataThanks);
            
            $this->set('data', $data);  
        }
        
        function getHeaderComments($event_id){
            $this->layout = "empty";
            $isDailyCoupon = isset($_GET['is_daily_coupon']) ? $_GET['is_daily_coupon'] : false;
            $options['conditions'] = array(
                'Comment.event_id' => $event_id,
                'Comment.is_daily_coupon' => $isDailyCoupon
            );
            $total = $this->Comment->find('count', $options);
            $this->set('total', $total);
        }
        
        function thanksComment($comment_id){
            $this->autoRender = false;
            $this->isAuthenticated();
            $user_id = $this->_usersUserID();
            
            
            
            // check user not yet thanks
            $options['conditions'] = array(
                'ThanksComment.user_id' => $user_id,
                'ThanksComment.comment_id' => $comment_id
            );
            $userThank = $this->ThanksComment->find('count', $options);
            if ($userThank == 0){
                $this->Comment->id = $comment_id;
                $comment = $this->Comment->read();
                $comment['Comment']['thanks'] = $comment['Comment']['thanks'] + 1;
                $this->Comment->save($comment);
                
                // create a record for table ThanksComments
                $thanksComment['ThanksComment'] = array();
                $thanksComment['ThanksComment']['comment_id'] =  $comment_id;
                $thanksComment['ThanksComment']['user_id'] = $user_id;
                $this->ThanksComment->save($thanksComment);
                
            }else{
                throw new Exception('This user have thanked this comment!');
            }
        }
        
        /**
        * This action shoud be call through ajax
        * Action send mail notification when user comment in event
        * 
        */
        function sendMailNotificationComment(){
            $this->loadModel('Email');
            $this->loadModel('Notification');
            $this->autoRender = false;
            
            if($this->request->is('post')){
                $event_id = $_POST['event_id'];
                $commenter_id = $_POST['commenter_id'];
                $commenter = $_POST['commenter'];
                $comment = $_POST['comment'];
                
                $arrEmail = array();
                $emailRecord = array();
                $emailRecord['priority'] = PRIORITY_HIGH;
                
                
                // get event detail
                $optionsEvent['conditions'] = array(
                            'Event.id' => $event_id
                );
                $optionsEvent['joins'] = array(
                            array(
                                'table' => 'users',
                                'alias' => 'User',
                                'type' => 'LEFT',
                                'conditions' => array(
                                    'User.id = Event.user_id',
                                )
                            )    
                 );
                 
                 $optionsEvent['fields'] = array(
                    'User.fullname',
                    'User.email',
                    'Event.id',
                    'Event.user_id',
                    'Event.title'
                );
                $event = $this->Event->find('first', $optionsEvent);
                $event_title = $event['Event']['title'];
                $owner_id = $event['Event']['user_id'];
                $owner_email = $event['User']['email'];
                $owner_fullname = $event['User']['fullname']; 
                
                
                // get information of receivers
                $options['conditions'] = array(
                            'Comment.event_id' => $event_id,
                            'Comment.is_daily_coupon' => false
                );
                 $options['joins'] = array(
                            array(
                                    'table' => 'users',
                                    'alias' => 'User',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                    'User.id = Comment.user_id',
                                    )
                            )
                 );
                 $options['fields'] = array(
                        'DISTINCT (User.id)',
                        'User.fullname',
                        'User.email'
                 );
                 
                 $users_comment = $this->Comment->find('all', $options);
                 
                 // create notification
                 $arrNotification = array();
                 $recordNotification = array();
                 $notification = '<strong>'. $commenter . '</strong> vừa bình luận trên sự kiện ' . $event_title;
                 $linkNotification = Router::url(array(
                                            "controller"=>"Events", 
                                            "action"=> "detail",
                                            'slug' => Link::seoTitle($event_title),
                                            'id'=> $event_id), true);
                 $this->User->id =   $commenter_id;
                 $userCommented = $this->User->read();
                 $imageNotification = $userCommented['User']['avatar_url'];
                 
                 $recordNotification['link']  = $linkNotification;
                 $recordNotification['notification'] = $notification;
                 $recordNotification['viewed'] = false;
                 $recordNotification['created'] = date(TIME_FORMAT_MYSQL);
                 $recordNotification['image_url'] = $imageNotification;
                 
                 // notification for ower of event
                 if ($this->_usersUserID() != $owner_id){
                     $recordNotification['user_id'] = $owner_id;
                     array_push($arrNotification, $recordNotification);
                 }
                 
                 // setup email
                $parameters = array();
                $parameters['subject'] = 'Thamgia.net - '. $commenter .' vừa bình luận sự kiện';
                $parameters['template'] = 'comment';
                $parameters['event_url'] = $linkNotification;
                 
                 // create email to send to owner event
                 $parameters['to_email'] = $owner_email;
                 //$parameters['event_id'] = $event_id;
                 $parameters['commenter'] = $commenter;
                 $parameters['event_title'] = $event_title;
                 $parameters['receiver'] = $owner_fullname;
                 $parameters['comment'] = $comment;
                 $emailRecord['parameters'] = serialize($parameters);
                 array_push($arrEmail, $emailRecord);
                 
                 
                 
                 
                 // create email to send to all users
                 foreach($users_comment as $user){                 
                     if (($user['User']['id'] != $commenter_id) && ($user['User']['id'] != $owner_id)){
                         $parameters['to_email'] = $user['User']['email'];
                         //$parameters['event_id'] = $event_id;
                         $parameters['commenter'] = $commenter;
                         $parameters['event_title'] = $event_title;
                         $parameters['receiver'] = $user['User']['fullname'];
                         $parameters['comment'] = $comment;
                         $emailRecord['parameters'] = serialize($parameters);
                         array_push($arrEmail, $emailRecord);
                         
                         // notification
                         if ($this->_usersUserID() != $user['User']['id']){
                             $recordNotification['user_id'] = $user['User']['id'];
                             array_push($arrNotification, $recordNotification);
                         }
                     }
                 }
                 
                 // store email to queue
                 $this->Email->saveAll($arrEmail);
                 
                 // store all notifications
                 $this->Notification->saveAll($arrNotification);
             }
        }
        
        function sendMailNotificationCommentDaiylCoupon(){
            $this->loadModel('Email');
            $this->loadModel('Notification');
            $this->autoRender = false;
            
            if($this->request->is('post')){
                $daily_coupon_id = $_POST['daily_coupon_id'];
                $commenter_id = $_POST['commenter_id'];
                $commenter = $_POST['commenter'];
                $comment = $_POST['comment'];
                
                $arrEmail = array();
                $emailRecord = array();
                $emailRecord['priority'] = PRIORITY_HIGH;
                
                
                // get event detail
                $optionsEvent['conditions'] = array(
                            'DailyCoupon.id' => $daily_coupon_id
                );
                $optionDailyCoupon['joins'] = array(
                            array(
                                'table' => 'users',
                                'alias' => 'User',
                                'type' => 'LEFT',
                                'conditions' => array(
                                    'User.id = DailyCoupon.user_id',
                                )
                            )    
                 );
                 
                 $optionDailyCoupon['fields'] = array(
                    'User.fullname',
                    'User.email',
                    'DailyCoupon.id',
                    'DailyCoupon.user_id',
                    'DailyCoupon.title'
                );
                $this->loadModel("DailyCoupon");
                $dailyCoupon = $this->DailyCoupon->find('first', $optionDailyCoupon);
                $dailyCouponTitle = $dailyCoupon['DailyCoupon']['title'];
                $ownerId = $dailyCoupon['DailyCoupon']['user_id'];
                $ownerEmail = $dailyCoupon['User']['email'];
                $ownerFullname = $dailyCoupon['User']['fullname']; 
                
                
                // get information of receivers
                $options['conditions'] = array(
                            'Comment.event_id' => $daily_coupon_id,
                            'Comment.is_daily_coupon' => true
                );
                 $options['joins'] = array(
                            array(
                                    'table' => 'users',
                                    'alias' => 'User',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                    'User.id = Comment.user_id',
                                    )
                            )
                 );
                 $options['fields'] = array(
                        'DISTINCT (User.id)',
                        'User.fullname',
                        'User.email'
                 );
                 
                 $users_comment = $this->Comment->find('all', $options);
                 
                 // create notification
                 $arrNotification = array();
                 $recordNotification = array();
                 $notification = '<strong>'. $commenter . '</strong> vừa bình luận trên sự kiện ' . $event_title;
                 $linkNotification = Router::url(array(
                                            "controller"=>"DailyCoupons", 
                                            "action"=> "detail",
                                            'slug' => Link::seoTitle($dailyCouponTitle),
                                            'id'=> $daily_coupon_id), true);
                 $this->User->id =   $commenter_id;
                 $userCommented = $this->User->read();
                 $imageNotification = $userCommented['User']['avatar_url'];
                 
                 $recordNotification['link']  = $linkNotification;
                 $recordNotification['notification'] = $notification;
                 $recordNotification['viewed'] = false;
                 $recordNotification['created'] = date(TIME_FORMAT_MYSQL);
                 $recordNotification['image_url'] = $imageNotification;
                 
                 // notification for ower of event
                 if ($this->_usersUserID() != $ownerId){
                     $recordNotification['user_id'] = $ownerId;
                     array_push($arrNotification, $recordNotification);
                 }
                 
                 // setup email
                $parameters = array();
                $parameters['subject'] = 'Thamgia.net - '. $commenter .' vừa bình luận daily coupon';
                $parameters['template'] = 'comment';
                $parameters['event_url'] = $linkNotification;
                 
                 // create email to send to owner event
                 $parameters['to_email'] = $ownerEmail;
                 //$parameters['event_id'] = $event_id;
                 $parameters['commenter'] = $commenter;
                 $parameters['event_title'] = $event_title;
                 $parameters['receiver'] = $owner_fullname;
                 $parameters['comment'] = $comment;
                 $emailRecord['parameters'] = serialize($parameters);
                 array_push($arrEmail, $emailRecord);

                 // create email to send to all users
                 foreach($users_comment as $user){                 
                     if (($user['User']['id'] != $commenter_id) && ($user['User']['id'] != $owner_id)){
                         $parameters['to_email'] = $user['User']['email'];
                         //$parameters['event_id'] = $event_id;
                         $parameters['commenter'] = $commenter;
                         $parameters['event_title'] = $event_title;
                         $parameters['receiver'] = $user['User']['fullname'];
                         $parameters['comment'] = $comment;
                         $emailRecord['parameters'] = serialize($parameters);
                         array_push($arrEmail, $emailRecord);
                         
                         // notification
                         if ($this->_usersUserID() != $user['User']['id']){
                             $recordNotification['user_id'] = $user['User']['id'];
                             array_push($arrNotification, $recordNotification);
                         }
                     }
                 }
                 
                 // store email to queue
                 $this->Email->saveAll($arrEmail);
                 
                 // store all notifications
                 $this->Notification->saveAll($arrNotification);
                 
                 
             }                
        }
    }  
?>
