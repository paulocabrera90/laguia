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

function com_install() {

        if (defined ( '_JEXEC' ))      {
                global $mainframe;
                $database =& JFactory::getDBO();        
        } else {
                global $database, $mosConfig_live_site;
        }

        //add admin menu images
        $database->setQuery("SELECT id FROM #__components WHERE admin_menu_link = 'option=com_jpfchat'");
        $id = $database->loadResult();
        $database->setQuery("UPDATE #__components " . "SET admin_menu_img  = '../administrator/components/com_jpfchat/images/jpfchat_menu.png'" . ",   admin_menu_link = 'option=com_jpfchat' " . "WHERE id='$id'");
        $database->query();

        //add the serverID value
        $serverid = md5(__FILE__);
        $database->setQuery("UPDATE #__jpfchat SET value = '$serverid' WHERE name='serverid'");
        $database->query();
}

?>
