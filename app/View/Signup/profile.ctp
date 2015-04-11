<!-- File: /app/View/Signup/profile.ctp -->
<?php echo $this->Html->script('jquery.charsleft-0.1.js'); ?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#fullname").charsLeft({
            maxChars: 25,
            attachment: "after",
            charPrefix: "(Còn lại",
            charSuffix: "ký tự)"
        });
        $("#frm_profile").validationEngine();
    });
    var currentDays = 31;
    
    function daysInMonth(month, year) {
        return new Date(year, month, 0).getDate();
    }
    
    function changeDay(){
        try{
            var year = $('#year').val();
            var month = $('#month').val();
            if (!isNaN(year) && !isNaN(month)){
                var newDay = daysInMonth(month, year);
                if (newDay != currentDays){
                    currentDays = newDay;
                    $('#day').empty();
                    var i=0;
                    $("#day").append('<option>Ngày</option>');
                    for(i=1; i<=currentDays; i++)
                        $("#day").append('<option value="'+ i + '">'+ i +'</option>');
                    $('#day').val('1');
                    $('.select-day .select').html('1');
                }   
            }
        }
        catch(ex){
            
        }
    }
</script>
<?php 
    $fullname ='';
    if (isset($data)){
        $fullname = $data['name'];
    }
    
    $hasError = true;   
    if (empty ($error))
        $hasError = false;
?>

<h2 class="titel">ĐĂNG KÝ THÀNH VIÊN</h2>
<?php if ($hasError){ ?>
    <div class="error"><?php echo $error ?></div>
<?php } ?>
<div class="registered-users-left">
    <div class="login-successful">
        <form id="frm_profile" name="frm_profile" method="post" action="<?php echo $this->Html->url('/') ?>signup/profile" class="login-subscription" enctype="multipart/form-data">
            <fieldset>
                <ul class="successful">
                    <li>
                        <label>Họ tên</label>
                        <input type="text" name="data[Profile][name]" title="" value="<?php echo $fullname; ?>" id="fullname" class="validate[required] text"/>
                    </li>
                    <li>
                        <label>Ngày sinh</label>
                        <div class="select-year">
                            <select id="year" onchange="changeDay()" name="data[Profile][year]">
                                <option>Năm</option>
                                <?php 
                                for ($i=2012; $i>=1950; $i--){?>
                                    <option value="<?php echo $i ?>"><?php echo $i ?></option>    
                                <?php }?>
                            </select>
                        </div>
                        <div class="select-months">
                            <select id="month" onchange="changeDay()" name="data[Profile][month]">
                                <option>Tháng</option>
                                <option value="1">01</option>
                                <option value="2">02</option>
                                <option value="3">03</option>
                                <option value="4">04</option>
                                <option value="5">05</option>
                                <option value="6">06</option>
                                <option value="7">07</option>
                                <option value="8">08</option>
                                <option value="9">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </select>
                        </div>
                        <div class="select-day">
                            <select id="day" name="data[Profile][day]">
                                <option>Ngày</option>
                                <?php 
                                for ($i=1; $i<=31; $i++){?>
                                    <option value="<?php echo $i ?>"><?php echo $i ?></option>    
                                <?php }?>
                            </select>
                        </div>
                        <span></span>
                    </li>
                    <li>
                        <label>Đển từ</label>
                         <div class="select-1">
                            <select id="city" name="data[Profile][city]">
                                <?php foreach ($cities as $city) {?>
                                <option value="<?php echo $city['City']['id']; ?>">
                                    <?php echo $city['City']['name']; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                    </li>
                    <li>
                        <label>Lĩnh vực hoạt động</label>
                         <div class="select-1">
                            <select id="career" name="data[Profile][career]">
                                <?php foreach ($careers as $career) {?>
                                <option value="<?php echo $career['Career']['id']; ?>">
                                    <?php echo $career['Career']['name']; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                    </li>
                    <li>
                        <label>Hình đại diện</label>
                        <div class="up-img">
                            <img src="<?php echo $this->Html->url('/'); ?>img/img-02.png" alt="Image">
                            <input type="file" name="data[Profile][avatar]"  id="FileImage" />
                            <span>Ảnh phải dưới 2M (jpg, png...)</span>
                        </div>
                        
                    </li>
                    <li class="button">
                        <button class="button-3" title="Tham Gia" type="submit"><span>Hoàn tất</span></button>
                        <button class="button-2" title="Tham Gia" onclick="$('#frm_profile')[0].reset(); return false;" type="submit"><span>Hoàn tất</span></button>
                    </li>
                </ul>
            </fieldset>
        </form>
    </div>    
</div>

<div class="registered-users-right">
    <ul>
        <li class="complete">
            <p><span>1</span>Thông tin tài khoản</p>
            <div class="border-left-01"></div>
        </li>
        <li class="active">
            <p><span>2</span>Thông tin cá nhân</p>
            <div class="border-left-01"></div>
        </li>
        <li>
            <p><span>3</span>Hoàn tất</p>
            <div class="border-left-02"></div>
        </li>
        
    </ul>
</div>


