<?php
namespace SimpleBlogPHP30;
$installed = 'yes';
include("configs.php");

if (isset($_GET["install"]) and $_GET["install"]==1) {
	$message = '';
	$connBl = mysqli_connect(trim($_REQUEST["hostname"]), trim($_REQUEST["mysql_user"]), trim($_REQUEST["mysql_password"]));
	if (mysqli_connect_errno()) {
		$message = "MySQL database details are incorrect. Please, check the database details(MySQL server, username and password) and/or contact your hosting company to verify them. If you have troubles just send us login details for your hosting account control panel and we will do the installation of the script for you for free.
		<br /> Error message: " . mysqli_connect_error();
	} else {
		if (!mysqli_select_db($connBl, trim($_REQUEST["mysql_database"]))) {
			$message = "Unable to select database. Database name is incorrect or is not created. Please check database details - MySQL server, Database name, Username and Password and try again. If you have troubles just send us login details for your hosting account control panel and we will do the installation of the script for you for free.";
		} else {
					
			$sql = "DROP TABLE IF EXISTS `".$TABLE["Posts"]."`;";
			$sql_result = sql_result($sql);
			
			$sql = "CREATE TABLE `".$TABLE["Posts"]."` (
					  `id` int(11) NOT NULL auto_increment,
					  `publish_date` datetime default NULL,
					  `status` varchar(50) default NULL,		
					  `cat_id` int(11) default NULL,					  
					  `post_title` varchar(250) default NULL,
					  `image` varchar(250) default NULL,
					  `imgpos` varchar(20) default NULL,
					  `post_text` text,
					  `post_limit` varchar(10) default NULL,
					  `post_tags` text,
					  `post_comments` varchar(50) default NULL,
					  `reviews` int(11) default NULL, 
					  PRIMARY KEY  (`id`))
					  CHARACTER SET utf8 COLLATE utf8_unicode_ci";
  			$sql_result = sql_result($sql);
			
			
			$sql = "DROP TABLE IF EXISTS `".$TABLE["Categories"]."`;";
            $sql_result = sql_result($sql);

            $sql = "CREATE TABLE `".$TABLE["Categories"]."` (
                      `id` int(11) NOT NULL auto_increment,
                      `cat_name` varchar(250) default NULL,
                      PRIMARY KEY  (`id`))
                      CHARACTER SET utf8 COLLATE utf8_unicode_ci";
            $sql_result = sql_result($sql);
			
			
			$sql = "DROP TABLE IF EXISTS `".$TABLE["Comments"]."`;";
			$sql_result = sql_result($sql);
			
			$sql = "CREATE TABLE `".$TABLE["Comments"]."` (
					  `id` int(11) NOT NULL auto_increment,
					  `ipaddress` varchar(50) default NULL,
					  `publish_date` datetime default NULL,
					  `status` varchar(50) default NULL,
					  `post_id` int(11) NOT NULL,
					  `name` varchar(250) default NULL,
					  `email` varchar(250) default NULL,
					  `comment` text,
					  PRIMARY KEY  (`id`))
					  CHARACTER SET utf8 COLLATE utf8_unicode_ci";
  			$sql_result = sql_result($sql);
			
			
			$sql = "DROP TABLE IF EXISTS `".$TABLE["Options"]."`;";
			$sql_result = sql_result($sql);
			
			$sql = "CREATE TABLE `".$TABLE["Options"]."` (
					  `options_id` int(11) NOT NULL auto_increment,
					  `email` varchar(250),
					  `pagination` varchar(50),
					  `per_page` varchar(10),
					  `post_limit` varchar(10),
					  `showshare` varchar(10),
					  `share_side` varchar(20),
					  `items_link` varchar(250),
					  `time_zone` varchar(50),
					  `htmleditor` varchar(20),
					  `showrightbar` varchar(10),
					  `captcha` varchar(10),
					  `captcha_theme` varchar(20),
					  `css_file` varchar(50),
					  `approval` varchar(20),
					  `commentsoff` varchar(10),
					  `comments_order` varchar(10),
					  `comm_req` text,
					  `ban_ips` text,
					  `ban_words` text,
					  `visual` text,
					  `visual_comm` text,
					  `language` text,
					  PRIMARY KEY  (`options_id`))
					  CHARACTER SET utf8 COLLATE utf8_unicode_ci";
  			$sql_result = sql_result($sql);
			
			$sql = 'INSERT INTO `'.$TABLE["Options"].'` 
					SET `email`="admin@email.com", 
						`pagination`="LoadMore",
						`per_page`="5",
						`showrightbar`="yes", 
						`htmleditor`="ck", 
						`showshare`="yes",  						 
						`share_side`="right",  
						`post_limit`="50", 
						`items_link`="http://www.yourwebsite.com/blogpage.php", 						  
						`captcha`="cap", 
						`captcha_theme`="clean", 
						`css_file`="no", 	
						`approval`="true", 
						`comments_order`="AtBottom", 
						`comm_req`=\'a:1:{i:0;s:5:"Email";}\', 
						`ban_ips`="",
						
						`visual`=\'a:156:{s:15:"gen_font_family";s:50:"Oswald-Regular,Helvetica Neue,Helvetica,sans-serif";s:14:"gen_font_color";s:7:"#333333";s:13:"gen_font_size";s:4:"14px";s:14:"gen_text_align";s:4:"left";s:15:"gen_line_height";s:3:"1.4";s:13:"gen_bgr_color";s:0:"";s:9:"gen_width";s:4:"1170";s:13:"gen_width_dim";s:2:"px";s:10:"showsearch";s:3:"yes";s:10:"sear_color";s:7:"#FFFFFF";s:14:"sear_bor_color";s:7:"#DBDBDB";s:16:"sear_font_family";s:50:"Oswald-Regular,Helvetica Neue,Helvetica,sans-serif";s:12:"showrightbar";s:3:"yes";s:9:"showcateg";s:3:"yes";s:10:"showrecent";s:3:"yes";s:11:"showarchive";s:3:"yes";s:14:"cat_word_color";s:7:"#4A5054";s:15:"cat_word_family";s:50:"Oswald-Regular,Helvetica Neue,Helvetica,sans-serif";s:18:"cat_word_font_size";s:5:"1.1em";s:19:"cat_word_font_style";s:6:"normal";s:20:"cat_word_font_weight";s:6:"normal";s:13:"cat_ctm_color";s:7:"#7C7C7C";s:19:"cat_ctm_color_hover";s:7:"#00AEFF";s:18:"line_cat_ctm_color";s:7:"#A6AAAE";s:14:"cat_ctm_family";s:48:"Lato-Regular,Helvetica Neue,Helvetica,sans-serif";s:17:"cat_ctm_font_size";s:5:"0.9em";s:18:"cat_ctm_font_style";s:6:"normal";s:19:"cat_ctm_font_weight";s:6:"normal";s:15:"list_title_font";s:50:"Oswald-Regular,Helvetica Neue,Helvetica,sans-serif";s:16:"list_title_color";s:7:"#00AEFF";s:22:"list_title_color_hover";s:7:"#4A5054";s:15:"list_title_size";s:5:"1.2em";s:22:"list_title_font_weight";s:6:"normal";s:21:"list_title_font_style";s:6:"normal";s:16:"list_title_align";s:4:"left";s:22:"list_title_line_height";s:3:"1.2";s:15:"post_title_font";s:50:"Oswald-Regular,Helvetica Neue,Helvetica,sans-serif";s:16:"post_title_color";s:7:"#4A5054";s:15:"post_title_size";s:5:"1.7em";s:21:"post_title_font_style";s:6:"normal";s:22:"post_title_font_weight";s:6:"normal";s:16:"post_title_align";s:4:"left";s:17:"title_line_height";s:3:"1.2";s:14:"list_text_font";s:48:"Lato-Regular,Helvetica Neue,Helvetica,sans-serif";s:15:"list_text_color";s:7:"#9A9DA0";s:14:"list_text_size";s:5:"1.1em";s:21:"list_text_font_weight";s:6:"normal";s:20:"list_text_font_style";s:6:"normal";s:20:"list_text_text_align";s:4:"left";s:21:"list_text_line_height";s:3:"1.6";s:9:"text_font";s:48:"Lato-Regular,Helvetica Neue,Helvetica,sans-serif";s:10:"text_color";s:7:"#333333";s:14:"text_bgr_color";s:0:"";s:9:"text_size";s:5:"1.1em";s:16:"text_font_weight";s:7:"inherit";s:15:"text_font_style";s:7:"inherit";s:15:"text_text_align";s:7:"inherit";s:16:"text_line_height";s:3:"1.6";s:12:"text_padding";s:3:"1px";s:14:"list_date_font";s:50:"Oswald-Regular,Helvetica Neue,Helvetica,sans-serif";s:15:"list_date_color";s:7:"#AAAAAA";s:21:"list_date_color_hover";s:7:"#4A5054";s:26:"list_date_decoration_hover";s:9:"underline";s:14:"list_date_size";s:3:"1em";s:20:"list_date_font_style";s:6:"normal";s:20:"list_date_text_align";s:4:"left";s:16:"list_date_format";s:7:"F jS, Y";s:14:"list_show_date";s:3:"yes";s:17:"list_showing_time";s:0:"";s:9:"date_font";s:7:"inherit";s:10:"date_color";s:7:"#AAAAAA";s:9:"date_size";s:5:"0.9em";s:16:"date_color_hover";s:7:"#4A5054";s:21:"date_decoration_hover";s:9:"underline";s:15:"date_font_style";s:6:"normal";s:15:"date_text_align";s:4:"left";s:11:"date_format";s:7:"F jS, Y";s:9:"show_date";s:3:"yes";s:12:"showing_time";s:0:"";s:7:"show_aa";s:3:"yes";s:15:"more_font_color";s:7:"#626465";s:21:"more_font_color_hover";s:7:"#00AEFF";s:9:"more_font";s:50:"Oswald-Regular,Helvetica Neue,Helvetica,sans-serif";s:14:"more_font_size";s:3:"1em";s:15:"more_font_style";s:6:"normal";s:16:"more_font_weight";s:6:"normal";s:20:"more_text_decoration";s:4:"none";s:26:"more_text_decoration_hover";s:4:"none";s:15:"back_font_color";s:7:"#00AEFF";s:21:"back_font_color_hover";s:7:"#4A5054";s:9:"back_font";s:50:"Oswald-Regular,Helvetica Neue,Helvetica,sans-serif";s:14:"back_font_size";s:5:"1.1em";s:15:"back_font_style";s:6:"normal";s:16:"back_font_weight";s:6:"normal";s:20:"back_text_decoration";s:4:"none";s:26:"back_text_decoration_hover";s:4:"none";s:16:"links_font_color";s:7:"#666666";s:22:"links_font_color_hover";s:7:"#333333";s:21:"links_text_decoration";s:9:"underline";s:27:"links_text_decoration_hover";s:4:"none";s:15:"links_font_size";s:5:"1.1em";s:16:"links_font_style";s:6:"normal";s:17:"links_font_weight";s:6:"normal";s:17:"tagged_font_color";s:7:"#848484";s:13:"tagged_family";s:50:"Oswald-Regular,Helvetica Neue,Helvetica,sans-serif";s:16:"tagged_font_size";s:3:"1em";s:17:"tagged_font_style";s:6:"normal";s:18:"tagged_font_weight";s:6:"normal";s:15:"tags_font_color";s:7:"#848484";s:21:"tags_font_color_hover";s:7:"#444444";s:11:"tags_family";s:50:"Oswald-Regular,Helvetica Neue,Helvetica,sans-serif";s:20:"tags_text_decoration";s:4:"none";s:26:"tags_text_decoration_hover";s:9:"underline";s:14:"tags_font_size";s:3:"1em";s:15:"tags_font_style";s:6:"normal";s:16:"tags_font_weight";s:6:"normal";s:15:"pag_font_family";s:54:"Montserrat-Regular,Helvetica Neue,Helvetica,sans-serif";s:14:"pag_font_color";s:7:"#333333";s:20:"pag_font_color_hover";s:7:"#FFFFFF";s:18:"pag_font_color_sel";s:7:"#FFFFFF";s:18:"pag_font_color_prn";s:7:"#26C9F4";s:19:"pag_color_prn_hover";s:7:"#EEEEEE";s:18:"pag_font_color_ina";s:7:"#BDBDBD";s:13:"pag_font_size";s:3:"1em";s:15:"pag_font_weight";s:6:"normal";s:14:"pag_font_style";s:6:"normal";s:12:"pag_align_to";s:6:"center";s:14:"show_scrolltop";s:3:"yes";s:15:"scrolltop_width";s:4:"40px";s:16:"scrolltop_height";s:4:"40px";s:19:"scrolltop_bgr_color";s:7:"#999999";s:25:"scrolltop_bgr_color_hover";s:7:"#808080";s:17:"scrolltop_opacity";s:2:"40";s:23:"scrolltop_opacity_hover";s:2:"60";s:16:"scrolltop_radius";s:3:"0px";s:16:"bott_font_family";s:54:"Montserrat-Regular,Helvetica Neue,Helvetica,sans-serif";s:10:"bott_color";s:7:"#333333";s:16:"bott_color_hover";s:7:"#252525";s:14:"bott_bgr_color";s:7:"#FFFFFF";s:20:"bott_bgr_color_hover";s:7:"#EEEEEE";s:9:"bott_size";s:3:"1em";s:10:"bott_style";s:6:"normal";s:11:"bott_weight";s:6:"normal";s:13:"bott_align_to";s:6:"center";s:13:"dist_from_top";s:3:"4em";s:15:"dist_title_date";s:5:"1.4em";s:20:"list_dist_title_date";s:3:"1em";s:14:"dist_date_text";s:3:"1em";s:19:"list_dist_date_text";s:3:"1em";s:14:"dist_btw_posts";s:3:"4em";s:18:"dist_btw_post_more";s:3:"1em";s:15:"dist_link_title";s:5:"1.5em";s:14:"dist_text_tags";s:5:"1.1em";s:13:"dist_tags_nav";s:5:"1.4em";s:15:"dist_comm_links";s:5:"2.5em";s:16:"dist_from_bottom";s:3:"2em";}\',
						
						`visual_comm`=\'a:39:{s:18:"w_comm_font_family";s:50:"Oswald-Regular,Helvetica Neue,Helvetica,sans-serif";s:17:"w_comm_font_color";s:7:"#333333";s:16:"w_comm_font_size";s:5:"1.1em";s:17:"w_comm_font_style";s:6:"normal";s:18:"w_comm_font_weight";s:4:"bold";s:15:"comm_bord_color";s:7:"#BAB6B6";s:9:"name_font";s:50:"Oswald-Regular,Helvetica Neue,Helvetica,sans-serif";s:15:"name_font_color";s:7:"#848484";s:14:"name_font_size";s:5:"1.2em";s:15:"name_font_style";s:6:"normal";s:16:"name_font_weight";s:6:"normal";s:14:"comm_date_font";s:50:"Oswald-Regular,Helvetica Neue,Helvetica,sans-serif";s:15:"comm_date_color";s:7:"#848484";s:14:"comm_date_size";s:5:"0.7em";s:20:"comm_date_font_style";s:6:"normal";s:16:"comm_date_format";s:7:"F jS, Y";s:17:"comm_showing_time";s:5:"g:i a";s:9:"comm_font";s:48:"Lato-Regular,Helvetica Neue,Helvetica,sans-serif";s:15:"comm_font_color";s:7:"#848484";s:14:"comm_font_size";s:3:"1em";s:15:"comm_font_style";s:6:"normal";s:16:"comm_font_weight";s:6:"normal";s:17:"leave_font_family";s:50:"Oswald-Regular,Helvetica Neue,Helvetica,sans-serif";s:16:"leave_font_color";s:7:"#00AEFF";s:15:"leave_font_size";s:5:"1.4em";s:17:"leave_font_weight";s:6:"normal";s:16:"leave_font_style";s:6:"normal";s:17:"field_font_family";s:48:"Lato-Regular,Helvetica Neue,Helvetica,sans-serif";s:16:"field_font_color";s:7:"#333333";s:15:"field_bgr_color";s:7:"#F6F1DE";s:15:"field_font_size";s:3:"1em";s:16:"subm_font_family";s:50:"Oswald-Regular,Helvetica Neue,Helvetica,sans-serif";s:10:"subm_color";s:7:"#FFFFFF";s:14:"subm_bgr_color";s:7:"#00AEFF";s:15:"subm_brdr_color";s:7:"#00AEFF";s:17:"subm_bgr_color_on";s:7:"#FFFFFF";s:14:"subm_font_size";s:3:"1em";s:15:"subm_bor_radius";s:3:"0px";s:13:"dist_btw_comm";s:3:"3em";}\',
						 
						`language`=\'a:53:{s:9:"Back_home";s:4:"BACK";s:13:"Search_button";s:9:"search...";s:8:"Category";s:10:"CATEGORIES";s:12:"Category_all";s:14:"All categories";s:12:"Recent_Posts";s:12:"RECENT POSTS";s:8:"Archives";s:8:"ARCHIVES";s:9:"Read_more";s:9:"READ MORE";s:13:"Comments_link";s:8:"COMMENTS";s:9:"Tagged_as";s:5:"Tags:";s:8:"No_Posts";s:16:"No Posts To List";s:10:"Older_Post";s:10:"Older Post";s:10:"Newer_Post";s:10:"Newer Post";s:6:"Monday";s:6:"Monday";s:7:"Tuesday";s:7:"Tuesday";s:9:"Wednesday";s:9:"Wednesday";s:8:"Thursday";s:8:"Thursday";s:6:"Friday";s:6:"Friday";s:8:"Saturday";s:8:"Saturday";s:6:"Sunday";s:6:"Sunday";s:7:"January";s:3:"Jan";s:8:"February";s:3:"Feb";s:5:"March";s:3:"Mar";s:5:"April";s:3:"Apr";s:3:"May";s:3:"May";s:4:"June";s:3:"Jun";s:4:"July";s:3:"Jul";s:6:"August";s:3:"Aug";s:9:"September";s:3:"Sep";s:7:"October";s:3:"Oct";s:8:"November";s:3:"Nov";s:8:"December";s:3:"Dec";s:13:"Word_Comments";s:10:"Comments: ";s:10:"Written_by";s:10:"Written by";s:7:"on_date";s:2:"on";s:18:"No_comments_posted";s:21:"No comments posted...";s:13:"Leave_Comment";s:15:"Leave a Comment";s:12:"Comment_Name";s:6:"* Name";s:13:"Comment_Email";s:31:"* Email (will not be published)";s:12:"Comment_here";s:22:"* Your comment here...";s:23:"Enter_verification_code";s:25:"* Enter verification code";s:15:"Required_fields";s:15:"Required fields";s:14:"Submit_Comment";s:4:"SEND";s:16:"Banned_word_used";s:18:"Banned word used! ";s:14:"Banned_ip_used";s:24:"Banned IP address used! ";s:27:"Incorrect_verification_code";s:29:"Incorrect verification code! ";s:17:"Comment_Submitted";s:33:"Your comment has been submitted! ";s:20:"After_Approval_Admin";s:54:"Will be published after approval of the administrator!";s:15:"required_fields";s:34:"Please, fill all required fields! ";s:13:"correct_email";s:35:"Please, fill correct email address!";s:10:"field_code";s:32:"Please, enter verification code!";s:18:"New_comment_posted";s:19:"New comment posted!";s:9:"metatitle";s:28:"Blog Preview Page meta Title";s:15:"metadescription";s:35:"Blog Preview Page meta description ";}\'';
			
			$sql_result = sql_result($sql);
			
					
			
			
			
			$ConfigFile = "allinfo.php";
			$CONFIG='$CONFIG';
			
			$handle = @fopen($ConfigFile, "r");
			
			if ($handle) {
				$buffer = fgets($handle, 4096);
	  			$buffer .=fgets($handle, 4096);	
				$buffer .=fgets($handle, 4096);	
				
				$buffer .=$CONFIG."[\"hostname\"]='".trim($_REQUEST["hostname"])."';\n";
				
				$buffer .=$CONFIG."[\"mysql_user\"]='".trim($_REQUEST["mysql_user"])."';\n";
				
				$buffer .=$CONFIG."[\"mysql_password\"]='".trim($_REQUEST["mysql_password"])."';\n";
				
				$buffer .=$CONFIG."[\"mysql_database\"]='".trim(addslashes($_REQUEST["mysql_database"]))."';\n";
				
				$buffer .=$CONFIG."[\"server_path\"]='".trim($_REQUEST["server_path"])."';\n";
				
				$buffer .=$CONFIG."[\"full_url\"]='".trim(addslashes($_REQUEST["full_url"]))."';\n";
								
				$buffer .=$CONFIG."[\"folder_name\"]='".trim(addslashes($_REQUEST["folder_name"]))."';\n";
				
				$buffer .=$CONFIG."[\"admin_user\"]='".trim($_REQUEST["admin_user"])."';\n";
				
				$buffer .=$CONFIG."[\"admin_pass\"]='".trim($_REQUEST["admin_pass"])."';\n";
				
				while (!feof($handle)) {
					$buffer .= fgets($handle, 4096);
				}
				
				fclose($handle);
				
				$handle = @fopen($ConfigFile, "w");
				
				if (!$handle) {
					echo "Configuration file $ConfigFile is missing or the permissions does not allow to be changed. Please upload the file and/or set the right permissions (CHMOD 777).";
					exit();
				}
				
				if (!fwrite($handle,$buffer)) {
				  	echo "Configuration file $ConfigFile is missing or the permissions does not allow to be changed. Please upload the file and/or set the right permissions (CHMOD 777).";
					exit();
				}
				
				fclose($handle);
				
			} else {
				echo "Error opening file.";
				exit();
			}
			
			$message = 'Script successfully installed';	
?>
		<script type="text/javascript">
			window.document.location.href='installation.php?install=2'
		</script>           		
<?php		
		}
	}
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Script installation</title>
<link href="styles/installation.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div class="install_wrap">

<?php if (isset($_GET["install"]) && $_GET["install"]==2) { ?>
	<table border="0" class="form_table" align="center" cellpadding="4">
	  <tr>
      	<td>
			Script successfully installed. <a href='admin.php'>Login here</a>.
        </td>
      </tr>
    </table>
<?php } else {?>

	<form action="installation.php" method="get" name="installform">
    <input name="install" type="hidden" value="1" />
	<table border="0" class="form_table" align="center" cellpadding="4">
      
      
      <tr>
      	<td colspan="3">
        	<?php 
			if (isset($message) and $message!='') { 
				echo "<span class='alerts'>".$message."</span>";
			} else {
				echo 'These are the details that script will use to install and run: ';
			}
			?>
	  	</td>
      </tr>
      
      <tr>
        <td align="left" colspan="3" class="head_row">Minimum version required (PHP <?php echo $php_version_min; ?>, MySQL <?php echo $mysql_version_min; ?>): </td>
      </tr>
      
      	<?php 
		
		$error_msg = "";
		
		//////////////// CHECKING FOR PHP VERSION REQUIRED //////////////////
		
		$curr_php_version = phpversion();
		$check_php_version=true;
		
		
		if (version_compare($curr_php_version, $php_version_min, "<")) {
			//echo 'I am using PHP 5.4, my version: ' . phpversion() . "\n. Minimum is ".$php_version_min;
			$check_php_version=false;
		}
		
		if($check_php_version==false) {
			$not = "<span style='color:red;'>not</span>";
			$error_msg .= "PHP requirement checks failed and the script may not work properly. You have version ".$curr_php_version." but the required version is ".$php_version_min.". Please contact your hosting company or system administrator for assistance. <br />";
		} else {
			$not = "";
		}
		?>
        
      <tr>
        <td width="30%" align="left">PHP: </td>
        <td><?php echo "Server version of PHP '".$curr_php_version."' is ".$not." ok!"; ?> </td>
      </tr>
      
      
      	<?php 	
	  	//////////////// CHECKING FOR MYSQL VERSION REQUIRED //////////////////	
		$curr_mysql_version = '-.-.--';
		$not = "";		
		
		$check_mysql_version=true;		
		
		ob_start(); 
		phpinfo(INFO_MODULES); 
		$info = ob_get_contents(); 
		ob_end_clean(); 
		$info = stristr($info, 'Client API version'); 
		preg_match('/[1-9].[0-9].[1-9][0-9]/', $info, $match); 
		$gd = $match[0]; 
		//echo '</br>MySQL:  '.$gd.' <br />';
		$curr_mysql_version = $gd;
		
		
		if (version_compare($curr_mysql_version, $mysql_version_min, "<")) {
			$check_mysql_version=false;
			$not = "<span style='color:red;'>not</span>";
		} else if(trim($curr_mysql_version)=="-.-.--") {
			$error_msg .= "Information about MySQL version is missing or is incomplete. Please ask your hosting company or system administrator for the version. The minimum required version of MySQL is ".$mysql_version_min.". <br />";
			$not = "<span style='color:red;'>not</span>";
		}
		
		if($check_mysql_version==false) {
			$not = "<span style='color:red;'>not</span>";
			$error_msg .= "MySQL requirement checks failed and the script may not work properly. You have version ".$curr_mysql_version." but the required version is ".$mysql_version_min.". Please contact your hosting company or system administrator for assistance. <br />";
		} 
		?>
        
      <tr>
        <td align="left">MySQL: </td>
        <td><?php echo "Server version of MySQL '".$curr_mysql_version."' is ".$not." ok!"; ?></td>
      </tr> 
      
      <?php if(isset($error_msg) and $error_msg!='') {?>
      <tr>
        <td colspan="2" style="color:#FF0000;"><?php echo $error_msg; ?></td>
      </tr>       
      <?php } ?>
      
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      
      <tr>
        <td align="left" colspan="3" class="head_row">MySQL login details: <span style="font-weight:normal; font-size:11px; font-style:italic;">(In case you don't have database yet, you should enter your hosting control panel and create it)</span></td>
      </tr>
      
      <tr>
        <td align="left">MySQL Server:</td>
        <td align="left"><input type="text" name="hostname" value="<?php if(isset($_REQUEST['hostname'])) echo $_REQUEST['hostname']; else echo 'localhost'; ?>" size="30" /></td>
      </tr>
      <tr>
        <td align="left">MySQL Username: </td>
        <td align="left"><input name="mysql_user" type="text" size="30" maxlength="50" value="<?php if(isset($_REQUEST['mysql_user'])) echo $_REQUEST['mysql_user']; ?>" /></td>
      </tr>
      <tr>
        <td align="left">MySQL Password: </td>
        <td align="left"><input name="mysql_password" type="text" size="30" maxlength="50" value="<?php if(isset($_REQUEST['mysql_password'])) echo $_REQUEST['mysql_password']; ?>" /></td>
      </tr>
      <tr>
        <td align="left">Database name:</td>
        <td align="left"><input name="mysql_database" type="text" size="30" maxlength="50" value="<?php if(isset($_REQUEST['mysql_database'])) echo $_REQUEST['mysql_database']; ?>" /></td>
      </tr>
      
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      
      <tr>
        <td align="left" colspan="3" class="head_row">Installation paths to script directory: </td>
      </tr>
      
      	<?php 
	  	$server_path=$_SERVER['SCRIPT_FILENAME'];
		if (preg_match("/(.*)\//",$server_path,$matches)) {
			$server_path=$matches[0];
		}
		
		$server_path = str_replace("\\","/",$server_path);
		$server_path = str_replace("installation.php","",$server_path);
			
	  	?>
      <tr>
        <td align="left" valign="top">Server path to script directory:</td>
        <td align="left" colspan="2">
        	<input name="server_path" type="text" value="<?php echo $server_path; ?>" style="width:95%" /><br />
        	<span style="font-size:11px;font-style:italic;">Example: /home/server/public_html/SCRIPTFOLDER/ -  for Linux host</span><br />
            <span style="font-size:11px;font-style:italic;">Example: D:/server/www/websitedir/SCRIPTFOLDER/ -  for Windows host</span>
        </td>
      </tr>
      
      <?php 
	  	$full_url = 'http';
		if (array_key_exists('HTTPS', $_SERVER) && $_SERVER["HTTPS"] == "on") {$full_url .= "s";}
		$full_url .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$full_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$full_url .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		if (preg_match("/(.*)\//",$full_url,$matches)) {
			$full_url=$matches[0];
		}
		//$full_url = str_replace("installation.php","",$full_url);
		?>
      <tr>
        <td align="left" valign="top">Full URL to script directory:</td>
        <td align="left" colspan="2">
        	<input name="full_url" type="text" value="<?php echo $full_url; ?>" style="width:95%" /><br />
        	<span style="font-size:11px;font-style:italic;">Example: http://yourdomain.com/SCRIPTFOLDER/</span>
        </td>
      </tr>      
      
      	<?php 
	  	$url = $_SERVER['PHP_SELF']; 
		if (preg_match("/(.*)\//",$url,$matches)) {
			$folder_name=$matches[0];
		}
	  	?>
      <tr>
        <td align="left" valign="top">Script directory name:</td>
        <td align="left" colspan="2">
        	<input name="folder_name" type="text" value="<?php echo $folder_name; ?>" style="width:95%" /><br />
            <span style="font-size:11px;font-style:italic;">Example: /SCRIPTFOLDER/</span>
        </td>
      </tr>
      
      	
      
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="left" colspan="3" class="head_row">Administrator login details: <span style="font-weight:normal; font-size:11px; font-style:italic;">(Choose Username and Password you should use later when log in admin area)</span></td>
      </tr>
      <tr>
        <td align="left">Admin Username:</td>
        <td align="left"><input name="admin_user" type="text" size="30" maxlength="50" value="<?php if(isset($_REQUEST['admin_user'])) echo $_REQUEST['admin_user']; ?>" /></td>
      </tr>
      <tr>
        <td align="left">Admin Password:</td>
        <td align="left"><input name="admin_pass" type="text" size="30" maxlength="50" value="<?php if(isset($_REQUEST['admin_pass'])) echo $_REQUEST['admin_pass']; ?>" /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input name="installScript" type="submit" value="Install Script"></td>
      </tr>
    </table>
	</form>
<?php } ?>    

</div>

</body>
</html>
