<h2 class="titel">Sự Kiện Sắp Diễn Ra <?php echo isset($city_name) ? ' - ' . $city_name : '';  ?></h2>
<?php echo $this->element('thamgia/list_events',
    array(
        'city_id' => $city_id,
        'user_id' => $users_userid
    ));

        echo $this->element('thamgia/month_navigation');
?>

<script type="text/javascript">
    $(document).ready(function() {
        $('#home').toggleClass('active');
    });
</script>
