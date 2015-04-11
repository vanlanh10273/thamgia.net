<?php 
    echo $this->Html->script('jquery-ui-timepicker-addon.js');
    echo $this->Html->script('jquery-ui-sliderAccess.js');
    echo $this->Html->script('validationEngine');
    echo $this->Html->script('validationEngine-en');
    
    $size_id = isset($data['size_id']) ? $data['size_id'] : 0;
    $side_id = isset($data['side_id']) ? $data['side_id'] : 0;
    $city_id = isset($data['city_id']) ? $data['city_id'] : $city_id;
?>
<script type="text/javascript">
    $(document).ready(function(){
        $('#start').datetimepicker({dateFormat: 'dd/mm/yy'});
        $('#end').datetimepicker({dateFormat: 'dd/mm/yy'});
        $("#frm_advertisement").validationEngine();   
    })
</script>
<?php if (isset($error)){?>
    <h4 class="alert_error"><?php echo $error ?></h4>
<?php } ?>
<article class="module width_full">
    <header><h3>Thêm quản cáo</h3></header>
        <div class="module_content">
            <form id="frm_advertisement" name="frm_advertisement" method="post" action="<?php echo $this->Html->url(array('controller' => 'Admin', 'action' =>'addAdvertisement')) ?>" class="login-subscription" enctype="multipart/form-data">
                <fieldset style="width:48%; float:left; margin-right: 3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>Thông tin *</label>
                    <input type="text" class="validate[required]" style="width:92%;" value="<?php echo isset($data['start']) ? $data['start'] : '' ?>" id="infomation" name="data[Advertisement][information]"  />
                </fieldset>
                <fieldset style="width:48%; float:left;"> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>Thành phố *</label>
                    <select name="data[Advertisement][city_id]">
                        <?php foreach($cities as $city){ ?>
                        <option <?php if ($city['City']['id'] == $city_id) echo 'selected="selected"';  ?>  value="<?php echo $city['City']['id']; ?>" > <?php echo $city['City']['name'];  ?></option>
                        <?php } ?>
                    </select>
                </fieldset>
                <fieldset style="width:48%; float:left; margin-right: 3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>Bắt đầu *</label>
                    <input type="text" style="width:92%;" readonly="true" value="<?php echo isset($data['start']) ? $data['start'] : '' ?>" id="start" name="data[Advertisement][start]" class="validate[required]" />
                </fieldset>
                <fieldset style="width:48%; float:left;"> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>Kết thúc *</label>
                    <input type="text"  style="width:92%;" readonly="true" id="end" name="data[Advertisement][end]" value="<?php echo isset($data['end']) ? $data['end'] : '' ?>" class="validate[required]" />
                </fieldset>
                <fieldset style="width:48%; float:left; margin-right: 3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>Vị Trí *</label>
                    <select name="data[Advertisement][side_id]">
                        <?php foreach($sides as $side){ ?>
                        <option <?php if ($side['Side']['id'] == $side_id) echo 'selected="selected"';  ?>  value="<?php echo $side['Side']['id']; ?>" > <?php echo $side['Side']['name'];  ?></option>
                        <?php } ?>
                    </select>
                </fieldset>
                
                <fieldset style="width:48%; float:left;"> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>Kích Thước *</label>
                    <select name="data[Advertisement][size_id]">
                        
                        <?php foreach($sizes as $size){ ?>
                        <option <?php if ($size['Size']['id'] == $size_id) echo 'selected="selected"';  ?>  value="<?php echo $size['Size']['id']; ?>" > <?php echo $size['Size']['name'];  ?></option>
                        <?php } ?>
                    </select>
                </fieldset>
                <div class="clear"></div>
                <fieldset> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>Liên kết *</label>
                    <input  style="width:92%;"  type="text" class="validate[required]" name="data[Advertisement][url]" />
                </fieldset>
                <fieldset> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>Banner</label>
                    <input  style="width:92%;"  type="file" name="data[Advertisement][banner]"  id="FileImage" />
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