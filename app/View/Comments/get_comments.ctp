<?php 

?>
<script type="text/javascript">
    function bubbleUsersThanks(e){
        if ($(e).HasBubblePopup())
            $(e).RemoveBubblePopup();
            
        $(e).CreateBubblePopup({

            position : 'top',
            align     : 'center',
            
            innerHtml: $(e).parent().find('.users-thanks').html(),            

            innerHtmlStyle: {
                                color:'#FFFFFF', 
                                'text-align':'center'
                            },

            themeName: 'all-azure',
            themePath: '<?php echo $this->Html->url('/'); ?>' + 'img/jquerybubblepopup-themes'
         
        });
    }
    
</script>    
<ul>
<?php
    foreach($data as $item){
?>
    <li>
        <img src="<?php echo General::getUrlImage($item['User']['avatar_url']);?>" alt="Image"  />
        <div class="how">
            <h4><a target="_blank" href="<?php echo $this->Html->url(array('controller' => 'Users', 'action' =>'profile', $item['User']['id'])); ?>"><?php echo $item['User']['fullname']; ?></a><span><?php echo date(TIME_FORMAT_CLIENT, strtotime($item['Comment']['created']));?></span></h4>
            <p><?php echo $item['Comment']['comment'];?> </p>
            <span class="thank"><strong onmouseover="bubbleUsersThanks(this)"><?php echo $item['Comment']['thanks']; ?> người </strong>đã cảm ơn điều này
                <a href="#" <?php   echo $logged_in ? 'onclick="thanked(this,'.$item['Comment']['id'].');return false;"' : 'onclick="login(); return false;"' ?> >cảm ơn</a>
                <div class="users-thanks" style="display: none;">
                    <?php echo $item['Comment']['usersThanks'];?>  
                </div>
            </span>
        </div>
    </li>
<?php        
} 
?>
</ul>
<script type="text/javascript">
    function thanked(e, comment_id){
        $.ajax({
               type:"GET",
               async: false,
               url:'<?php echo $this->Html->url(array("controller" => "Comments", "action" => "thanksComment")) ?>/' + comment_id,
               success : function(data) {
                    $.ajax({
                       type:"GET",
                       async: false,
                       url:'<?php echo $this->Html->url(array("controller" => "Comments", "action" => "getAComment")) ?>/' + comment_id,
                       success : function(data) {
                            $(e).parent().parent().parent().empty().append(data);
                       },
                       error : function() {
                           alert('Lỗi load comment!');
                       },
                   });
               },
               error : function() {
                   //do nothing
               },
           });
    }
</script>
