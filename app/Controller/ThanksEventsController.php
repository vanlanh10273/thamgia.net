<?php
class ThanksEventsController extends AppController{
    function thanksEvent($event_id, $user_id){
        $this->autoRender = false;
        $data = array();
        // object thanks event
        $options['conditions'] = array('ThanksEvent.event_id' => $event_id, 'ThanksEvent.user_id' => $user_id);
        $thanksEvent = $this->ThanksEvent->find('all', $options);
        if(!$thanksEvent){
            $thanksEvent = array();
            $thanksEvent['ThanksEvent']['event_id'] = $event_id;
            $thanksEvent['ThanksEvent']['user_id'] = $user_id;
            $this->ThanksEvent->save($thanksEvent);
            
            // object event
            $this->loadModel('Event');
            $this->Event->id = $event_id;
            $event = $this->Event->read();
            $event['Event']['thanks'] = $event['Event']['thanks'] + 1;
            $this->Event->save($event);   
            $data['Success'] = true;
            $data['Thanks'] = $event['Event']['thanks'];
            
            // log activity
            $this->logActivity($user_id, $event_id, "vừa cảm ơn sự kiện");
            
        }else{
            $data['Success']  = false;
        }
        $this->RequestHandler->respondAs('json');
        echo json_encode($data);
    }
    
    function getThanksEvent($event_id){
        $this->autoRender = false;
        $options['joins'] = array(
            array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'LEFT',
                    'conditions' => array(
                    'User.id = ThanksEvent.user_id',
                    )
            )
        );
        $options['conditions'] = array(
                'ThanksEvent.event_id' => $event_id
            );
        $options['fields'] = array(
            'User.fullname'
        );    
        $userThanks = $this->ThanksEvent->find('all', $options);
        $result = "";
        foreach($userThanks as $item){
            $result .= $item['User']['fullname'] . " <br /> ";
        }
        return $result;
    }
}
?>
