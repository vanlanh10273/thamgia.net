<?php
    $minScrollMonth = 3;
    //$currentMonth = new DateTime();

    $startMonth = new DateTime();
    $startMonth = new DateTime($startMonth->format('Y-m-01'));
    $startMonth = $startMonth->modify('-6 months');

    $endMonth = new DateTime();
    $endMonth = new DateTime($endMonth->format('Y-m-01'));
    $endMonth = $endMonth->modify('+5 months');

    $countMonth = 1;
?>
<div  id="month-navigation">
    <div class="pt-how-jcarouselLite">
        <div class="wd-jcarouselLite" >
            <a href="#" class="prev">  </a>
            <div class="wd-jcarouselLite-content" >
                <ul id="month-content">
                    <?php while($startMonth <= $endMonth){
                        // set class for li
                        $liClass = '';
                        if ($countMonth % $minScrollMonth == 0)
                            $liClass .= ' pt-bd-none';
                    ?>
                        <li value="<?php echo $startMonth->getTimestamp(); ?>" class="<?php echo $liClass;?>">
                            <a href="javascript:" onclick="selectMonth(<?php echo $startMonth->getTimestamp(); ?>)" ><?php echo 'Tháng ' . $startMonth->format('m/Y'); ?> </a>
                        </li>
                    <?php
                        $startMonth->modify('+1 month');
                        $countMonth++;
                    }?>
                </ul>
            </div>
            <a href="#" class="next">  </a>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#center').append($('#month-navigation').html());
        $('#month-navigation').empty();

       $(".today-title").click(function(){$(this).toggleClass("active");
            $(this).next(".wd-jcarouselLite-vertical").stop('true','true').slideToggle("slow");});

        $(".wd-jcarouselLite .wd-jcarouselLite-content").jCarouselLite({
            visible: <?php echo $minScrollMonth ?>,
            auto: 0,
            scroll: <?php echo $minScrollMonth ?>,
            btnNext: ".wd-jcarouselLite .next",
            btnPrev: ".wd-jcarouselLite .prev",
            speed: 500,
            start: 6
        });

    });

    function selectMonth(month){
        $("#month-content li").removeClass('active');
        $("#month-content li").filter(function() {
            return $(this).attr("value") ===  month ;
        }).toggleClass('active');
        loadEventOfMonth(month);
        $("html, body").animate({ scrollTop: 135 }, "slow");
    }
</script>