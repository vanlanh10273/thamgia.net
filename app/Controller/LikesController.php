<?php
    class LikesController extends AppController{
        
        function beforeFilter(){
            parent::beforeFilter();
        }
        
        function getUsersLike($event_id){
            $this->layout = "empty";
            
            // count like and disklike
            $options['conditions'] = array(
                'Like.event_id' => $event_id,
                'Like.liked' => LIKE
            );
            $countLikes = $this->Like->find('count', $options);
            
            $options['conditions'] = array(
                'Like.event_id' => $event_id,
                'Like.liked' => DISLIKE
            );
            $countDisLikes = $this->Like->find('count', $options);
            $href_like = 'href="javascript:like('.$event_id.')"';
            $href_dislike = 'href="javascript:disLike('.$event_id.')"';
            if ($this->_isLogin()){
                $options['conditions'] = array(
                    'Like.event_id' => $event_id,
                    'Like.user_id' => $this->_usersUserID()
                );
                $user_like = $this->Like->find('first', $options);
                if ($user_like){
                    if ($user_like['Like']['liked']){
                        $id_like = '';
                        $href_like = '';
                    }else{
                        $id_dislike = '';
                        $href_dislike = '';
                    }
                }
            }else{
                $href_like = 'href="javascript:login()"';
                $href_dislike = $href_like;
            }
            
            // pass data to view
            $this->set('countLikes', $countLikes);
            $this->set('countDisLikes', $countDisLikes);                
            $this->set('href_like', $href_like);
            $this->set('href_dislike', $href_dislike);
        }
        
        function addLike($event_id, $liked, $event_type = EVENT_TYPE_NORMAL){
            
            $this->isAuthenticated();
            
            $options['conditions'] = array(
                'Like.event_id' => $event_id,
                'Like.user_id' => $this->_usersUserID(),
                'Like.event_type' => $event_type
            );
            $user_like = $this->Like->find('first', $options);
            if ($user_like){
                $user_like['Like']['liked'] = $liked;
                $this->Like->save($user_like);
            }else{
                $arrLike['Like'] = array();
                $arrLike['Like']['event_id'] = $event_id;
                $arrLike['Like']['user_id'] = $this->_usersUserID();
                $arrLike['Like']['liked'] = $liked;
                $arrLike['Like']['event_type'] = $event_type;
                $this->Like->save($arrLike);
            }
            
            // redirect to controller get_user_like
            $this->redirect(array('controller'=>'Likes', 'action'=>'getUsersLike', $event_id));
        }
    }
?>
