<?php

	// no direct access
	defined('_JEXEC') or die('Restricted access');

	$url = JURI::getInstance();	
	$user = JFactory::getUser();
	$userID = $user->get('id');
	$popup_class = '';
	
	if(!($this->modules('register') && $userID == 0)) {
		$popup_class = ' class="only-one"';
	}

?>

<?php if($this->modules('login')) : ?>		
<div id="loginForm"<?php echo $popup_class; ?>>
	<jdoc:include type="modules" name="login" style="<?php echo $this->module_styles['login']; ?>" />
</div>
<?php endif; ?>	