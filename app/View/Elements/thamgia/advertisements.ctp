<?php
    $side_id = (isset($side_id) ? $side_id : LEFT_SIDE);
    $city_id = (isset($city_id)) ? $city_id : DEFAULT_CITY_ID;
    $data = $this->requestAction(array('controller' => 'Advertisements', 'action' => 'getAdvertisements', $side_id,  $city_id));
    if (count($data) > 0){
?>
    <div class="block ads">
<?php
        foreach($data as $advertisement){
?>
        
            <a href="<?php echo $advertisement['Advertisement']['url']; ?>">
                <img src="<?php echo $this->Html->url('/').$advertisement['Advertisement']['banner_url']; ?>" alt="Image" />
            </a>
<?php } ?>
    </div>
<?php } ?>
