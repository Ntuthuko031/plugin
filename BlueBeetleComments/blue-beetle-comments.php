<?php
// Silence is golden.
/**
* Plugin Name: blue-beetle-comments
* Plugin URI: http://www.iys.co.za
* Description: Comments Plugin For My New Job
* Version: 0.1
* Author: ntuthuko buthelezi
* Author URI: http://www.iys.co.za
		
**/

add_action('init', 'load_important_scripts');



function load_important_scripts(){
		wp_register_style('beetle_style', 'http://localhost/wordpress/wp-content/plugins/BlueBeetleComments/_inc/mystyle.css');
		
		
}

function get_my_current_page(){
	global $wp;
	$current_uri = home_url( add_query_arg(array(), $wp->request ));
	return $current_uri;
}
 
function display_beetle_box($content){
	
	wp_enqueue_style('beetle_style');
	$string = '<div class="comment_main_box" > 
	<div class="my_comment_image"></div>'.
	'<div class="my_actual_comment">
		<span class="my_comment_user">Username</span><span class="time_posted"> 1 min ago</span><br/>
		<span class="my_comment_text"> This is a hard coded comment for my new job</span>
	</div>
	<div class="my_comment_input">
		<textarea rows="5" class="my_comment_textarea"></textarea>
		<button class="my_comment_button">post comment</button>
	</div>
	 </div>';
	 
	$parent_element = 'div';
	
	$insert_content = "<{$parent_element}>{$string}</{$parent_element}>";
	
	$doc = new DOMDocument();
	@$doc->loadHTML(get_my_current_page(), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
	
	$appendBox = $doc->getElementsByTagName('li');
	
	$nodelist = array();
	foreach ($appendBox as $key => $node){
			if('body' === $node->parentNode->nodeName){
					$nodelist[] = $key;
			}
	}
	
	if(1< count( $nodelist )){
		$position = floor(count($nodelist)/2);
		$insert_html = new DOMDocument();
	
	@$insert_html->loadHTML($insert_content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
	
	$parent_node = $insert_html->getElementsByTagName( $parent_element )->item(0);
	
	$appendBox->item($nodelist[ $position ])->parentNode->insertBefore( $doc->importNode( $parent_node, true ), $appendBox->item( $nodelist[ $position ] ) );
	
	
	$content = $doc->saveHTML();
	
	
} else {
	$content .= $insert_content;
}
	return $content;
}

add_filter('the_content','display_beetle_box');

