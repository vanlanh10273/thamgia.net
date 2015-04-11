<?php
class ActivitiesController extends AppController{
    function getActivites(){
        $this->autoRender = false;
        
        $options['joins'] = array(
                array(
                        'table' => 'users',
                        'alias' => 'User',
                        'type' => 'LEFT',
                        'conditions' => array(
                        'User.id = Activity.user_id',
                        )
                ),
                array(
                        'table' => 'events',
                        'alias' => 'Event',
                        'type' => 'LEFT',
                        'conditions' => array(
                        'Event.id = Activity.event_id',
                        )
                ),
                array(
                        'table' => 'daily_coupons',
                        'alias' => 'DailyCoupon',
                        'type' => 'LEFT',
                        'conditions' => array(
                        'DailyCoupon.id = Activity.event_id',
                        )
                ),
                array(
                        'table' => 'cities',
                        'alias' => 'City',
                        'type' => 'LEFT',
                        'conditions' => array(
                        'City.id = Activity.city_id',
                        )
                )
        );
        
        $options['order'] = array(
            'Activity.created DESC'
        );
        $options['fields'] = array(
            'User.id',
            'User.fullname',
            'User.avatar_url',
            'Event.id',
            'DailyCoupon.title',
            'DailyCoupon.id',
            'Event.title',
            'Activity.description',
            'Activity.created',
            'Activity.is_daily_coupon',
            'Activity.city_id',
            'City.id',
            'City.name'
        );
        $options['limit'] = 20;
        $data = $this->Activity->find('all', $options);
        return $data;
    }
}  
?>
