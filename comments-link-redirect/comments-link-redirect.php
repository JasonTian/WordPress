<?php
/*
Plugin Name: Comments Link Redirect
Plugin URI:  http://blog.SuccessFu.net.ru/tag/comments-link-redirect
Description: Comments Link Redirect. <a href="http://blog.SuccessFu.net.ru/tag/comments-link-redirect" target="_blank">How to use this Plugin?</a>
Version: 1.0
Author: Jason
Author URI: http://blog.SuccessFu.net.ru/
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
			header("Location: http://blog.SuccessFul.net.ru/");
			exit;
		}
	}
	
}
?>