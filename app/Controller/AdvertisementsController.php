<?php
    class AdvertisementsController extends AppController{
        
        function beforeFilter(){
            parent::beforeFilter();
        }
        
        function getAdvertisements($side_id, $city_id){
            $this->autoRender = false;
            $current = new DateTime();
            
            $options['conditions'] = array(
                /*'Advertisement.side_id =' => $side_id,*/
                'Advertisement.city_id =' => $city_id,
                'Advertisement.start <=' => $current->format(TIME_FORMAT_MYSQL),
                'Advertisement.end >=' => $current->format(TIME_FORMAT_MYSQL)
            );
            $advertisements = $this->Advertisement->find('all', $options);
            return $advertisements;
        }
        
    } 
?>
