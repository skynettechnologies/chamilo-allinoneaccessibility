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


            $overridesHead = api_get_path(SYS_PATH) . 'main/template/overrides/layout/head.tpl';
            $templateHead = api_get_path(SYS_PATH) . 'main/template/' . $theme . '/layout/head.tpl';

            if (is_file($overridesHead) && is_writable($overridesHead)) {
                // Override file exists, modify it
                $content = file_get_contents($overridesHead);
            } elseif (is_file($templateHead) && is_readable($templateHead)) {
                // Override file does not exist, copy and modify from theme
                $content = file_get_contents($templateHead);
            } else {
                echo 'Cannot find or read any head.tpl file.';
                return;
            }

            // Check if script is already injected
            if (strpos($content, $scriptContentToAdd) === false) {
                if (strpos($content, '{{ extra_headers }}') !== false) {
                    $newContent = str_replace(
                        '{{ extra_headers }}',
                        '{{ extra_headers }}' . "\n" . $scriptContentToAdd,
                        $content
                    );
                } else {
                    // Fallback: inject before </head> if {{ extra_headers }} not found
                    $newContent = str_replace(
                        '</head>',
                        $scriptContentToAdd . "\n</head>",
                        $content
                    );
                }

                // Ensure override directory exists
                $overrideDir = dirname($overridesHead);
                if (!is_dir($overrideDir)) {
                    mkdir($overrideDir, 0775, true);
                }

                if (file_put_contents($overridesHead, $newContent)) {
                    echo 'head.tpl override created or updated successfully.';
                } else {
                    echo 'Failed to write to override head.tpl.';
                }
            } else {
                echo 'Script already present in head.tpl. No changes made.';
            }
        } else {
            echo ('all_in_one_accessibility: Could not determine the active theme during installation.');
            //    return false;
        }

        self::getWidgetInfo();
    }

    public static function removeScript()
    {
        $scriptContentToRemove = self::$script;

        // Get the active theme
        $theme = "default";

        if ($theme) {


            $overridesHead = api_get_path(SYS_PATH) . 'main/template/overrides/layout/head.tpl';
            $templateHead = api_get_path(SYS_PATH) . 'main/template/' . $theme . '/layout/head.tpl';

            // Step 1: Prefer modifying the override file
            if (file_exists($overridesHead) && is_writable($overridesHead)) {
                $content = file_get_contents($overridesHead);

                if (strpos($content, $scriptContentToRemove) !== false) {
                    $newContent = str_replace($scriptContentToRemove, '', $content);

                    if ($content !== $newContent) {
                        if (file_put_contents($overridesHead, $newContent)) {
                            echo 'Script removed from override head.tpl.';
                        } else {
                            error_log('Failed to write to override head.tpl during script removal.');
                        }
                    }
                } else {
                    echo 'Script not found in override head.tpl.';
                }
            }
            // Step 2: If no override exists, fallback to theme file (NOT recommended, but optional)
            elseif (file_exists($templateHead) && is_writable($templateHead)) {
                $content = file_get_contents($templateHead);

                if (strpos($content, $scriptContentToRemove) !== false) {
                    $newContent = str_replace($scriptContentToRemove, '', $content);

                    if ($content !== $newContent) {
                        // Write to override file instead of theme file to keep things upgrade-safe
                        $overrideDir = dirname($overridesHead);
                        if (!is_dir($overrideDir)) {
                            mkdir($overrideDir, 0775, true);
                        }

                        if (file_put_contents($overridesHead, $newContent)) {
                            echo 'Override file created with script removed.';
                        } else {
                            error_log('Failed to create override file during script removal.');
                        }
                    }
                } else {
                    echo 'Script not found in theme head.tpl.';
                }
            } else {
                error_log('No suitable head.tpl file found to remove script.');
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

        $result = rmdirr($archive_path, true, true);

        rmdirr(api_get_path(SYS_ARCHIVE_PATH) . 'twig/', true, true);
    }


    public static function getWidgetInfo()
    {
        try {
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
                    'name'              => '',
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

            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // ⚠️ For development only

            $response = curl_exec($curl);
            $curlInfo = curl_getinfo($curl);

            if ($response === false) {
                $error = curl_error($curl);
                throw new Exception("cURL error: $error");
            }

            curl_close($curl);

            return json_decode($response);
        } catch (Exception $e) {
            return null;
        }
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

        $res = json_decode($response, true);

        if (empty($res['Data'])) {
            self::getWidgetInfo();
            sleep(1);
            self::fetchWidgetSettings();
        } else {
            return json_encode($res);
        }
    }

    public static function settingsUpdated()
    {
        session_start();

        // success message
        $_SESSION['success'] = "Settings updated successfully!";

        return true;
    }
}
