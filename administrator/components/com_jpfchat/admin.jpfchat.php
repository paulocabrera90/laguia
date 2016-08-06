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
 define ( 'jPFC_VERSION', '2.1.0');

if (defined ( '_JEXEC' ))      {
      require_once( JApplicationHelper::getPath( 'admin_html' ) );
      JTable::addIncludePath(JPATH_COMPONENT.'/tables');
      define ( 'COM_PATH', JPATH_SITE . '/components/com_jpfchat' );
} else {
      global $database, $mosConfig_absolute_path;
      require_once( $mainframe->getPath( 'admin_html' ) );
      $task = mosGetParam( $_REQUEST, 'task', '' );
      $option = strtolower( mosGetParam( $_REQUEST, 'option', '' ));
      define('COM_PATH', $mosConfig_absolute_path.'/components/com_jpfchat' );
}

switch ($task) {
             case "save":
                $newValues = JRequest::getVar( 'newValues', '', 'POST');
                saveParams ( $option, $newValues );
                break;
            case "saveeditconf":
                $newValues = mosGetParam($_REQUEST,'newValues', '');
                saveParams ( $option , $newValues );
                break;
             default:
                showConf( $option );
                break;
}

function showConf ($option) {
     if (defined ( '_JEXEC' )) {
           global $mainframe;
          $database =& JFactory::getDBO();
     } else {
           global $database;
     }
     $query = "SELECT * FROM #__jpfchat WHERE tab>0 ORDER BY tab,seq";
     $database->setQuery($query);
     $rows = $database->loadObjectList();

     jPFChatView::showjPFChat( $option, $rows );
}

function saveParams ( $option , $newValues ) {
     if (defined ( '_JEXEC' )) {
           global $mainframe;
          $database =& JFactory::getDBO();
     } else {
           global $database;
     }
     $query = "SELECT * FROM #__jpfchat";
     $database->setQuery($query);
     $rows = $database->loadObjectList();

     foreach ($rows as $row) {
         if ($row->name == 'serverid') {
              $serverid = $row->value;
         } else {
              if ($row->type == 'N') {
                   if (is_intval2($row->value) !== is_intval2($newValues[$row->name])) {
                          $newValues[$row->name] = $row->value;
                   }
              }
              $query = "UPDATE #__jpfchat SET value ='".$newValues[$row->name]."' WHERE name ='".$row->name."'";
              $database->setQuery($query);
              $database->query();
         }
     }

     require_once ( COM_PATH . '/pfc/src/pfcinfo.class.php');
     $info = new pfcInfo($serverid);
     $info->rehash();

     if (defined ( '_JEXEC' ))  {
        $mainframe->redirect( 'index.php?option=' . $option . '&task=showConf', 'Settings Updated! - ALL Chat users must be logged out to clear the cache. CLOSE YOUR BROWSER BEFORE OPENING THE CHAT!!!');
     } else {
        mosRedirect( 'index2.php?option=' . $option . '&task=showConf', 'Settings Updated! - ALL Chat users must be logged out to clear the cache.  CLOSE YOUR BROWSER BEFORE OPENING THE CHAT!!!');
     }
}

function is_intval2($a) {
   return ((string)$a === (string)(int)$a);
}

?>
