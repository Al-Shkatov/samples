<?php
$images = require './config.php';
header('Content-type:application/javascript');
?>
var base_url='http://site.thedanburywhalers.com/top-slider/';
var images=JSON.parse('<?php echo json_encode($images);?>');
document.write('<style type="text/css"><?php echo str_replace(array("\r\n","\r","\n"),'',file_get_contents('./style.css')); ?></style>');
<?php
    echo file_get_contents('./slider.js');
?>