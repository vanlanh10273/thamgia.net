<?php 
    echo $this->Html->script('jquery-ui-timepicker-addon.js');
    echo $this->Html->script('jquery-ui-sliderAccess.js');
    echo $this->Html->script('validationEngine');
    echo $this->Html->script('validationEngine-en');
?>
<script type="text/javascript">
    $(document).ready(function(){
        $('#start').datetimepicker({dateFormat: 'dd/mm/yy'});
        $('#end').datetimepicker({dateFormat: 'dd/mm/yy'});
        $('#start-event').datetimepicker({dateFormat: 'dd/mm/yy'});
        $("#frm_high_light").validationEngine();   
    })
</script>
<?php 
    $event_id = isset($data['event_id']) ? $data['event_id'] : 0;
    
    $free = isset($data['free']) ? $data['free'] : false;
 ?>
<article class="module width_full">
    <header><h3>Thêm sự kiện nổi bật</h3></header>
        <div class="module_content">
            <form id="frm_high_light" name="frm_high_light" method="post" action="<?php echo $this->Html->url(array('controller' => 'Admin', 'action' =>'addHighLight')) ?>" class="login-subscription" enctype="multipart/form-data">
                <fieldset>
                    <label>Tên sự kiện *</label>
                    <input name="data[Highlight][title]" id="title" class="validate[required]" type="text" value="<?php echo isset($data['title']) ? $data['title'] : ''; ?>"  />
                </fieldset>
                
                <fieldset style="width:48%; float:left; margin-right: 3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>Bắt đầu *</label>
                    <input type="text" style="width:92%;" readonly="true" value="<?php echo isset($data['start']) ? $data['start'] : '' ?>" id="start" name="data[Highlight][start]" class="validate[required]" />
                </fieldset>
                <fieldset style="width:48%; float:left;"> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>Kết thúc *</label>
                    <input type="text"  style="width:92%;" readonly="true" id="end" name="data[Highlight][end]" value="<?php echo isset($data['end']) ? $data['end'] : '' ?>" class="validate[required]" />
                </fieldset>
                <fieldset style="width:48%; float:left; margin-right: 3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>Link Sự kiện</label>
                    <input type="text"  style="width:92%;"  id="end" name="data[Highlight][event_url]" value="<?php echo isset($data['event_url']) ? $data['event_url'] : '' ?>" class="validate[required]" />
                </fieldset>
                
                <fieldset style="width:48%; float:left;"> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>Thành Phố *</label>
                    <select name="data[Highlight][city_id]">
                        
                        <?php foreach($cities as $city){ ?>
                        <option <?php if ($city['City']['id'] == $city_id) echo 'selected="selected"';  ?>  value="<?php echo $city['City']['id']; ?>" > <?php echo $city['City']['name'];  ?></option>
                        <?php } ?>
                    </select>
                </fieldset>
                
                <fieldset style="width:48%; float:left; margin-right: 3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>Ngày bắt đầu sự kiện *</label>
                    <input type="text"  style="width:92%;" readonly="true" id="start-event" name="data[Highlight][start_event]" value="<?php echo isset($data['start_event']) ? $data['start_event'] : '' ?>" class="validate[required]" />
                </fieldset>
                <fieldset width:48%; float:left; > <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>Miễn Phí</label>
                    <input style="margin-left: -140px;" type="checkbox"  name="data[Highlight][free]" <?php if ($free)  echo 'checked="checked"';  ?>  />
                </fieldset>
                <div class="clear"></div>
                <fieldset> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>Hình ảnh</label>
                    <input  style="width:92%;"  type="file" name="data[Highlight][image]"  id="FileImage" />
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