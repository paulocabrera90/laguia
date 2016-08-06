<?php
/*------------------------------------------------------------------------
# mod_filterednews - Filtered News Module
# ------------------------------------------------------------------------
# author    Joomla!Vargas
# copyright Copyright (C) 2010 joomla.vargas.co.cr. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://joomla.vargas.co.cr
# Technical Support:  Forum - http://joomla.vargas.co.cr/forum
-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;

JHTML::script('scroller.js','modules/mod_filterednews/scripts/',false); ?>
<script type="text/javascript" language="javascript">
<!--
var FN_Pausecontent_<?php echo $filterednews_id; ?>=new Array();
<?php
$rowItems = (int)$params->get('scroll_items', 1) ? (int)$params->get('scroll_items', 1) : 1;
$contents = array();
for($i=0;$i<count($list);$i++) :
	$list[$i]->content = preg_replace( "/[\n\t\r]+/",' ',$list[$i]->content);
	$list[$i]->content = str_replace( "'", "\\'",$list[$i]->content ); 
	$contents[floor($i/$rowItems)][] = '<div>'.$list[$i]->content.'</div>';
endfor;
for($i=0;$i<count($contents);$i++) : ?>
	FN_Pausecontent_<?php echo $filterednews_id; ?>[<?php echo $i; ?>]='<?php echo implode('', $contents[$i])  ?>';
<?php endfor; ?>
new FN_Pausescroller(FN_Pausecontent_<?php echo $filterednews_id; ?>, "fn_scroller_<?php echo $filterednews_id; ?>", "", <?php echo $params->get('delay', 3000) ?>);
-->
</script>
