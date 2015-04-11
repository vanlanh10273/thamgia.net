<?php 
    echo $this->Html->script('jquery-ui-timepicker-addon.js');
    echo $this->Html->script('jquery-ui-sliderAccess.js');
    echo $this->Html->script('validationEngine.js');
    echo $this->Html->script('validationEngine-en.js');
?>
<script type="text/javascript">
    $(document).ready(function(){
        $('#start').datetimepicker({dateFormat: 'dd/mm/yy'});
        $('#end').datetimepicker({dateFormat: 'dd/mm/yy'});
        $('#start-event').datetimepicker({dateFormat: 'dd/mm/yy'});
        $("#frm_daily_coupon").validationEngine();   
    })
</script>
<?php 
    $city_id = isset($data['city_id']) ? $data['city_id'] : 0;
 ?>

<article class="module width_full">
    <header><h3>Thêm sự kiện nổi bật</h3></header>
        <div class="module_content">
            <form id="frm_daily_coupon" name="frm_daily_coupon" method="post" action="<?php echo $this->Html->url(array('controller' => 'Admin', 'action' =>'editDailyCoupon', $data['id'])) ?>" class="login-subscription" enctype="multipart/form-data">
                <fieldset>
                    <label>Tên sự kiện *</label>
                    <input name="data[DailyCoupon][title]" id="title" class="validate[required]" type="text" value="<?php echo isset($data['title']) ? $data['title'] : ''; ?>"  />
                    <input name="data[DailyCoupon][code]" value="<?php echo $data['code']; ?>" type="hidden" />
                </fieldset>
                 <fieldset>
                    <label>Tóm tắt *</label>
                    <input name="data[DailyCoupon][summary]" id="title" class="validate[required]" type="text" value="<?php echo isset($data['summary']) ? $data['summary'] : ''; ?>"  />
                </fieldset>
                <fieldset style="width:48%; float:left; margin-right: 3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>Bắt đầu *</label>
                    <input type="text" style="width:92%;" readonly="true" value="<?php echo isset($data['start']) ? $data['start'] : '' ?>" id="start" name="data[DailyCoupon][start]" class="validate[required]" />
                </fieldset>
                <fieldset style="width:48%; float:left;"> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>Kết thúc *</label>
                    <input type="text"  style="width:92%;" readonly="true" id="end" name="data[DailyCoupon][end]" value="<?php echo isset($data['end']) ? $data['end'] : '' ?>" class="validate[required]" />
                </fieldset>
                <fieldset style="width:48%; float:left; margin-right: 3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>Địa chỉ *</label>
                    <input type="text"  style="width:92%;"  id="address" name="data[DailyCoupon][address]" value="<?php echo isset($data['address']) ? $data['address'] : '' ?>" class="validate[required]" />   
                </fieldset>
                
                <fieldset style="width:48%; float:left;"> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>Thành Phố *</label>
                    <select name="data[DailyCoupon][city_id]">
                        
                        <?php foreach($cities as $city){ ?>
                        <option <?php if ($city['City']['id'] == $city_id) echo 'selected="selected"';  ?>  value="<?php echo $city['City']['id']; ?>" > <?php echo $city['City']['name'];  ?></option>
                        <?php } ?>
                    </select>
                </fieldset>
                <fieldset style="width:48%; float:left; margin-right: 3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>Hotline</label>
                    <input type="text"  style="width:92%;"  id="end" name="data[DailyCoupon][hotline]" value="<?php echo isset($data['hotline']) ? $data['hotline'] : '' ?>" class="validate[required]" />
                </fieldset>
                <fieldset style="width:48%; float:left;"> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>Chiết khấu</label>
                    <input type="text"  style="width:92%;"  id="end" name="data[DailyCoupon][discount]" value="<?php echo isset($data['discount']) ? $data['discount'] : '' ?>" class="validate[required]" />
                </fieldset>
                <div class="clear"></div>
                <div>
                    <?php echo $this->fck->ckeditor(array('DailyCoupon', 'description'), $this->webroot, isset($data['description']) ? $data['description'] : ''); ?>
                </div>
                <fieldset> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>Hình ảnh</label>
                    <img alt="" style="float:left;" width="129" height="128" src="<?php echo $this->Html->url('/') .  (isset($data['image_url']) ? $data['image_url'] : '')  ?>" />
                    <input  style="width:92%;"  type="file" name="data[DailyCoupon][image]"  id="FileImage" />
                    <input type="hidden" name="data[DailyCoupon][image_url]" value="<?php echo $data['image_url']; ?>" >
                    <input type="hidden" name="data[DailyCoupon][image_thumb_url]" value="<?php echo $data['image_thumb_url']; ?>" >
                </fieldset>
                <div class="clear"></div>
                <input type="submit" class="alt_btn" value="Add">
                <div class="clear"></div>
            </form>
        </div>
    <footer>
        <div class="submit_link">
        </div>
    </footer>
</article>