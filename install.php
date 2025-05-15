<?php
/* PHP code to install the plugin
 * For example:
 *
    // To query something to the database

    $table = Database::get_main_table(TABLE_MAIN_USER); // TABLE_MAIN_USER is a constant check the main/inc/database.constants.inc.php
    $sql = "SELECT firstname, lastname FROM $table_users ";
    $users = Database::query($sql);

    You can also use the Chamilo classes
    $users = UserManager::get_user_list();

 */
// Define the script content or URL
@mkdir(_MPDF_TEMP_PATH, api_get_permissions_for_new_directories(), true);

require_once 'functions.php';

SkynetWidget::addScript();
SkynetWidget::clearCache();
