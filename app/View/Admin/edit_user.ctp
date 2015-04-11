<?php 
    echo $this->Html->script('jquery-ui-timepicker-addon.js');
    echo $this->Html->script('jquery-ui-sliderAccess.js');
    echo $this->Html->script('validationEngine');
    echo $this->Html->script('validationEngine-en');
    
    $career_id = isset($data['careerid']) ? $data['careerid'] : 0;
    $role_id = isset($data['level']) ? $data['level'] : 0;
    $city_id = isset($data['cityid']) ? $data['cityid'] : $city_id;
?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#frm_user").validationEngine();   
    })
</script>
<?php if (isset($error)){?>
    <h4 class="alert_error"><?php echo $error ?></h4>
<?php } ?>
<article class="module width_full">
    <header><h3>Thêm user</h3></header>
        <div class="module_content">
            <form id="frm_user" name="frm_user" method="post" action="<?php echo $this->Html->url(array('controller' => 'Admin', 'action' =>'editUser', $data['id'])) ?>" class="login-subscription" enctype="multipart/form-data">
                <fieldset style="width:48%; float:left; margin-right: 3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>email *</label>
                    <input type="text" class="validate[required]" readonly="readonly" style="width:92%;" value="<?php echo isset($data['email']) ? $data['email'] : '' ?>" id="email" name="data[User][email]"  />
                    <input type="hidden" name="data[User][id]" value="<?php echo $data['id']; ?>" />
                </fieldset>
                <fieldset style="width:48%; float:left;"> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>Thành phố *</label>
                    <select name="data[User][cityid]">
                        <?php foreach($cities as $city){ ?>
                        <option <?php if ($city['City']['id'] == $city_id) echo 'selected="selected"';  ?>  value="<?php echo $city['City']['id']; ?>" > <?php echo $city['City']['name'];  ?></option>
                        <?php } ?>
                    </select>
                </fieldset>
                <fieldset style="width:48%; float:left; margin-right: 3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>Họ tên *</label>
                    <input type="text" style="width:92%;"  value="<?php echo isset($data['fullname']) ? $data['fullname'] : '' ?>" id="fullname" name="data[User][fullname]" class="validate[required]" />
                </fieldset>
                <fieldset style="width:48%; float:left;"> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>Quyền *</label>
                    <select name="data[User][level]">
                        <?php foreach($roles as $role){ ?>
                        <option <?php if ($role['Role']['id'] == $role_id) echo 'selected="selected"';  ?>  value="<?php echo $role['Role']['id']; ?>" > <?php echo $role['Role']['name'];  ?></option>
                        <?php } ?>
                    </select>
                </fieldset>
                <fieldset style="width:48%; float:left; margin-right: 3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>Lĩnh vực *</label>
                    <select name="data[User][careerid]">
                        <?php foreach($careers as $career){ ?>
                        <option <?php if ($career['Career']['id'] == $career_id) echo 'selected="selected"';  ?>  value="<?php echo $career['Career']['id']; ?>" > <?php echo $career['Career']['name'];  ?></option>
                        <?php } ?>
                    </select>
                </fieldset>                
                <div class="clear"></div>

                <fieldset> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>Banner</label>
                    <input  style="width:92%;"  type="file" name="data[User][avatar]"  id="FileImage" />
                </fieldset>
                <div class="clear"></div>
                <input type="submit" class="alt_btn" value="Update">
                <div class="clear"></div>
            </form>
        </div>
    <footer>
        <div class="submit_link">
        </div>
    </footer>
</article>