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

class jPFChatView {
     function showjPFChat(  $option, $rows ) {
          if (defined ( '_JEXEC' ))      {          

        ?><form action="index.php" method="post" name="adminForm"><?php

        }  else { ?>
                 <script language="javascript" type="text/javascript">
                    function submitbutton(pressbutton) {
                          var form = document.adminForm;
                          submitform( pressbutton );
                    }
                  </script> 
                  <form action="index2.php" method="post" name="adminForm"><?php
         }

                if (defined ( '_JEXEC' )) {
                   jimport('joomla.html.pane');
                   $pane = JPane::getInstance('tabs');
                   echo $pane->startPane( "jPFChat" );
                   echo $pane->startPanel( 'Main Settings' , "tabGeneral" );

                } else {
                   mosCommonHTML::loadOverlib();
                   $tabs = new mosTabs( 1 );
                   $tabs->startPane( "jPFChat" );
                   $tabs->startTab( 'Main Settings' , "tabGeneral" );
                }

                tablestart() ;
                jPFChatView::showconfrows($rows, 1);
                ?></table><?php

                if (defined ( '_JEXEC' )) {
                  echo $pane->endPanel();
                  echo $pane->startPanel(  "Display" , "tabDisplay" );
                } else {
                  $tabs->endTab();
                  $tabs->startTab(  "Display" , "tabDisplay" );
                }
                
                tablestart() ;
                jPFChatView::showconfrows($rows, 2);
                ?></table><?php

                if (defined ( '_JEXEC' )) {
                  echo $pane->endPanel();
                  echo $pane->startPanel( "Advanced" , "tabAdvanced" );
                } else {
                  $tabs->endTab();
                  $tabs->startTab( "Advanced" , "tabAdvanced" );
                }

                tablestart() ;
                jPFChatView::showconfrows($rows, 3);
                ?></table><?php

                if (defined ( '_JEXEC' ))  {
                   echo $pane->endPanel();
                   echo $pane->startPanel( "License" , "tabLicense" );
                } else {
                   $tabs->endTab();
                   $tabs->startTab( "License" , "tabLicense" );
                }
                
                tablestart() ;
                jPFChatView::showconfrows($rows, 4);
                ?></table><?php

                if (defined ( '_JEXEC' )) {
                   echo $pane->endPanel();
                   echo $pane->startPanel("About...", "tabAbout" );
                } else {
                   $tabs->endTab();
                   $tabs->startTab("About...", "tabAbout" );
                }
                
                tablestart() ;  ?>

                <tr>
                        <td align="left">
                        <img style="margin-bottom: 10px; margin-right: 15px;" align="left" border="1" src='../../components/com_jpfchat/images/jpfchat_logo_mini.png' alt='jPFChat' />
                        <p><a target="_blank" href="http://www.jpfchat.com/index.php?option=com_versions&catid=1&myVersion=<?php echo jPFC_VERSION ?>"><font color="black" size="2"><u>Version Check</u></font></a></p>
                        <p>jPFChat is maintained and supported by <a href="http://www.vizimetrics.com">Vizimetrics, Inc.</a><br />
                        <p>Please visit the <strong><u>official jPFChat website</u></strong> at <a href="http://www.jPFChat.com">www.jPFChat.com</a> for updates.</p>
                        <p>Support is available via our online help ticket system at
                        <a href="http://support.vizimetrics.com">support.vizimetrics.com</a> or via email: <a href="mailto:service@vizimetrics.com">service@vizimetrics.com</a> </p>
                        <p>phpFreeChat information and help is available at: <a href="http://www.phpfreechat.net">www.phpFreeChat.net</a></p>
                        <p>If you're pleased with jPFChat, please don't forget to support the jPFChat project.</p>
                        <ul><li>Write a favorable comment on the Joomla.org website...</li>
                            <li>Purchase the right to remove the jPFChat logo, or...</li>
                            <li>Donate to the jPFChat project.</li>
                        </ul>
                        <p>Thanks for your continued confidence and for giving jPFChat a try!</p>
                        <p>jPFChat for Joomla &nbsp;&copy; 2008 ViziMetrics, Inc - All rights reserved.</p>
                        </td>
                        <td></td>
                </tr>
                </table>

                <?php
                if (defined ( '_JEXEC' )) {
                   echo $pane->endPanel();
                   echo $pane->endPane();
                } else {
                   $tabs->endTab();
                   $tabs->endPane();
                }
                ?>
                <input type="hidden" name="option" value="<?php echo $option;?>" />
                <input type="hidden" name="task" value="" />
                </form>  <?php
     } 
              

     function showconfrows($rows, $c_row) {
          foreach ($rows as $row) {
            if ($row->tab == $c_row) {
                if ($row->type == 'B') {
                   if (defined ( '_JEXEC' )) {
                        $optionHTML = JHTML::_( 'select.booleanlist', 'newValues['.$row->name.']', '', $row->value, 'ON', 'OFF' );
                   } else {
                        $myOnOff = array();
                        $myOnOff[] = mosHTML::makeOption( 1, 'ON' );
                        $myOnOff[] = mosHTML::makeOption( 0, 'OFF' );
                        $optionHTML = mosHTML::radioList( $myOnOff, 'newValues['.$row->name.']', '', $row->value );
                    }
                } elseif ($row->name == 'date_format') {
                    $myDates = array();
                    if (defined ( '_JEXEC' ))  {
                        $myDates[] = JHTML::_( 'select.option', 'm/d/Y', 'm/d/Y' );
                        $myDates[] = JHTML::_( 'select.option', 'd/m/Y', 'd/m/Y' );
                        $optionHTML = JHTML::_('select.genericlist', $myDates,    'newValues['.$row->name.']', 'size="1" class="inputbox"', 'value', 'text', $row->value );
                    } else {
                        $myDates[] = mosHTML::makeOption( 'm/d/Y' );
                        $myDates[]  = mosHTML::makeOption( 'd/m/Y' );
                        $optionHTML = mosHTML::radioList( $myDates, 'newValues['.$row->name.']', '', $row->value );
                    }
                } elseif ($row->name == 'name_or_uname') {
                    $myName = array();
                    if (defined ( '_JEXEC' ))  {
                        $myName[] = JHTML::_( 'select.option', 'Username', 'Username' );
                        $myName[] = JHTML::_( 'select.option', 'Name', 'Name' );
                        $optionHTML = JHTML::_('select.genericlist', $myName,    'newValues['.$row->name.']', 'size="1" class="inputbox"', 'value', 'text', $row->value );
                    } else {
                        $myName[] = mosHTML::makeOption( 'Username' );
                        $myName[] = mosHTML::makeOption( 'Name' );
                        $optionHTML = mosHTML::radioList( $myName, 'newValues['.$row->name.']', '', $row->value );
                    }
                } elseif ($row->name == 'mysql_file') {
                    $myName = array();
                    if (defined ( '_JEXEC' ))  {
                        $myName[] = JHTML::_( 'select.option', 'Mysql', 'Mysql' );
                        $myName[] = JHTML::_( 'select.option', 'File', 'File' );
                        $optionHTML = JHTML::_('select.genericlist', $myName,    'newValues['.$row->name.']', 'size="1" class="inputbox"', 'value', 'text', $row->value );
                    } else {
                        $myName[] = mosHTML::makeOption( 'Mysql' );
                        $myName[] = mosHTML::makeOption( 'File' );
                        $optionHTML = mosHTML::radioList( $myName, 'newValues['.$row->name.']', '', $row->value );
                    }
                } elseif ($row->name == 'allowed_level') {
                    $myName = array();
                    if (defined ( '_JEXEC' ))  {
                        $myName[] = JHTML::_( 'select.option', '0', 'Everyone' );
                        $myName[] = JHTML::_( 'select.option', '1', 'Registered' );
                        $myName[] = JHTML::_( 'select.option', '2', 'Special' );
                        $optionHTML = JHTML::_('select.genericlist', $myName,    'newValues['.$row->name.']', 'size="1" class="inputbox"', 'value', 'text', $row->value );
                    } else {
                        $myName[] = mosHTML::makeOption( '0','Everyone' );
                        $myName[] = mosHTML::makeOption( '1','Registered' );
                        $myName[] = mosHTML::makeOption( '2','Special' );
                        $optionHTML = mosHTML::radioList( $myName, 'newValues['.$row->name.']', '', $row->value );
                    }
                } elseif ($row->name == 'theme') {
                    $myFolders = getFolders ( COM_PATH.'/pfc/themes');
                    if (defined ( '_JEXEC' ))  {
                        $optionHTML = JHTML::_('select.genericlist', $myFolders, 'newValues['.$row->name.']', 'size="1" class="inputbox"', 'value', 'text', $row->value );
                    } else {
                        $optionHTML = mosHTML::selectList( $myFolders, 'newValues['.$row->name.']', 'size="1" class="inputbox"', 'value', 'text', $row->value );
                    }
                } elseif ($row->name == 'language') {
                    $myFolders = getFolders ( COM_PATH.'/pfc/i18n');
                    if (defined ( '_JEXEC' ))  {
                         $optionHTML = JHTML::_('select.genericlist', $myFolders, 'newValues['.$row->name.']', 'size="1" class="inputbox"', 'value', 'text', $row->value );
                    } else {
                        $optionHTML = mosHTML::selectList( $myFolders, 'newValues['.$row->name.']', 'size="1" class="inputbox"', 'value', 'text', $row->value );
                    }
                } elseif ($row->name == 'shownotice') {
                    $myNotices = array();
                    if (defined ( '_JEXEC' ))  {
                        $myNotices[] = JHTML::_( 'select.option', 0, 'No Notifications' );
                        $myNotices[] = JHTML::_( 'select.option', 1, 'Nickname Changes Only' );
                        $myNotices[] = JHTML::_( 'select.option', 2, 'Connects/Disconnects Only' );
                        $myNotices[] = JHTML::_( 'select.option', 3, 'All Notices' );
                        $optionHTML = JHTML::_('select.genericlist', $myNotices, 'newValues['.$row->name.']', 'size="1" class="inputbox"', 'value', 'text', $row->value );
                    } else {
                        $myNotices[] = mosHTML::makeOption( 0, 'No System Notifications' );
                        $myNotices[] = mosHTML::makeOption( 1, 'Show Nickname Changes Only' );
                        $myNotices[] = mosHTML::makeOption( 2, 'Show Connects / Disconnects Only' );
                        $myNotices[] = mosHTML::makeOption( 3, 'Show All Notices' );
                        $optionHTML = mosHTML::selectList( $myNotices, 'newValues['.$row->name.']', 'size="1" class="inputbox"', 'value', 'text', $row->value );
                    }
                } else {
                    $optionHTML = '<input type="text" name="newValues['.$row->name.']" value="'.$row->value.'">';
                }

                if ($row->tab == 4) {   ?>   
                    <tr><th align="left" valign="top"><?php echo $row->prompt; ?></th>
                            <td align="left" valign="top"><?php echo $optionHTML; ?></td>
                            <td align="left" valign="top"><font color="red"><b><?php echo $row->description; ?></b></font></td>
                    </tr><?php
                } else {  ?>                
                    <tr><th align="left" valign="top"><?php echo $row->prompt; ?></th>
                           <td align="left" valign="top"><?php echo $optionHTML; ?></td>
                           <td align="left" valign="top"><?php echo $row->description; ?></td>
                    </tr><?php
               }
            }
          }
     }  //end function
}  // end class

function tablestart()  {
  ?><table width="90%" border="0" cellpadding="6" cellspacing="4" class="adminForm"> <?php
}

function getFolders( $dir ) {
    $folderList = array();
    if ($handle = opendir($dir)) {
        while (false !== ($folder = readdir($handle))) {
            if ($folder != "." && $folder != "..") {
                  if (defined ( '_JEXEC' ))  {
                      $folderList[] = JHTML::_( 'select.option', $folder );
                  } else {
                      $folderList[] = mosHTML::makeOption( $folder );
                  }
            }
        }
        closedir($handle);
    }
    return $folderList;
}
?>
