<?php
class PinsUsersController extends AppController{
    function pinsUser($event_id, $user_id){
        $this->autoRender = false;
        $data = array();
        // object thanks event
        $options['conditions'] = array('PinsUser.event_id' => $event_id, 'PinsUser.user_id' => $user_id);
        $pinsUser = $this->PinsUser->find('first', $options);
        if(!$pinsUser){
            $pinsUser = array();
            $pinsUser['PinsUser']['event_id'] = $event_id;
            $pinsUser['PinsUser']['user_id'] = $user_id;
            $this->PinsUser->save($pinsUser);

            $data['Success'] = true;
            
            // log activity
            $this->logActivity($user_id, $event_id, "vừa đánh dấu sự kiện");
            
        }else{
            $data['Success']  = false;
        }
        $this->RequestHandler->respondAs('json');
        echo json_encode($data);
    }
    
    function removePinsUser($event_id, $user_id){
        $this->autoRender = false;
        //$this->layout = 'ajax';
        $data = array();
        $options['conditions'] = array('PinsUser.event_id' => $event_id, 'PinsUser.user_id' => $user_id);
        $pinsUser = $this->PinsUser->find('first', $options);    
        if ($pinsUser){
            $this->PinsUser->delete($pinsUser['PinsUser']['id'], true);
            $data['Success'] = true;
        }else{
            $data['Success']  = false; 
        }
        $this->RequestHandler->respondAs('json');
        echo json_encode($data);
    }
}
?>
