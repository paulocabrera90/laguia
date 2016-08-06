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

class TOOLBAR_jPFChat {
        function _DEFAULT() {
                if (defined ( '_JEXEC' ))      {
                    JToolBarHelper::title(JText::_('jPFChat Administration'),'generic.png');
                    JToolBarHelper::save();
                    JToolBarHelper::cancel();
                    JToolBarHelper::help('help',true);
                } else {
                    mosMenuBar::startTable();
                    mosMenuBar::spacer();
                    mosMenuBar::save('saveeditconf');
                    mosMenuBar::spacer();
                    mosMenuBar::help( 'help',true );
                    mosMenuBar::endTable();
                }
        }
}
?>
