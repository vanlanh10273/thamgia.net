<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo $this->Html->charset(); ?>
<title><?php echo $title; ?></title>
<meta name="description" content="thamgia.net - Mạng chia sẻ sự kiện, hội thảo, khóa đào tạo, triển lãm, khai trương và sự kiện cộng đồng khác diễn ra tại các thành phố lớn. Hòa mạng tham gia, mở ra cơ hội." />
<meta name="keywords" content="sự kiện, hội thảo, triển lãm, khai trương, khánh thành, sự kiện Đà Nẵng, su kien Da nang, hội thảo Đà Nẵng, hoi thao Da nang, su kien Ha noi, sự kiện Hà nội, hội thảo Hà nội, hội thảo tp HCM, chia se su kien, dien gia, diễn giả, nguoi khong lo, người khổng lồ, hội thảo du học, hoi thao du học, tham gia" />
<?php 
    echo $this->Html->css('common'); 
    echo $this->Html->script('jquery-1.7.1.min');
    
?>
</head>

<body style="background-color:#303233">
    <form id="frm_participate" name="frm_participate" method="post" action="<?php echo $this->Html->url(array("controller" => "Home", "action" => "selectCity")); ?>" class="login-subscription">
        <div id="popup">
            <div class="popup-content">
                <p class="text">Bạn mong muốn nhận thông tin sự kiện từ thành phố nào ?</p>
                <div class="how">
                    <div class="select-1">
                        <select name="data[city_id]">
                            <?php foreach($cities as $city){ ?>
                                <option value="<?php echo $city['City']['id'];?>" ><?php echo $city['City']['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <button class="button-2" title="Tiếp Tục" type="submit"><span>Tiếp Tục</span></button>
                </div>
            </div>
        </div>
    </form>

    
</body>
</html>


<script type="text/javascript">
        $(document).ready(function() {
            if (!$.browser.opera) {
                $('select').each(function(){
                    var title = $(this).attr('title');
                    if( $('option:selected', this).val() != ''  ) title = $('option:selected',this).text();
                    $(this)
                        .css({'z-index':10,'opacity':0,'-khtml-appearance':'none'})
                        .after('<span class="select">' + title + '</span>')
                        .change(function(){
                            val = $('option:selected',this).text();
                            $(this).next().text(val);
                            })
                });
            };    
        });
        
</script>

