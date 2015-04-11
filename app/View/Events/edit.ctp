<?php
    /*echo $this->Html->css('jquery-ui/smoothness/jquery-ui-1.8.22.custom'); */
    echo $this->Html->script('jquery.charsleft-0.1.js');
    echo $this->Html->script('jquery-ui-timepicker-addon.js');
    echo $this->Html->script('jquery-ui-sliderAccess.js');
?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#title").charsLeft({
            maxChars: 100,
            attachment: "after",
            charPrefix: "(Còn lại",
            charSuffix: "ký tự)"
        });
        $('#start').datetimepicker({dateFormat: 'dd/mm/yy'});
        $('#end').datetimepicker({dateFormat: 'dd/mm/yy'});
        
        $("#frm_add_event").validationEngine();
        
        $('#free').live('change', function(){
            if($(this).is(':checked')){
                $('#fee').attr('disabled', 'disabled');
            }
        });
        
        $('#not-free').live('change', function(){
            if($(this).is(':checked')){
                $('#fee').removeAttr('disabled');
            }
        });
        
        if($("#not-free").is(':checked')){
            $('#fee').removeAttr('disabled');
        }else{
            $('#fee').attr('disabled', 'disabled');   
        }
        
    });
    var defaultFeeValue = "<?php echo DEFAULT_FEE; ?>";
    var free = true;
    function feeFocus(){
        if ($('#fee').val() == defaultFeeValue){
            $('#fee').val('');
            $('#fee').attr('class', 'validate[required] text');
        }
    }
    
    function feeBlur(){
        if ($('#fee').val() == ''){
            $('#fee').val(defaultFeeValue);
            
            if (free){
                $('#fee').attr('class', 'text');
            }
                
        }
    }
    
    function freeClick(){
        free = true;
        $('#fee').attr('class', 'text');   
    }
    
    function notFreeClick(){
        free = false;
        $('#fee').attr('class', 'text');
    }
</script>

<?php 
    $cityId = isset($data['city_id']) ? $data['city_id'] : 0;
    $typeId = isset($data['type_id']) ? $data['type_id'] : 0;
    $careerId = isset($data['career_id']) ? $data['career_id'] : 0;    
    $free = isset($data['free']) ? $data['free'] : true;
    $fee = isset($data['fee']) ? $data['fee'] : DEFAULT_FEE;
?>

<div class="content-center">
    <div class="content-event">
        <?php if (isset($error)){?>
            <div class="error"><?php echo $error ?></div>
        <?php } ?>
        
        <h2 class="titel">SỰ KIỆN SẮP DIỄN RA</h2>
        <div class="subscription">
            <form id="frm_add_event" name="frm_add_event" method="post" action="<?php echo $this->Html->url('/') ?>events/edit/<?php echo isset($data['id']) ? $data['id'] : '' ?>" class="login-subscription" enctype="multipart/form-data">
                <fieldset>
                    <input type="hidden" name="data[Event][user_id]" value="<?php echo $users_userid; ?>" />
                    <ul>
                        <li>
                            <label>Sự kiện *</label>
                            <input type="text" name="data[Event][title]" title="" value="<?php echo isset($data['title']) ? htmlentities($data['title'], ENT_QUOTES, 'UTF-8') : ''; ?>" id="title" class="validate[required] text"/>
                            
                        </li>
                        <li>
                            <label>Địa điểm *</label>
                            <input type="text" name="data[Event][address]" title="" value="<?php echo isset($data['address']) ? $data['address'] : ''; ?>" id="address" class="validate[required] text"/>
                            <div class="select-1">
                                <select id="city_id" name="data[Event][city_id]">
                                    <?php foreach ($cities as $city) {?>
                                    <option value="<?php echo $city['City']['id']; ?>" <?php if ($city['City']['id'] == $cityId) echo 'selected="selected"'; ?> >
                                        <?php echo $city['City']['name']; ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </li>
                        <li class="reservation">
                            <fieldset id="form-reservation-1">
                                <div class="calendar">
                                    <label>Bắt đầu *</label>
                                    <input type="text" readonly="true" value="<?php echo isset($data['start']) ? $data['start'] : '' ?>" id="start" name="data[Event][start]" class="validate[required]" />
                                </div>
                                <div class="calendar last">
                                    <label>Kết thúc *</label>
                                    <input type="text" readonly="true" id="end" name="data[Event][end]" value="<?php echo isset($data['end']) ? $data['end'] : '' ?>" class="validate[required]" />
                                </div>
                            </fieldset>    
                            <span class="help tooltip">(Chỉ dẫn: Vui lòng kéo thanh trượt ở cửa sổ lịch để chọn giờ và phút)</span>
                        </li>
                        <li>
                            <label>Thể loại</label>
                            <div class="select-1">
                                <select id="type" name="data[Event][type_id]">
                                    <?php foreach ($types as $type) {?>
                                    <option value="<?php echo $type['Type']['id']; ?>"  <?php if ($type['Type']['id'] == $typeId) echo 'selected="selected"'; ?>>
                                        <?php echo $type['Type']['name']; ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </li>
                        <li>
                            <label>Lĩnh vực</label>
                            <div class="select-1">
                                <select id="career" name="data[Event][career_id]">
                                    <?php foreach ($careers as $career) {?>
                                    <option value="<?php echo $career['Career']['id']; ?>"  <?php if ($career['Career']['id'] == $careerId) echo 'selected="selected"'; ?>>
                                        <?php echo $career['Career']['name']; ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </li>
                        <li class="expense">
                            <label>Chi phí</label>
                            <div class="free">
                                <input id="free" type="radio" name="data[Event][free]" title="" onclick="freeClick()" value="1" <?php if ($free) echo 'checked="checked"';?> id="is_free" class="checkbox"/>
                                <label>Miễn phí</label>
                            </div>
                            <div class="computer-expense">
                                <input id="not-free" type="radio" name="data[Event][free]" title="" onclick="notFreeClick()" value="0" <?php if (!$free) echo 'checked="checked"';?> id="is_subscribed4" class="checkbox-computer"/>
                                <label>Có tính phí</label>
                                <input type="text" name="data[Event][fee]" title="" id="fee" class="text" value="<?php echo $fee; ?>" 
                                    onfocus="feeFocus()" onblur="feeBlur()"/>
                                <span class="tooltip">(Khuyến cáo nhập chi phí bằng VNĐ)</span>
                            </div>
                        </li>
                        <li class="hotline">
                            <label>Hotline</label>
                            <input type="text" name="data[Event][hotline]" title="" value="<?php echo isset($data['hotline']) ? $data['hotline'] : '' ?>" id="is_name5" class="text"/>
                        </li>
                         <li>
                            <label>Hình ảnh</label>
                            <div class="information-users">
                                <div class="information-users-left">
                                    <img alt="" style="float:left;" width="129" height="128" src="<?php echo $this->Html->url('/') .  (isset($data['image_url']) ? $data['image_url'] : '')  ?>" />
                                </div>
                                <div class="information-users-right">
                                    <input type="file" name="data[Event][image]"  id="FileImage" />   
                                </div>
                            </div>
                            
                        </li>
                        <li class="message" >
                            <label>Mô tả</label>
                            <?php echo $this->fck->ckeditor(array('Event', 'description'), $this->webroot, isset($data['description']) ? $data['description'] : ''); ?>
                        </li>
                        <li class="button">
                            <button class="button-1" title="Lưu Sự kiện" type="submit"><span>Đăng Sự kiện</span></button>
                            <button class="button-2" title="Nhập lại" type="reset"><span>Nhập lại</span></button>
                        </li>
                    </ul>
                </fieldset>    
            </form>
        </div>
    </div>
</div>

