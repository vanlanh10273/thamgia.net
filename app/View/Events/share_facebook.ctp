<?php
        $url_detail = $this->Html->url(array(
                                            "controller"=>"Events", 
                                            "action"=> "detail",
                                            'slug' => $event_title,
                                            'id' => $event_id));
?>
<a name="fb_share" type="button_count" share_url="<?php echo $url_detail; ?>" href="http://www.facebook.com/sharer.php">Share</a>
<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>