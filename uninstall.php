<?php
/* PHP code to uninstall the plugin */


require_once 'functions.php';

SkynetWidget::removeScript();
SkynetWidget::clearCache();