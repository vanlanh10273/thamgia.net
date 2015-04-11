
<?php 
App::import('Vendor', 'dompdf', true, array(), 'dompdf' . DS . 'dompdf_config.inc.php');
$dompdf = new DOMPDF();
$dompdf->load_html($this->fetch('content'), Configure::read('App.encoding'));
$dompdf->render();
echo $dompdf->output();
?>