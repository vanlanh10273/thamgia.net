<?php
class ThanksDailyCouponsController extends AppController{
    function thanksDailyCoupon($dailyCouponId, $userId){
        $this->autoRender = false;
        $data = array();
        // object thanks event
        $options['conditions'] = array('ThanksDailyCoupon.daily_coupon_id' => $dailyCouponId, 'ThanksDailyCoupon.user_id' => $userId);
        $thanksDailyCoupon = $this->ThanksDailyCoupon->find('all', $options);
        if(!$thanksDailyCoupon){
            $thanksDailyCoupon = array();
            $thanksDailyCoupon['ThanksDailyCoupon']['daily_coupon_id'] = $dailyCouponId;
            $thanksDailyCoupon['ThanksDailyCoupon']['user_id'] = $userId;
            $this->ThanksDailyCoupon->save($thanksDailyCoupon);
            
            // object event
            $this->loadModel('DailyCoupon');
            $this->DailyCoupon->id = $dailyCouponId;
            $dailyCoupon = $this->DailyCoupon->read();
            $dailyCoupon['DailyCoupon']['thanks'] = $dailyCoupon['DailyCoupon']['thanks'] + 1;
            $this->DailyCoupon->save($dailyCoupon);   
            $data['Success'] = true;
            $data['Thanks'] = $dailyCoupon['DailyCoupon']['thanks'];
            
        }else{
            $data['Success']  = false;
        }
        $this->RequestHandler->respondAs('json');
        echo json_encode($data);
    }
    
    function getThanksDailyCoupon($dailyCouponId){
        $this->autoRender = false;
        $options['joins'] = array(
            array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'LEFT',
                    'conditions' => array(
                    'User.id = ThanksDailyCoupon.user_id',
                    )
            )
        );
        $options['conditions'] = array(
                'ThanksDailyCoupon.daily_coupon_id' => $dailyCouponId
            );
        $options['fields'] = array(
            'User.fullname'
        );    
        $userThanks = $this->ThanksDailyCoupon->find('all', $options);
        $result = "";
        foreach($userThanks as $item){
            $result .= $item['User']['fullname'] . " <br /> ";
        }
        return $result;
    }
}
?>
