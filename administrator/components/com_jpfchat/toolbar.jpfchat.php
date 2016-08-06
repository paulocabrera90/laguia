<?php
/**
 * jPFChat - A joomla chatroom component
 * NOTE: This component uses the phpFreeChat script.    We did NOT write phpFreeChat.
 *       Please see phpfreechat.net if you have questions or issues about the chat script itself.
 * @version $Id: jPFChat.php
 * @author Vizimetrics, Inc (Tim Milo)
 * @link http://www.jPFChat.com
 * @copyright (C) 2008 ViziMetrics, Inc - All rights reserved.
 * @license GNU/GPL License
 */

(defined( '_VALID_MOS' ) or defined ( '_JEXEC' )) or die ( 'Restricted Access' );

if (defined ( '_JEXEC' ))      {
     require_once( JApplicationHelper::getPath( 'toolbar_html' ) );
} else {
     require_once( $mainframe->getPath( 'toolbar_html' ) );
}

TOOLBAR_jPFChat::_DEFAULT();
?>
