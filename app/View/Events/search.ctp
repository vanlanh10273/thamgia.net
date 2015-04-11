<h2 class="titel">KẾT QUẢ TÌM KIẾM</h2>
<?php echo $this->element('thamgia/list_events',
    array(
        'city_id' => $city_id,
        'user_id' => $users_userid,
        'type_id' => $type_id,
        'is_search' => true,
        'search' => $search
    )); 
?>