<?php

/**
 * @copyright	Copyright (C) 2011 Cédric KEIFLIN alias ced1870
 * http://www.ck-web-creation-alsace.com
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.event.plugin');

class plgSystemMaximenuckparams extends JPlugin {

    function plgSystemMaximenuckparams(&$subject, $params) {
        parent::__construct($subject, $params);
    }

    /**
     * @param       JForm   The form to be altered.
     * @param       array   The associated data for the form.
     * @return      boolean
     * @since       1.6
     */
    function onContentPrepareForm($form, $data) {
        if ($form->getName() != 'com_modules.module'
                && $form->getName() != 'com_menus.item'
                || ($form->getName() == 'com_modules.module' && $data && $data->module != 'mod_maximenuck')
                || ($form->getName() == 'com_advancedmodules.module' && $data && $data->module != 'mod_maximenuck'))
            return;
        // TODO : ajouter condition pour advanced modules manager

        JForm::addFormPath(JPATH_SITE . '/plugins/system/maximenuckparams/params');
        JForm::addFieldPath(JPATH_SITE . '/modules/mod_maximenuck/elements');

        // get the language
        $lang = JFactory::getLanguage();
        $langtag = $lang->getTag(); // returns fr-FR or en-GB
        $this->loadLanguage();

        // module options
        if ($form->getName() == 'com_modules.module' || $form->getName() == 'com_advancedmodules.module') {
            $form->loadFile('advanced_menuparams_maximenuck', false);
        }

        // menu item options
        if ($form->getName() == 'com_menus.item') {
            $form->loadFile('advanced_itemparams_maximenuck', false);
        }
    }

    // end of the class
}

?>