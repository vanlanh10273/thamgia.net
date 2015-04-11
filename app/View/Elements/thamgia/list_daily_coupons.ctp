<?php 
    $limit = 12;
    $from_id = 0;
    
    
    $data = array();
    
    $data = $this->requestAction(array('controller' => 'DailyCoupons', 'action' => 'getListDailyCoupons', $city_id, $limit, $from_id));    
    $index = 0;
?>
<ul class="view-as">
    <li><a href="#" class="grid active" rel="1">grid</a></li>
    <li><a href="#" class="list"  rel="2">list</a></li>
</ul>
<div class="yoxview">
    <ul id="events-new" class="events-new">
        <?php foreach($data as $daily_coupon){
            $url_detail = $this->Html->url(array(
                                                "controller"=>"DailyCoupons", 
                                                "action"=> "detail",
                                                'slug' => Link::seoTitle($daily_coupon['DailyCoupon']['title']),
                                                'id'=> $daily_coupon['DailyCoupon']['id']));
            $startDate =  new DateTime($daily_coupon['DailyCoupon']['start']);
        ?>
        <li class="item" for="<?php echo $index++; ?>">
            <span class="promotion-01"><?php echo $daily_coupon['DailyCoupon']['discount'];?></span>
            <div class="how-img">
                <a class="img" href="<?php echo $url_detail ?>">
                    <img src="<?php echo $this->Html->url('/') . ($daily_coupon['DailyCoupon']['image_url'] != '' ? $daily_coupon['DailyCoupon']['image_url'] : NO_IMG_URL); ?>" alt="Image"  />
                </a>
            </div>
            <div class="how">
                <h3><a href="<?php echo $url_detail; ?>"><?php echo $daily_coupon['DailyCoupon']['title']; ?></a></h3>
                <p><?php echo $daily_coupon['DailyCoupon']['address']; ?></p>
                <span><span id="view_<?php echo $daily_coupon['DailyCoupon']['id']; ?>">Xem: <?php echo ($daily_coupon['DailyCoupon']['views'] + DEFAULT_VIEW) ?></span><span id="thanks_<?php echo $daily_coupon['DailyCoupon']['id']; ?>">Cảm ơn: <?php echo $daily_coupon['DailyCoupon']['thanks']; ?></span></span>
            </div>
            <a href="#" class="day"><?php echo $startDate->format('d-m-Y'); ?></a>
        </li>
        <?php } ?>
        
    </ul>
</div>
<div class="view-more">
    <img src="<?php echo $this->Html->url('/'); ?>img/ajax-loader.gif" alt="Image"  />
    <!--<a href="#">Xem Thêm</a> -->
</div>

<script type="text/javascript">
    $(document).ready(function() {
         //lastAddedLiveFunc();
         window.loadMore = true;
        $(window).scroll(function(){

            var wintop = $(window).scrollTop(), docheight = $(document).height(), winheight = $(window).height();
            var  scrolltrigger = 0.75;

            if  ((wintop/(docheight-winheight)) > scrolltrigger) {
                if (window.loadMore){
                    loadMoreEvents();
                }
            }
        });
        
        $('.events-new').addClass('grid-content');
        $(".grid").click(function () {
            $('.grid').addClass('active');
            $('.list').removeClass('active');
            $('ul.events-new').addClass('grid-content').slideDown();
            $('ul.events-new').removeClass('list-content');
            return false;
        });
        
        $(".list").click(function () {
            $('.list').addClass('active');
            $('.grid').removeClass('active');
            $('ul.events-new').addClass('list-content');
            $('ul.events-new').removeClass('grid-content');
            return false;
        });
        
        var $container = $('#events-new');

        $container.imagesLoaded( function(){
          $container.masonry({
             itemSelector : '.item',
             columnWidth: 3
          });
        });
         
    });
    function reorderList(){
        var $container = $('#events-new');

        $container.imagesLoaded( function(){
            $container.masonry('reload'); 
        });
    }
   
    function loadMoreEvents(){
        window.loadMore = false;
        $('.view-more img').show();
        $('.view-more a').hide();
        var from = $(".events-new").find("li.item").last().attr("for");
        var data = new Array();
        var url = '';
        data = {city_id: '<?php echo $city_id; ?>' , limit: 6, from: from};
        url = '<?php echo $this->Html->url(array("controller" => "DailyCoupons", "action" => "moreDailyCoupons"))?>';
        
        $.ajax({
           type:"GET",
           async:false,
           cache: false,
           url: url,
           data: data,
           success : function(data) {
                if (data != ''){
                    $('#events-new').append(data);
                    reorderList();
                    window.loadMore = true;
                    /*$('.view-more img').hide('slow');
                    $('.view-more a').show('slow');*/
                }else{
                    $('.view-more img').hide('slow');
                    /*$('.view-more a').hide('slow');*/
                    window.loadMore = false;
                }
                
           },
           error : function() {
               alert('Lỗi load daily coupon!');
           },
       });    
    } 
    
</script>