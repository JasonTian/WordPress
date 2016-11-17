<?php
/*
Plugin Name: 中文关键词自动链接
Plugin URI: http://www.dijksterhuis.org/wordpress-plugins/keyword-link-plugin/
Description: 可以轻松定义关键词自动链接，完美支持中文，并有相关实用功能。 | 汉化     
Author: 麦子
Version: 1.0
Author URI: http://J.2mei.org
*/

/* The idea for this plugin comes from a website I designed. It has lots of touristic 
 * information and it would be great if every important keyword would automatically be linked
 * to its relevant articles instead of me having to do this by hand.  
 *
 * Example:  "A visit to the UK is not complete without a visit to stonehenge."
 *
 * Would become:  "A visit to the UK is not complete without a visit to <A href="link">stonehenge</a>."
*/

/* Constants */
define(BM_KEYWORDLINK_OPTION,'bm_keywordlinkoption');

/* If you would like to change only the first occurance of a term, as opposed to all
 * occurances, please change the following to "TRUE"
*/

function bm_keywordlink_admininit()
{
	 // Add a page to the options section of the website
   if (current_user_can('manage_options')) 				
 		add_options_page("BM Keywordlink","中文关键词自动链接", 8, __FILE__, 'bm_keywordlink_optionpage');
}

function bm_keywordlink_topbarmessage($msg)
{
	 echo '<div class="updated fade" id="message"><p>' . $msg . '</p></div>';
}

function bm_keywordlink_showdefinitions()
{
 		 /* Retrieve the keyword definitions */ 
		 $links = get_option(BM_KEYWORDLINK_OPTION);

		 echo "<h3>关键词链接表</h3>";
		 
		 if ($links)
		 { 
  		 echo "<table class='widefat'>\n";		 	  
 	     echo "<thead><tr><th>#</th><th>关键词</th><th>链接地址</th><th>属性</th><th>操作</th></tr></thead>\n";
			 $cnt = 0;
		 	 foreach ($links as $keyword => $details) 
		 	 {
				list($link,$nofollow,$firstonly,$newwindow,$ignorecase) = explode('|',$details);		 	 
		 	 
			   if ($cnt++ % 2) echo '<tr class=alternate>'; else echo '<tr>';
				 echo "<td>$cnt</td><td>$keyword</td><td><a href='$link'>$link</a></td>";
				 
				/* show attributes */
				echo "<td>";				 
				if ($nofollow) echo "[nofollow] ";
				if ($firstonly) echo "[first only] ";
				if ($newwindow) echo "[new window] ";
				if ($ignorecase) echo "[ignore case] ";				
				echo "</td>";				 
				 
				echo "<td><input type=button value=删除 onClick='javascript:BMDeleteKeyword(\"$keyword\");' /></td></tr>";
		 	 }
			 echo "</table>";
		 }
		 else
		 	 echo "<p>No links have been defined!</p>";
			 
		?>
		
		<!-- Support for the delete button , we use Javascript here -->
		<form name=delete_form method="post" action="">
		 <input type=hidden name=action value=delete />
		 <input type=hidden name=keyword value="" />
		</form>
		<script type="text/javascript">
		
		function BMDeleteKeyword(keyword)
		{
		   if (confirm('Are you sure you want to delete this keyword?'))
			 {
			   document.delete_form.keyword.value = keyword; 
			   document.delete_form.submit();
			 }
		} 
		</script>
		<?php	 
			 

}

function bm_keywordlink_addnew()
{
 		echo '<h3>增加新关键词链接</h3>';
		echo '<form name=bm_keywordadd method="post" action="">';
		echo '<input type=hidden name=action value=save />';
		echo '<table>';
		echo '<tr>';
		echo '<tr><td><label for=keyword>关键词</label>&nbsp;&nbsp;<input type=text name=keyword /></td></tr>';
		echo '<tr><td><label for=link>链接地址</label>&nbsp;<input type=text size=50 maxlength=200 name=link /></td></tr>';
		echo '<tr><td><input type=checkbox name=nofollow value="nofollow">&nbsp;<label for=nofollow>不追踪链接 | 使用Nofollow属性让搜索引擎不要抓取并追踪此链接，通常用于控制同页面中多个关键词权重所使用</label></td></tr>';
		echo '<tr><td><input type=checkbox name=firstonly value="firstonly">&nbsp;<label for=firstonly>第一次有效 | 在同一页面中多次出现关键词时候仅对第一次出现位置有效</label></td></tr>';
		echo '<tr><td><input type=checkbox name=newwindow value="newwindow">&nbsp;<label for=newwindow>新窗口链接 | 在点击关键词时将在新窗口打开链接</label></td></tr>';
		echo '<tr><td><input type=checkbox name=ignorecase value="ignorecase">&nbsp;<label for=ignorecase>忽略大小写 | 英文关键词有效</label></td></tr>';
		echo '</td></tr>';
		echo '<tr><td><input type=submit value="保存" /></td></tr></table>';
		echo '</form>'; 
}

function bm_keywordlink_savenew()
{
    $links = get_option(BM_KEYWORDLINK_OPTION);
		
		$keyword = $_POST['keyword'];
		$link = $_POST['link'];
		$nofollow = $_POST['nofollow'];
 		$firstonly = $_POST['firstonly'];
 		$newwindow = $_POST['newwindow'];
 		$ignorecase = $_POST['ignorecase']; 
		
		if ($keyword == '' || $link == '')
		{
		  bm_keywordlink_topbarmessage(__('Please enter both a keyword and URL'));
			return;     		  
		}
		
		if (isset($links[$keyword]))
		{
		  bm_keywordlink_topbarmessage(__('This keyword already has an entry, no duplicates please!'));
			return;     		  
		}
 
 		/* Store the link */ 
	  $links[$keyword] = implode('|',array($link,$nofollow,$firstonly,$newwindow,$ignorecase));
	  update_option(BM_KEYWORDLINK_OPTION,$links);      
}

function bm_keywordlink_deletekeyword()
{
		$links = get_option(BM_KEYWORDLINK_OPTION);
    $keyword = $_POST['keyword'];
		
		if (!isset($links[$keyword]))
		{
		  bm_keywordlink_topbarmessage(__('No such keyword, bizarre error!'));
			return;     		  
		}

		unset($links[$keyword]);
		update_option(BM_KEYWORDLINK_OPTION,$links);
} 


function bm_keywordlink_optionpage()
{
    /* Perform any action */
		if ($_POST['action']=='save')
		 bm_keywordlink_savenew(); 

		if ($_POST['action']=='delete')
		 bm_keywordlink_deletekeyword(); 
		
		/* Definition */
      echo '<div class="wrap">';
		echo '<h2>中文关键词自动链接</h2>';

		/* Introduction */ 
		echo '<p>此插件可以轻松定义关键词链接</p>';

		/* Show the existing options */
		bm_keywordlink_showdefinitions();
		
		/* Allow adding a new link */ 
		bm_keywordlink_addnew(); 		

		echo '</div>';
}

/* bm_keywordlink_replace
 *
 * This is where everything happens... search the content and search for our set of keywords
 * and add the links in the right places. 
*/

function bm_keywordlink_replace($content)
{
    $links = get_option(BM_KEYWORDLINK_OPTION);

    if ($links)
	 	 foreach ($links as $keyword => $details)
		 {
			   list($link,$nofollow,$firstonly,$newwindow,$ignorecase) = explode("|",$details);		 
		 
		 	   $url  = "<span class='bm_keywordlink'>";
		 	   $url .= "<a href='$link'";

				if ($nofollow) $url .= ' rel="nofollow"';
				if ($newwindow) $url .= ' target="_blank"';
		 	   
		 	   $url .= ">$keyword</a>";
		 	   $url .= "</span>";
		 	   
				if ($firstonly) $limit = 1; else $limit=-1;
				if ($ignorecase) $case = "i"; else $case="";

				// The following expression comes from an older 
				// auto link plugin by Sean Hickey. It fixed the autolinking inside a link
				// problem. Thanks to [Steph] for the code. 
					
				
    			//$regEx = '\'(?!((<.*?)|(<a.*?)))(\b'. $keyword . '\b)(?!(([^<>]*?)>)|([^>]*?</a>))\'s' . $case;
    			$regEx = '\'(?!((<.*?)|(<a.*?)))('. $keyword . ')(?!(([^<>]*?)>)|([^>]*?</a>))\'s' . $case;
				$content = preg_replace($regEx,$url,$content,$limit);		 	   
		 }	
		
	return $content; 
}

 /* Tie the module into Wordpress */
add_action('admin_menu','bm_keywordlink_admininit');
add_filter('the_content','bm_keywordlink_replace',10);




?>
