<?php echo $this->element('thamgia/layout_header'); ?>
<body>
    <?php echo $this->element('thamgia/header'); ?>
    <?php echo $this->element('thamgia/navigation'); ?>
    <?php echo $this->element('thamgia/daily_coupon'); ?>
    <div id="content">
        <div class="center">
            <div class="content-right new-update-01"">
                <?php if (isset($hasEvent)){
                    echo $this->element('thamgia/event_summary',
                        array(
                            'event_id' => $data['id'],
                            'totalParticipation' => $totalParticipation,
                            'participated'=>$participated,
                            'user_name'=>$data['user_name'],
                            'timeCountDown'=>$timeCountDown,
                            'owner_id'=> $data['owner_id'],
                            'eventStatus'=>$eventStatus,
                            'is_daily_coupon' => $data['is_daily_coupon'],
                            'avatar_url' => $data['avatar_url'],
                            'created' => $data['created'],
                            'thanks' => $data['thanks']
                        ));
                } ?>
                
                 <?php 
                 echo $this->element('thamgia/invitation',
                                                array('event_id' => $data['id'])
                                            );
                 ?>
                 
                 <?php 
                 echo $this->element('thamgia/import_contact',
                                                array('event_id' => $data['id'],
                                                      'event_address' => $data['address'],
                                                      'event_title' => $data['title']
                                                )
                                            );
                 ?>
                
            </div>
            <div class="content-center content-events">
                <?php echo $this->Session->flash(); ?>
                <?php echo $this->fetch('content'); ?>
            </div>
        </div>
    </div>
    <?php echo $this->element('thamgia/footer'); ?>
    <?php //echo $this->element('sql_dump'); ?>
</body>
</html>
