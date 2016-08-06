<?php

// No direct access.
defined('_JEXEC') or die;

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


<div id="gkToolbar">
    <?php if( $this->modules('topmenu')) : ?>
<div id="gkTopMenu">
      <jdoc:include type="modules" name="topmenu" style="<?php echo $this->module_styles['topmenu']; ?>" />
</div>
<?php endif; ?>
   
   <div id="gkButtons">
  
  	<?php if($this->modules('cart')): ?>
  	<div id="gkItems">
  		( <strong>0</strong> <?php echo JText::_('TPL_GK_LANG_ITEMS'); ?> )
  	</div>
  	<div id="gkCart">
  		<a href="#" id="btnCart"><span><?php echo JText::_('TPL_GK_LANG_CART'); ?></span></a>
  	</div>
  	<?php endif; ?>
  
    <?php if($this->getToolsOverride()) : ?>
        <a href="#" id="gkButtonTools"><?php echo JText::_('GK_LANG_TOOLS') ?></a>
    	<?php $this->loadBlock('tools/tools'); ?>
    <?php endif; ?>
    
     <?php if((GK_REGISTER || GK_LOGIN) && !GK_COM_USERS) : ?>
        
        <?php if(GK_REGISTER) : ?>
			<a href="<?php echo $this->URLbase(); ?>index.php?option=com_users&amp;view=registration" id="btnRegister"><span><?php echo JText::_('GK_LANG_REGISTER'); ?></span></a>
			<?php endif; ?>
     
        <?php if(GK_LOGIN) : ?>
			<a href="<?php echo $this->URLbase(); ?>index.php?option=com_users&amp;view=login" id="btnLogin"><span><?php echo ($userID > 0) ? JText::_('GK_LANG_LOGOUT') : JText::_('GK_LANG_LOGIN'); ?></span></a>
			<?php endif; ?>
			
	 <?php endif; ?>        
        
	</div>
</div>