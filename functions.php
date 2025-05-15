<?php
require_once __DIR__ . '/../../main/inc/global.inc.php';

class SkynetWidget
{

    public static string $script = '<script id="aioa-adawidget" src="https://www.skynettechnologies.com/accessibility/js/all-in-one-accessibility-js-widget-minify.js?colorcode=#420083&token=&position=bottom_right" defer></script>';

    public static function addScript()
    {

        $scriptContentToAdd = self::$script;

        // Get the active theme
        $theme = "default";

        if ($theme) {
            $themeName = $theme;
            $layoutFile = api_get_path(SYS_PATH) . 'main/template/' . $themeName . '/layout/head.tpl'; // Adjust if needed

            if (file_exists($layoutFile)) {

                $content = file_get_contents($layoutFile);

                if (strpos(
                    $content,
                    $scriptContentToAdd
                ) === false) {
                    // Assuming </head> is still the closing tag
                    // $newContent = str_replace('</head>', $scriptContentToAdd . "\n</head>", $content);
                    $newContent = str_replace(
                        '{{ extra_headers }}',
                        '{{ extra_headers }}' . "\n" . $scriptContentToAdd,
                        $content
                    );

                    if ($content !== $newContent) {
                        if (file_put_contents($layoutFile, $newContent)) {
                            // Script added successfully during installation
                            //    return true;
                        } else {
                            echo ('all_in_one_accessibility: Failed to write to layout file during installation.');
                            //    return false;
                        }
                    } else {
                        // return true; // Script already present
                    }
                } else {
                    //  return true; // Script already exists
                }
            } else {
                echo ('all_in_one_accessibility: Layout file not found for theme: ' . $themeName);
                //   return false;
            }
        } else {
            echo ('all_in_one_accessibility: Could not determine the active theme during installation.');
            //    return false;
        }
    }

    public static function removeScript()
    {
        $scriptContentToRemove = self::$script;

        // Get the active theme
        $theme = "default";

        if ($theme) {
            $themeName = $theme;
            $layoutFile = api_get_path(SYS_PATH) . 'main/template/' . $themeName . '/layout/head.tpl'; // Adjust if needed

            if (file_exists($layoutFile)) {
                // Get the content of the layout file
                $content = file_get_contents($layoutFile);

                // Check if the script tag exists in the content
                if (strpos($content, $scriptContentToRemove) !== false) {
                    // Remove the script tag from the content
                    $newContent = str_replace($scriptContentToRemove, '', $content);

                    // If the content was modified, write the changes back
                    if ($content !== $newContent) {
                        if (file_put_contents($layoutFile, $newContent)) {
                        } else {
                            error_log('all_in_one_accessibility: Failed to write to layout file during removal.');
                        }
                    }
                } else {
                }
            } else {
                error_log('all_in_one_accessibility: Layout file not found for theme: ' . $themeName);
            }
        } else {
            error_log('all_in_one_accessibility: Could not determine the active theme during removal.');
        }
    }

    public static function clearCache()
    {

        if (function_exists('opcache_reset')) {
            opcache_reset();
        }

        $file = api_get_path(SYS_PUBLIC_PATH) . 'build/main.js';
        if (file_exists($file)) {
            unlink($file);
        }
        $dir = api_get_path(SYS_PUBLIC_PATH) . 'build';
        $files = scandir($dir);
        foreach ($files as $file) {
            if (preg_match('/main\..*\.js/', $file)) {
                unlink($dir . '/' . $file);
            }
        }

        $archive_path = api_get_path(SYS_ARCHIVE_PATH);
        $htaccess = <<<TEXT
                        <IfModule mod_authz_core.c>
                            Require all denied
                        </IfModule>
                        <IfModule !mod_authz_core.c>
                            Order deny,allow
                            Deny from all
                        </IfModule>
                        # pChart generated files should be allowed
                        <FilesMatch "^[0-9a-f]+$">
                            order allow,deny
                            allow from all
                        </FilesMatch>
                        php_flag engine off
                        TEXT;

        $result = rmdirr($archive_path, true, true);

        if (!empty($htaccess)) {
            @file_put_contents($archive_path . '/.htaccess', $htaccess);
        }
    }

    public static function getWidgetInfo()
    {

        $user_id = api_get_user_id();
        $user_info = api_get_user_info($user_id);
        $domain = api_get_path(WEB_PATH);

        $now = new DateTime('now', new DateTimeZone('UTC'));
        $dateTime = $now->format('Y-m-d\TH:i:sO');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://ada.skynettechnologies.us/api/add-user-domain',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'name'              => $user_info['complete_name'],
                'email'             => $user_info['email'],
                'company_name'      => '',
                'website'           => base64_encode($domain),
                'package_type'      => 'free-widget',
                'start_date'        => $dateTime,
                'end_date'          => '',
                'price'             => '',
                'discount_price'    => '0',
                'platform'          => 'Craft',
                'api_key'           => '',
                'is_trial_period'   => '',
                'is_free_widget'    => '1',
                'bill_address'      => '',
                'country'           => '',
                'state'             => '',
                'city'              => '',
                'post_code'         => '',
                'transaction_id'    => '',
                'subscr_id'         => '',
                'payment_source'    => ''
            ),
        ));

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  // ⚠️ Don't use false in production

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);
    }

    public static function fetchWidgetSettings()
    {

        $domain = api_get_path(WEB_PATH);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://ada.skynettechnologies.us/api/widget-settings',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('website_url' => $domain),
        ));

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public static function settingsUpdated()
    {
        session_start();

        // success message
        $_SESSION['success'] = "Settings updated successfully!";

        return true;
    }
}
