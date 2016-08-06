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

JHTML::script('slider.js','modules/mod_filterednews/scripts/',false); ?>

<div id="fn_slider_<?php echo $filterednews_id; ?>" class="fn_slider_<?php echo $filterednews_id; ?>">
  <div class="opacitylayer">
    <?php foreach ($list as $item) : ?>
    <div class="fn_news">
	    <?php echo $item->content; ?>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<div class="fn_pagination_<?php echo $filterednews_id; ?>" id="paginate-fn_slider_<?php echo $filterednews_id; ?>"></div>
<?php

$doc = JFactory::getDocument();

if (!defined('_MOD_VARGAS_ONLOAD')) {
    define ('_MOD_VARGAS_ONLOAD',1);
    $doc->addScriptDeclaration("function addLoadEvent(func){if(typeof window.addEvent=='function'){window.addEvent('load',function(){func()});}else if(typeof window.onload!='function'){window.onload=func;}else{var oldonload=window.onload;window.onload=function(){if(oldonload){oldonload();}func();}}}");
}

$doc->addScriptDeclaration("addLoadEvent(function(){FN_ContentSlider('fn_slider_". $filterednews_id."',".$params->get('delay', 3000).",'".$params->get('next', '')."');});");

?>