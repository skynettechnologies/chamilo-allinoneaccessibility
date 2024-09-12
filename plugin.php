<?php
/**
 * This script is a configuration file for the date plugin. You can use it as a master for other platform plugins (course plugins are slightly different).
 * These settings will be used in the administration interface for plugins (Chamilo configuration settings->Plugins).
 *
 * @package chamilo.plugin
 *
 * @author Skynet Technologies USA LLC <hello@skynettechnologies.com >
 */
/**
 * Plugin details (must be present).
 */

//the plugin title
$plugin_info['title'] = 'All in One Accessibility™';

//the comments that go with the plugin
$plugin_info['comment'] = "Quick Web Accessibility Implementation with All In One Accessibility!";
//the plugin version
$plugin_info['version'] = '1.0';
//the plugin author
$plugin_info['author'] = 'Skynet Technologies USA LLC';
$plugin_info['directory'] = api_get_path(WEB_PLUGIN_PATH).'chamilo-allinoneaccessibility/';

//the plugin configuration
$form = new FormValidator('allinoneaccessibility_form');
$form->addElement('header', '<script src="'.$plugin_info['directory'].'javascript/aios.js"></script>');

//display form
$plugin_info['settings_form'] = $form;

 