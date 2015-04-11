<article class="module width_full">
    <header>
        <h3 class="tabs_involved"> Emails</h3>
    </header>

    <div class="tab_container">
        <div id="tab1" class="tab_content">
        <table class="tablesorter" cellspacing="0"> 
        <thead> 
            <tr> 
                <th>Email</th> 
                <th>Quantity</th>
                <th>Tương tác</th>
            </tr> 
        </thead> 
        <tbody> 
            <?php foreach($data as $email){ ?>
            <tr>
                <td><?php echo $email['AccountEmail']['username']; ?></td>
                <td><?php echo $email['AccountEmail']['quantity']; ?></td>
                <td>
                    <a href="<?php echo $this->Html->url(array('controller' => 'AccountEmails', 'action' => 'setActive', $email['AccountEmail']['id'])); ?>">Active</a>
                    <a class="installed" href="<?php echo $this->Html->url(array('controller' => 'AccountEmails', 'action' => 'edit', $email['AccountEmail']['id'])); ?>"><input type="image" src="<?php echo $this->Html->url('/'); ?>images/icn_edit.png" title="Chỉnh Sửa"></a> 
                    <a target="_blank" href="<?php echo $this->Html->url(array('controller' => 'SendEmail', 'action' => 'testSendMail', $email['AccountEmail']['id'])); ?>">Test</a>
                </td>
            </tr>    
            <?php } ?> 
        </tbody> 
        </table>
        </div><!-- end of #tab1 -->

    </div><!-- end of .tab_container -->

    <footer>
        
    </footer>
            
</article><!-- end of content manager article -->

<div class="paging">
<?php
  echo $this->Paginator->prev(__(''), array('tag' => 'span'));
  echo $this->Paginator->numbers(array('separator'=>'<span>-</span>'));      
  echo $this->Paginator->next(__(''), array('tag' => 'span'));
?>
</div>

