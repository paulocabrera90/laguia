<?php



// Here you can modify the navigation of the website



defined('_JEXEC') or die;

$logo_image = $this->getParam('logo_image', '');



if(($logo_image == '') || ($this->getParam('logo_type', '') == 'css')) {

     $logo_image = $this->URLtemplate() . '/images/logo.png';

} else {

     $logo_image = $this->URLbase() . $logo_image;

}



$logo_text = $this->getParam('logo_text', '');

$logo_slogan = $this->getParam('logo_slogan', '');

//

$user = JFactory::getUser();

// getting User ID

$userID = $user->get('id');



?>

<div id="gkPageTop" class="gkMain <?php echo $this->generatePadding('gkPageTop'); ?>">

    <div id="gkMenuWrap">

	    <?php if ($this->getParam('logo_type', 'image')!=='none'): ?>

     <?php if($this->getParam('logo_type', 'image') == 'css') : ?>

     <h1 id="gkLogo">

          <a href="./" class="cssLogo"></a>

          <span><?php echo $this->getParam('logo_text', ''); ?></span>

     </h1>

     <?php elseif($this->getParam('logo_type', 'image')=='text') : ?>

     <h1 id="gkLogo" class="text">

         <a href="./">

              <span><?php echo $this->getParam('logo_text', ''); ?></span>

               <small class="gkLogoSlogan"><?php echo $this->getParam('logo_slogan', ''); ?></small>

         </a>

     </h1>

    <?php elseif($this->getParam('logo_type', 'image')=='image') : ?>

    <h1 id="gkLogo">

          <a href="./">

          <img src="<?php echo $logo_image; ?>" alt="<?php echo $this->getPageName(); ?>" />

          </a>

     </h1>

     <?php endif; ?>

<?php endif; ?>
	<?php if($this->getParam('show_menu', 1)) : ?>
	<div id="gkMenu">

   

		<?php

			$this->menu->loadMenu($this->getParam('menu_name','mainmenu')); 

		    $this->menu->genMenu($this->getParam('startlevel', 0), $this->getParam('endlevel',-1));

		?>
		<?php endif; ?>
        <?php if( $this->modules('search') ): ?>

	<div id="gkSearch">

	      <jdoc:include type="modules" name="search" style="<?php echo $this->module_styles['search']; ?>" />

	</div>

	<?php endif; ?>

	</div>

<?php if($this->generateSubmenu && $this->menu->genMenu($this->getParam('startlevel', 0)+1, $this->getParam('endlevel',-1), true)): ?>

<div id="gkSubmenu">	

	<?php $this->menu->genMenu($this->getParam('startlevel', 0)+1, $this->getParam('endlevel',-1));?>

</div>	

<?php endif; ?>

</div>

</div>

