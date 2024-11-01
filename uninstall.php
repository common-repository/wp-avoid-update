<?php
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
 
$option_name = 'wpau_avoid_update_plugins';
// for site options in Singlesite
delete_option($option_name);
// for site options in Multisite
delete_site_option($option_name);
?>