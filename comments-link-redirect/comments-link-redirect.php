<?php
/*
Plugin Name: Comments Link Redirect
Plugin URI: http://www.wheatime.com/2009/06/comments-link-redirect.html 
Description: Comments Link Redirect. <a href="http://www.wheatime.com/2009/06/comments-link-redirect.html" target="_blank">How to use this Plugin?</a>
Version: 1.1
Author: Jason
Author URI: http://www.wheatime.com/
*/


add_action('init', 'redirect_comment_link');
function redirect_comment_link(){
	$redirect = $_GET['r'];
	if($redirect){
		if(strpos($_SERVER['HTTP_REFERER'],get_option('home')) !== false){
			header("Location: $redirect");
			exit;
		}
		else {
			header("Location: http://www.wheatime.com/");
			exit;
		}
	}
	
}
?>
