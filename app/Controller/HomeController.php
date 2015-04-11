<?php                       
    class HomeController extends AppController{
        public $components = array('Session', 'Email');
        
        function beforeFilter(){
            parent::beforeFilter();
        }
        
        function index(){
            if ($this->Session->read(CITY_ID) == null){
                $this->layout = 'ajax';   
            }
            else{
                $this->redirectToCurrentCity();
            }
                
        }
        
        function selectCity($city_id=null){
            if ($this->request->is('post')){
                $this->storeCityToSession($this->request->data(CITY_ID), $this->getCityNameById($this->request->data(CITY_ID)));
                //$this->redirectToCurrentCity();
                $this->redirect(env('HTTP_REFERER'));
            }else if ($this->request->is('get')){
                if ($city_id){
                    $this->storeCityToSession($city_id, $this->getCityNameById($city_id));
                    $this->redirectToCurrentCity();
                }else
                    $this->redirect(array('controller'=>'home', 'action'=>'index'));
            }
        }
        
        function city($slug, $city_id){
            $this->layout = 'event';
            // store city_id to cookie
            $this->storeCityToSession($city_id, $this->getCityNameById($city_id));

            //set active menu home
            $this->set('type_id', 0 );
            
        }
        
        function introduction(){
            $this->set('title', DEFAULT_TITLE.' - '. 'Giới Thiệu');
            $this->layout = "no_column";
        }
        
        function advertisement(){
            $this->set('title', DEFAULT_TITLE.' - '. 'Bảng giá truyền thông');
            $this->layout = "default"; 
        }
        
        function terms(){
            $this->set('title', DEFAULT_TITLE.' - '. 'Điều khoản sử dụng');
            $this->layout = "default"; 
        }
        
        function deleteCity(){
            $this->Session->delete(CITY_ID);
            $this->redirect(array('controller'=>'home', 'action'=>'index'));    
        }
        
        function testSendMail(){
            
            $email = new CakeEmail('default');
            $email->emailFormat('html');
            $email->template('dangky');
            $email->from(array(EMAIL_SENDING => EMAIL_DISPLAY_SENDING));
            $email->to('dinhienhy@gmail.com');
            $email->viewVars(array('fullname' => 'Hien Phan',
                                    'email' => 'dinhienhy@gmail.com',
                                    'password' => '123456'));
            $email->subject('Thamgia.net - Test send email '. uniqid());
            $email->send('My message');
            
            
            //$this->redirect(array('controller'=>'home', 'action'=>'index'));    
        }
        
        function testTime(){
            $this->autoRender = true;
            $this->layout = 'ajax';
            
            var_dump(Router::url(array('controller' =>'Home', 'action' => 'testTime')));
            var_dump(date_default_timezone_get());
            var_dump(date('m/d/Y h:i:s a', time()));
                                                                                                            
        }
        
        function redirectToCurrentCity(){
            $this->redirect(array('controller'=>'Home', 
                                        'action'=>'city', 
                                        'slug' =>  Link::seoTitle($this->Session->read(CITY_NAME)),
                                        'city_id'=> $this->Session->read(CITY_ID)));
        }
    }
?>
