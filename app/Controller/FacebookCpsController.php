<?php
/* -----------------------------------------------------------------------------------------
   IdiotMinds - http://idiotminds.com
   -----------------------------------------------------------------------------------------
*/
App::uses('Controller', 'Controller');
App::import('Vendor', 'Facebook',array('file'=>'Facebook'.DS.'facebook.php'));	


class FacebookCpsController extends AppController {
	
	public $name = 'FacebookCps';
	public $uses=array();
	

	public function index(){
		$this->layout=false;
	}
	
	function login()
	{
		Configure::load('facebook');
		$appId=Configure::read('Facebook.appId');
		$app_secret=Configure::read('Facebook.secret');
		$facebook = new Facebook(array(
				'appId'		=>  $appId,
				'secret'	=> $app_secret,
				));
		$loginUrl = $facebook->getLoginUrl(array(
			'scope'			=> 'email,read_stream, publish_stream, user_birthday, user_location, user_work_history, user_hometown, user_photos',
			'redirect_uri'	=> BASE_URL.'FacebookCps/facebook_connect',
			'display'=>'popup'
			));
		$this->redirect($loginUrl);
   	}
	
	function facebook_connect()
	{
	    Configure::load('facebook');
	    $appId=Configure::read('Facebook.appId');
	    $app_secret=Configure::read('Facebook.secret');
	   
	   	 $facebook = new Facebook(array(
		'appId'		=>  $appId,
		'secret'	=> $app_secret,
		));
	
	    $user = $facebook->getUser();
	
		
		if($user){
	     	try{
					$user_profile = $facebook->api('/me');
                    $userEmail = isset($user_profile['email']) ? $user_profile['email'] : '';
                    $avatarUrl = 'https://graph.facebook.com/'. $user_profile['id'] .'/picture';
					$params=array('next' => BASE_URL.'FacebookCps/facebook_logout');
					$logout =$facebook->getLogoutUrl($params);
                    
                    $this->loadModel('User');
                    $options['conditions'] = array('OR' => array(
                                                                array('User.facebook_id' => $user_profile['id']),
                                                                array('User.email' => $userEmail)
                                                            ));
                    $exitUser = $this->User->find('first', $options);
                    if ($exitUser){
                        $exitUser['User']['email'] = $userEmail;
                        $exitUser['User']['fullname'] = $user_profile['name'];
                        $exitUser['User']['avatar_url'] = $avatarUrl;
                        $exitUser['User']['facebook_id'] = $user_profile['id'];
                        $this->Session->write(AUTH_USER_LEVEL, $exitUser['User']['level']);
                        $this->User->save($exitUser);
                    }else{
                        $newUser['User'] = array();
                        $newUser['User']['email'] = $userEmail;
                        $newUser['User']['fullname'] = $user_profile['name'];
                        $newUser['User']['avatar_url'] = $avatarUrl;
                        $newUser['User']['facebook_id'] = $user_profile['id'];
                        $newUser['User']['level'] = LEVEL_USER;
                        $this->Session->write(AUTH_USER_LEVEL, LEVEL_USER);
                        $this->User->save($newUser);
                    }
                    
                    $this->Session->write(AUTH_USER_ID, $this->User->id);
                    $this->Session->write(AUTH_USER_FULLNAME, $user_profile['name']);
                    $this->Session->write(AUTH_USER_EMAIL, $userEmail);
                    $this->Session->write(AUTH_USER_AVATAR_URL, $avatarUrl);
                    
			        //$this->Session->write('User',$user_profile);
					//$this->Session->write('logout',$logout);
			}
			catch(FacebookApiException $e){
					error_log($e);
					$user = NULL;
			}		
		}
		
	   else
	   {
	       $this->Session->setFlash('Sorry.Please try again','default',array('class'=>'msg_req'));   
		   $this->redirect(array('controller' => 'FacebookCps', 'action'=>'index'));
	   }
   }
   
   function facebook_logout(){
	    $this->Session->delete('User');
	    $this->Session->delete('logout');   
	    $this->redirect(array('action'=>'index'));
   }
	
}
