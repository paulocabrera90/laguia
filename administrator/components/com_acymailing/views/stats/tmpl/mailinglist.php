<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.3.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><div id="acy_content" >
	<?php
	if(empty($this->isData)) return;
	if(JRequest::getString('tmpl') == 'component') include(dirname(__FILE__).DS.'menu.mailinglist.php'); ?>
	<style type="text/css">
		 .mailingListChart{
		 	float:left;
			margin:2px;
		 }
		 .noDataChart{
		 	display:none;
		 }
	</style>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script language="JavaScript" type="text/javascript">
		function getDataMailSent(){
		 	var data = new google.visualization.DataTable();
			data.addColumn('string', 'Name');
			data.addColumn('number', 'Value');
			data.addRows(<?php echo count($this->mydata); ?>);
			<?php
			$i = 0;
			foreach($this->mydata as $list){
				echo 'data.setValue('. $i .', 0, \''. str_replace("'", "\'", $list['listname']) .'\'); ';
				echo 'data.setValue('. $i .', 1, '. $list['nbMailSent'] .'); ';
				$i++;
			}?>
			return data;
		}

		function drawMailSent(){
			var vis = new google.visualization.PieChart(document.getElementById('chartMailSent'));
			var options = {
				width: 370,
				height: 350,
				colors: [<?php echo $this->listColors; ?>],
				legend:'right',
				title: '<?php echo str_replace("'", "\'", JText::_('ACY_SENT_EMAILS')); ?>',
				legendTextStyle: {color:'#333333'},
				pieSliceText: 'value',
				is3D:true
			};
			vis.draw(getDataMailSent(), options);
		}

		var optionsColumnChart = {
			width: 370,
			height: 350,
			colors: [<?php echo $this->listColors; ?>],
			legend: 'none',
			vAxis: {minValue:0, maxValue: 100}
		};

		function getDataOpen(){
			var data = new google.visualization.DataTable();
			data.addColumn('string', 'Columns');
			<?php foreach($this->mydata as $list){
				echo 'data.addColumn(\'number\', \''. str_replace("'", "\'", $list['listname']) .'\'); ';
			} ?>
			data.addRows(1);
			data.setValue(0, 0, '');
			<?php $i = 1;
			foreach($this->mydata as $list){
				echo 'data.setValue(0,'. $i .', '. $list['nbOpenRatio'] .'); ';
				$i++;
			} ?>
			return data;
		}
		function drawOpen(){
			var vis = new google.visualization.ColumnChart(document.getElementById('chartMailOpen'));
			optionsColumnChart['title'] = '<?php echo str_replace("'", "\'", JText::_('OPEN')); ?> (%)';
			vis.draw(getDataOpen(), optionsColumnChart);
		}

		function getDataBounce(){
		 	var data = new google.visualization.DataTable();
		 	data.addColumn('string', 'Columns');
			<?php foreach($this->mydata as $list){
				echo 'data.addColumn(\'number\', \''. str_replace("'", "\'", $list['listname']) .'\'); ';
			} ?>
			data.addRows(1);
			data.setValue(0, 0, '');
			<?php $i = 1;
			foreach($this->mydata as $list){
				echo 'data.setValue(0,'. $i .', '. $list['nbBounceRatio'] .'); ';
				$i++;
			} ?>
			return data;
		}
		function drawBounce(){
			var vis = new google.visualization.ColumnChart(document.getElementById('chartBounce'));
			optionsColumnChart['title'] = '<?php echo str_replace("'", "\'", JText::_('BOUNCES')); ?> (%)';
			vis.draw(getDataBounce(), optionsColumnChart);
		}

		function getDataClic(){
		 	var data = new google.visualization.DataTable();
			data.addColumn('string', 'Columns');
			<?php foreach($this->mydata as $list){
				echo 'data.addColumn(\'number\', \''. str_replace("'", "\'", $list['listname']) .'\'); ';
			} ?>
			data.addRows(1);
			data.setValue(0, 0, '');
			<?php $i = 1;
			foreach($this->mydata as $list){
				echo 'data.setValue(0,'. $i .', '. $list['nbClicRatio'] .'); ';
				$i++;
			} ?>
			return data;
		}
		function drawClic(){
			var vis = new google.visualization.ColumnChart(document.getElementById('chartClic'));
			optionsColumnChart['title'] = '<?php echo str_replace("'", "\'", JText::_('CLICKED_LINK')); ?> (%)';
			vis.draw(getDataClic(), optionsColumnChart);
		}

		function getDataUnsub(){
		 	var data = new google.visualization.DataTable();
			data.addColumn('string', 'Columns');
			<?php foreach($this->mydata as $list){
				echo 'data.addColumn(\'number\', \''. str_replace("'", "\'", $list['listname']) .'\'); ';
			} ?>
			data.addRows(1);
			data.setValue(0, 0, '');
			<?php $i = 1;
			foreach($this->mydata as $list){
				echo 'data.setValue(0,'. $i .', '. $list['nbUnsubRatio'] .'); ';
				$i++;
			} ?>
			return data;
		}
		function drawUnsub(){
			var vis = new google.visualization.ColumnChart(document.getElementById('chartUnsubscribed'));
			optionsColumnChart['title'] = '<?php echo str_replace("'", "\'", JText::_('UNSUBSCRIBED')); ?> (%)';
			vis.draw(getDataUnsub(), optionsColumnChart);
		}

		function getDataForward(){
		 	var data = new google.visualization.DataTable();
			data.addColumn('string', 'Columns');
			<?php foreach($this->mydata as $list){
				echo 'data.addColumn(\'number\', \''. str_replace("'", "\'", $list['listname']) .'\'); ';
			} ?>
			data.addRows(1);
			data.setValue(0, 0, '');
			<?php $i = 1;
			$dataForward = 0;
			foreach($this->mydata as $list){
				echo 'data.setValue(0,'. $i .', '. $list['nbForward'] .'); ';
				if($list['nbForward'] != 0) $dataForward = 1;
				$i++;
			} ?>
			return data;
		}
		function drawForward(){
			var vis = new google.visualization.ColumnChart(document.getElementById('chartForward'));
			optionsColumnChart['title'] = '<?php echo str_replace("'", "\'", JText::_('FORWARDED')); ?>';
			optionsColumnChart['vAxis'] = {minValue:0};
			vis.draw(getDataForward(), optionsColumnChart);
		}

		google.load("visualization", "1", {packages:["corechart"]});
		google.setOnLoadCallback(drawMailSent);
		google.setOnLoadCallback(drawOpen);
		google.setOnLoadCallback(drawBounce);
		google.setOnLoadCallback(drawClic);
		google.setOnLoadCallback(drawUnsub);
		google.setOnLoadCallback(drawForward);
	</script>

	<div id="iframedoc"></div>
	<?php echo JText::_('SEND_DATE').' : <span class="statnumber">'.acymailing_getDate($this->mailing->senddate); ?></span><br/>
	<div id="chartMailSent" class="acychart mailingListChart"></div>
	<div id="chartMailOpen" class="acychart mailingListChart"></div>
	<!--[if !IE]><!--><div style="page-break-after: always;">&nbsp;</div><!--<![endif]-->
	<div id="chartClic" class="acychart mailingListChart"></div>
	<div id="chartForward" class="acychart mailingListChart <?php echo ($dataForward==0?'noDataChart':''); ?>"></div>
	<?php echo($dataForward!=0?'<!--[if !IE]><!--><div style="page-break-after: always">&nbsp;</div><!--<![endif]-->':''); ?>
	<div id="chartBounce" class="acychart mailingListChart"></div>
	<?php echo($dataForward==0?'<!--[if !IE]><!--><div style="page-break-after: always">&nbsp;</div><!--<![endif]-->':''); ?>
	<div id="chartUnsubscribed" class="acychart mailingListChart"></div>
</div>
