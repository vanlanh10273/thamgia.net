<?php 
    echo $this->Html->script('jquery-ui-timepicker-addon.js');
    echo $this->Html->script('jquery-ui-sliderAccess.js');
    echo $this->Html->script('validationEngine');
    echo $this->Html->script('validationEngine-en');
    
?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#frm_email").validationEngine();   
    })
</script>
<?php if (isset($error)){?>
    <h4 class="alert_error"><?php echo $error ?></h4>
<?php } ?>
<article class="module width_full">
    <header><h3>Thêm user</h3></header>
        <div class="module_content">
            <form id="frm_email" name="frm_user" method="post" action="<?php echo $this->Html->url(array('controller' => 'AccountEmails', 'action' =>'edit', $data['id'])) ?>" class="login-subscription" enctype="multipart/form-data">
                <fieldset style="width:48%; float:left; margin-right: 3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>username *</label>
                    <input type="text" class="validate[required]" style="width:92%;" value="<?php echo isset($data['username']) ? $data['username'] : '' ?>" id="email" name="data[AccountEmail][username]"  />
                </fieldset>
                <fieldset style="width:48%; float:left;"> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>password *</label>
                    <input type="text" class="validate[required]" style="width:92%;" value="<?php echo isset($data['password']) ? $data['password'] : '' ?>" id="email" name="data[AccountEmail][password]"  />
                    
                </fieldset>
                <fieldset style="width:48%; float:left; margin-right: 3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>host *</label>
                    <input type="text" style="width:92%;"  value="<?php echo isset($data['host']) ? $data['host'] : '' ?>" id="fullname" name="data[AccountEmail][host]" class="validate[required]" />
                </fieldset>
                <fieldset style="width:48%; float:left;"> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>port *</label>
                    <input type="text" class="validate[required]" style="width:92%;" value="<?php echo isset($data['port']) ? $data['port'] : '' ?>" id="email" name="data[AccountEmail][port]"  />
                </fieldset>
                <fieldset style="width:48%; float:left; margin-right: 3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>transport *</label>
                    <input type="text" style="width:92%;"  value="<?php echo isset($data['transport']) ? $data['transport'] : '' ?>" id="fullname" name="data[AccountEmail][transport]" class="validate[required]" />
                </fieldset>
                <fieldset style="width:48%; float:left;"> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>active*</label>
                    <select name="data[AccountEmail][active]">
                        <option value="1">yes</option>   
                        <option value="0">no</option>
                    </select>
                </fieldset>                
                <div class="clear"></div>
                <input type="submit" class="alt_btn" value="Edit">
            </form>
        </div>
    <footer>
        <div class="submit_link">
        </div>
    </footer>
</article>