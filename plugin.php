<?php

/**
 * This script is a configuration file for the date plugin.
 * You can use it as a master for other platform plugins (course plugins are slightly different).
 * These settings will be used in the administration interface for plugins (Chamilo configuration settings->Plugins).
 *
 * @package chamilo.plugin
 *
 * @author Julio Montoya <gugli100@gmail.com>
 */
/**
 * Plugin details (must be present).
 */

/* Plugin config */

//the plugin title
$plugin_info['title'] = 'All in One Accessibility';
//the comments that go with the plugin
$plugin_info['comment'] = "Did you know? Your website's accessibility can make or break your audience's experience. With the All in One Accessibility AI Free Accessibility Widget, you can instantly boost your site's inclusivity and support over 140+ languages! Experience the power of 23 essential features in our free version and take the first step towards creating a better web for everyone.";
//the plugin version
$plugin_info['version'] = '1.0';
//the plugin author
$plugin_info['author'] = 'Skynet Technologies USA LLC';
/* Plugin optional settings */


/*
 * This form will be showed in the plugin settings once the plugin was installed
 * in the plugin/hello_world/index.php you can have
 * access to the value: $plugin_info['settings']['hello_world_show_type']
*/



$redirect_url = '/plugin/all_in_one_accessibility/all_in_one_accessibility.php'; // Your custom domain

if (isset($_GET['name']) && $_GET['name'] == "all_in_one_accessibility") {
    header('Location: ' . $redirect_url);
    exit(); // Always use exit() after a header redirect
}

// redirect_launcher.php




// $form = new FormValidator('hello_world_form');

// //A simple select
// $options = ['hello_world' => 'Hello World', 'hello' => 'Hello', 'hi' => 'Hi!'];
// $form->addElement('select', 'show_type', 'Hello world types', $options);
// $form->addButtonSave(get_lang('Save'), 'submit_button');

// $plugin_info['settings_form'] = $form;
