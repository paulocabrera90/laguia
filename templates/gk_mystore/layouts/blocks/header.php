<?php

// No direct access.
defined('_JEXEC') or die;

?>
<?php if($this->modules('breadcrumb')) : ?>
<div id="gkBreadcrumb">
	<?php if($this->modules('breadcrumb')) : ?>
	   <jdoc:include type="modules" name="breadcrumb" style="<?php echo $this->module_styles['breadcrumb']; ?>" />
	<?php endif; ?>
    <div id="gkDate">
      <?php echo JHtml::_('date', 'now', 'l, d M Y');?>
    </div>
</div>
<?php endif; ?>
<?php if( $this->modules('banner1')) : ?>
<div id="gkBanner1" class="clear clearfix">
      <jdoc:include type="modules" name="banner1" style="<?php echo $this->module_styles['banner1']; ?>" />
</div>
<?php endif; ?>

<?php if($this->modules('header')) : ?>
<div id="gkHeader" class="gkMain">
	<?php if($this->modules('header')) : ?>
	<div id="gkHeaderModule">
		<jdoc:include type="modules" name="header" style="<?php echo $this->module_styles['header']; ?>" />
	</div>
	<?php endif; ?>
</div>
<?php endif; ?>

<?php if( $this->modules('banner2')) : ?>
<div id="gkBanner2" class="clear clearfix">
      <jdoc:include type="modules" name="banner2" style="<?php echo $this->module_styles['banner2']; ?>" />
</div>
<?php endif; ?>