<?php
/*------------------------------------------------------------------------
# mod_vtem_weather - VTEM Weather Module
# ------------------------------------------------------------------------
# author Nguyen Van Tuyen
# copyright Copyright (C) 2011 VTEM.NET. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.vtem.net
# Technical Support: Forum - http://vtem.net/en/forum.html
-------------------------------------------------------------------------*/
// no direct access
defined('_JEXEC') or die('Restricted access');
$slideID 				= $params->get( 'slideID','vtemfisheyemenu1' );
$width 			= $params->get('width', 280);
$height 		= $params->get('height', 160);
$border		= $params->get('border', '5px solid #ddd');
$unit		= $params->get('unit', 'c');
$image		= $params->get('image', 1 ) ? 'true' : 'false';
$highlow		= $params->get('highlow', 1 ) ? 'true' : 'false';
$wind				= $params->get( 'wind', 1 ) ? 'true' : 'false';
$link				= $params->get( 'link', 1 ) ? 'true' : 'false';
$showerror				= $params->get( 'showerror', 1 ) ? 'true' : 'false';
$location 				= $params->get( 'location',"'UKXX0085','EGXX0011','UKXX0061'" );
$interval 				= $params->get( 'interval', 3000);
$script 				= $params->get( 'script', 1 );

if ($script == 1){
echo "<script src='".JURI::root()."modules/mod_vtem_weather/assets/jquery-1.4.2-min.js' type='text/javascript'></script>";
}
?>
<script type="text/javascript" src="<?php echo JURI::root();?>modules/mod_vtem_weather/assets/jquery.tools.min.js"></script>
<?php require_once (dirname(__FILE__).DS.'zweatherfeed.php');?>
<style type="text/css">
.vertical,.items div.weatherItem{
width: <?php echo $width;?>px;
height: <?php echo $height;?>px;
}
.vertical{
border: <?php echo $border;?>;
}
</style>
<script type="text/javascript">
var vtemfisheyemenu = jQuery.noConflict();
(function($) {
$(document).ready(function(){
    $('#<?php echo $slideID;?>').weatherfeed([<?php echo $location;?>])<?php if($interval != 0):?>.ajaxStop(function() {
    $("div.scrollable").scrollable({
      vertical: true,
      size: 1
      }).circular().autoscroll({
          interval:<?php echo $interval;?>
          });
    }); 
	<?php endif;?>
});
})(jQuery);
</script>
<div class="scrollable vertical vtemweather">
<div id="<?php echo $slideID;?>" class="items"></div>
</div>