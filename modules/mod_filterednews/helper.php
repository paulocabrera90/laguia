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

require_once JPATH_SITE.'/components/com_content/helpers/route.php';

jimport('joomla.application.component.model');

JModel::addIncludePath(JPATH_SITE.'/components/com_content/models', 'ContentModel');

abstract class modFilteredNewsHelper
{

    public static function getFN_Img( &$params, $link, $img ) {
	
	$align 	    = $params->get( 'item_img_align', 'left' );
	$margin 	= $params->get( 'item_img_margin', '3px' );
	$width 		= (int)$params->get( 'item_img_width', '' );
	$height 	= (int)$params->get( 'item_img_height', '' );
	$border		= $params->get( 'item_img_border', '0' );
	
	$style = 'margin:'.$margin.';border:'.$border.';';
	
	if ( $align == 'left' )  :
		   $style .= 'float:left;';
	endif;
	
	if ( $align == 'right' )  :
		   $style .= 'float:right;';
	endif;
	
	$attribs = array ('style' => $style);
	
	if (!$params->get('thumb_image', 1)) {
		
		if ( $height )  :
			   $attribs = array('height' => $height, $attribs);
		endif;
		
		if ( $width )  :
			   $attribs = array('width' => $width,  $attribs );
		endif;
	}
			   		
    return ( $link ? '<a href="'.$link.'">' : '' )
		  .JHTML::_('image', $img, '', $attribs)
		  .( $link ? '</a>' : '' );
    }
	
	public static function getFN_List(&$params)
	{
		$db			= JFactory::getDBO();
		$user		= JFactory::getUser();
		$userId		= (int) $user->get('id');
		
		$option		= JRequest::getCmd('option');
		$view		= JRequest::getCmd('view');
		
		$temp		= JRequest::getString('id');
		$temp		= explode(':', $temp);
		$id			= $temp[0];

		$count		= (int) $params->get('count', 5);
		$cat		= $params->get('cat', 1);
		$only		= $params->get('only', 0);
		$current    = $params->get('current', 0);
		$user_id    = $params->get('user_id', 0);
		$layout 	= $params->get('layout', 'default');
		$html       = $params->get('html');
		$show_front	= $params->get('show_front', 1);
		$aid		= $user->get('aid', 0);
		$catlink    = $params->get('cat_link');

		$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		
		$app = JFactory::getApplication();
		
		$concats = ' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,' .
			' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug';
			
		if ( $catlink ) :
			$concats .= ', CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug';
		endif;

		$nullDate = $db->getNullDate();

		$date = JFactory::getDate();
		$now = $date->toMySQL();
		
		$where		= 'a.state = 1'
			. ' AND ( a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' )'
			. ' AND ( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )'
			;
			
		if ( $recent = $params->get('recent') ) :
			$where .= ' AND DATEDIFF('.$db->Quote($now).', a.'.$params->get('date', 'created').') < ' . $recent;
		endif;
		
		if ($app->getLanguageFilter()) {
			$where .= ' AND a.language in ('.$db->quote(JFactory::getLanguage()->getTag()).','.$db->quote('*').')';
		}

		if ( $user_id > 1 && $userId ) {
			switch ($user_id)
			{
				case '2':
					$where .= ' AND (created_by = ' . (int) $userId . ' OR modified_by = ' . (int) $userId . ')';
					break;
				case '3':
					$where .= ' AND (created_by <> ' . (int) $userId . ' AND modified_by <> ' . (int) $userId . ')';
					break;

			}
		}

		switch ($params->get( 'ordering' ))
		{
			case 'random':
				$ordering		= ' ORDER BY rand()';
				break;
			case 'h_asc':
				$ordering		= ' ORDER BY a.hits ASC';
				break;
			case 'h_dsc':
				$ordering		= ' ORDER BY a.hits DESC';
				break;
			case 'm_dsc':
				$ordering		= ' ORDER BY a.modified DESC, a.created DESC';
				break;
			case 'order':
				$ordering		= ' ORDER BY a.ordering ASC';
				break;
			case 'c_dsc':
			default:
				$ordering		= ' ORDER BY a.created DESC';
				break;
		}
		
		$joins = ' INNER JOIN #__categories AS cc ON cc.id = a.catid';
				 
		switch ( $show_front )
		{
			case 1:
				$joins .= ' LEFT JOIN #__content_frontpage AS f ON f.content_id = a.id';
				$where .= ' AND f.content_id IS NULL';
				break;
			case 2:
				$joins .= ' LEFT JOIN #__content_frontpage AS f ON f.content_id = a.id';
				$where .= ' AND f.content_id = a.id';
				break;
		}
		
        $catid = $the_id = $catCondition = '';
		
		if ( $only == 0 && $option == 'com_content' && $view == 'category' && $cat ==1 ) {
	    	$catid = $id;
		}
				   
        if ( $option == 'com_content' && $view == 'article' && $id ) {

                $the_id = $id;
				
				$article = JTable::getInstance('content');
				$article->load( $id );
   
                if ($current == 0) {
                    $where .= ' AND a.id!='.$the_id;
                }
				if ( $cat == 1 ){
					$catid = $article->catid;
				}
                if ($user_id == 1)
		            $where .= ' AND created_by = ' . $article->created_by;
					
                if ( $params->get( 'key', 0 ) == 1 ) {
                     if ($metakey = trim($article->metakey)) {
		                 $keys = explode(',', $metakey);
	                     $likes = array ();
		                 foreach ($keys as $key) {
		                       $key = trim($key);
				               if ($key) {
			                       $likes[] = $db->getEscaped($key);
		                       }
		                 }
                         if (count($likes)) {
		                     $where .= ' AND ( a.metakey LIKE "%'.implode('%" OR a.metakey LIKE "%', $likes).'%" )';
		                 }
                  }	
             }
        }
		if ($catid) {
			$catCondition .= ' AND (cc.id='. $catid;
			if ($params->get('show_child_category_articles', 0) && (int) $params->get('levels', 0) > 0) {
				$categories = JModel::getInstance('Categories', 'ContentModel', array('ignore_request' => true));
				$categories->setState('params', $appParams);
				$levels = $params->get('levels', 1) ? $params->get('levels', 1) : 9999;
				$categories->setState('filter.get_children', $levels);
				$categories->setState('filter.published', 1);
				$categories->setState('filter.access', $access);
				$additional_catids = array();
				$categories->setState('filter.parentId', $catid);
				$recursive = true;
				$items = $categories->getItems($recursive);
	
				if ($items)
				{
					foreach($items as $category)
					{
						$condition = (($category->level - $categories->getParent()->level) <= $levels);
						if ($condition) {
							$catCondition .= ' OR cc.id='. $category->id;
						}
	
					}
				}
			}
			$catCondition .= ')';
		}
		
		$catexc	= $params->get('catexc', '');
		if ( !empty($catexc[0]) ) {
                $catCondition .= ' AND (cc.id!=' . implode( ' AND cc.id!=', $catexc ) . ')';
        }
		
		$query = 'SELECT a.*, cc.title AS catname,' .
			$concats  .
			' FROM #__content AS a' .
			$joins  .
			' WHERE '. $where .
			($access ? ' AND a.access IN ('.implode(',', $user->getAuthorisedViewLevels()).')' : '') .
			$catCondition .
			' AND cc.published = 1' .
			$ordering;
			
		$db->setQuery($query, 0, $count);
		$rows = $db->loadObjectList();

		$i		= 0;
		$lists	= array();
		foreach ( $rows as &$row ) {
		
		    $link = '';
		    $row->title = htmlspecialchars( $row->title );
		
            if ( $the_id != $row->id or $current != 2 ) {
		         $link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug));
				 if ( $params->get('link_title', 1) ) {
		         	$row->title = '<a href="'.$link.'">'.htmlspecialchars( $row->title ).'</a>'; 
				}
			}
						
	        if ( $layout ) :
			
				$fn_image    = '';
				$fn_title    = '';
				$fn_created  = '';
				$fn_author   = '';
				$fn_text     = '';
				$fn_readmore = '';
				$rm          = 0;
				
				$fn_category = $row->catname;
				
				if ( $catlink ) {
					 $cat_link = JRoute::_(ContentHelperRoute::getCategoryRoute( $row->catslug ));
					 $fn_category = '<a href="'.$cat_link.'">'.htmlspecialchars( $fn_category  ).'</a>'; 
				}
			
				if ( preg_match("/FN_title/", $html) ) {
		        	$fn_title = $row->title;
					$fn_title = preg_replace('/\$/','$-',$fn_title);
			    }
			
		        if ( preg_match("/FN_date/", $html) ) {
	      	    	$fn_created = JHTML::_('date',  ($params->get( 'date' ) == 'created' ? $row->created : $row->modified ),  $params->get('date_format', '' ) );
			    }
				
		        if ( preg_match("/FN_author/", $html) ) {
					$author = $params->get( 'author' );
					if ( $author != 'alias' ) {
						$query = "SELECT " . $author . " FROM #__users WHERE id = " . $row->created_by;
						$db->setQuery($query);
						$fn_author = $db->loadResult();
					} else {
						$fn_author = $row->created_by_alias;
					}
			    }
				 
		        if ( preg_match("/FN_image/", $html) ) {
					$img = '';
					$images = json_decode($row->images);
					if (isset($images->image_intro) and !empty($images->image_intro)) :
						$img = $images->image_intro;
					elseif (isset($images->image_fulltext) and !empty($images->image_fulltext)) :
						$img = $images->image_fulltext;
					else :
						$regex   = "/<img[^>]+src\s*=\s*[\"']\/?([^\"']+)[\"'][^>]*\>/";
						$search  = $row->introtext . $row->fulltext;
						preg_match ($regex, $search, $matches);
						$images = (count($matches)) ? $matches : array();
						if ( count($images) ) :
							$img  = $images[1];
						endif;
					endif;
					
					if ($img) {
						if ($params->get('thumb_image', 1)) {
							$img = str_replace(JURI::base(false),'',$img);
							$img = modFilteredNewsHelper::getThumbnail($img,$params,$row->id); 
						}
					 	$fn_image  = modFilteredNewsHelper::getFN_Img ($params, $link, $img);
					}
		        }
						
		        if ( preg_match("/FN_text/", $html) ) {
					$text    = $params->get( 'text', 0 );
					$limit   = trim( $params->get('limittext', '150') );
					if ($text < 2) { 
					  $fn_text = $row->introtext;
					  if ( $text == 1 )
					    $fn_text .= '&nbsp;' . $row->fulltext;
					}
					if ( $text == 2 )
					  $fn_text = $row->fulltext;
					if ( $params->get('striptext', '1'))
					  $fn_text = trim( strip_tags(  $fn_text, $params->get('allowedtags', '') ) );
					$pattern = array("/[\n\t\r]+/",'/{(\w+)[^}]*}.*{\/\1}|{(\w+)[^}]*}/Us', '/\$/');
					$replace = array(' ', '', '$-');
					$fn_text = preg_replace( $pattern, $replace, $fn_text );
					if ( $limit && strlen( $fn_text ) > $limit ) {
					   $fn_text = substr( $fn_text, 0, $limit );
					   $space   = strrpos( $fn_text, ' ' );
					   $fn_text = substr( $fn_text, 0, $space ). '...';
					   $rm = 1;
					} elseif ( $text == 0 && $row->fulltext ) {
					   $rm = 1;
					}
			    }
				 
	 			if ( preg_match("/FN_readmore/", $html) && $link && $rm ) {
		            $fn_readmore  = JHTML::_('link', $link, JText::_('MOD_FILTEREDNEWS_READ_MORE_TITLE'));
	            }
				 
				$code = array("/FN_image/", "/FN_title/", "/FN_date/", "/FN_author/", "/FN_text/", "/FN_category/", "/FN_readmore/", "/FN_break/","/FN_space/");
				$rplc = array( $fn_image, $fn_title, $fn_created, $fn_author, $fn_text, $fn_category, $fn_readmore, "<br />", "&nbsp;");
				 
				$row->content = preg_replace($code, $rplc, $html);
				$row->content = preg_replace('/\$-/','$',$row->content);
							 
            endif;
			
			$i++;
		}
		
		return $rows;
	}
	
	public static function addFN_CSS(&$params,$layout,$filterednews_id) {
	
		 $doc = JFactory::getDocument();
		 
		 $style = ' border:'.$params->get('border', '1px solid #EFEFEF').';'
				 .' padding:'.$params->get('padding', '5px').';'
				 .' width:'.$params->get('width', 'auto').';'
				 .' height:'.$params->get('height', '125px').';'
				 .' background-color:'.$params->get('color', '#FFFFFF').';'
				 .' overflow:hidden;';
						
		 switch ( $layout ) {
	
			  case 'static' :
				   $css = ".fn_static_".$filterednews_id."{"
						   .$style
						   ." margin-bottom:2px;"
						   ." }";
					  
				   break;
			  case 'slider' :
				   $css = ".fn_slider_".$filterednews_id." {"
						   .$style
						   ." border-bottom:none;"
						   ." }\n"
						   .".fn_slider_".$filterednews_id." .opacitylayer{"
						   ." width:100%;"
						   ." height:100%;"
						   ." filter:progid:DXImageTransform.Microsoft.alpha(opacity=100);"
						   ." -moz-opacity:1;"
						   ." opacity:1;"
						   ." }\n"
						   .".fn_slider_".$filterednews_id." .contentdiv{"
						   ." display: none;"
						   ." }\n"
						   .".fn_pagination_".$filterednews_id." {"
						   ." width:".$params->get('width', 'auto').";"
						   ." border:".$params->get('border', '1px solid #EFEFEF').";"
						   ." border-top:none;"
						   ." padding:2px ".$params->get('padding', '5px').";"
						   ." text-align:right;"
						   ." background-color:".$params->get('color', '#FFFFFF').";"
						   ." }\n"
						   .".fn_pagination_".$filterednews_id." a:link{"
						   ." font-weight:bold;"
						   ." padding:0 2px"
						   ." }\n"
						   .".fn_pagination_".$filterednews_id." a:hover,"
						   ." .fn_pagination_".$filterednews_id." a.selected {"
						   ." color:#000;"
						   ." }";
				   break;
			  case 'browser' :
				   $css = "#fn_container_".$filterednews_id." {"
						   .$style
						   ." position: relative;"
						   ." }";
				   break;
			  case 'scroller' :
				   $css = "#fn_scroller_".$filterednews_id." {"
						   .$style
						   ." }";
				   break;
		 }
		
		 return $doc->addStyleDeclaration($css);		 
	}
	
	public static function getThumbnail($img,&$params,$item_id) 
	{
		
		$width      = $params->get('item_img_width',90);
		$height     = $params->get('item_img_height',90);
		$proportion = $params->get('thumb_proportions','bestfit');
		$img_type   = $params->get('thumb_type','');
		$bgcolor    = hexdec($params->get('thumb_bg','#FFFFFF'));
		
		$img_name   = pathinfo($img, PATHINFO_FILENAME);
		$img_ext    = pathinfo($img, PATHINFO_EXTENSION);
		$img_path   = JPATH_BASE  . '/' . $img;
		$size 	    = @getimagesize($img_path);
		
		$errors = array();
		
		if(!$size) 
		{	
			$errors[] = 'There was a problem loading image ' . $img_name . '.' . $img_ext;
		
		} else {
							
			$sub_folder = '0' . substr($item_id, -1);
							
			if ( $img_type ) {
				$img_ext = $img_type;
			}
	
			$origw = $size[0];
			$origh = $size[1];
			if( ($origw<$width && $origh<$height)) {
				$width = $origw;
				$height = $origh;
			}
			
			$prefix = substr($proportion,0,1) . "_".$width."_".$height."_".$bgcolor."_".$item_id."_";
	
			$thumb_file = $prefix . str_replace(array( JPATH_ROOT, ':', '/', '\\', '?', '&', '%20', ' '),  '_' ,$img_name . '.' . $img_ext);		
			
			$thumb_path = dirname(__FILE__).'/thumbs/' . $sub_folder . '/' . $thumb_file;
			
			$attribs = array();
			
			if(!file_exists($thumb_path))	{
		
				modFilteredNewsHelper::calculateSize($origw, $origh, $width, $height, $proportion, $newwidth, $newheight, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);
	
				switch(strtolower($size['mime'])) {
					case 'image/png':
						$imagecreatefrom = "imagecreatefrompng";
						break;
					case 'image/gif':
						$imagecreatefrom = "imagecreatefromgif";
						break;
					case 'image/jpeg':
						$imagecreatefrom = "imagecreatefromjpeg";
						break;
					default:
						$errors[] = "Unsupported image type $img_name.$img_ext ".$size['mime'];
				}
	
				
				if ( !function_exists ( $imagecreatefrom ) ) {
					$errors[] = "Failed to process $img_name.$img_ext. Function $imagecreatefrom doesn't exist.";
				}
				
				$src_img = $imagecreatefrom($img_path);
				
				if (!$src_img) {
					$errors[] = "There was a problem to process image $img_name.$img_ext ".$size['mime'];
				}
				
				$dst_img = ImageCreateTrueColor($width, $height);
				
				// $bgcolor = imagecolorallocatealpha($image, 200, 200, 200, 127);
				
				imagefill( $dst_img, 0,0, $bgcolor);
				if ( $proportion == 'transparent' ) {
					imagecolortransparent($dst_img, $bgcolor);
				}
				
				imagecopyresampled($dst_img,$src_img, $dst_x, $dst_y, $src_x, $src_y, $newwidth, $newheight, $src_w, $src_h);		
				
				switch(strtolower($img_ext)) {
					case 'png':
						$imagefunction = "imagepng";
						break;
					case 'gif':
						$imagefunction = "imagegif";
						break;
					default:
						$imagefunction = "imagejpeg";
				}
				
				if($imagefunction=='imagejpeg') {
					$result = @$imagefunction($dst_img, $thumb_path, 80 );
				} else {
					$result = @$imagefunction($dst_img, $thumb_path);
				}
	
				imagedestroy($src_img);
				if(!$result) {				
					if(!$disablepermissionwarning) {
					$errors[] = 'Could not create image:<br />' . $thumb_path . '.<br /> Check if the folder exists and if you have write permissions:<br /> ' . dirname(__FILE__) . '/thumbs/' . $sub_folder;
					}
					$disablepermissionwarning = true;
				} else {
					imagedestroy($dst_img);
				}
			}
		}
		
		if (count($errors)) {
			JError::raiseWarning(404, implode("\n", $errors));
			return false;
		}
		
		$image = JURI::base(false)."modules/mod_filterednews/thumbs/$sub_folder/" . basename($thumb_path);
		
		return  $image;
    }
	
	public static function calculateSize($origw, $origh, &$width, &$height, &$proportion, &$newwidth, &$newheight, &$dst_x, &$dst_y, &$src_x, &$src_y, &$src_w, &$src_h) {
		
		if(!$width ) {
			$width = $origw;
		}

		if(!$height ) {
			$height = $origh;
		}

		if ( $height > $origh ) {
			$newheight = $origh;
			$height = $origh;
		} else {
			$newheight = $height;
		}
		
		if ( $width > $origw ) {
			$newwidth = $origw;
			$width = $origw;
		} else {
			$newwidth = $width;
		}
		
		$dst_x = $dst_y = $src_x = $src_y = 0;

		switch($proportion) {
			case 'fill':
			case 'transparent':
				$xscale=$origw/$width;
				$yscale=$origh/$height;

				if ($yscale<$xscale){
					$newheight =  round($origh/$origw*$width);
					$dst_y = round(($height - $newheight)/2);
				} else {
					$newwidth = round($origw/$origh*$height);
					$dst_x = round(($width - $newwidth)/2);

				}

				$src_w = $origw;
				$src_h = $origh;
				break;

			case 'crop':

				$ratio_orig = $origw/$origh;
				$ratio = $width/$height;
				if ( $ratio > $ratio_orig) {
					$newheight = round($width/$ratio_orig);
					$newwidth = $width;
				} else {
					$newwidth = round($height*$ratio_orig);
					$newheight = $height;
				}
					
				$src_x = ($newwidth-$width)/2;
				$src_y = ($newheight-$height)/2;
				$src_w = $origw;
				$src_h = $origh;				
				break;
				
 			case 'only_cut':
				// }
				$src_x = round(($origw-$newwidth)/2);
				$src_y = round(($origh-$newheight)/2);
				$src_w = $newwidth;
				$src_h = $newheight;
				
				break; 
				
			case 'bestfit':
				$xscale=$origw/$width;
				$yscale=$origh/$height;

				if ($yscale<$xscale){
					$newheight = $height = round($width / ($origw / $origh));
				}
				else {
					$newwidth = $width = round($height * ($origw / $origh));
				}
				$src_w = $origw;
				$src_h = $origh;	
				
				break;
			}

	}
}
