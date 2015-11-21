<?php
/*
Plugin Name: Development Theme Test
Plugin URI: http://www.kwista.com/dev-theme-test
Description: Trying to edit your theme or test a new theme can be very difficult without a testing server or a dev location. Dev Theme Test allows you to create a password protected subdomain such as dev.yoursite.com in order to make modifications to your current theme or a new theme while still displaying your original theme to site visitors.
Version: 1.0
Author: Chris London; Kwista, LLC.
Author URI: http://www.kwista.com/author/chris-london/
*/

register_activation_hook(__FILE__, 'kw_td_activate');
register_deactivation_hook(__FILE__, 'kw_td_deactivate');

add_filter('pre_option_template', 'kw_td_template', 1);
add_filter('pre_option_stylesheet', 'kw_td_template', 1);

add_action('admin_menu', 'kw_td_menu');


// Using the plugin's options check if the subdomain is set and change the theme if it is.
function kw_td_template() {
	$subdomain = explode('.', $_SERVER['HTTP_HOST']);
	$data = get_option('kw_td_subdomains');

	if (isset($data[$subdomain[0]])) {
		if ($data[$subdomain[0]]['p'] && !is_user_logged_in()) wp_redirect(wp_login_url());

		return $data[$subdomain[0]]['t'] . ($data[$subdomain[0]]['d'] == 1 ? '_dev' : '');
	}
	return false;
}

// Set up plugin options menu
function kw_td_menu(){
	add_options_page('Development Theme Test', 'Dev Theme Test', 'administrator', 'kw-template-dev', 'kw_td_control');
}


// Set default options for kw_td_activate
function kw_td_activate(){
	$data = array();

	if (!get_option('kw_td_subdomains')){
		add_option('kw_td_subdomains', $data);
	} else {
		update_option('kw_td_subdomains', $data);
	}
}

// Remove options on deactivate
function kw_td_deactivate(){
	delete_option('kw_td_subdomains');
}

// Widget option control function
function kw_td_control(){
	$data = get_option('kw_td_subdomains');

	if (isset($_POST['addnew'])){
		$data[htmlentities($_POST['subdomain'])] = array(
			't'=>htmlentities($_POST['theme']),
			'd'=>htmlentities(($_POST['dev'] == 1) ? true : false),
			'p'=>htmlentities(($_POST['pwd'] == 1) ? true : false));

		if ($_POST['dev'] == 1) kw_td_create_dev($_POST['theme']);

		update_option('kw_td_subdomains', $data);
	} elseif (isset($_POST['delete'])) {
		unset($data[$_POST['delete']]);
		update_option('kw_td_subdomains', $data);
	} elseif (isset($_POST['edit'])) {
		unset($data[$_POST['edit']]);
		$data[htmlentities($_POST['subdomain'])] = array(
			't'=>htmlentities($_POST['theme']),
			'd'=>htmlentities(($_POST['dev'] == 1) ? true : false),
			'p'=>htmlentities(($_POST['pwd'] == 1) ? true : false));

		if ($_POST['dev'] == 1) kw_td_create_dev($_POST['theme']);

		update_option('kw_td_subdomains', $data);
	} elseif (isset($_POST['commit'])) {
		$theme_dir = dirname(get_template_directory());
		$errors = recurse_copy($theme_dir . '/' . $_POST['commit'] . '_dev', $theme_dir . '/' . $_POST['commit']);
	} elseif (isset($_POST['revert'])) {
		$theme_dir = dirname(get_template_directory());
		$errors = recurse_copy($theme_dir . '/' . $_POST['revert'], $theme_dir . '/' . $_POST['revert'] . '_dev');
	}

	$themes = kw_td_get_themes();

	require('admin-menu.php');
}

function kw_td_get_themes() {
	$themes = array();
	$theme_dir = dirname(get_template_directory());

	if ($handle = opendir($theme_dir)) {
	    /* This is the correct way to loop over the directory. */
	    while (false !== ($file = readdir($handle))) {
	    	if ($file == '.' || $file == '..' || substr($file, -4) == '_dev') continue;

	        if (is_dir($theme_dir . '/' . $file)) $themes[] = $file;
	    }

	    closedir($handle);
	}

	return $themes;
}

function kw_td_create_dev($theme) {
	$theme_dir = dirname(get_template_directory());

	if (!is_dir($theme_dir . '/' . $_POST['theme'] . '_dev')) {
		recurse_copy($theme_dir . '/' . $_POST['theme'], $theme_dir . '/' . $_POST['theme'] . '_dev');
	}
}

function recurse_copy($src, $dst, $overwrite = false) {
	$errors = array();
    $dir = opendir($src);
    if (!is_dir($dst)) mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                $errors = array_merge($errors, recurse_copy($src . '/' . $file,$dst . '/' . $file));
            }
            else {
            	if (filemtime($src . '/' . $file) > filemtime($dst . '/' . $file)) {
                	copy($src . '/' . $file, $dst . '/' . $file);
            	} else {
            		$errors[] = "Could not copy " . $src . '/' . $file . ' because ' . $dst . '/' . $file . ' is newer';
            	}
            }
        }
    }
    closedir($dir);
    return $errors;
}
?>