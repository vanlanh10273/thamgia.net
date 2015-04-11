<?php
    class  NotificationsController extends AppController{
        function beforeFilter(){
            parent::beforeFilter();
        }
        
        function getNotifications($user_id){
            $this->autoRender = false;
            $options['order'] =  array('Notification.created' => 'desc');
            $options['limit'] = 10;
            $options['conditions'] = array(
                'Notification.user_id' => $user_id
            );
            $notifications = $this->Notification->find('all', $options);
            return $notifications;
        }
        
        function countNewNotifications($user_id){
            $this->autoRender = false;
            $options['order'] =  array('Notification.created' => 'desc');
            $options['conditions'] = array(
                'Notification.viewed' => false,
                'Notification.user_id' => $user_id
            );
            $notifications = $this->Notification->find('count', $options);
            return $notifications;
        }
        
        function markViewedNotification($notification_id){
            $this->autoRender = false;
            $this->Notification->id = $notification_id;
            $notification = $this->Notification->read();
            $notification['Notification']['viewed'] = true;
            $this->Notification->save($notification);
        }
        
        function index(){
            $this->isAuthenticated();
            
            $this->layout = "default";
            
            $this->paginate = array(
                'Notification' => array(
                    'limit' => DEFAULT_SIZE,
                    'order' => array('Notification.created' => 'desc'),
                    'conditions' => array(
                                    'Notification.user_id =' => $this->_usersUserID())
                )
            );
            $dataPaginate = $this->paginate('Notification');
            $this->set('data', $dataPaginate);
            //var_dump($dataPaginate);
            //$this->autoRender = false;
        }
        
        function delete($user_id, $id){
            $this->isAuthenticated();
            
            if ($this->_usersUserID() == $user_id){
                $options['conditions'] = array(
                    'Notification.id' => $id,
                    'Notification.user_id' => $user_id
                );
                $count = $this->Notification->find('count', $options);
                if ($count == 1){
                    $this->Notification->delete($id);
                }
            }
            
            $this->redirect(env('HTTP_REFERER'));
        }
    }
?>
