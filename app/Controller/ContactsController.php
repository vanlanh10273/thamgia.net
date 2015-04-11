<?php
    /*App::import('Vendor', 'importer/gmail/GmailOath');
    App::import('Vendor', 'importer/gmail/GmailGetContacts');*/
    App::import('Vendor', 'OAuth/OAuthClient');                                                                         
    
    class ContactsController extends AppController{
        public $helpers = array('Html'); 
        
        public function beforeFilter() {
            parent::beforeFilter();
            $this->loadModel('Email');
        }
        
        public function requestGmailContacts($event_id = null){
            $this->autoRender = false;
            $this->Session->write(EVENT_ID_CONTACT, $event_id);
            $client = $this->createGmailClient();
            $parameter['scope'] = 'https://www.google.com/m8/feeds';
            $requestToken = $client->getRequestToken('https://www.google.com/accounts/OAuthGetRequestToken', GMAIL_CALL_BACK, 'POST', $parameter);
            
            if ($requestToken) {
                $this->Session->write(GMAIL_REQUEST_TOKEN, $requestToken);
                $urlRequest = 'https://www.google.com/accounts/OAuthAuthorizeToken?oauth_token=' . $requestToken->key;
                $this->redirect($urlRequest);
            } else {
                var_dump($requestToken);
            }
            
            /*$argarray = 'script.php';
            $debug = 0; // Set to 1 for verbose debugging output
            
            $oauth = new GmailOath(GMAIL_CONSUMER_KEY, GMAIL_CONSUMER_SECRET, $argarray, $debug, GMAIL_CALL_BACK);
            $getcontact=new GmailGetContacts();
            $access_token=$getcontact->get_request_token($oauth, false, true, true);
            
            $this->Session->write('oauth_token', $access_token['oauth_token']);
            $this->Session->write('oauth_token_secret', $access_token['oauth_token_secret']);
            $this->redirect("https://www.google.com/accounts/OAuthAuthorizeToken?oauth_token=".$oauth->rfc3986_decode($access_token['oauth_token']));*/
        }
        
        public function getGmailContacts(){
            $event_id = $this->Session->read(EVENT_ID_CONTACT);
            $this->set('event_id_contact', $event_id);
            
            $requestToken = $this->Session->read(GMAIL_REQUEST_TOKEN);
            $client = $this->createGmailClient();
            $accessToken = $client->getAccessToken('https://www.google.com/accounts/OAuthGetAccessToken', $requestToken);
            $response = $client->get($accessToken->key, $accessToken->secret, 'https://www.google.com/m8/feeds/contacts/default/full?alt=json&max-results='. EMAILS_COUNT);
            $body = json_decode($response->body);
            $contacts = $body->feed->entry;
            $gmails = array();
            foreach($contacts as $item){ 
                $email = $this->getGmail($item);
                if ($email != '')
                    array_push($gmails, $email);
            }
            $this->set('emails', $gmails);
            $this->render('get_contacts', 'ajax');
        }   
        
        public function requestYahooContacts($event_id = null){
            $this->autoRender = false;
            $this->Session->write(EVENT_ID_CONTACT, $event_id);
            
            $client = $this->createYahooClient();
            
            $requestToken = $client->getRequestToken('https://api.login.yahoo.com/oauth/v2/get_request_token', YAHOO_CALL_BACK);
            
            if ($requestToken) {
                $this->Session->write(YAHOO_REQUEST_TOKEN, $requestToken);
                $urlRequest = 'https://api.login.yahoo.com/oauth/v2/request_auth?oauth_token=' . $requestToken->key;
                $this->redirect($urlRequest);
            } else {
                var_dump($requestToken);
            }   
        }
        
        public function getYahooContacts(){
            $event_id = $this->Session->read(EVENT_ID_CONTACT);
            $this->set('event_id_contact', $event_id);
            
            $this->autoRender  = false;
            $requestToken = $this->Session->read(YAHOO_REQUEST_TOKEN);
            $client = $this->createYahooClient();
            $accessToken = $client->getAccessToken('https://api.login.yahoo.com/oauth/v2/get_token', $requestToken);
            $response = $client->get($accessToken->key, $accessToken->secret, 'http://social.yahooapis.com/v1/user/' . $accessToken->guid . '/contacts?count='.EMAILS_COUNT.'&format=json');
            
            $contacts = json_decode($response->body);
            $contacts = $contacts->contacts->contact;
            $yahooMails = array();
            foreach($contacts as $item){
                $email = $this->getYahooMail($item->fields);
                if ($email != '')
                    array_push($yahooMails, $email);
            }
            
            $this->set('emails', $yahooMails);
            $this->render('get_contacts', 'ajax');
        }
        
        public function sendMailToCantacts($event_id){
            
            $this->autoRender = false;
            
            $data = $this->request->data('Contact');
            // get event detail
            $this->loadModel('Event');
            $options['joins'] = array(
                    array(
                            'table' => 'users',
                            'alias' => 'User',
                            'type' => 'LEFT',
                            'conditions' => array(
                            'User.id = Event.user_id',
                            )
                    ),
                    array(
                            'table' => 'cities',
                            'alias' => 'City',
                            'type' => 'LEFT',
                            'conditions' => array(
                            'City.id = Event.city_id',
                            )
                    )
            );
            $options['conditions'] = array(
                'Event.id' => $event_id
            );
            
            $options['fields'] = array(
                'User.fullname',
                'User.id',
                'Event.id',
                'Event.type_id',
                'Event.city_id',
                'Event.title',
                'Event.start',
                'Event.end',
                'Event.address',
                'Event.hotline',
                'Event.free',
                'Event.fee',
                'Event.image_url',
                'Event.description',
                'Event.created',
                'Event.views',
                'City.name'
            );
            
            $event = $this->Event->find('first', $options);
            $dayOfWeek = array();
            $dayOfWeek[1] = "Thứ 2";
            $dayOfWeek[2] = "Thứ 3";
            $dayOfWeek[3] = "Thứ 4";
            $dayOfWeek[4] = "Thứ 5";
            $dayOfWeek[5] = "Thứ 6";
            $dayOfWeek[6] = "Thứ 7";
            $dayOfWeek[7] = "Chủ nhật";
            
            $dataEvent = array();
            $dataEvent['title'] = $event['Event']['title'];
            $startTimesObj = new DateTime($event['Event']['start']);
            $dataEvent['start'] = 'Từ: '.$dayOfWeek[$startTimesObj->format('N')] . ', '. $startTimesObj->format('d'). ' Tháng '. $startTimesObj->format('m'). ' ' . $startTimesObj->format('Y'). ' '. $startTimesObj->format('H').':'. $startTimesObj->format('i');
            
            $endTimesObj = new DateTime($event['Event']['end']);
            $dataEvent['end'] = 'Đến: '.$dayOfWeek[$endTimesObj->format('N')] . ', '. $endTimesObj->format('d'). ' Tháng '. $endTimesObj->format('m'). ' ' . $endTimesObj->format('Y'). ' '. $endTimesObj->format('H').':'. $endTimesObj->format('i'); 
            $dataEvent['address'] = $event['Event']['address'].' - '.$event['City']['name'];
            
            // store emails to queue
            $arrEmail = array();
            $recordEmail = array();
            $recordEmail['priority'] = PRIORITY_NORMAL;
            $recordEmail['message'] = $data['message'];
            
            $parameters = array();
            $parameters['template'] = 'inviter';
            $parameters['subject'] = $this->_usersName(). ' vừa mời bạn tham gia một sự kiện tại thamgia.net';
            
            $parameters['event_id'] = $event_id;
            $parameters['user_name'] = $this->_usersName();
            $parameters['event_title'] = $dataEvent['title'];
            $parameters['event_time'] = $dataEvent['start'] . ' - ' . $dataEvent['end'];
            $parameters['event_address'] = $dataEvent['address'];
            
            foreach($data['emails'] as $emailAddress){ 
                $parameters['to_email'] = $emailAddress;         
                $recordEmail['parameters'] = serialize($parameters);
                array_push($arrEmail, $recordEmail);
            }
            
            $this->Email->saveAll($arrEmail);
        }
        
        private function createYahooClient() {
            return new OAuthClient( YAHOO_CONSUMER_KEY, YAHOO_CONSUMER_SECRET);
        }
        
        private function createGmailClient() {
            return new OAuthClient( GMAIL_CONSUMER_KEY, GMAIL_CONSUMER_SECRET);
        }
        
        private function getYahooMail($fields){
            $result = '';
            foreach($fields as $item){
                if (isset($item->type)){
                    if ($item->type == 'yahooid'){
                        $result = $item->value.'@yahoo.com';
                        break;
                    }else if ($item->type == 'email'){
                        $result = $item->value;
                        break;
                    }
                }
            }
            return $result;   
        }
        
        private function getGmail($entry){
            $result = '';
            $arr = get_object_vars($entry);
            if (isset($arr['gd$email'][0])){                
                $result = isset($arr['gd$email'][0]->address) ? $arr['gd$email'][0]->address : '';
            }
            return $result;
        }
        
    }
?>
