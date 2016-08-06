<?php
/**
 *
 * Default view
 *
 * @version             1.0.0
 * @package             Gavern Framework
 * @copyright			Copyright (C) 2010 - 2011 GavickPro. All rights reserved.
 *               
 */
 
// No direct access.
defined('_JEXEC') or die;
if($this->getParam("cwidth_position", 'head') == 'head') {
$this->generateColumnsWidth();
}
$this->addCSSRule('#gkWrap1, #gkWrap2, #gkWrap3 { width: ' . $this->getParam('template_width','980px') . '; }');
$tpl_name = str_replace(' ', '_', JText::_('TPL_GK_LANG_NAME'));
$tpl_page_suffix = '';
if($this->page_suffix != '') {
	$tpl_page_suffix = ' class="'.$this->page_suffix.'"';
}
$user = JFactory::getUser();
// getting User ID
$userID = $user->get('id');
// getting params
$option = JRequest::getCmd('option', '');
$view = JRequest::getCmd('view', '');
// defines if register is active
define('GK_REGISTER', ($this->modules('register') ? $userID == 0 : false));
// defines if login is active
define('GK_LOGIN', $this->modules('login'));
// defines if com_users
define('GK_COM_USERS', $option == 'com_users' && ($view == 'login' || $view == 'registration'));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" 
	  xmlns:og="http://ogp.me/ns#" 
	  xmlns:fb="http://www.facebook.com/2008/fbml" 
	  xml:lang="<?php echo $this->API->language; ?>" lang="<?php echo $this->API->language; ?>">
<head>
<jdoc:include type="head" />
    <?php $this->loadBlock('head'); ?>
</head>
<body<?php echo $tpl_page_suffix; ?>>
	<?php if($this->browser->get('browser') == 'ie6' && $this->getParam('ie6bar', '1') == 1) : ?>
	<div id="gkInfobar"><a href="http://browsehappy.com"><?php echo JText::_('TPL_GK_GAVERN_IE6_BAR'); ?></a></div>
	<?php endif; ?>
	
	<?php $this->messages('message-position-1'); ?>	
	
	<div id="gkBg">   
		<div id="gkWrap1">
        <?php if(isset($_COOKIE['gkGavernMobile'.$tpl_name]) &&
          $_COOKIE['gkGavernMobile'.$tpl_name] == 'desktop') : ?>
          <div class="mobileSwitch gkWrap">
        <a href="javascript:setCookie('gkGavernMobile<?php echo $tpl_name; ?>', 'mobile', 365);window.location.reload();"><?php echo JText::_('TPL_GK_LANG_SWITCH_TO_MOBILE'); ?></a>
         </div>
    <?php endif; ?>
            <?php $this->loadBlock('toolbar'); ?>
			<?php $this->loadBlock('nav'); ?>
			
			<?php $this->loadBlock('header'); ?>
		</div>
    </div>
    
    <?php $this->messages('message-position-2'); ?>
    
    <div id="gkWrap2">	
    	<?php $this->loadBlock('top'); ?>
    	
    	<?php $this->loadBlock('main'); ?>
        
        <?php $this->loadBlock('user'); ?>
    	
    </div>
    
    <div id="gkWrap3">
    	<?php $this->loadBlock('bottom'); ?>
    	<?php $this->loadBlock('footer'); ?>
    </div>
    
    <?php if($this->modules('cart')) : ?>
    <div id="popupCart" class="gkPopup">	
    	<div class="gkPopupWrap">
    		<jdoc:include type="modules" name="cart" style="<?php echo $this->module_styles['cart']; ?>" />
    	</div>
    </div>
    <?php endif; ?>
    
     <?php if((GK_REGISTER || GK_LOGIN) && !GK_COM_USERS) : ?>	
		<?php if(GK_LOGIN) : ?>
		<div id="popupLogin" class="gkPopup">	
				<div class="gkPopupWrap">
			        <?php $this->loadBlock('tools/login'); ?>
				</div>
		</div>
		<?php endif; ?>
         <?php if(GK_REGISTER) : ?>
        <div id="popupRegister" class="gkPopup">	
				<div class="gkPopupWrap">
        		<?php $this->loadBlock('tools/register'); ?>
						</div>
		</div>
         <?php endif; ?>
	<?php endif; ?>
    
    <?php // $this->loadBlock('popup'); ?>
	<?php $this->loadBlock('social'); ?>
	
	<jdoc:include type="modules" name="debug" />
<script type="text/javascript" src="/cometchat/js/jquery.js"> </ script> <script type = "text / javascript" src = "/ cometchat / js / cometchat. js "> </ script> 
</body>
</html>