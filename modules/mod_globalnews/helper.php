<?php
/*------------------------------------------------------------------------
# mod_globalnews - Global News Module
# ------------------------------------------------------------------------
# author    Joomla!Vargas
# copyright Copyright (C) 2010 joomla.vargas.co.cr. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://joomla.vargas.co.cr
# Technical Support:  Forum - http://joomla.vargas.co.cr/forum
-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');

jimport('joomla.application.component.model');

JModel::addIncludePath(JPATH_SITE.'/components/com_content/models', 'ContentModel');

abstract class  modGlobalNewsHelper {

	public static function getGN_Img( &$params, $link, $img, $pfx ) {
	
		$align 	    = $params->get( $pfx.'_img_align', 'left' );
		$margin 	= $params->get( $pfx.'_img_margin', '3px' );
		$width 		= (int)$params->get( $pfx.'_img_width', '' );
		$height 	= (int)$params->get( $pfx.'_img_height', '' );
		$border		= $params->get( $pfx.'_img_border', '0' );
		
		if ( $align == 'left' )  :
			   $style = 'float:left;';
			   if ( $pfx == 'cat' ) {
					$style .= 'margin-right:' . $margin . ';';
			   } else {
					$style .= 'margin:' . $margin . ';';
			   }
		endif;
		
		if ( $align == 'right' )  :
			   $style = 'float:right;';
			   if ( $pfx == 'cat' ) {
					$style .= 'margin-left:' . $margin . ';';
			   } else {
					$style .= 'margin:' . $margin . ';';
			   }
		endif;
		
		$style .= 'border:' . $border . ';';
		
		$attribs = array ('style' => $style);
			
		if (!$params->get('thumb_image', 1)) {
			
			if ( $height )
				$attribs = array('height' => $height, $attribs);
			
			if ( $width )
				$attribs = array('width' => $width,  $attribs );
		}
		
		$image = JHTML::_('image', $img, JText::_('IMAGE'), $attribs );
		
		if ( $link )
			$image = JHTML::_('link', $link, $image);
			
		if ( $align == 'center')
			$image = '<center>' . $image . '</center>';
			
		return $image;
	}

	public static function getGN_Cats(&$params)
	{

		$db			= JFactory::getDBO();
		$user		= JFactory::getUser();
		$groups		= implode(',', $user->getAuthorisedViewLevels());

		$catid  	= $params->get('catid', '');
		$curcat     = $params->get('curcat', 0);
		$current    = $params->get('current', 1);
		$show_cat   = $params->get('show_cat', 1);
		$cat_title  = $params->get('cat_title', 1);
		$cat_img    = $params->get( 'cat_img_align', 0);

		$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
		
		$group_id = $condition = '';

		switch ($params->get( 'cat_order', 1))
		{
			case '0':
				$ordering		= 'rand()';
				break;
			case '1':
				$ordering		= 'c.id ASC';
				break;
			case '2':
				$ordering		= 'c.title ASC';
				break;
			case '3':
			default:
				$ordering		= 'c.lft ASC';
				break;
		}
		
        if ( $curcat == 1 || $current != 1 ) :
		
			 if ( JRequest::getCmd('option') == 'com_content' ) {
			 
		          $view		= JRequest::getCmd('view');
			 
		          $temp		= JRequest::getString('id');
		          $temp		= explode(':', $temp);
		          $id		= $temp[0];		 
			  }		
        endif;
		
		$catids = $params->get('catid');
		JArrayHelper::toInteger($catids);
		$catids = implode(',', $catids);
		if (!empty($catids)) {
			$condition .= ' AND c.id IN ('.$catids.')';
		}
		
		$query = 'SELECT c.*, ' .
		' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(":", c.id, c.alias) ELSE c.id END as slug' .
		' FROM #__categories AS c' .
		' WHERE c.published = 1 AND c.extension = "com_content"' .
		($access ? ' AND c.access IN ('.$groups.')' : '').
		($condition!='' ? $condition : '').
		' ORDER BY '. $ordering;
			
		$db->setQuery($query);
		$cats = $db->loadObjectList();
		
		foreach ( $cats as &$cat ) {
				
			$cat->link = JRoute::_(ContentHelperRoute::getCategoryRoute( $cat->slug ));
			$cat->cond = $cat->id;
	    	$cat->image = '';
	
			if ( $cat_img ) {
				$catParams = new JRegistry;
				$catParams->loadString($cat->params);
				if ( $image = $catParams->get('image')) {
					$cat->image .= modGlobalNewsHelper::getGN_Img( $params, $cat->link, $image, 'cat' );
				}
        	}
		
	    	if ( $group_id == $cat->id && $curcat == 1 ) {
			 	$cat->link = '';
			}
		
	    	if ( $cat_title != 0 ) {
			 	$tags = array(array('',''),array('',''),array('<strong>','</strong>'),array('<u>','</u>'),array('<strong><u>','</u></strong>'),array('<h1>','</h1>'),array('<h2>','</h2>'),array('<h3>','</h3>'),array('<h4>','</h4>'),array('<h5>','</h5>'),array('<h6>','</h6>'));
		     	if ( $show_cat == 2 ) {
			      	$cat->title = $tags[$cat_title][0] .  $cat->title . $tags[$cat_title][1];
		     	} else {
			      $cat->title = ( $cat_title > 4 ? $tags[$cat_title][0] : '' ) . ( $cat->link ? '<a href="' . $cat->link. '">' : '' ) . ( $cat_title < 5 ? $tags[$cat_title][0] : '' ) .  $cat->title . ( $cat_title < 5 ? $tags[$cat_title][1] : '' ) . ( $cat->link ? '</a>' : '' ) . ( $cat_title > 4 ? $tags[$cat_title][1] : '' );
		        }
		    } else {
				$cat->title = '';
			}
		}

		return $cats;
	}

	public static function getGN_List(&$params,$catid)
	{
		$db			= JFactory::getDBO();
		$user		= JFactory::getUser();
		$userId		= (int) $user->get('id');
		$groups		= implode(',', $user->getAuthorisedViewLevels());

		$count		= trim( $params->get('count', 5) );
		$current    = trim( $params->get('current', 1) );
		$layout     = $params->get('layout', 'list');
		$html       = $params->get('html');
		$aid		= $user->get('aid', 0);
		$limittitle	= $params->get('limittitle', '');
		
		$nullDate	= $db->getNullDate();
		jimport('joomla.utilities.date');
		$date = new JDate();
		$now = $date->toMySQL();
		
		$article_id = 0;
        if ($current != 1 && JRequest::getCmd('option') === 'com_content' && JRequest::getCmd('view') === 'article') {
            $article_id = JRequest::getInt('id');
        }
		
		$articles = JModel::getInstance('Articles', 'ContentModel', array('ignore_request' => true));

		$app = JFactory::getApplication();
		$appParams = $app->getParams();
		$articles->setState('params', $appParams);

		$articles->setState('list.start', 0);
		$articles->setState('list.limit', (int) $params->get('count', 5));
		$articles->setState('filter.published', 1);

		$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		$articles->setState('filter.access', $access);

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
						$additional_catids[] = $category->id;
					}

				}
			}

			$catid = array_unique(array_merge(array($catid), $additional_catids));
		}

		$articles->setState('filter.category_id', $catid);

		$articles->setState('list.ordering', $params->get('article_ordering', 'a.ordering'));
		if ($params->get('article_ordering') != 'rand()') {
			$articles->setState('list.direction', $params->get('article_ordering_direction', 'ASC'));
		} else {
			$articles->setState('list.direction', '');
		}
		$articles->setState('filter.featured', $params->get('show_front', 'show'));
		$articles->setState('filter.author_id', $params->get('created_by', ""));
		$articles->setState('filter.author_id.include', 1);

		if ( $article_id && $current == 0 )
		{
			$articles->setState('filter.article_id', $article_id);
			$articles->setState('filter.article_id.include', false); // Exclude
		}
		
		$articles->setState('filter.language',$app->getLanguageFilter());

		$rows = $articles->getItems();
		
		foreach ( $rows as &$row ) {
							
            if ( $article_id == $row->id && $current == 2 ) {
				$link = '';
			} else {
				$row->slug = $row->id.':'.$row->alias;
				$row->catslug = $row->catid ? $row->catid .':'.$row->category_alias : $row->catid;
				$link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug));
			}		
			if ( $limittitle && strlen( $row->title ) > $limittitle ) {
			   $row->title = substr( $row->title, 0, $limittitle );
			   $space   = strrpos( $row->title, ' ' );
			   $row->title = substr( $row->title, 0, $space ). '...';
			}			
            if ( $link ) {
		         $row->title = '<a href="'.$link.'">'.htmlspecialchars( $row->title ).'</a>'; 
            } else {
		         $row->title = htmlspecialchars( $row->title );
			}
			
	        if ( $layout != 'list' ) :
			
				$gn_image    = '';
				$gn_title    = '';
				$gn_created  = '';
				$gn_author   = '';
				$gn_text     = '';
				$gn_readmore = '';
				$gn_comments = '';
			
				if ( preg_match("/GN_title/", $html) ) {
		            $gn_title = $row->title;
					$gn_title = preg_replace('/\$/','$-',$gn_title);
			    }
			
		        if ( preg_match("/GN_date/", $html) ) {
	      	    	$gn_created = JHTML::_('date',  ($params->get( 'date' ) == 'created' ? $row->created : $row->modified ),  $params->get('date_format', '' ) );
			    }
				
		        if ( preg_match("/GN_author/", $html) ) {
					$author = $params->get( 'author' );
					if ( $author != 'alias' ) {
						$query = "SELECT " . $author . " FROM #__users WHERE id = " . $row->created_by;
						$db->setQuery($query);
						$gn_author = $db->loadResult();
					} else {
						$gn_author = $row->created_by_alias;
					}
			    }
				 
		        if ( preg_match("/GN_image/", $html) ) {
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
						if ( count($images) ) {
						  $img  = $images[1];
						}
					endif;
					
					if ($img) {
						if ($params->get('thumb_image', 1)) {
							$img = str_replace(JURI::base(false),'',$img);
							$img = modGlobalNewsHelper::getThumbnail($img,$params,$row->id); 
						}
						$gn_image = modGlobalNewsHelper::getGN_Img ( $params, $link, $img, 'item' );
					}
						
		        }
						
		        if ( preg_match("/GN_text/", $html) ) {
					$limit   = trim( $params->get('limittext', '150') );
					$gn_text = $row->introtext;
					if ( $params->get('striptext', '1'))
					  $gn_text = trim( strip_tags(  $gn_text, $params->get('allowedtags', '') ) );
					$pattern = array("/[\n\t\r]+/",'/{(\w+)[^}]*}.*{\/\1}|{(\w+)[^}]*}/Us', '/\$/');
					$replace = array(' ', '', '$-');
					$gn_text = preg_replace( $pattern, $replace, $gn_text );
					if ( $limit && strlen( $gn_text ) > $limit ) {
					   $gn_text = substr( $gn_text, 0, $limit );
					   $space   = strrpos( $gn_text, ' ' );
					   $gn_text = substr( $gn_text, 0, $space ). '...';
					}
			    }
				 
	 			if ( preg_match("/GN_readmore/", $html) && $link ) {
		        	$gn_readmore  = JHTML::_('link', $link, JText::_('MOD_GLOBALNEWS_READ_MORE_TITLE'));
	            }
				 
				$code = array("/GN_image/", "/GN_title/", "/GN_date/", "/GN_author/", "/GN_text/", "/GN_readmore/", "/GN_comments/","/GN_break/","/GN_space/","/GN_hits_label/","/GN_hits_value/");
				$rplc = array( $gn_image, $gn_title, $gn_created, $gn_author, $gn_text, $gn_readmore, $gn_comments, "<br />", "&nbsp;", JText::_('MOD_GLOBALNEWS_HITS_LABEL'), $row->hits);
				 
				$row->content = preg_replace($code, $rplc, $html);
				$row->content = preg_replace('/\$-/','$',$row->content);
				 				 
            endif;
		}

		return $rows;
	}
	
	public static function addGN_CSS(&$params, $layout, $globalnews_id, $total) {
	
		$doc = JFactory::getDocument();

		$layout   = $params->get( 'layout', 'list' );
		$padding  = (int)$params->get('padding', '5px');
		$border   = $params->get('border', '1px solid #EFEFEF');
		$color    = $params->get('color', '#FFFFFF');
		$show_cat = $params->get('show_cat', '1');
		
		$css = '';
		
		if ( $globalnews_id == 1 ) :
			$css .= ".gn_clear { clear:both; height:0; line-height:0; }\n";
		endif;
		
		$header   = '.gn_header_' . $globalnews_id . ' {'
			.' background-color:' . $params->get('header_color', '#EFEFEF') . ';'
			.' border:' . $border . ';'
			.' border-bottom:none;'
			.' padding:' . (int)$params->get('header_padding', '5px') . 'px;'
			.' }';
		
		$marquee  = ' border:' . $border . ';'
			. ( $show_cat != 0 ? ' border-top:none;' : '' )
			.' padding:' . $padding . 'px;'
			.' height:' . $params->get('height', '100px') . ';'
			.' background-color:' . $color . ';'
			.' overflow:hidden;';
				 
		switch ( $layout ) {
		 
			 case 'list' :
				   $css .=  $header . "\n"
						 .".gn_list_" . $globalnews_id . " {"
						 . $marquee
						 ." }";
				   break;
			
			 case 'static' :
				   $css .=  $header . "\n"
						 .".gn_static_" . $globalnews_id . " {"
						 . $marquee
						 ." }";
				   break;
		
			 case 'slider' :
				   $css .=  $header . "\n"
						 .".gn_slider_" . $globalnews_id . " {"
						 . $marquee
						 ." border-bottom:none;"
						 ." }" . "\n"
						 .".gn_slider_" . $globalnews_id . " .gn_opacitylayer {"
						 ." height:100%;"
						 ." filter:progid:DXImageTransform.Microsoft.alpha(opacity=100);"
						 ." -moz-opacity:1;"
						 ." opacity:1;"
						 ." }" . "\n"
						 .".gn_pagination_" . $globalnews_id . " {"
						 ." border:" . $border . ";"
						 ." border-top:none;"
						 ." padding:2px " . $padding . "px;"
						 ." text-align:right;"
						 ." background-color:" . $color . ";"
						 ." }" . "\n"
						 .".gn_pagination_" . $globalnews_id . " a:link {"
						 ." font-weight:bold;"
						 ." padding:0 2px;"
						 ." }" . "\n"
						 .".gn_pagination_" . $globalnews_id . " a:hover, .gn_pagination_" . $globalnews_id . " a.selected {"
						 ." color:#000;"
						 ." }";
				   break;
		
			 case 'browser' :
				   $containerIds = array();
				   for ($m=0;$m<$total;$m++) { 
						$containerIds[$m] = '#gn_container_' . $globalnews_id . '_' . ($m+1); }
				   $css .=  $header . "\n"
						 . implode(',' , $containerIds) . " {"
						 . $marquee
						 ." position: relative;"
						 ." }";
				   break;
		
			 case 'scroller' :
				   $scrollerIds = array();
				   for ($m=0;$m<$total;$m++) { 
						$scrollerIds[$m] = '#gn_scroller_' . $globalnews_id . '_' . ($m+1); }
				   $css .=  $header . "\n"
						 . implode(',' , $scrollerIds) . " {"
						 . $marquee
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
		
				modGlobalNewsHelper::calculateSize($origw, $origh, $width, $height, $proportion, $newwidth, $newheight, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);
	
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
		
		$image = JURI::base(false)."modules/mod_globalnews/thumbs/$sub_folder/" . basename($thumb_path);
		
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