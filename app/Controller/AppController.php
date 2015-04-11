<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('CakeEmail', 'Network/Email');
App::uses('Controller', 'Controller');
App::uses('Link','Lib');
App::uses('TimeFormat','Lib');
App::uses('General','Lib');
App::import('Html',"html");
App::import('Form',"form");
App::import('Session',"session");


/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    public $components = array('Session', 'Email', 'RequestHandler'); 
    
    function beforeFilter(){
        $this->set("logged_in", $this->_isLogin());
        if ($this->_isLogin()){
            $this->set("users_userid", $this->_usersUserID());
            $this->set("users_level", $this->_usersLevel());
            $this->set("users_name",$this->_usersName());   
            $this->set("users_avatar",$this->_usersAvatar());
            $this->set("users_email",$this->_usersEmail());
        }else{
            $this->set("users_userid", 0);
            $this->set("users_level", 0);
            $this->set("users_name", '');
            $this->set("users_avatar", '');
            $this->set("users_email", '');
        }
        
        // load cities
        $this->loadModel('City');
        $cities = $this->City->find('all');
        $this->set('cities', $cities);
        
        // load typies
        $this->loadModel('Type');
        $options['order'] = array('Type.order' => 'asc');
        $types = $this->Type->find('all', $options);
        $this->set('types', $types);
        
        // load events
        $this->loadModel('Event');
        $this->loadModel('Participation');
        $this->loadModel('Message');
        $this->loadModel('User');
        $this->loadModel('Highlight');
        
        //load highlights
        //$this->_highlightEvents();
        
        if ($this->_isLogin()){
            $this->_userInfromation();
        }
        
        // set default city_name, type_id, type_name
        $this->set('city_name', DEFAULT_CITY_NAME);
        $this->set('type_id', 0);
        $this->set('type_name', 'TẤT CẢ SỰ KIỆN');
        
        // set default title of page
        $this->set('title', DEFAULT_TITLE.' - '. DEFAULT_CITY_NAME);
        $this->set('meta_description', DEFAULT_META_DESCRIPTION . $this->Session->read(CITY_NAME));
        $this->set('meta_keywords', DEFAULT_META_KEYWORDS);
        
        // set city_id from customer
        if ($this->Session->read(CITY_ID) != null){
            $this->set('city_id', $this->Session->read(CITY_ID));
            $this->set('city_name', $this->Session->read(CITY_NAME));
            $this->set('title', DEFAULT_TITLE.' - '. $this->Session->read(CITY_NAME));
            
            $this->setTotalEventType($this->Session->read(CITY_ID));
        }else
            $this->setTotalEventType(DEFAULT_CITY_ID);
            
       
        
    }
    
    function setTotalEventType($cityId){
         // get total event of type
        // Type workshop    
        $options['conditions'] = array();
        $options['conditions']['Event.start >='] = date(TIME_FORMAT_MYSQL);
        $options['conditions']['Event.type_id'] = TYPE_WORKSHOP;
        $options['conditions']['Event.city_id'] = $cityId;
        $this->set('total_workshop', $this->Event->find('count', $options));
        
        // Type Promotion    
        $options['conditions']['Event.type_id'] = TYPE_PROMOTION;
        $this->set('total_promotion', $this->Event->find('count', $options));
        
        // Type training
        $options['conditions']['Event.type_id'] = TYPE_TRAINING;
        $this->set('total_training', $this->Event->find('count', $options));
        
        // Type entertainment
        $options['conditions']['Event.type_id'] = TYPE_ENTERTAINMENT;
        $this->set('total_entertainment', $this->Event->find('count', $options));
    }
    
    
  
  /**
   * Kiem tra da login chua
   */ 
  function _isLogin(){
    $login = FALSE;
    if ($this->Session->read(AUTH_USER_ID) != null)
        $login = TRUE;
    return $login;
  }
  
  /**
  * check authenticate user is admin
  */
  function _isAdmin(){
      
      if (!$this->_isLogin())
        return false;
        
      if ($this->Session->read(AUTH_USER_LEVEL) != null){
          return ($this->Session->read(AUTH_USER_LEVEL) == LEVEL_ADMIN);
      }
      
      return false;
  }
  
  /**
   * Xac nhan userID
   */ 
  function _usersUserID(){
    $users_userid = NULL;
    if ($this->Session->read(AUTH_USER_ID) != null)
        $users_userid = $this->Session->read(AUTH_USER_ID);
    return $users_userid;
  }
  
  
  function _usersName(){
    $users_name = NULL;
    if ($this->Session->read(AUTH_USER_FULLNAME) != null)
        $users_name = $this->Session->read(AUTH_USER_FULLNAME);
    return $users_name;
  }
  
  function _usersLevel(){
    $users_level = NULL;
    if ($this->Session->read(AUTH_USER_LEVEL) != null)
        $users_level = $this->Session->read(AUTH_USER_LEVEL);
    return $users_level;
  }
  
  function _usersEmail(){
    $users_email = NULL;
    if ($this->Session->read(AUTH_USER_EMAIL) != null)
        $users_email = $this->Session->read(AUTH_USER_EMAIL);
    return $users_email;   
  }
  
  function _usersAvatar(){
    $users_avatar = NULL;
    if ($this->Session->read(AUTH_USER_AVATAR_URL) != null)
        $users_avatar = $this->Session->read(AUTH_USER_AVATAR_URL);
    return $users_avatar;   
  }
  
  /**
  * require login
  * 
  */
  function isAuthenticated(){
        if (!$this->_isLogin()){
            $this->redirect(array('controller' => 'Users' ,'action' => 'login'));
        }
  }
  
  /**
  * require admin authentication
  */
  
  function isAdminAuthenticated(){
        if (!$this->_isAdmin()){
            $this->redirect(array('controller' => 'Users' ,'action' => 'login'));
        }
  }
  
  
  function uploadFiles($folder, $formdata, $itemId = null) {
    // setup dir names absolute and relative
    $folder_url = WWW_ROOT.$folder;
    
    $rel_url = $folder;
    
    // create the folder if it does not exist
    if(!is_dir($folder_url)) {
        mkdir($folder_url);
    }
        
    // if itemId is set create an item folder
    if($itemId) {
        // set new absolute folder
        $folder_url = WWW_ROOT.$folder.'/'.$itemId; 
        // set new relative folder
        $rel_url = $folder.'/'.$itemId;
        // create directory
        if(!is_dir($folder_url)) {
            mkdir($folder_url);
        }
    }
    
    // list of permitted file types, this is only images but documents can be added
    $permitted = array('image/gif','image/jpeg','image/pjpeg','image/png', 'image/jpg');
    
    // loop through and deal with the files
    foreach($formdata as $file) {
        // replace spaces with underscores
        $filename = str_replace(' ', '_', $file['name']);
        // assume filetype is false
        $typeOK = false;
        // check filetype is ok
        foreach($permitted as $type) {
            if($type == $file['type']) {
                $typeOK = true;
                break;
            }
        }
        
        // if file type ok upload the file
        if($typeOK) {
            // switch based on error code
            switch($file['error']) {
                case 0:
                    // check filename already exists
                    if(!file_exists($folder_url.'/'.$filename)) {
                        // create full filename
                        $full_url = $folder_url.'/'.$filename;
                        $url = $rel_url.'/'.$filename;
                        // upload the file
                        $success = move_uploaded_file($file['tmp_name'], $url);
                    } else {
                        // create unique filename and upload file
                        ini_set('date.timezone', 'Europe/London');
                        $now = date('Y-m-d-His');
                        $full_url = $folder_url.'/'.$now.$filename;
                        $url = $rel_url.'/'.$now.$filename;
                        $success = move_uploaded_file($file['tmp_name'], $url);
                    }
                    // if upload was successful
                    if($success) {
                        // save the url of the file
                        $result['urls'][] = $url;
                    } else {
                        $result['errors'][] = "Error uploaded $filename. Please try again.";
                    }
                    break;
                case 3:
                    // an error occured
                    $result['errors'][] = "Error uploading $filename. Please try again.";
                    break;
                default:
                    // an error occured
                    $result['errors'][] = "System error uploading $filename. Contact webmaster.";
                    break;
            }
        } elseif($file['error'] == 4) {
            // no file was selected for upload
            $result['nofiles'][] = "No file Selected";
        } else {
            // unacceptable file type
            $result['errors'][] = "$filename cannot be uploaded. Acceptable file types: gif, jpg, png.";
        }
    }
    return $result;
    } 
    
  function _userInfromation(){
      // added events
      $options['conditions'] = array(
            'Event.user_id' => $this->_usersUserID()
      );
      $this->set('count_added_events', $this->Event->find('count', $options));
      
      // participated events
      $options['conditions'] = array(
            'Participation.user_id' => $this->_usersUserID()
      );
      $this->set('participated_events', $this->Participation->find('count', $options));
      
      // participated events
      $options['conditions'] = array(
            'Message.receiver' => $this->_usersUserID(),
            'Message.viewed' => false,
            'Message.receiver_deleted' => false
      );
      $this->set('inbox_items', $this->Message->find('count', $options));
      
      // default mobile number to participation
      $options['conditions'] = array(
            'Participation.user_id' => $this->_usersUserID()
      );
      $options['order']  =  array('Participation.id' => 'desc');
      $last_participation = $this->Participation->find('first', $options);
      if ($last_participation){
        $this->set('default_user_mobile', $last_participation['Participation']['mobile']);    
      }
  }
  
  /**
  * Conver string date time d/m/Y H:i to time (mktime)
  */
  function clientDateToTime($date){
      $date = str_replace(' ', '/', $date);
      $date = str_replace(':', '/', $date);
      list($month, $day, $year, $hour, $minute) = explode('/', $date);
      
      return mktime($hour, $minute, 0, $day, $month, $year);
  }
  
  function timeToString($timeline) {
        $periods = array('ngày' => 86400, 'giờ' => 3600, 'phút' => 60, 'giây' => 1);
        $ret = '';
        foreach($periods AS $name => $seconds){
            $num = floor($timeline / $seconds);
            $timeline -= ($num * $seconds);
            if ($num > 0){
                $ret = $num.' '.$name.(($num > 1) ? '' : '').' ';
                break;   
            }
        }

        return trim($ret);
  }
  
  function getUserNameById($id){
        $options['conditions'] = array(
                'User.id' => $id
        );
        $user = $this->User->find('first', $options);
        return $user['User']['fullname'];
    }
    
    function getCityNameById($id){
        $options['conditions'] = array(
                'City.id' => $id
        );
        $city = $this->City->find('first', $options);
        return $city['City']['name'];
    }
    
  
  
  /**
  * Convert date time format to Vie format: Ngày .. tháng ... năm
  * 
  * @param DateTime $dateObj
  */
  function dateToVieFormat(DateTime $dateObj){
    return 'Ngày ' . $dateObj->format('d'). ' tháng '. $dateObj->format('m'). ' năm ' . $dateObj->format('Y');       
  }
  
  function storeCityToSession($city_id, $city_name){
      $oldCityId = 0;
      if ($this->Session->read(CITY_ID)!= NULL){
          $oldCityId = $this->Session->read(CITY_ID);
      }
      if ($oldCityId != $city_id){
          $this->Session->write(CITY_ID, $city_id);
          $this->Session->write(CITY_NAME, $city_name);
          $this->set('city_id', $city_id);
          $this->set('city_name', $city_name);
          $this->setTotalEventType($city_id);
      }
  }
  
    function logActivity($user_id, $event_id, $description, $isDailyCoupon = false, $atCityId = 0){
        $this->loadModel('Activity');
        $arrActivity['Activity'] = array();
        $arrActivity['Activity']['user_id'] = $user_id;
        $arrActivity['Activity']['event_id'] = $event_id;
        $arrActivity['Activity']['description'] = $description;
        $arrActivity['Activity']['created'] = date(TIME_FORMAT_MYSQL);
        $arrActivity['Activity']['is_daily_coupon'] = $isDailyCoupon;
        $cityId = $atCityId != 0 ? $atCityId : $this->Session->read(CITY_ID); 
        $arrActivity['Activity']['city_id'] = $cityId;
        $this->Activity->save($arrActivity);
    }

}
