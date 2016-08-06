<?php







// No direct access.



defined('_JEXEC') or die;


$tpl_name = str_replace(' ', '_', JText::_('TPL_GK_LANG_NAME'));




?>



<div id="gkFooter" class="gkMain">

	<?php if($this->modules('footer_nav')) : ?>

	<div id="gkFooterNav">

		<jdoc:include type="modules" name="footer_nav" style="<?php echo $this->module_styles['footer_nav']; ?>" />

	</div>

	<?php endif; ?>

	

	<?php if($this->getParam('stylearea', '0') == '1') : ?>

	<p id="gkStyleArea">

		<a href="#" id="gkStyle1">Red</a>

		<a href="#" id="gkStyle2">Blue</a>

		<a href="#" id="gkStyle3">Green</a>

	</p>

	<?php endif; ?>

	

	<?php if($this->getParam('copyrights', '') !== '') : ?>

                <span>

        		<?php echo $this->getParam('copyrights', ''); ?>

        	 </span>

    <?php else : ?>

            	<span>

            	Template Design &copy; <a href="http://www.gavick.com" title="Joomla Templates">Joomla Templates</a> | GavickPro. All rights reserved.

            	</span>

    <?php endif; ?>

	

	<?php if(isset($_COOKIE['gkGavernMobile'.$tpl_name]) && 

		$_COOKIE['gkGavernMobile'.$tpl_name] == 'desktop') : ?>

		<span class="mobileSwitcher"><a href="javascript:setCookie('gkGavernMobile<?php echo $tpl_name; ?>', 'mobile', 365);window.location.reload();"><?php echo JText::_('TPL_GK_LANG_SWITCH_TO_MOBILE'); ?></a></span>

	<?php endif; ?>

</div>



<?php if($this->getParam('framework_logo', '0') == '1') : ?>

<div id="gkFrameworkLogo">Framework logo</div>

<?php endif; ?>