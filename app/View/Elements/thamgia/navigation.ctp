<?php $highlights = $this->requestAction(array('controller' => 'Highlights', 'action' => 'getHighlights'));  ?>

<script type="text/javascript">
    $(document).ready(function() {
        $('ul.news li').last().attr('class', 'last');
    });
    function addEvent(){
        window.location = '<?php echo $this->Html->url(array('controller' => 'Events', 'action' => 'add')); ?>';
    }
    
    function bubbleAddEvent(e){
        $(e).CreateBubblePopup({

            position : 'top',
            align     : 'center',
            
            innerHtml: 'Đăng sự kiện hoàn toàn miễn phí',            

            innerHtmlStyle: {
                                color:'#ffffff', 
                                'text-align':'center'
                            },

            themeName: 'all-azure',
            themePath: '<?php echo $this->Html->url('/'); ?>' + 'img/jquerybubblepopup-themes'
         
        });
    }
    
    function removeBubbleAddEvent(e){
        $(e).RemoveBubblePopup();
    }
</script>
<div id="navigation">
    <div class="center">
        <div class="content">
            <h2 class="titel">Được tài trợ
                <a href="<?php echo $this->Html->url(array('controller' => 'Home', 'action' => 'advertisement')); ?>">Xem chương trình tài trợ</a>
            </h2>
        </div>
        <ul class="news">
            <?php foreach($highlights as $highlight){ 
                $startDate = new DateTime($highlight['Highlight']['start_event']);
            ?>
            <li>
                <a href="<?php echo $highlight['Highlight']['event_url']?>"><img src="<?php echo $this->Html->url('/'). ($highlight['Highlight']['image_url'] != '' ? $highlight['Highlight']['image_url'] : HIGHLIGHT_NO_IMG_URL); ?>" alt="Image"  /></a>
                <h3><a href="<?php echo $highlight['Highlight']['event_url']?>"><?php echo $highlight['Highlight']['title']; ?></a></h3>
                <p><?php echo ($highlight['Highlight']['start_event'] != '') ? $startDate->format('d-m-Y') : ''; ?></p>
                <?php if ($highlight['Highlight']['free']){ ?>
                <span class="free"> </span>    
                
                <?php } ?>
                <a href="<?php echo $highlight['Highlight']['event_url']?>" class="link"></a>
            </li>
            <?php } ?>
        </ul>
     </div>
</div>
