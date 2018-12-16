<?php 
namespace SimpleBlogPHP30;
$installed = '';
session_start();
include("configs.php");
include("language_admin.php");

$message = "";
if(!isset($_REQUEST["p"])) $_REQUEST["p"] = ''; 

if(isset($_REQUEST["act"])) {
  if ($_REQUEST["act"]=='logout') {
	$_SESSION["ProFiAnTsBLOgLogin"] = "";
	unset($_SESSION["ProFiAnTsBLOgLogin"]);
		
	//setcookie("ProFiAnTsBLOgLogin", "", 0);
	//$_COOKIE["ProFiAnTsBLOgLogin"] = "";	
	
	unset($_SESSION["KCFINDER"]);
	
 } elseif ($_REQUEST["act"]=='login') {
  	if ($_REQUEST["user"] == $CONFIG["admin_user"] and $_REQUEST["pass"] == $CONFIG["admin_pass"]) {
		$_SESSION["ProFiAnTsBLOgLogin"] = "BLoggedIn";	
		
		//setcookie("ProFiAnTsBLOgLogin", "BLoggedIn", time()+8*3600);
		//$_COOKIE["ProFiAnTsBLOgLogin"] = "BLoggedIn";			
			
 		$_REQUEST["act"]='posts';
  	} else {
		$logMessage = $lang['Login_message'];
  	}
  }
} ?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<title><?php echo $lang['Script_Administration_Header']; ?></title>

<link href="styles/bootstrap.css" rel="stylesheet">
<script language="javascript" src="include/jquery-1.11.2.min.js"></script>


<script type="text/javascript" src="accordion/javascript/prototype.js"></script>
<script type="text/javascript" src="accordion/javascript/effects.js"></script>
<script type="text/javascript" src="accordion/javascript/accordion.js"></script>
<script language="javascript" src="include/functions.js"></script>
<script language="javascript" src="include/color_pick.js"></script>
<script language="javascript" src="include/jscolor.js"></script>
<script type="text/javascript" src="include/datetimepicker_css.js"></script>
<link href="styles/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<script>
    CKEDITOR.env.isCompatible = true;
</script>


</head>

<body>

<div class="logo">
	<div class="script_name"><?php echo $lang['Script_Administration_Header']; ?></div>
	<div class="logout_button"><a href="admin.php?act=logout"><img src="images/logout1.png" width="32" alt="Logout" border="0" /></a></div>
    <div class="clear"></div>
</div>

<div style="clear:both"></div>

<?php  
$Logged = false;
//if(isset($_COOKIE["ProFiAnTsBLOgLogin"]) and ($_COOKIE["ProFiAnTsBLOgLogin"]=="BLoggedIn")) {
if(isset($_SESSION["ProFiAnTsBLOgLogin"]) and ($_SESSION["ProFiAnTsBLOgLogin"]=="BLoggedIn")) {
	$Logged = true;
}
if ( $Logged ){

if (isset($_REQUEST["act"]) and $_REQUEST["act"]=='updateOptionsAdmin') {
	
	if (!isset($_REQUEST["showshare"]) or $_REQUEST["showshare"]=='') $_REQUEST["showshare"] = 'yes';
	
	$sql = "UPDATE ".$TABLE["Options"]." 
			SET `email`			='".SafetyDB($_REQUEST["email"])."',
				`per_page`		='".SafetyDB($_REQUEST["per_page"])."',
				`post_limit`	='".SafetyDB($_REQUEST["post_limit"])."',							
				`showshare`		='".SafetyDB($_REQUEST["showshare"])."',
				`items_link`	='".SafetyDB($_REQUEST["items_link"])."',
				`time_zone`		='".SafetyDB($_REQUEST["time_zone"])."',
				`htmleditor`	='".SafetyDB($_REQUEST["htmleditor"])."'";
	$sql_result = sql_result($sql);
	$_REQUEST["act"]='admin_options'; 
  	$message = $lang['Message_Admin_options_saved']; 
  
} elseif(isset($_REQUEST["act"]) and $_REQUEST["act"]=='updateOptionsPost') {

	if (!isset($_REQUEST["approval"]) or $_REQUEST["approval"]=='') $_REQUEST["approval"] = 'false';
	
	if(!empty($_REQUEST["comm_req"])) {
		$comm_req = serialize($_REQUEST["comm_req"]);
	} else {
		$comm_req = "";
	}
	
	$sql = "UPDATE ".$TABLE["Options"]." 
			SET `approval`		='".SafetyDB($_REQUEST["approval"])."', 
				`commentsoff`	='".SafetyDB($_REQUEST["commentsoff"])."',
				`captcha`		='".SafetyDB($_REQUEST["captcha"])."',
				`comments_order`='".SafetyDB($_REQUEST["comments_order"])."',
				`comm_req`		= '".SafetyDB($comm_req)."', 
				`ban_ips`		='".SafetyDB($_REQUEST["ban_ips"])."', 
				`ban_words`		='".SafetyDB($_REQUEST["ban_words"])."'";
	$sql_result = sql_result($sql);
	$_REQUEST["act"]='post_options'; 
  	$message = $lang['Message_Comments_options_saved']; 

} elseif(isset($_REQUEST["act"]) and $_REQUEST["act"]=='updateOptionsVisual') {
	
	// general visual options
	$visual['gen_font_family'] 	= $_REQUEST['gen_font_family'];
	$visual['gen_font_color'] 	= $_REQUEST['gen_font_color']; 
	$visual['gen_font_size'] 	= $_REQUEST['gen_font_size'];  
	$visual['gen_text_align'] 	= $_REQUEST['gen_text_align'];
	$visual['gen_line_height'] 	= $_REQUEST['gen_line_height'];
	$visual['gen_bgr_color'] 	= $_REQUEST['gen_bgr_color'];
	$visual['gen_width'] 		= $_REQUEST['gen_width'];
	$visual['gen_width_dim'] 	= $_REQUEST['gen_width_dim'];
		
	// Search box style 
	$visual['showsearch'] 		= $_REQUEST['showsearch']; 
	$visual['sear_color'] 		= $_REQUEST['sear_color']; 
	$visual['sear_bor_color'] 	= $_REQUEST['sear_bor_color'];
	$visual['sear_font_family'] = $_REQUEST['sear_font_family'];
	
	// CATEGORIES, RECENT POSTS, ARCHIVES style
	$visual['showrightbar'] 		= $_REQUEST['showrightbar'];
	$visual['showcateg'] 			= $_REQUEST['showcateg']; 
	$visual['showrecent'] 			= $_REQUEST['showrecent']; 
	$visual['showarchive'] 			= $_REQUEST['showarchive']; 
	$visual['cat_word_color'] 		= $_REQUEST['cat_word_color'];
	$visual['cat_word_family'] 		= $_REQUEST['cat_word_family'];
	$visual['cat_word_font_size'] 	= $_REQUEST['cat_word_font_size'];
	$visual['cat_word_font_style'] 	= $_REQUEST['cat_word_font_style'];
	$visual['cat_word_font_weight'] = $_REQUEST['cat_word_font_weight'];
	$visual['cat_ctm_color'] 		= $_REQUEST['cat_ctm_color']; 
	$visual['cat_ctm_color_hover'] 	= $_REQUEST['cat_ctm_color_hover']; 	
	$visual['line_cat_ctm_color']	= $_REQUEST['line_cat_ctm_color'];
	$visual['cat_ctm_family'] 		= $_REQUEST['cat_ctm_family']; 
	$visual['cat_ctm_font_size']	= $_REQUEST['cat_ctm_font_size']; 
	$visual['cat_ctm_font_style'] 	= $_REQUEST['cat_ctm_font_style'];
	$visual['cat_ctm_font_weight'] 	= $_REQUEST['cat_ctm_font_weight'];
	
	
	// posts title style
	$visual['list_title_font'] 			= $_REQUEST['list_title_font']; 
	$visual['list_title_color'] 		= $_REQUEST['list_title_color']; 
	$visual['list_title_color_hover'] 	= $_REQUEST['list_title_color_hover']; 
	$visual['list_title_size'] 			= $_REQUEST['list_title_size']; 
	$visual['list_title_font_weight']	= $_REQUEST['list_title_font_weight'];
	$visual['list_title_font_style'] 	= $_REQUEST['list_title_font_style']; 
	$visual['list_title_align'] 		= $_REQUEST['list_title_align'];
	$visual['list_title_line_height'] 	= $_REQUEST['list_title_line_height'];
	
	// posts title style
	$visual['post_title_font'] 			= $_REQUEST['post_title_font']; 
	$visual['post_title_color'] 		= $_REQUEST['post_title_color']; 
	$visual['post_title_size'] 			= $_REQUEST['post_title_size']; 
	$visual['post_title_font_style'] 	= $_REQUEST['post_title_font_style']; 
	$visual['post_title_font_weight']	= $_REQUEST['post_title_font_weight'];
	$visual['post_title_align'] 		= $_REQUEST['post_title_align'];
	$visual['title_line_height'] 		= $_REQUEST['title_line_height'];
	
	// posts list text style 
	$visual['list_text_font'] 		= $_REQUEST['list_text_font'];
	$visual['list_text_color'] 		= $_REQUEST['list_text_color'];
	$visual['list_text_size'] 		= $_REQUEST['list_text_size']; 
	$visual['list_text_font_weight']= $_REQUEST['list_text_font_weight'];
	$visual['list_text_font_style'] = $_REQUEST['list_text_font_style']; 
	$visual['list_text_text_align'] = $_REQUEST['list_text_text_align'];
	$visual['list_text_line_height']= $_REQUEST['list_text_line_height'];
	
	// posts text style 
	$visual['text_font'] 		= $_REQUEST['text_font'];
	$visual['text_color'] 		= $_REQUEST['text_color'];
	$visual['text_bgr_color'] 	= $_REQUEST['text_bgr_color'];
	$visual['text_size'] 		= $_REQUEST['text_size']; 
	$visual['text_font_weight']	= $_REQUEST['text_font_weight'];
	$visual['text_font_style'] 	= $_REQUEST['text_font_style']; 
	$visual['text_text_align'] 	= $_REQUEST['text_text_align'];
	$visual['text_line_height'] = $_REQUEST['text_line_height'];
	$visual['text_padding'] 	= $_REQUEST['text_padding'];
	
	// posts list date style
	$visual['list_date_font'] 		= $_REQUEST['list_date_font']; 
	$visual['list_date_color'] 		= $_REQUEST['list_date_color']; 
	$visual['list_date_color_hover']= $_REQUEST['list_date_color_hover']; 
	$visual['list_date_decoration_hover']= $_REQUEST['list_date_decoration_hover']; 
	$visual['list_date_size'] 		= $_REQUEST['list_date_size']; 
	$visual['list_date_font_style']	= $_REQUEST['list_date_font_style']; 
	$visual['list_date_text_align'] = $_REQUEST['list_date_text_align']; 
	$visual['list_date_format'] 	= $_REQUEST['list_date_format']; 
	$visual['list_show_date'] 		= $_REQUEST['list_show_date'];
	$visual['list_showing_time'] 	= $_REQUEST['list_showing_time']; 
	
	// post date style
	$visual['date_font'] 		= $_REQUEST['date_font']; 
	$visual['date_color'] 		= $_REQUEST['date_color']; 
	$visual['date_size'] 		= $_REQUEST['date_size']; 
	$visual['date_color_hover']	= $_REQUEST['date_color_hover']; 
	$visual['date_decoration_hover']= $_REQUEST['date_decoration_hover']; 
	$visual['date_font_style']	= $_REQUEST['date_font_style']; 
	$visual['date_text_align'] 	= $_REQUEST['date_text_align']; 
	$visual['date_format'] 		= $_REQUEST['date_format']; 
	$visual['show_date'] 		= $_REQUEST['show_date'];
	$visual['showing_time'] 	= $_REQUEST['showing_time']; 
	$visual['show_aa'] 			= $_REQUEST['show_aa'];	
	
	// "COMMENTS", "MORE" link style
	$visual['more_font_color'] 			  = $_REQUEST['more_font_color']; 
	$visual['more_font_color_hover']	  = $_REQUEST['more_font_color_hover'];
	$visual['more_font']				  = $_REQUEST['more_font'];
	$visual['more_font_size'] 			  = $_REQUEST['more_font_size'];
	$visual['more_font_style'] 			  = $_REQUEST['more_font_style'];
	$visual['more_font_weight'] 		  = $_REQUEST['more_font_weight'];
	$visual['more_text_decoration'] 	  = $_REQUEST['more_text_decoration'];
	$visual['more_text_decoration_hover'] = $_REQUEST['more_text_decoration_hover'];
	
	// "back" button style
	$visual['back_font_color'] 				= $_REQUEST['back_font_color']; 
	$visual['back_font_color_hover']		= $_REQUEST['back_font_color_hover'];
	$visual['back_font'] 					= $_REQUEST['back_font']; 
	$visual['back_font_size'] 				= $_REQUEST['back_font_size'];
	$visual['back_font_style'] 				= $_REQUEST['back_font_style'];
	$visual['back_font_weight'] 			= $_REQUEST['back_font_weight'];
	$visual['back_text_decoration'] 		= $_REQUEST['back_text_decoration'];
	$visual['back_text_decoration_hover'] 	= $_REQUEST['back_text_decoration_hover'];
	
	
	// links in the post message area
	$visual['links_font_color'] 			= $_REQUEST['links_font_color']; 
	$visual['links_font_color_hover']		= $_REQUEST['links_font_color_hover'];
	$visual['links_text_decoration'] 		= $_REQUEST['links_text_decoration'];
	$visual['links_text_decoration_hover'] 	= $_REQUEST['links_text_decoration_hover'];
	$visual['links_font_size'] 				= $_REQUEST['links_font_size'];
	$visual['links_font_style'] 			= $_REQUEST['links_font_style'];
	$visual['links_font_weight'] 			= $_REQUEST['links_font_weight'];	
	
	// tags style
	$visual['tagged_font_color'] 			= $_REQUEST['tagged_font_color'];  
	$visual['tagged_family']				= $_REQUEST['tagged_family'];
	$visual['tagged_font_size'] 			= $_REQUEST['tagged_font_size'];
	$visual['tagged_font_style'] 			= $_REQUEST['tagged_font_style'];
	$visual['tagged_font_weight'] 			= $_REQUEST['tagged_font_weight'];
	$visual['tags_font_color'] 				= $_REQUEST['tags_font_color']; 
	$visual['tags_font_color_hover']		= $_REQUEST['tags_font_color_hover']; 
	$visual['tags_family']					= $_REQUEST['tags_family'];
	$visual['tags_text_decoration'] 		= $_REQUEST['tags_text_decoration'];
	$visual['tags_text_decoration_hover'] 	= $_REQUEST['tags_text_decoration_hover'];
	$visual['tags_font_size'] 				= $_REQUEST['tags_font_size'];
	$visual['tags_font_style'] 				= $_REQUEST['tags_font_style'];
	$visual['tags_font_weight'] 			= $_REQUEST['tags_font_weight'];
	
	
	/////////// pagination style ///////////
	$visual['pag_font_family'] 		= $_REQUEST['pag_font_family']; 
	$visual['pag_font_color'] 		= $_REQUEST['pag_font_color'];
	$visual['pag_font_color_hover'] = $_REQUEST['pag_font_color_hover'];
	$visual['pag_font_color_sel'] 	= $_REQUEST['pag_font_color_sel'];
	$visual['pag_font_color_prn'] 	= $_REQUEST['pag_font_color_prn'];
	$visual['pag_color_prn_hover'] 	= $_REQUEST['pag_color_prn_hover'];	
	$visual['pag_font_color_ina'] 	= $_REQUEST['pag_font_color_ina'];
	$visual['pag_font_size'] 		= $_REQUEST['pag_font_size']; 
	$visual['pag_font_weight'] 		= $_REQUEST['pag_font_weight']; 	 
	$visual['pag_font_style'] 		= $_REQUEST['pag_font_style'];
	$visual['pag_align_to'] 		= $_REQUEST['pag_align_to'];

	
	// Back to top style
	$visual['show_scrolltop'] 			= $_REQUEST['show_scrolltop']; 
	$visual['scrolltop_width'] 			= $_REQUEST['scrolltop_width']; 
	$visual['scrolltop_height'] 		= $_REQUEST['scrolltop_height']; 	 
	$visual['scrolltop_bgr_color'] 		= $_REQUEST['scrolltop_bgr_color'];
	$visual['scrolltop_bgr_color_hover']= $_REQUEST['scrolltop_bgr_color_hover']; 
	$visual['scrolltop_opacity'] 		= $_REQUEST['scrolltop_opacity']; 
	$visual['scrolltop_opacity_hover'] 	= $_REQUEST['scrolltop_opacity_hover']; 
	$visual['scrolltop_radius'] 		= $_REQUEST['scrolltop_radius']; 
	
	// navigation at the bottom "Older Post" - "Newer Post"
	$visual['bott_font_family'] 	= $_REQUEST['bott_font_family']; 
	$visual['bott_color'] 			= $_REQUEST['bott_color']; 
	$visual['bott_color_hover']		= $_REQUEST['bott_color_hover'];
	$visual['bott_bgr_color'] 		= $_REQUEST['bott_bgr_color']; 
	$visual['bott_bgr_color_hover'] = $_REQUEST['bott_bgr_color_hover'];
	$visual['bott_size'] 			= $_REQUEST['bott_size'];
	$visual['bott_style'] 			= $_REQUEST['bott_style'];
	$visual['bott_weight'] 			= $_REQUEST['bott_weight'];
	$visual['bott_align_to']		= $_REQUEST['bott_align_to'];
	
	// distances in the post
	$visual['dist_from_top'] 		= $_REQUEST['dist_from_top'];
	$visual['dist_title_date'] 		= $_REQUEST['dist_title_date'];
	$visual['list_dist_title_date'] = $_REQUEST['list_dist_title_date'];
	$visual['dist_date_text'] 		= $_REQUEST['dist_date_text'];
	$visual['list_dist_date_text'] 	= $_REQUEST['list_dist_date_text'];
	$visual['dist_btw_posts'] 		= $_REQUEST['dist_btw_posts'];	
	$visual['dist_btw_post_more']	= $_REQUEST['dist_btw_post_more'];	
	$visual['dist_link_title'] 		= $_REQUEST['dist_link_title'];	
	$visual['dist_text_tags'] 		= $_REQUEST['dist_text_tags'];
	$visual['dist_tags_nav'] 		= $_REQUEST['dist_tags_nav'];
	$visual['dist_comm_links'] 		= $_REQUEST['dist_comm_links'];
	$visual['dist_from_bottom'] 	= $_REQUEST['dist_from_bottom'];
	
	$visual = serialize($visual);
	
	$sql = "UPDATE ".$TABLE["Options"]." 
			SET `visual` ='".SafetyDB($visual)."'";
	$sql_result = sql_result($sql);
	$_REQUEST["act"]='visual_options'; 
  	$message = $lang['Message_Visual_options_saved']; 


} elseif(isset($_REQUEST["act"]) and $_REQUEST["act"]=='updateOptionsComm') {
		
	// comments visual options
		
	$visual['w_comm_font_family'] 	= $_REQUEST['w_comm_font_family'];
	$visual['w_comm_font_color'] 	= $_REQUEST['w_comm_font_color'];
	$visual['w_comm_font_size'] 	= $_REQUEST['w_comm_font_size']; 	 
	$visual['w_comm_font_style'] 	= $_REQUEST['w_comm_font_style'];
	$visual['w_comm_font_weight'] 	= $_REQUEST['w_comm_font_weight']; 
	
	$visual['comm_bord_color'] 	= $_REQUEST['comm_bord_color']; 
	$visual['name_font'] 		= $_REQUEST['name_font']; 
	$visual['name_font_color']	= $_REQUEST['name_font_color'];
	$visual['name_font_size'] 	= $_REQUEST['name_font_size']; 	 
	$visual['name_font_style'] 	= $_REQUEST['name_font_style'];
	$visual['name_font_weight'] = $_REQUEST['name_font_weight']; 
	
	$visual['comm_date_font'] 		= $_REQUEST['comm_date_font']; 
	$visual['comm_date_color'] 		= $_REQUEST['comm_date_color']; 
	$visual['comm_date_size'] 		= $_REQUEST['comm_date_size']; 
	$visual['comm_date_font_style']	= $_REQUEST['comm_date_font_style'];
	$visual['comm_date_format'] 	= $_REQUEST['comm_date_format']; 
	$visual['comm_showing_time'] 	= $_REQUEST['comm_showing_time'];
	
	$visual['comm_font'] 		= $_REQUEST['comm_font']; 
	$visual['comm_font_color']	= $_REQUEST['comm_font_color'];
	$visual['comm_font_size'] 	= $_REQUEST['comm_font_size']; 	 
	$visual['comm_font_style'] 	= $_REQUEST['comm_font_style'];
	$visual['comm_font_weight'] = $_REQUEST['comm_font_weight']; 
	
	$visual['leave_font_family']= $_REQUEST['leave_font_family'];
	$visual['leave_font_color'] = $_REQUEST['leave_font_color'];
	$visual['leave_font_size'] 	= $_REQUEST['leave_font_size']; 	 
	$visual['leave_font_weight']= $_REQUEST['leave_font_weight'];
	$visual['leave_font_style'] = $_REQUEST['leave_font_style']; 
	
	$visual['field_font_family']= $_REQUEST['field_font_family'];
	$visual['field_font_color'] = $_REQUEST['field_font_color'];
	$visual['field_bgr_color'] 	= $_REQUEST['field_bgr_color'];
	$visual['field_font_size'] 	= $_REQUEST['field_font_size']; 
	
	// submit comment button style
	$visual['subm_font_family'] = $_REQUEST['subm_font_family']; 
	$visual['subm_color'] 		= $_REQUEST['subm_color']; 
	$visual['subm_bgr_color'] 	= $_REQUEST['subm_bgr_color']; 
	$visual['subm_brdr_color']	= $_REQUEST['subm_brdr_color']; 
	$visual['subm_bgr_color_on']= $_REQUEST['subm_bgr_color_on'];
	$visual['subm_font_size'] 	= $_REQUEST['subm_font_size'];  
	$visual['subm_bor_radius'] 	= $_REQUEST['subm_bor_radius']; 
	
	$visual['dist_btw_comm'] 	= $_REQUEST['dist_btw_comm'];
	
		
	$visual_comm = serialize($visual);
	
	$sql = "UPDATE ".$TABLE["Options"]." 
			SET `visual_comm` = '".SafetyDB($visual_comm)."'";
	$sql_result = sql_result($sql);
	$_REQUEST["act"]='visual_options_comm'; 
  	$message = $lang['Message_Visual_comments_saved']; 
	
 
} elseif(isset($_REQUEST["act"]) and $_REQUEST["act"]=='updateOptionsLanguage') {
 	
	// main words in the front-end of the script
	$language['Back_home'] 			= $_REQUEST['Back_home'];
	$language['Search_button'] 		= $_REQUEST['Search_button'];
	$language['Category'] 			= $_REQUEST['Category'];
	$language['Category_all'] 		= $_REQUEST['Category_all']; 
	$language['Recent_Posts'] 		= $_REQUEST['Recent_Posts']; 
	$language['Archives'] 			= $_REQUEST['Archives']; 
	$language['Read_more'] 			= $_REQUEST['Read_more'];
	$language['Comments_link'] 		= $_REQUEST['Comments_link'];
	$language['Tagged_as'] 			= $_REQUEST['Tagged_as'];
	$language['No_Posts'] 			= $_REQUEST['No_Posts'];
	$language['Older_Post'] 		= $_REQUEST['Older_Post']; 
	$language['Newer_Post'] 		= $_REQUEST['Newer_Post']; 
	
	// days of the week in the dates
	$language['Monday'] 	= $_REQUEST['Monday']; 
	$language['Tuesday'] 	= $_REQUEST['Tuesday'];
	$language['Wednesday'] 	= $_REQUEST['Wednesday'];
	$language['Thursday'] 	= $_REQUEST['Thursday']; 
	$language['Friday'] 	= $_REQUEST['Friday']; 
	$language['Saturday'] 	= $_REQUEST['Saturday'];
	$language['Sunday'] 	= $_REQUEST['Sunday'];
	
	// month names in the dates
	$language['January'] 	= $_REQUEST['January']; 
	$language['February'] 	= $_REQUEST['February'];
	$language['March'] 		= $_REQUEST['March'];
	$language['April'] 		= $_REQUEST['April']; 
	$language['May'] 		= $_REQUEST['May']; 
	$language['June'] 		= $_REQUEST['June'];
	$language['July'] 		= $_REQUEST['July'];
	$language['August'] 	= $_REQUEST['August'];
	$language['September'] 	= $_REQUEST['September']; 
	$language['October'] 	= $_REQUEST['October']; 
	$language['November'] 	= $_REQUEST['November'];
	$language['December'] 	= $_REQUEST['December'];	
	
	// about comments	
	$language['Word_Comments'] 			= $_REQUEST['Word_Comments'];
	$language['Written_by'] 			= $_REQUEST['Written_by'];
	$language['on_date'] 				= $_REQUEST['on_date'];
	$language['No_comments_posted'] 	= $_REQUEST['No_comments_posted'];
	$language['Leave_Comment'] 			= $_REQUEST['Leave_Comment'];
	$language['Comment_Name'] 			= $_REQUEST['Comment_Name'];	
	$language['Comment_Email'] 			= $_REQUEST['Comment_Email']; 	
	$language['Comment_here'] 			= $_REQUEST['Comment_here']; 
	$language['Enter_verification_code']= $_REQUEST['Enter_verification_code']; 
	$language['Required_fields'] 		= $_REQUEST['Required_fields']; 
	$language['Submit_Comment'] 		= $_REQUEST['Submit_Comment'];
	
	$language['Banned_word_used'] 			= $_REQUEST['Banned_word_used'];
	$language['Banned_ip_used'] 			= $_REQUEST['Banned_ip_used'];
	$language['Incorrect_verification_code']= $_REQUEST['Incorrect_verification_code']; 
	$language['Comment_Submitted'] 			= $_REQUEST['Comment_Submitted'];
	$language['After_Approval_Admin'] 		= $_REQUEST['After_Approval_Admin'];
	
	$language['required_fields']= $_REQUEST['required_fields']; 
	$language['correct_email'] 	= $_REQUEST['correct_email']; 
	$language['field_code'] 	= $_REQUEST['field_code']; 	
	
	$language['New_comment_posted'] = $_REQUEST['New_comment_posted'];
	
	$language['metatitle'] 	= $_REQUEST['metatitle']; 
	$language['metadescription'] = $_REQUEST['metadescription'];
	
	$language = serialize($language);
	
	$sql = "UPDATE ".$TABLE["Options"]." 
			SET `language` ='".SafetyDB($language)."'";
	$sql_result = sql_result($sql);
	$_REQUEST["act"]='language_options'; 
  	$message = $lang['Message_Language_options_saved']; 


} elseif(isset($_REQUEST["act"]) and $_REQUEST["act"]=='addPost') {
	
	if (!isset($_REQUEST["post_comments"]) or $_REQUEST["post_comments"]=='') $_REQUEST["post_comments"] = 'false';
	
	$post_title = strip_tags($_REQUEST["post_title"]);
	
	$sql = "INSERT INTO ".$TABLE["Posts"]."
			SET `publish_date` 	= '".SafetyDB($_REQUEST["publish_date"])."', 
				`status` 		= '".$_REQUEST["status"]."',
				`cat_id` 		= '".$_REQUEST["cat_id"]."',
				`imgpos` 		= '".SafetyDB($_REQUEST["imgpos"])."',
				`post_title` 	= '".SaveDB($post_title)."',
                `post_text` 	= '".SaveDB($_REQUEST["post_text"])."',
				`post_limit`	= '".SafetyDB($_REQUEST["post_limit"])."',
                `post_tags` 	= '".SaveDB($_REQUEST["post_tags"])."',
				`post_comments` = '".SafetyDB($_REQUEST["post_comments"])."', 
				`reviews` 		= '0'";
	$sql_result = sql_result($sql);
	$_REQUEST["id"] = mysqli_insert_id($connBl);
	$index_id = mysqli_insert_id($connBl);
	
	// upload image to the post
	if (is_uploaded_file($_FILES["image"]['tmp_name'])) {
		
		$filexpl = explode(".", $_FILES["image"]['name']);
		$format = end($filexpl);					
		$formats = array("jpg","jpeg","JPG","png","PNG","gif","GIF");			
		if(in_array($format, $formats) and getimagesize($_FILES['image']['tmp_name'])) {

			$name = str_file_filter($_FILES['image']['name']);
			$name = $index_id . "_" . $name;

			$filePath = $CONFIG["upload_folder"] . $name;
			
			if (move_uploaded_file($_FILES["image"]['tmp_name'], $filePath)) {
				chmod($filePath, 0777);
				Resize_File($filePath, 1024, 0); 
	
				$sql = "UPDATE ".$TABLE["Posts"]."  
						SET `image` = '".$name."'  
						WHERE `id`='".$index_id."'";
				$sql_result = sql_result($sql);
				$message .= '';
			} else {
				$message = 'Cannot copy uploaded file to "'.$filePath.'". Try to set the right permissions (CHMOD 777) to "'.$CONFIG["upload_folder"].'" directory! ';  
			}
		} else {
			$message = $lang['Message_File_must_be_in_image_format'];   
		}
	} else { $message = $lang['Message_Image_file_is_not_uploaded']; }	
	
	
	$message = $lang['Message_Post_added']; 	
	$_REQUEST["act"]='posts'; 
	

} elseif(isset($_REQUEST["act"]) and $_REQUEST["act"]=='updatePost') {

	if (!isset($_REQUEST["post_comments"]) or $_REQUEST["post_comments"]=='') $_REQUEST["post_comments"] = 'false';
	
	$post_title = strip_tags($_REQUEST["post_title"]);

	$sql = "UPDATE ".$TABLE["Posts"]." 
			SET `publish_date`	= '".SafetyDB($_REQUEST["publish_date"])."', 
				`status` 		= '".$_REQUEST["status"]."',
				`cat_id` 		= '".$_REQUEST["cat_id"]."',
				`imgpos` 		= '".SafetyDB($_REQUEST["imgpos"])."',
				`post_title` 	= '".SaveDB($post_title)."',
                `post_text` 	= '".SaveDB($_REQUEST["post_text"])."',
				`post_limit`	= '".SafetyDB($_REQUEST["post_limit"])."',
                `post_tags` 	= '".SaveDB($_REQUEST["post_tags"])."',
				`post_comments` = '".SafetyDB($_REQUEST["post_comments"])."' 
			WHERE id='".SafetyDB($_REQUEST["id"])."'";
	$sql_result = sql_result($sql);	
	
	$sql = "SELECT * FROM ".$TABLE["Posts"]." WHERE id = '".SafetyDB($_REQUEST["id"])."'";
	$sql_result = sql_result($sql);
	$imageArr = mysqli_fetch_assoc($sql_result);
	$image = ReadDB($imageArr["image"]);
	
	$index_id = SafetyDB($_REQUEST["id"]);
	
	// upload image to the post
	if (is_uploaded_file($_FILES["image"]['tmp_name'])) { 
	
		$filexpl = explode(".", $_FILES["image"]['name']);
	  	$format = end($filexpl);			
	  	$formats = array("jpg","jpeg","JPG","png","PNG","gif","GIF");
	  	if(in_array($format, $formats) and getimagesize($_FILES['image']['tmp_name'])) {
		
			if($image != "") unlink($CONFIG["upload_folder"].$image);
			
			$name = str_file_filter($_FILES['image']['name']);
			$name = $index_id . "_" . $name;
			
			$filePath = $CONFIG["upload_folder"] . $name;
			
			if (move_uploaded_file($_FILES["image"]['tmp_name'], $filePath)) {
				chmod($filePath,0777); 				
				Resize_File($filePath, 1024, 0); 
				
				$sql = "UPDATE `".$TABLE["Posts"]."` 
						SET `image` = '".SafetyDB($name)."' 
						WHERE `id` = '".$index_id."'";
				$sql_result = sql_result($sql);
			} else {
				$message = 'Cannot copy uploaded file to "'.$filePath.'". Try to set the right permissions (CHMOD 777) to "'.$CONFIG["upload_folder"].'" directory.';  
			}
		} else {
			$message = $lang['Message_File_must_be_in_image_format'];   
		}
	}
	
	
	if(isset($_REQUEST["updatepreview"]) and $_REQUEST["updatepreview"]!='') {
		$_REQUEST["act"]='viewPost'; 		
	} else {
		$_REQUEST["act"]='posts'; 
	}	
	$message .= $lang['Message_Post_updated']; 

} elseif(isset($_REQUEST["act"]) and $_REQUEST["act"]=='delPost') {

	$sql = "DELETE FROM ".$TABLE["Comments"]." WHERE post_id='".SafetyDB($_REQUEST["id"])."'";
   	$sql_result = sql_result($sql);

	$sql = "DELETE FROM ".$TABLE["Posts"]." WHERE id='".SafetyDB($_REQUEST["id"])."'";
   	$sql_result = sql_result($sql);
 	$_REQUEST["act"]='posts'; 
	$message = $lang['Message_Post_deleted']; 

	
} elseif (isset($_REQUEST["act"]) and $_REQUEST["act"]=="delImage") { 
	
	$sql = "SELECT * FROM ".$TABLE["Posts"]." WHERE `id` = '".SafetyDB($_REQUEST["id"])."'";
	$sql_result = sql_result($sql);
	$imageArr = mysqli_fetch_assoc($sql_result);
	$image = ReadDB($imageArr["image"]);
	if($image != "") unlink($CONFIG["upload_folder"].$image);
	
	$sql = "UPDATE `".$TABLE["Posts"]."` SET `image` = '' WHERE `id` = '".SafetyDB($_REQUEST["id"])."'";
	$sql_result = sql_result($sql);
	
	$message = $lang['Message_Image_deleted'];
	$_REQUEST["act"] = "editPost";	

		
} elseif (isset($_REQUEST["act2"]) and $_REQUEST["act2"]=="change_status_post") { 
	
	$sql = "UPDATE ".$TABLE["Posts"]." 
			SET `status` = '".SafetyDB($_REQUEST["status"])."' 
			WHERE id='".$_REQUEST["id"]."'";
	$sql_result = sql_result($sql);
	
	$message = $lang['Message_Status_updated'];
	$_REQUEST["act"] = "posts";

} elseif (isset($_REQUEST["act"]) and $_REQUEST["act"] == "addCat"){

    $sql = "SELECT * FROM ".$TABLE["Categories"]." WHERE `cat_name` = '".SafetyDB(trim($_REQUEST["cat_name"]))."'";
    $sql_result = sql_result($sql);
    if(mysqli_num_rows($sql_result) == 0) {

        $sql = "INSERT INTO ".$TABLE["Categories"]."
				SET `cat_name` = '".SafetyDB($_REQUEST["cat_name"])."'";
        $sql_result = sql_result($sql);

        $_REQUEST["act"] = "cats";
        $message .= $lang['Message_Categ_added'];

    } else {
        $_REQUEST["act"] = "cats";
        $message .= $lang['Message_Categ_exist'];
    }


} elseif (isset($_REQUEST["act"]) and $_REQUEST["act"] == "updateCat"){

    $sql = "SELECT * FROM ".$TABLE["Categories"]." WHERE cat_name='".SafetyDB($_REQUEST["cat_name"])."'";
    $sql_result = sql_result($sql);
    if(mysqli_num_rows($sql_result)>1) {

        $_REQUEST["act"] = "cats";
        $message .= $lang['Message_Categ_exist'];

    } else {

        $sql = "UPDATE ".$TABLE["Categories"]."
				SET `cat_name` = '".SafetyDB($_REQUEST["cat_name"])."'
				WHERE id='".$_REQUEST["id"]."'";
        $sql_result = sql_result($sql);

        $_REQUEST["act"] = "cats";
        $message .= $lang['Message_Categ_updated'];

    }

} elseif (isset($_REQUEST["act"]) and $_REQUEST["act"]=='delCat') {

    $sql = "DELETE FROM ".$TABLE["Categories"]." WHERE id='".SafetyDB($_REQUEST["id"])."'";
    $sql_result = sql_result($sql);
    $_REQUEST["act"]='cats';
    $message = $lang['Message_Categ_deleted'];
	

} elseif(isset($_REQUEST["act"]) and $_REQUEST["act"]=='updateComment') {

	$sql = "UPDATE ".$TABLE["Comments"]." 
			SET status	='".$_REQUEST["status"]."', 
				name	='".SafetyDB($_REQUEST["name"])."', 
				email	='".SafetyDB($_REQUEST["email"])."', 
				comment	='".SafetyDB($_REQUEST["comment"])."' 
			WHERE id='".SafetyDB($_REQUEST["id"])."'";
	$sql_result = sql_result($sql);
	$_REQUEST["act"]='comments'; 
	$message = $lang['Message_Comment_saved'];
	
} elseif(isset($_REQUEST["act"]) and $_REQUEST["act"]=='BanIP') {

	$sql = "UPDATE ".$TABLE["Options"]." 
			SET `ban_ips` =  CONCAT(`ban_ips`, ', ".SafetyDB($_REQUEST["ip_addr"])."')";
	$sql_result = sql_result($sql);
	
	$_REQUEST["act"]='comments'; 
	$message = 'IP '.$_REQUEST["ip_addr"].' banned! ';


} elseif(isset($_REQUEST["act"]) and $_REQUEST["act"]=='delComment') {

	$sql = "DELETE FROM ".$TABLE["Comments"]." WHERE id='".SafetyDB($_REQUEST["id"])."'";
   	$sql_result = sql_result($sql);
 	$_REQUEST["act"]='comments'; 
	$message = $lang['Message_Comment_deleted']; 
	
} elseif(isset($_REQUEST["act2"]) and $_REQUEST["act2"]=='delCommBulk') {
	
	if(isset($_REQUEST['delCommBulkArr'])) {
		foreach($_REQUEST['delCommBulkArr'] as $id) {
			
			$sql = "DELETE FROM ".$TABLE["Comments"]." WHERE id='".$id."'";
			$sql_result = sql_result($sql);
			
		}
		$message .= $lang['Message_Selected_Comment_deleted']; 
	} else {
		$message .= $lang['Message_Comment_not_selected']; 
	}
	$_REQUEST["act"]='comments'; 	

} elseif(isset($_REQUEST["act"]) and $_REQUEST["act"]=="change_status_comm") { 
	
	$sql = "UPDATE ".$TABLE["Comments"]." 
			SET status = '".SafetyDB($_REQUEST["status"])."' 
			WHERE id='".SafetyDB($_REQUEST["id"])."'";
	$sql_result = sql_result($sql);
	
	$message = $lang['Message_Status_Updated']; 
	$_REQUEST["act"] = "comments";
}

if (!isset($_REQUEST["act"]) or $_REQUEST["act"]=='') $_REQUEST["act"]='posts';
	
$sql = "SELECT * FROM ".$TABLE["Options"];
$sql_result = sql_result($sql);
$Options = mysqli_fetch_assoc($sql_result);
mysqli_free_result($sql_result);

if(trim($Options['time_zone'])!='') {
	date_default_timezone_set(trim($Options['time_zone']));
}
$_SESSION['KCFINDER'] = array(
    'disabled' => false
);
?> 

<div class="menuButtons">
    <div class="menuButton"><a<?php if($_REQUEST['act']=='posts' or $_REQUEST['act']=='newPost' or $_REQUEST['act']=='editPost' or $_REQUEST['act']=='viewPost' or $_REQUEST['act']=='rss') echo ' class="selected"'; ?> href="admin.php?act=posts"><span><?php echo $lang['menu_Button_1']; ?></span></a></div>
    <div class="menuButton"><a<?php if($_REQUEST['act']=='comments' or $_REQUEST['act']=='editComment') echo ' class="selected"'; ?> href="admin.php?act=comments"><span><?php echo $lang['menu_Button_2']; ?></span></a></div>
    <div class="menuButton"><a<?php if($_REQUEST['act']=='cats' or $_REQUEST['act']=='newCat' or $_REQUEST['act']=='editCat' or $_REQUEST['act']=='HTML_Cat') echo ' class="selected"'; ?> href="admin.php?act=cats"><span><?php echo $lang['menu_Button_3']; ?></span></a></div>
    <div class="menuButton"><a<?php if($_REQUEST['act']=='admin_options' or $_REQUEST['act']=='post_options' or $_REQUEST['act']=='visual_options' or $_REQUEST['act']=='visual_options_comm' or $_REQUEST['act']=='language_options') echo ' class="selected"'; ?> href="admin.php?act=admin_options"><span><?php echo $lang['menu_Button_4']; ?></span></a></div>
    <div class="menuButton"><a<?php if($_REQUEST['act']=='html') echo ' class="selected"'; ?> href="admin.php?act=html"><span><?php echo $lang['menu_Button_5']; ?></span></a></div>
    <div class="clear"></div> 
</div>
	

<div class="admin_wrapper">


<?php
if ($_REQUEST["act"]=='posts' or $_REQUEST["act"]=='newPost' or $_REQUEST["act"]=='editPost' or $_REQUEST["act"]=='viewPost' or $_REQUEST["act"]=='rss') {
?>
	<div class="menuSubButton"><a<?php if($_REQUEST['act']=='posts') echo ' class="selected"'; ?> href="admin.php?act=posts"><?php echo $lang['submenu1_button1']; ?></a></div>	
    <div class="menuSubButton"><a<?php if($_REQUEST['act']=='newPost') echo ' class="selected"'; ?> href="admin.php?act=newPost"><?php echo $lang['submenu1_button2']; ?></a></div>
	<div class="menuSubButton"><a href="preview.php" target="_blank"><?php echo $lang['submenu1_button3']; ?></a></div>
	<div class="menuSubButton"><a<?php if($_REQUEST['act']=='rss') echo ' class="selected"'; ?> href="admin.php?act=rss"><?php echo $lang['submenu1_button4']; ?></a></div>
	<div class="clear"></div>        

<?php
} elseif ($_REQUEST["act"]=='cats' or $_REQUEST["act"]=='newCat' or $_REQUEST["act"]=='editCat' or $_REQUEST['act']=='HTML_Cat') {
    ?>
    <div class="menuSubButton"><a<?php if($_REQUEST['act']=='cats') echo ' class="selected"'; ?> href="admin.php?act=cats"><?php echo $lang['submenu3_button1']; ?></a></div>
    <div class="menuSubButton"><a<?php if($_REQUEST['act']=='newCat') echo ' class="selected"'; ?> href="admin.php?act=newCat"><?php echo $lang['submenu3_button2']; ?></a></div>
    <div class="clear"></div> 


<?php 
} elseif ($_REQUEST["act"]=='admin_options' or $_REQUEST["act"]=='post_options' or $_REQUEST["act"]=='visual_options' or $_REQUEST["act"]=='visual_options_comm' or $_REQUEST["act"]=='language_options') { 
?>
	<div class="menuSubButton"><a<?php if($_REQUEST['act']=='admin_options') echo ' class="selected"'; ?> href="admin.php?act=admin_options"><?php echo $lang['submenu2_button1']; ?></a></div>
	<div class="menuSubButton"><a<?php if($_REQUEST['act']=='post_options') echo ' class="selected"'; ?> href="admin.php?act=post_options"><?php echo $lang['submenu2_button2']; ?></a></div>
	<div class="menuSubButton"><a<?php if($_REQUEST['act']=='visual_options') echo ' class="selected"'; ?> href="admin.php?act=visual_options"><?php echo $lang['submenu2_button3']; ?></a></div>
	<div class="menuSubButton"><a<?php if($_REQUEST['act']=='visual_options_comm') echo ' class="selected"'; ?> href="admin.php?act=visual_options_comm"><?php echo $lang['submenu2_button4']; ?></a></div>
	<div class="menuSubButton"><a<?php if($_REQUEST['act']=='language_options') echo ' class="selected"'; ?> href="admin.php?act=language_options"><?php echo $lang['submenu2_button5']; ?></a></div>
	<div class="clear"></div>        
<?php } ?>



	<?php if(isset($message) and $message!='') {?>
    <div class="message<?php if($_REQUEST['act']=='comments' or $_REQUEST['act']=='editComment') echo ' comm_marg'; ?>"><?php echo $message; ?></div>
    <?php } ?>
    <script type="text/javascript">	
	jQuery.noConflict();
	jQuery(document).ready(function(){
		setTimeout(function(){
			jQuery("div.message").fadeOut("slow", function () {
				jQuery("div.message").remove();
			});
	 
		}, 3500);
	 });
	</script>
    

<?php 
if ($_REQUEST["act"]=='posts') {
	
	if(isset($_REQUEST["search"]) and $_REQUEST["search"]!='') {
		$_REQUEST["search"] = htmlspecialchars(urldecode($_REQUEST["search"]), ENT_QUOTES);
	} else { 
		$_REQUEST["search"] = ''; 
	}
	if(!isset($_REQUEST["orderBy"]))  $_REQUEST["orderBy"] = ''; 
	if(!isset($_REQUEST["orderType"])) $_REQUEST["orderType"] = ''; 
	
	$sql = "SELECT * FROM ".$TABLE["Options"];
	$sql_result = sql_result($sql);
	$Options = mysqli_fetch_assoc($sql_result);
	$OptionsVis = unserialize($Options['visual']); 
	$OptionsLang = unserialize($Options['language']);
	
	if(isset($_REQUEST["p"]) and $_REQUEST["p"]!='') { 
		$pageNum = (int) SafetyDB(urldecode($_REQUEST["p"]));
		if($pageNum<=0) $pageNum = 1;
	} else { 
		$pageNum = 1;
	}
	
	$orderByArr = array("publish_date", "post_title", "cat_id", "status", "count", "reviews");
	if(isset($_REQUEST["orderBy"]) and $_REQUEST["orderBy"]!='' and in_array($_REQUEST["orderBy"], $orderByArr)) { 
		$orderBy = $_REQUEST["orderBy"];
	} else { 
		$orderBy = "publish_date";
	}
	
    $orderTypeArr = array("DESC", "ASC");	
    if(isset($_REQUEST["orderType"]) and $_REQUEST["orderType"]!='' and in_array($_REQUEST["orderType"], $orderTypeArr)) { 
		$orderType = $_REQUEST["orderType"];
	} else {
		$orderType = "DESC";
	}
	if ($orderType == 'DESC') { $norderType = 'ASC'; } else { $norderType = 'DESC'; }
	
	$sqlPosted = "SELECT id FROM ".$TABLE["Posts"]." WHERE status='Posted'";
	$sql_resultPosted = sql_result($sqlPosted);
	$Posted = mysqli_num_rows($sql_resultPosted);
?>
	<div class="pageDescr"><?php echo $lang['List_Dashboard1']; ?> <strong style="font-size:16px"><?php echo $Posted; ?></strong> <?php echo $lang['List_Dashboard2']; ?></div>
    
    <div class="searchForm">
    <form action="admin.php?act=posts" method="post" name="form" class="formStyle">
      <input type="text" name="search" value="<?php echo urldecode($_REQUEST["search"]); ?>" class="searchfield" placeholder="<?php echo $lang['List_Search_placeholder']; ?>" />
      <input type="submit" value="<?php echo $lang['List_Search_Button']; ?>" class="submitButton" />
    </form>
    </div>
    
	<table border="0" cellspacing="0" cellpadding="8" class="allTables">
  	  <tr>
        <td width="20%" class="headlist"><a href="admin.php?act=posts&orderType=<?php echo $norderType; ?>&search=<?php echo urlencode($_REQUEST["search"]); ?>&orderBy=publish_date"><?php echo $lang['List_Date_published']; ?></a></td>
        <td class="headlist"><a href="admin.php?act=posts&orderType=<?php echo $norderType; ?>&search=<?php echo urlencode($_REQUEST["search"]); ?>&orderBy=post_title"><?php echo $lang['List_Title']; ?></a></td>
        <td width="11%" class="headlist"><a href="admin.php?act=posts&orderType=<?php echo $norderType; ?>&search=<?php echo urlencode($_REQUEST["search"]); ?>&orderBy=cat_id"><?php echo $lang['List_Category']; ?></a></td>
        <td width="10%" class="headlist"><a href="admin.php?act=posts&orderType=<?php echo $norderType; ?>&search=<?php echo urlencode($_REQUEST["search"]); ?>&orderBy=status"><?php echo $lang['List_Status']; ?></a></td>
        <td width="9%" class="headlist"><a href="admin.php?act=posts&orderType=<?php echo $norderType; ?>&search=<?php echo urlencode($_REQUEST["search"]); ?>&orderBy=count"><?php echo $lang['List_Comments']; ?></a></td>
        <td width="9%" class="headlist"><a href="admin.php?act=posts&orderType=<?php echo $norderType; ?>&search=<?php echo urlencode($_REQUEST["search"]); ?>&orderBy=reviews"><?php echo $lang['List_Views']; ?></a></td>
        <td class="headlist" colspan="3">&nbsp;</td>
  	  </tr>
      
  	<?php 
	if(isset($_REQUEST["search"]) and ($_REQUEST["search"]!="")) {
	  $findMe = trim(SafetyDB($_REQUEST["search"]));
	  $search = "WHERE (post_title LIKE '%".$findMe."%' OR post_text LIKE '%".$findMe."%')";
	} else {
	  $search = '';
	}

	$sql   = "SELECT count(*) as total FROM ".$TABLE["Posts"]." ".$search;
	$sql_result = sql_result($sql);
	$row   = mysqli_fetch_array($sql_result);
	$count = $row["total"];
	$pages = ceil($count/50);
			
	$sql = "SELECT DISTINCT b.*, (SELECT count(*) FROM `".$TABLE["Comments"]."` AS bc WHERE bc.post_id = c.post_id) AS count
			FROM `".$TABLE["Posts"]."` AS b LEFT JOIN `".$TABLE["Comments"]."` AS c ON b.id = c.post_id ".$search."
			ORDER BY " . $orderBy . " " . $orderType."  
			LIMIT " . ($pageNum-1)*50 . ",50";
	$sql_result = sql_result($sql);
	
	if (mysqli_num_rows($sql_result)>0) {
		$i=1;
		while ($Post = mysqli_fetch_assoc($sql_result)) {
	?>
  	  <tr>
        <td class="bodylist"><?php echo admin_date($Post["publish_date"]); ?></td>
        <td class="bodylist"><?php echo $Post["post_title"]; ?></td>
        <td class="bodylist">
              <?php
              $sqlCat = "SELECT * FROM ".$TABLE["Categories"]." WHERE id='".$Post["cat_id"]."'";
              $sql_resultCat = sql_result($sqlCat);
              $Cat = mysqli_fetch_assoc($sql_resultCat);
              if($Cat["id"]>0) echo ReadDB($Cat["cat_name"]); else echo "------"; ?>
        </td>
        <td class="bodylist">
        	<form action="admin.php?act=posts" method="post" name="form<?php echo $i; ?>" class="formStyle">
            <input type="hidden" name="act2" value="change_status_post" />
            <input type="hidden" name="id" value="<?php echo $Post["id"]; ?>" />
            <select name="status" onChange="document.form<?php echo $i; ?>.submit()">
                <option value="Posted"<?php if($Post['status']=='Posted') echo " selected='selected'"; ?>>Posted</option>
                <option value="Hidden"<?php if($Post['status']=='Hidden') echo " selected='selected'"; ?>>Hidden</option>
            </select>
            </form>	
        </td>
        <td class="bodylist"><a style="text-decoration:none" href="admin.php?act=comments&post_id=<?php echo $Post["id"]; ?>"><?php echo $Post["count"]; ?></a> <?php if($Post["post_comments"]=='false') echo "<sub>(not allowed)</sub>"; ?></td>
        <td class="bodylist"><?php if($Post["reviews"]=='') echo "0"; else echo $Post["reviews"]; ?></td>
        <td class="bodylistAct"><a class="view" href='admin.php?act=viewPost&id=<?php echo $Post["id"]; ?>' title="Preview"><img class="act" src="images/preview.png" alt="Preview" /></a></td>
        <td class="bodylistAct"><a href='admin.php?act=editPost&amp;id=<?php echo $Post["id"]; ?>&amp;p=<?php if(isset($_REQUEST["p"])) echo $_REQUEST["p"]; ?>' title="Edit"><img class="act" src="images/edit.png" alt="Edit" /></a></td>
        <td class="bodylistAct"><a class="delete" href="admin.php?act=delPost&amp;id=<?php echo $Post["id"]; ?>&amp;p=<?php if(isset($_REQUEST["p"])) echo $_REQUEST["p"]; ?>" onclick="return confirm('Are you sure you want to delete it?');" title="DELETE"><img class="act" src="images/delete.png" alt="DELETE" /></a></td>
  	  </tr>
  	<?php 
			$i++;
		}
		mysqli_free_result($sql_result);
	} else {
	?>
      <tr>
      	<td colspan="9" class="borderBottomList"><?php echo $lang['List_No_Entries']; ?></td>
      </tr>
    <?php	
	}
	?>
    
	<?php
    if ($pages>1) {
    ?>
  	  <tr>
      	<td colspan="9" class="bottomlist"><div class='paging'><?php echo $lang['List_Page']; ?> </div>
		<?php
        for($i=1;$i<=$pages;$i++){ 
            if($i == $pageNum ) echo "<div class='paging'>" .$i. "</div>";
            else echo "<a href='admin.php?act=posts&amp;p=".$i."&amp;search=".$_REQUEST["search"]."&amp;orderBy=".$_REQUEST["orderBy"]."&amp;orderType=".$_REQUEST["orderType"]."' class='paging'>".$i."</a>"; 
            echo "&nbsp; ";
        }
        ?>
      	</td>
      </tr>
	<?php
    }
    ?>
	</table>


<?php 
} elseif ($_REQUEST["act"]=='newPost') {
	$sql = "SELECT * FROM ".$TABLE["Options"];
	$sql_result = sql_result($sql);
	$Options = mysqli_fetch_assoc($sql_result);
	mysqli_free_result($sql_result);
?>
	<form action="admin.php" method="post" name="form" enctype="multipart/form-data">
  	<input type="hidden" name="act" value="addPost" />
  	<div class="pageDescr"><?php echo $lang['Create_Listing_Dashboard']; ?></div>
	<table border="0" cellspacing="0" cellpadding="8" class="fieldTables">
      <tr>
      	<td colspan="2" valign="top" class="headlist"><?php echo $lang['Create_Listing_Header']; ?></td>
      </tr>
      <tr>
        <td class="formLeft"><?php echo $lang['Create_Listing_Date']; ?></td>
        <td class="formRight">
      		<input type="text" name="publish_date" id="publish_date" maxlength="25" size="25" value="<?php echo date("Y-m-d H:i:s"); ?>" readonly /> <a href="javascript:NewCssCal('publish_date','yyyymmdd','dropdown',true,24,false)"><img src="images/cal.gif" width="16" height="16" alt="Pick a date" border="0" /></a>
        </td>
      </tr>      
      <tr>
      	<td class="formLeft"><?php echo $lang['Create_Listing_Status']; ?></td>
      	<td class="formRight">
        <select name="status">
          <option value="Posted"><?php echo $lang['Create_Listing_Posted']; ?></option>
          <option value="Hidden"><?php echo $lang['Create_Listing_Hidden']; ?></option>
        </select>
      	</td>
      </tr>
      <tr>
        <td><?php echo $lang['Create_Listing_Category']; ?> </td>
        <td>
            <select name="cat_id">
                <option value="0">---------</option>
                <?php
                $sql = "SELECT * FROM ".$TABLE["Categories"]." ORDER BY cat_name ASC";
                $sql_result = sql_result($sql);
                if (mysqli_num_rows($sql_result)>0) {
                    while ($Cat = mysqli_fetch_assoc($sql_result)) {
                        ?>
                        <option value="<?php echo $Cat["id"]; ?>"><?php echo ReadDB($Cat["cat_name"]); ?></option>
                    <?php
                    }
                }
                ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="formLeft"><?php echo $lang['Create_Listing_Title']; ?></td>
        <td class="formRight"><input class="input_post" type="text" name="post_title" /></td>
      </tr>    
      <tr>
        <td class="formLeft"><?php echo $lang['Create_Listing_Image']; ?></td>
        <td class="formRight">
        
        	<input type="file" name="image" size="40" /> 
        	&nbsp;&nbsp;&nbsp;Show it on the actual post:
        	<select name="imgpos">
                <option value="top">on top</option>
                <option value="bottom">at the bottom</option>
            	<option value="none">none</option>
            </select>
          
        </td>
      </tr>  
      <tr>
        <td class="formLeft" valign="top"><?php echo $lang['Create_Listing_Message']; ?></td>
        <td class="formRight">        	
            <textarea name="post_text" id="post_text" class="post_text"></textarea>
        	<script type="text/javascript">
				CKEDITOR.replace( 'post_text',
                {	
					
					<?php if(isset($Options['htmleditor']) and $Options['htmleditor']=="plug") { ?>
					extraPlugins: 'imageuploader,youtube,slimbox,oembed,widget,lineutils,codesnippet,pastecode',
					filebrowserUploadUrl : 'ckeditor/plugins/imageuploader/fileupload.php',
					<?php } else { ?>
					filebrowserBrowseUrl : 'ckeditor/kcfinder/browse.php?opener=ckeditor&type=files',
                    filebrowserImageBrowseUrl : 'ckeditor/kcfinder/browse.php?opener=ckeditor&type=images',
                    filebrowserFlashBrowseUrl : 'ckeditor/kcfinder/browse.php?opener=ckeditor&type=flash',
					filebrowserUploadUrl  :'ckeditor/kcfinder/upload.php?opener=ckeditor&type=files',
					filebrowserImageUploadUrl : 'ckeditor/kcfinder/upload.php?opener=ckeditor&type=images',
					filebrowserFlashUploadUrl : 'ckeditor/kcfinder/upload.php?opener=ckeditor&type=flash',
					<?php } ?>	
									
					height: 400, width: '98%'

				});
			</script>  
        </td>
      </tr>
      
      <tr>
        <td class="formLeft"><?php echo $lang['Create_Listing_CharNum']; ?></td>
        <td class="formRight">
        	<input type="text" name="post_limit" size="4" value="<?php if(isset($Options["post_limit"])) echo $Options["post_limit"]; ?>" onkeypress='return isNumberKey(event)' />
            <sub><?php echo $lang['Create_Listing_CharNum_note']; ?></sub>
        </td>
      </tr>
      <tr>
        <td class="formLeft" valign="top">
			<?php echo $lang['Create_Listing_Tags']; ?><br>
        	<span style="font-size:12px;"><?php echo $lang['Create_Listing_Tags_descr']; ?></span>
        </td>
        <td class="formRight">        	
            <input class="input_post" type="text" name="post_tags" />
        </td>
      </tr>
      <tr>
      	<td class="formLeft"><?php echo $lang['Create_Listing_Comments_al']; ?></td>
      	<td class="formRight"><input name="post_comments" type="checkbox" value="true"<?php if ($Options["commentsoff"]!='yes') echo ' checked="checked"'; ?> /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td class="formRight"><input name="submit" type="submit" value="<?php echo $lang['Create_Listing_Save']; ?>" class="submitButton" /></td>
      </tr>
  	</table>
	</form>


<?php 
} elseif ($_REQUEST["act"]=='editPost') {
	$sql = "SELECT * FROM ".$TABLE["Options"];
	$sql_result = sql_result($sql);
	$Options = mysqli_fetch_assoc($sql_result);
	mysqli_free_result($sql_result);
	
	$sql = "SELECT * FROM ".$TABLE["Posts"]." WHERE id='".$_REQUEST["id"]."'";
	$sql_result = sql_result($sql);
	$Post = mysqli_fetch_assoc($sql_result);
	mysqli_free_result($sql_result);

	$sqlC   = "SELECT count(*) FROM ".$TABLE["Comments"]." WHERE post_id='".$Post["id"]."'";
	$sql_resultC = sql_result($sqlC);
	$count = mysqli_fetch_array($sql_resultC);
	mysqli_free_result($sql_resultC);
?>
	<form action="admin.php" method="post" name="form" enctype="multipart/form-data">
  	<input type="hidden" name="act" value="updatePost" />
  	<input type="hidden" name="id" value="<?php echo $Post["id"]; ?>" />
  	<input type="hidden" name="p" value="<?php echo $_REQUEST["p"]; ?>" />
  	<div class="pageDescr"><?php echo $lang['Edit_Listing_Dashboard']; ?></div>
	<table border="0" cellspacing="0" cellpadding="8" class="fieldTables">
      <tr>
      	<td colspan="2" valign="top" class="headlist"><?php echo $lang['Edit_Listing_Header']; ?></td>
      </tr>
      <tr>
        <td class="formLeft"><?php echo $lang['Edit_Listing_Date']; ?></td>
        <td class="formRight">
      		<input type="text" name="publish_date" id="publish_date" maxlength="25" size="25" value="<?php echo $Post["publish_date"]; ?>" readonly /> <a href="javascript:NewCssCal('publish_date','yyyymmdd','dropdown',true,24,false)"><img src="images/cal.gif" width="16" height="16" alt="Pick a date" border="0" ></a>
        </td>
      </tr>
      <tr>
      	<td class="formLeft"><?php echo $lang['Edit_Listing_Comments']; ?></td>
      	<td class="formRight"><?php echo $count["count(*)"]; ?> (<a href="admin.php?act=comments&post_id=<?php echo $Post["id"]; ?>"><?php echo $lang['Edit_Listing_Comments_view']; ?></a>)</td>
      </tr>
      <tr>
      	<td class="formLeft"><?php echo $lang['Edit_Listing_Status']; ?></td>
      	<td class="formRight">
        <select name="status">
          <option value="Posted"<?php if ($Post["status"]=='Posted') echo ' selected="selected"'; ?>><?php echo $lang['Edit_Listing_Posted']; ?></option>
          <option value="Hidden"<?php if ($Post["status"]=='Hidden') echo ' selected="selected"'; ?>><?php echo $lang['Edit_Listing_Hidden']; ?></option>
        </select>
      	</td>
      </tr>
      <tr>
        <td><?php echo $lang['Edit_Listing_Category']; ?> </td>
        <td>
            <select name="cat_id">
                <option value="0">---------</option>
                <?php
                $sql = "SELECT * FROM ".$TABLE["Categories"]." ORDER BY cat_name ASC";
                $sql_result = sql_result($sql);
                if (mysqli_num_rows($sql_result)>0) {
                    while ($Cat = mysqli_fetch_assoc($sql_result)) {
                        ?>
                        <option value="<?php echo $Cat["id"]; ?>"<?php if($Cat["id"]==$Post["cat_id"]) echo ' selected="selected"'; ?>><?php echo ReadDB($Cat["cat_name"]); ?></option>
                    <?php
                    }
                }
                ?>
            </select>
        </td>
      </tr> 
      
      <tr>
        <td class="formLeft"><?php echo $lang['Edit_Listing_Title']; ?></td>
        <td class="formRight"><input class="input_post" type="text" name="post_title" value="<?php echo $Post["post_title"]; ?>" /></td>
      </tr>    
      <tr>
        <td class="formLeft"><?php echo $lang['Edit_Listing_Image']; ?></td>
        <td class="formRight">
        <?php if(ReadDB($Post["image"]) != "") { ?>
			<img src="<?php echo $CONFIG["upload_folder"].ReadDB($Post["image"]); ?>" border="0" width="160" /> 			&nbsp;&nbsp;<a href="<?php $_SERVER["PHP_SELF"]; ?>?act=delImage&id=<?php echo $Post["id"]; ?>"><?php echo $lang['Edit_Listing_delete']; ?></a><br /> 
            <?php echo $lang['Edit_Listing_If_you_upload']; ?> <br />
            <?php } ?>
          	<input type="file" name="image" size="40" />
            
            &nbsp;&nbsp;&nbsp;Show it on the actual post:
        	<select name="imgpos">
                <option value="top"<?php if($Post["imgpos"]=='top') echo ' selected="selected"'; ?>>on top</option>
                <option value="bottom"<?php if($Post["imgpos"]=='bottom') echo ' selected="selected"'; ?>>at the bottom</option>
            	<option value="none"<?php if($Post["imgpos"]=='none') echo ' selected="selected"'; ?>>none</option>
            </select>
            
        </td>
      </tr>  
      <tr>
        <td class="formLeft" valign="top"><?php echo $lang['Edit_Listing_Message']; ?></td>
        <td class="formRight">
            <textarea name="post_text" id="post_text" class="post_text"><?php echo $Post["post_text"]; ?></textarea>
            <script type="text/javascript">
				CKEDITOR.replace( 'post_text',
                {	
					
					<?php if(isset($Options['htmleditor']) and $Options['htmleditor']=="plug") { ?>
					extraPlugins: 'imageuploader,youtube,slimbox,oembed,widget,lineutils,codesnippet,pastecode',
					filebrowserUploadUrl : 'ckeditor/plugins/imageuploader/fileupload.php',
					<?php } else { ?>
					filebrowserBrowseUrl : 'ckeditor/kcfinder/browse.php?opener=ckeditor&type=files',
                    filebrowserImageBrowseUrl : 'ckeditor/kcfinder/browse.php?opener=ckeditor&type=images',
                    filebrowserFlashBrowseUrl : 'ckeditor/kcfinder/browse.php?opener=ckeditor&type=flash',
					filebrowserUploadUrl  :'ckeditor/kcfinder/upload.php?opener=ckeditor&type=files',
					filebrowserImageUploadUrl : 'ckeditor/kcfinder/upload.php?opener=ckeditor&type=images',
					filebrowserFlashUploadUrl : 'ckeditor/kcfinder/upload.php?opener=ckeditor&type=flash',
					<?php } ?>		
					height: 400, width: '98%'

				});
			</script>  
        </td>
      </tr>
      <tr>
        <td class="formLeft"><?php echo $lang['Edit_Listing_CharNum']; ?></td>
        <td class="formRight">
        	<input type="text" name="post_limit" size="4" value="<?php if(isset($Post["post_limit"])) echo $Post["post_limit"]; ?>" onkeypress='return isNumberKey(event)' />
            <sub><?php echo $lang['Edit_Listing_CharNum_note']; ?></sub>
        </td>
      </tr>
      <tr>
        <td class="formLeft" valign="top">
			<?php echo $lang['Edit_Listing_Tags']; ?><br>
        	<span style="font-size:12px;"><?php echo $lang['Edit_Listing_Tags_descr']; ?></span>
        </td>
        <td class="formRight">   
        	<input class="input_post" type="text" name="post_tags" value="<?php echo $Post["post_tags"]; ?>"/>
        </td>
      </tr>
      <tr>
      	<td class="formLeft"><?php echo $lang['Edit_Listing_Comments_al']; ?></td>
      	<td class="formRight"><input name="post_comments" type="checkbox" value="true"<?php if($Post["post_comments"]=='true') echo ' checked="checked"'; ?> /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td class="formRight"><input name="submit3" type="submit" value="<?php echo $lang['Edit_Listing_Update']; ?>" class="submitButton" /> &nbsp; &nbsp; &nbsp; &nbsp; 
        	<input name="updatepreview" type="submit" value="<?php echo $lang['Edit_Listing_Update_View']; ?>" class="submitButton" /></td>
      </tr>
  	</table>
	</form>

<?php 
} elseif ($_REQUEST["act"]=='viewPost') {
	
	$sql = "SELECT * FROM ".$TABLE["Options"];
	$sql_result = sql_result($sql);
	$Options = mysqli_fetch_assoc($sql_result);
	mysqli_free_result($sql_result);
	$OptionsVis = unserialize($Options['visual']);
	$OptionsLang = unserialize($Options['language']);
	
	$sql = "SELECT * FROM ".$TABLE["Posts"]." WHERE `id`='".SafetyDB($_REQUEST["id"])."'";
	$sql_result = sql_result($sql);
	$Post = mysqli_fetch_assoc($sql_result);
	mysqli_free_result($sql_result);
	
	// fetch post category
	$sqlCat   = "SELECT * FROM ".$TABLE["Categories"]." WHERE `id`='".$Post["cat_id"]."'";
	$sql_resultCat = sql_result($sqlCat);
	$Cat = mysqli_fetch_array($sql_resultCat);
	
	if(!function_exists('lang_date')){ 
		function lang_date($subject) {	
			$search  = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
			
			$replace = array(
							ReadDB($GLOBALS['OptionsLang']['January']), 
							ReadDB($GLOBALS['OptionsLang']['February']), 
							ReadDB($GLOBALS['OptionsLang']['March']), 
							ReadDB($GLOBALS['OptionsLang']['April']), 
							ReadDB($GLOBALS['OptionsLang']['May']), 
							ReadDB($GLOBALS['OptionsLang']['June']), 
							ReadDB($GLOBALS['OptionsLang']['July']), 
							ReadDB($GLOBALS['OptionsLang']['August']), 
							ReadDB($GLOBALS['OptionsLang']['September']), 
							ReadDB($GLOBALS['OptionsLang']['October']), 
							ReadDB($GLOBALS['OptionsLang']['November']), 
							ReadDB($GLOBALS['OptionsLang']['December']), 
							ReadDB($GLOBALS['OptionsLang']['Monday']), 
							ReadDB($GLOBALS['OptionsLang']['Tuesday']), 
							ReadDB($GLOBALS['OptionsLang']['Wednesday']), 
							ReadDB($GLOBALS['OptionsLang']['Thursday']), 
							ReadDB($GLOBALS['OptionsLang']['Friday']), 
							ReadDB($GLOBALS['OptionsLang']['Saturday']), 
							ReadDB($GLOBALS['OptionsLang']['Sunday'])
							);
		
			$lang_date = str_replace($search, $replace, $subject);
			return $lang_date;
		}
	}
?>
<link href="styles/bootstrap.css" rel='stylesheet' type='text/css' />
<link rel="stylesheet" href="styles/font-awesome/css/font-awesome.min.css">
<?php include("styles/css_front_end.php"); ?>
<link href="styles/style.css" rel='stylesheet' type='text/css' />
<script type="text/javascript" src="include/textsizer.js"></script>
<script src="include/jquery-2.1.1.min.js"></script>

<div style="clear:both;padding-left:40px;padding-top:10px;padding-bottom:10px;"><a href="admin.php?act=editPost&id=<?php echo ReadDB($Post['id']); ?>"><?php echo $lang['Preview_Edit_Item']; ?></a></div>

<div style="background-color:<?php echo $OptionsVis["gen_bgr_color"];?>;">
<div class="content-wrapper-sbp">
  <div class="container-sbp">
	<div class="content-grids">
    
    	<div class="" style="padding: 0 4%;">             

            <div class="single-grid">
            
                <?php if(ReadDB($Post["image"])!='' and (!isset($Post["imgpos"]) or ReadDB($Post["imgpos"])=='' or ReadDB($Post["imgpos"])=='top')) { ?>
                <img class="single-image-top" src="<?php echo $CONFIG["full_url"].$CONFIG["upload_folder"].ReadDB($Post["image"]); ?>" alt="<?php echo ReadHTML($Post["post_title"]); ?>" />
                <?php } ?>
                
                <h4><?php echo ReadDB($Post["post_title"]); ?></h4> 
                
                <!-- post date --> 
                <?php if($OptionsVis["show_date"]!='no' or $OptionsVis["show_aa"]!='no' or (isset($Post["cat_id"]) and $Post["cat_id"]>0) or $Post['post_comments']=='true') { ?>   
                <div class="date_style">
                	
                    <?php if($Cat["cat_name"]!="") echo $Cat["cat_name"]." &nbsp; / &nbsp; "; ?>
                    <?php if($OptionsVis["show_date"]!='no') { ?>  
                    <?php echo lang_date(date($OptionsVis["date_format"],strtotime($Post["publish_date"]))); ?> 
                    <?php if($OptionsVis["showing_time"]!='') echo date($OptionsVis["showing_time"],strtotime($Post["publish_date"])); ?>
                    <?php } ?>
                    
                    
                    <?php 
					if($Post['post_comments']=='true') { // if Allow comments
                    $sqlNum = "SELECT * FROM ".$TABLE["Comments"]." WHERE post_id='".$Post["id"]."' AND status='Approved'";
                    $sql_resultNum = sql_result($sqlNum);
                    $numComments = mysqli_num_rows($sql_resultNum);
                    ?>
                    
                     
                    &nbsp; / &nbsp; <a href="#comments"><?php echo $numComments; ?> <?php echo $OptionsLang["Comments_link"]; ?></a>
                    <?php } ?>
                    
                    
                    <?php if($OptionsVis["show_aa"]!='no') { ?>
                    &nbsp;&nbsp;/&nbsp;&nbsp;<a style="text-decoration:none;color:#999;font-size:<?php echo $OptionsVis["date_size"];?>;" href="javascript:ts('post-text',+1)">A<sup>+</sup></a> | <a style="text-decoration:none;color:#999;font-size:<?php echo $OptionsVis["date_size"];?>;" href="javascript:ts('post-text',-1)">a<sup>-</sup></a>
                    <?php } ?>
                    
                    
                </div>
                <?php } ?>
                 
                <?php if($Options["showshare"]=='yes') { ?>    
                <div class="share_buttons">
                	<!-- AddToAny BEGIN -->
                    <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
                    <a class="a2a_dd" href="https://www.addtoany.com/share"></a>
                    <a class="a2a_button_facebook"></a>
                    <a class="a2a_button_twitter"></a>
                    <a class="a2a_button_google_plus"></a>
                    <a class="a2a_button_pinterest"></a>
                    </div>
                    <script>
                    var a2a_config = a2a_config || {};
                    a2a_config.locale = "bg";
                    a2a_config.num_services = 6;
                    a2a_config.color_main = "D7E5ED";
                    a2a_config.color_border = "AECADB";
                    a2a_config.color_link_text = "333333";
                    a2a_config.color_link_text_hover = "333333";
                    a2a_config.prioritize = ["facebook", "twitter", "pinterest", "google_plus", "email"];
                    </script>
                    <script async src="https://static.addtoany.com/menu/page.js"></script>
                    <!-- AddToAny END -->               
                </div>
                <?php } ?>
                
                <div class="dist_date_text"></div>
                
                <div id="post-text" class="post-text"><?php echo $Post["post_text"]; ?></div>
                
                <?php if(ReadDB($Post["image"])!='' and ReadDB($Post["imgpos"])=='bottom') { ?>
                <img src="<?php echo $CONFIG["full_url"].$CONFIG["upload_folder"].ReadDB($Post["image"]); ?>" alt="<?php echo ReadHTML($Post["post_title"]); ?>" />
                <?php } ?>
                
                <div class="post-text-to-tags"></div>
                
                <div class="clearboth"></div> 
            </div>
		</div>
    
    </div>
  </div>
</div>
</div>


<?php
} elseif ($_REQUEST["act"]=='cats') {

    if(isset($_REQUEST["p"]) and $_REQUEST["p"]!='') {
        $pageNum = (int) SafetyDB(urldecode($_REQUEST["p"]));
        if($pageNum<=0) $pageNum = 1;
    } else {
        $pageNum = 1;
    }

    $orderByArr = array("cat_name");
    if(isset($_REQUEST["orderBy"]) and $_REQUEST["orderBy"]!='' and in_array($_REQUEST["orderBy"], $orderByArr)) {
    	$orderBy = $_REQUEST["orderBy"];
    } else {
    	$orderBy = "cat_name";
    }

    $orderTypeArr = array("DESC", "ASC");
    if(isset($_REQUEST["orderType"]) and $_REQUEST["orderType"]!='' and in_array($_REQUEST["orderType"], $orderTypeArr)) {
    	$orderType = $_REQUEST["orderType"];
    } else {
    	$orderType = "ASC";
    }
    if ($orderType == 'DESC') { $norderType = 'ASC'; } else { $norderType = 'DESC'; }
    ?>
    <div class="pageDescr"><?php echo $lang['Category_Below_is_a_list']; ?></div>

    <table border="0" cellspacing="0" cellpadding="8" class="allTables">
        <tr>
            <td width="33%" class="headlist"><a href="admin.php?act=cats&orderType=<?php echo $norderType; ?>&orderBy=cat_name"><?php echo $lang['Category_Category']; ?></a></td>
        	<td width="33%" class="headlist"><?php echo $lang['Category_Put_this_category']; ?></td>
            <td class="headlist" colspan="2">&nbsp;</td>
        </tr>

        <?php
        $sql   = "SELECT count(*) as total FROM ".$TABLE["Categories"];
        $sql_result = sql_result($sql);
        $row   = mysqli_fetch_array($sql_result);
        mysqli_free_result($sql_result);
        $count = $row["total"];
        $pages = ceil($count/50);

        $sql = "SELECT * FROM ".$TABLE["Categories"]."
			ORDER BY " . $orderBy . " " . $orderType."
			LIMIT " . ($pageNum-1)*50 . ",50";
        $sql_result = sql_result($sql);

        if (mysqli_num_rows($sql_result)>0) {
            while ($Cat = mysqli_fetch_assoc($sql_result)) {
                ?>
                <tr>
                    <td class="bodylist"><?php echo ReadDB($Cat["cat_name"]); ?></td>
					<td class="bodylist"><a href='admin.php?act=HTML_Cat&id=<?php echo $Cat["id"]; ?>'><?php echo $lang['Category_Copy_the_code']; ?></a></td>
                    <td class="bodylistAct"><a href='admin.php?act=editCat&id=<?php echo $Cat["id"]; ?>' title="Edit"><img class="act" src="images/edit.png" alt="Edit" /></a></td>
                    <td class="bodylistAct"><a class="delete" href="admin.php?act=delCat&id=<?php echo $Cat["id"]; ?>" onclick="return confirm('Are you sure you want to delete it?');" title="DELETE"><img class="act" src="images/delete.png" alt="DELETE" /></a></td>

                </tr>
                <?php 
            }
        } else {
            ?>
            <tr>
                <td colspan="8" style="border-bottom:1px solid #CCCCCC"><?php echo $lang['Category_No_Categories']; ?></td>
            </tr>
        <?php
        }
        ?>

        <?php
        if ($pages>1) {
            ?>
            <tr>
                <td colspan="8" class="bottomlist"><div class='paging'><?php echo $lang['Category_Page']; ?> </div>
                    <?php
                    for($i=1;$i<=$pages;$i++){
                        if($i == $pageNum ) echo "<div class='paging'>" .$i. "</div>";
                        else echo "<a href='admin.php?act=cats&p=".$i."' class='paging'>".$i."</a>";
                        echo "&nbsp; ";
                    }
                    ?>
                </td>
            </tr>
        <?php
        }
        ?>
    </table>


<?php
} elseif ($_REQUEST["act"]=='newCat') {
    ?>
    <form action="admin.php" method="post" name="form">
        <input type="hidden" name="act" value="addCat" />
        <div class="pageDescr"><?php echo $lang['Category_To_create_Category']; ?></div>
        <table border="0" cellspacing="0" cellpadding="8" class="fieldTables">
            <tr>
                <td colspan="2" valign="top" class="headlist"><?php echo $lang['Category_Create_Category']; ?></td>
            </tr>

            <tr>
                <td class="formLeft"><?php echo $lang['Category_Category_name']; ?></td>
                <td><input type="text" name="cat_name" size="40" maxlength="250" /></td>

            </tr>

            <tr>
                <td>&nbsp;</td>
                <td><input name="submit" type="submit" value="<?php echo $lang['Category_Create_Category_but']; ?>" class="submitButton" /></td>
            </tr>
        </table>
    </form>

<?php
} elseif ($_REQUEST["act"]=='editCat') {
    $sql = "SELECT * FROM ".$TABLE["Categories"]." WHERE id='".$_REQUEST["id"]."'";
    $sql_result = sql_result($sql);
    $Cat = mysqli_fetch_assoc($sql_result);
?>
    <form action="admin.php" method="post" name="form">
        <input type="hidden" name="act" value="updateCat" />
        <input type="hidden" name="id" value="<?php echo $Cat["id"]; ?>" />
        <div class="pageDescr"><?php echo $lang['Category_change_details']; ?></div>
        <table border="0" cellspacing="0" cellpadding="8" class="fieldTables">
            <tr>
                <td colspan="2" valign="top" class="headlist"><?php echo $lang['Category_Edit_Category']; ?></td>
            </tr>

            <tr>
                <td class="formLeft"><?php echo $lang['Category_Category_name_edit']; ?></td>
                <td><input type="text" name="cat_name" size="40" maxlength="250" value="<?php echo ReadHTML($Cat["cat_name"]); ?>" /></td>
            </tr>

            <tr>
                <td>&nbsp;</td>
                <td>
                    <input name="submit" type="submit" value="<?php echo $lang['Category_Update_Category']; ?>" class="submitButton" />
                </td>
            </tr>
        </table>
    </form>


<?php 
} elseif ($_REQUEST["act"]=='HTML_Cat') { 
	$sql = "SELECT * FROM ".$TABLE["Categories"]." WHERE id='".$_REQUEST["id"]."'";
	$sql_result = sql_result($sql);
	$Cat = mysqli_fetch_assoc($sql_result);	
?>
	
    <div style="clear:both; padding-top: 20px;">
    
    <div class="pageDescr">There is one easy way to put <strong>'<?php echo $Cat['cat_name']; ?>'</strong> category on your webpage.</div>
        
    <table border="0" cellspacing="0" cellpadding="8" class="allTables">
    
      <tr>
        <td class="copycode"><strong>Using PHP include()</strong> - you can use a PHP include() in any of your PHP pages. Edit your .php page and put the code below where you want the blog to be.</td>
      </tr>
      
      <tr>
        <td class="putonwebpage">        	
        	<div class="divCode">&lt;?php $_REQUEST['cat_id']=<?php echo $_REQUEST["id"]; ?>; $_REQUEST['hide_cat']='yes'; include(&quot;<?php echo $CONFIG["server_path"]; ?>blog.php&quot;); ?&gt; </div>     
        </td>
      </tr>
      
      <tr>
      	<td>
        	At the top of the php page (first line) you should put this line of code too so captcha image verification can work on the comment form.
        </td>
      </tr>
      
      <tr>
        <td class="putonwebpage">        	
        	<div class="divCode">&lt;?php session_start(); ?&gt;</div>     
        </td>
      </tr>
            
    </table>
    
    </div>




<?php 
} elseif ($_REQUEST["act"]=='comments') {
	
	if(isset($_REQUEST["search"]) and $_REQUEST["search"]!='') {
		$_REQUEST["search"] = htmlspecialchars(urldecode($_REQUEST["search"]), ENT_QUOTES);
	} else { 
		$_REQUEST["search"] = ''; 
	}
	if(!isset($_REQUEST["orderBy"]))  $_REQUEST["orderBy"] = ''; 
	if(!isset($_REQUEST["orderType"])) $_REQUEST["orderType"] = ''; 
	if(!isset($_REQUEST["post_id"])) $_REQUEST["post_id"] = ''; 
	
	$sql = "SELECT * FROM ".$TABLE["Options"];
	$sql_result = sql_result($sql);
	$Options = mysqli_fetch_assoc($sql_result);
	
    if(isset($_REQUEST["p"]) and $_REQUEST["p"]!='') { 
		$pageNum = (int) SafetyDB(urldecode($_REQUEST["p"]));
		if($pageNum<=0) $pageNum = 1;
	} else { 
		$pageNum = 1;
	}
	
    $orderByArr = array("publish_date", "name", "status");
	if(isset($_REQUEST["orderBy"]) and $_REQUEST["orderBy"]!='' and in_array($_REQUEST["orderBy"], $orderByArr)) { 
		$orderBy = $_REQUEST["orderBy"];
	} else { 
		$orderBy = "publish_date";
	}	
	
    $orderTypeArr = array("DESC", "ASC");	
    if(isset($_REQUEST["orderType"]) and $_REQUEST["orderType"]!='' and in_array($_REQUEST["orderType"], $orderTypeArr)) { 
		$orderType = $_REQUEST["orderType"];
	} else {
		$orderType = "DESC";
	}
	if ($orderType == 'DESC') { $norderType = 'ASC'; } else { $norderType = 'DESC'; }
?>

	<div class="pageDescr"><?php echo $lang['Comments_Dashboard']; ?></div>
    
    <div class="searchForm">    
    <form action="admin.php?act=comments&post_id=<?php echo $_REQUEST["post_id"]; ?>" method="post" name="form" class="formStyle">
      <input type="text" name="search" value="<?php echo $_REQUEST["search"]; ?>" class="searchfield" placeholder="enter poster Name or Email" />
      <input type="submit" value="<?php echo $lang['Comments_Search_Button']; ?>" class="submitButton" />
    </form>
	</div>
    
	<?php
	if (isset($_REQUEST["post_id"]) and $_REQUEST["post_id"]>0) {
	  $sql = "SELECT * FROM ".$TABLE["Posts"]." WHERE id='".$_REQUEST["post_id"]."'";
	  $sql_resultP = sql_result($sql);
	  $Post = mysqli_fetch_assoc($sql_resultP);
	  mysqli_free_result($sql_resultP);
	?>
	<div class="pageDescr"><?php echo $lang['Comments_Dashboard2_1']; ?> "<?php echo $Post["post_title"]; ?>". <a href="admin.php?act=comments"><?php echo $lang['Comments_Dashboard2_2']; ?></a>.</div>
	<?php	
    }
    ?>
    <form action="admin.php" method="post" name="formdelcomm" class="formStyle">
    <input type="hidden" name="act2" value="delCommBulk" />
	<table border="0" cellspacing="0" cellpadding="8" class="allTables">
    
      <tr>
      	<td width="18%" class="headlist"><a href="admin.php?act=comments&post_id=<?php echo $_REQUEST["post_id"]; ?>&orderType=<?php echo $norderType; ?>&search=<?php echo urlencode($_REQUEST["search"]); ?>&orderBy=publish_date"><?php echo $lang['Comments_Date']; ?></a></td>
      	<td width="15%" class="headlist"><a href="admin.php?act=comments&post_id=<?php echo $_REQUEST["post_id"]; ?>&orderType=<?php echo $norderType; ?>&search=<?php echo urlencode($_REQUEST["search"]); ?>&orderBy=name"><?php echo $lang['Comments_Name']; ?></a></td>
      	<td width="14%" class="headlist"><a href="admin.php?act=comments&post_id=<?php echo $_REQUEST["post_id"]; ?>&orderType=<?php echo $norderType; ?>&search=<?php echo urlencode($_REQUEST["search"]); ?>&orderBy=status"><?php echo $lang['Comments_Status']; ?></a></td>
      	<td class="headlist"><?php echo $lang['Comments_on_post']; ?></td>        
        <td width="17%" class="headlist"><?php echo $lang['Comments_IP_address']; ?></td>
        <script language="JavaScript">		
		function CheckedAll(){    
		 if (document.getElementById('ChkAll').checked) {
			 for(i=0; i<document.getElementsByTagName('input').length;i++){
			 document.getElementsByTagName('input')[i].checked = true;
			 }
		 }
		 else {
			 for(i=0; i<document.getElementsByTagName('input').length;i++){
			  document.getElementsByTagName('input')[i].checked = false;
			 }
		 }
		}
		</script>
      	<td colspan="3" class="headlistComm"><label><input id="ChkAll" type="checkbox" onchange="javascript:CheckedAll();" /><span><?php echo $lang['Comments_SelectAll']; ?></span></label></td>
      </tr>
      
    <?php 
	$search = '';
	if(isset($_REQUEST["search"]) and ($_REQUEST["search"]!="")) {
		$find = SafetyDB($_REQUEST["search"]);
		$search .= "WHERE (name LIKE '%".$find."%' OR email LIKE '%".$find."%' OR comment LIKE '%".$find."%')";
		if (isset($_REQUEST["post_id"]) and $_REQUEST["post_id"]>0) $search .= " AND post_id='".SafetyDB($_REQUEST["post_id"])."'";
	} else {
		if (isset($_REQUEST["post_id"]) and $_REQUEST["post_id"]>0) {
			$search .= "WHERE post_id='".SafetyDB($_REQUEST["post_id"])."'";
		} 
	}
	
	$sql   = "SELECT count(*) as total FROM ".$TABLE["Comments"]." ".$search;
	$sql_result = sql_result($sql);
	$row   = mysqli_fetch_array($sql_result);
	$count = $row["total"];
	$pages = ceil($count/100);

	$sql = "SELECT * FROM ".$TABLE["Comments"]." ".$search." 
			ORDER BY " . $orderBy . " " . $orderType."  
			LIMIT " . ($pageNum-1)*100 . ",100";
	$sql_result = sql_result($sql);
	
	if (mysqli_num_rows($sql_result)>0) {
		
		while ($Comments = mysqli_fetch_assoc($sql_result)) {
			$sqlP = "SELECT * FROM ".$TABLE["Posts"]." WHERE id='".$Comments["post_id"]."'";
			$sql_resultP = sql_result($sqlP);
			$Post = mysqli_fetch_assoc($sql_resultP);
	?>
      <tr>
        <td class="bodylist"><?php echo admin_date($Comments["publish_date"]); ?></td>
        <td class="bodylist"><?php echo ReadHTML($Comments["name"]); ?></td>
        <td class="bodylist">
            <select name="status" onchange="window.location.href='admin.php?status='+this.value+'&act=change_status_comm&id=<?php echo $Comments["id"]; ?>'">
				<option value="Approved" <?php if($Comments['status']=='Approved') echo "selected='selected'"; ?>><?php echo $lang['Comments_Approved']; ?></option>
				<option value="Not approved" <?php if($Comments['status']=='Not approved') echo "selected='selected'"; ?>><?php echo $lang['Comments_Not_approved']; ?></option>
            </select>		
        </td>
        <td class="bodylist"><?php echo cutStrHTML($Post["post_title"],0,80); ?></td>
        
        
        <?php 
		$IPAllowed = true;
		$BannedIPs = explode(",", ReadDB($Options["ban_ips"]));
		if (count($BannedIPs)>0) {
		  $checkIP = strtolower(ReadDB($Comments["ipaddress"]));
		  for($i=0;$i<count($BannedIPs);$i++){
			  $banIP = trim($BannedIPs[$i]);
			  if (trim($BannedIPs[$i])<>'') {
				  if(preg_match("/".$banIP."/i", $checkIP)){
					  $IPAllowed = false;
					  break;
				  }
			  }
		  }
		}
		?>        
        <td class="bodylist"><?php echo ReadDB($Comments["ipaddress"]); ?> - <?php if($IPAllowed == false) { ?><span style="color:#F00">Banned</span><?php } else { ?><a class="view" href='admin.php?act=BanIP&ip_addr=<?php echo ReadDB($Comments["ipaddress"]); ?>' onclick="return confirm('Are you sure you want to ban IP - <?php echo ReadDB($Comments["ipaddress"]); ?> ?');">Ban</a><?php } ?></td>
        
        <td class="bodylistAct"><input name="delCommBulkArr[]" type="checkbox" value="<?php echo $Comments["id"]; ?>" /></td>
        
        <td class="bodylistAct"><a href='admin.php?act=editComment&id=<?php echo $Comments["id"]; ?>&search=<?php if(isset($_REQUEST["search"])) echo $_REQUEST["search"]; ?>&post_id=<?php if(isset($_REQUEST["post_id"])) echo $_REQUEST["post_id"]; ?>&p=<?php if(isset($_REQUEST["p"])) echo $_REQUEST["p"]; ?>' title="Edit"><img class="act" src="images/edit.png" alt="Edit" /></a></td>
        <td class="bodylistAct"><a class="delete" href="admin.php?act=delComment&id=<?php echo $Comments["id"]; ?>&search=<?php if(isset($_REQUEST["search"])) echo $_REQUEST["search"]; ?>&post_id=<?php if(isset($_REQUEST["post_id"])) echo $_REQUEST["post_id"]; ?>&p=<?php if(isset($_REQUEST["p"])) echo $_REQUEST["p"]; ?>" onclick="return confirm('Are you sure you want to delete it?');" title="DELETE"><img class="act" src="images/delete.png" alt="DELETE" /></a></td>
      </tr>
    <?php 
		}
		mysqli_free_result($sql_result);
	} else {
	?>
      <tr>
      	<td colspan="8" class="borderBottomList"><?php echo $lang['Comments_No_Comments']; ?></td>        
      </tr>      
    <?php	
	}
	?>

	<?php
    if ($pages>0) {
    ?>
      <tr>
    	<td colspan="5" class="bottomlist"><div class='paging'><?php echo $lang['Comments_Page']; ?> </div> 
		<?php
        for($i=1;$i<=$pages;$i++){ 
            if($i == $pageNum ) echo "<div class='paging'>" .$i. "</div>";
            else echo "<a href='admin.php?act=comments&amp;p=".$i."&amp;search=".$_REQUEST["search"]."&amp;post_id=".$_REQUEST["post_id"]."&amp;orderBy=".$_REQUEST["orderBy"]."&amp;orderType=".$_REQUEST["orderType"]."' class='paging'>".$i."</a>"; 
            echo "&nbsp; ";
        } 
        ?>
		</td>
        <td colspan="3" class="bottomlist">
        	<div style="text-align:left;"><input class="delete_comm" name="submit" type="submit" value="<?php echo $lang['Comments_DELETE']; ?>" onclick="return confirm('Are you sure you want to delete selected comments?');" /></div>
      	</td>
  	  </tr>
	<?php
    }
    ?>
  </table>
  </form>


<?php 
} elseif ($_REQUEST["act"]=='editComment') {
	$sql = "SELECT * FROM ".$TABLE["Comments"]." WHERE id='".$_REQUEST["id"]."'";
	$sql_result = sql_result($sql);
	$Comments = mysqli_fetch_assoc($sql_result);
	mysqli_free_result($sql_result);
	
	$sqlP = "SELECT * FROM ".$TABLE["Posts"]." WHERE id='".$Comments["post_id"]."'";
	$sql_resultP = sql_result($sqlP);
	$Post = mysqli_fetch_assoc($sql_resultP);
?>


    <form action="admin.php" method="post" style="margin:0px; padding:0px" name="form">
    <input type="hidden" name="act" value="updateComment" />
    <input type="hidden" name="id" value="<?php echo $Comments["id"]; ?>" />
  	<input type="hidden" name="p" value="<?php echo $_REQUEST["p"]; ?>" />
    
    <div class="pageDescr"><a href="admin.php?act=comments&search=<?php echo $_REQUEST["search"]; ?>&post_id=<?php echo $_REQUEST["post_id"]; ?>"><?php echo $lang['Edit_Comment_Back']; ?></a></div>    

	<table border="0" cellspacing="0" cellpadding="8" class="fieldTables">
  	  <tr>
      	<td colspan="2" valign="top" class="headlist"><?php echo $lang['Edit_Comment_Header']; ?></td>
      </tr>
      <tr>
        <td class="formLeft"><?php echo $lang['Edit_Comment_Published_on']; ?></td>
        <td><?php echo admin_date($Comments["publish_date"]); ?></td>
      </tr>
      <tr>
        <td class="formLeft"><?php echo $lang['Edit_Comment_IP_address']; ?></td>
        <td><?php echo ReadDB($Comments["ipaddress"]); ?></td>
      </tr>
      <tr>
      	<td class="formLeft"><?php echo $lang['Edit_Comment_Status']; ?></td>
      	<td>
        <select name="status" id="status">
          <option value="Not approved"<?php if ($Comments["status"]=='Not approved') echo ' selected="selected"'; ?>><?php echo $lang['Edit_Comment_Not_approved']; ?></option>
          <option value="Approved"<?php if ($Comments["status"]=='Approved') echo ' selected="selected"'; ?>><?php echo $lang['Edit_Comment_Approved']; ?></option>
        </select>
      	</td>
      </tr>
      <tr>
        <td class="formLeft"><?php echo $lang['Edit_Comment_Name']; ?></td>
        <td><input class="input_post" name="name" type="text" maxlength="250" value="<?php echo ReadHTML($Comments["name"]); ?>" /></td>
	  </tr>
  	  <tr>
        <td class="formLeft"><?php echo $lang['Edit_Comment_Email']; ?></td>
        <td><input class="input_post" name="email" type="text" maxlength="250" value="<?php echo ReadHTML($Comments["email"]); ?>" /></td>
      </tr>
  	  <tr>
    	<td class="formLeft" valign="top"><?php echo $lang['Edit_Comment_Comment']; ?></td>
    	<td><textarea class="input_post" name="comment" rows="10"><?php echo $Comments["comment"]; ?></textarea></td>
  	  </tr>
      <tr>
        <td class="formLeft"><?php echo $lang['Edit_Comment_on_post']; ?></td>
        <td><a href="<?php if(trim($Options["items_link"])!=''){ echo ReadDB($Options["items_link"])."?pid=".$Post['id']; } else { echo $CONFIG["full_url"]."preview.php?pid=".$Post["id"]; } ?>" target="_blank"><strong><?php echo $Post["post_title"]; ?></strong></a></td>
      </tr>
  	  <tr>
        <td class="formLeft" align="left">&nbsp;</td>
        <td>
          <input type="submit" name="button2" id="button2" value="<?php echo $lang['Edit_Comment_Update']; ?>" class="submitButton" />
        </td>
  	  </tr>
    </table>
    </form>



<?php
} elseif ($_REQUEST["act"]=='admin_options') {
	$sql = "SELECT * FROM ".$TABLE["Options"];
	$sql_result = sql_result($sql);
	$Options = mysqli_fetch_assoc($sql_result);
	mysqli_free_result($sql_result);
?>
	
    <div class="paddingtop"></div>
    
    <form action="admin.php" method="post" name="frm">
	<input type="hidden" name="act" value="updateOptionsAdmin" />

    <table border="0" cellspacing="0" cellpadding="8" class="allTables">
      <tr>
        <td colspan="3" class="headlist">Administrator options</td>
      </tr>
      <tr>
        <td width="45%" class="left_top">Administrator email:<br />
        	/<span style="font-size:11px; font-style:italic;">all new posts/comments notification emails will be sent to this email address/</span>
        </td>
        <td class="left_top">
          <input class="input_opt" name="email" type="text" value="<?php echo ReadDB($Options["email"]); ?>" />
        </td>
      </tr>
      <tr>
        <td class="left_top">Number of posts per page: </td>
        <td class="left_top"><input name="per_page" type="text" size="3" value="<?php echo ReadDB($Options["per_page"]); ?>" /></td>
      </tr>    
      <tr>
        <td class="left_top">Default number of characters that will appear on the homepage: <br />
        	/<span style="font-size:11px; font-style:italic;">Default number of characters that will appear when create new post. The post will be cut out after this number and visitor will be able to click on "MORE" link and read the full post, write and read comments</span>/
		</td>
        <td class="left_top"><input name="post_limit" type="text" size="4" maxlength="6" value="<?php echo ReadDB($Options["post_limit"]); ?>" /> characters &nbsp; <sub>(leave blank if you don't need that limitation)</sub></td>
      </tr>
      <tr>
        <td class="left_top">Show share buttons next to date on the post:</td>
        <td class="left_top">
          <select name="showshare"> 
           	<option value="yes"<?php if ($Options["showshare"]=='yes') echo ' selected="selected"'; ?>>yes</option>       
           	<option value="no"<?php if ($Options["showshare"]=='no') echo ' selected="selected"'; ?>>no</option>
          </select>
          
          <!--- on the: 
          <select name="share_side"> 
           	<option value="right"<?php if ($Options["share_side"]=='right') echo ' selected="selected"'; ?>>right</option>       
           	<option value="left"<?php if ($Options["share_side"]=='left') echo ' selected="selected"'; ?>>left</option>
          </select>
          side --->
       </td>
      </tr>
      <tr>
        <td class="left_top">URL of the page where you placed the blog on your website: <br />
        	/<span style="font-size:11px; font-style:italic;">needed for RSS to be linked to the page</span>/
        </td>
        <td class="left_top">
        	<input class="input_opt" name="items_link" type="text" value="<?php echo ReadDB($Options["items_link"]); ?>" />
            <div style="padding-top:6px;font-size:11px;">for example http://www.yourwebsite.com/blogpage.php</div>
        </td>
      </tr>
      <tr>
        <td class="left_top">Set Default Time Zone:</td>
        <td class="left_top">
          <select name="time_zone"> 
           	<option value=""<?php if ($Options["time_zone"]=='') echo ' selected="selected"'; ?>>Server Time</option>
            <?php
			if(!function_exists('timezone_identifiers_list')){ 
				$o = timezone_list();
			} else {
				$o = get_timezones();
			}
			foreach($o as $timezone => $tz_label) {
			?>	
            	<option value='<?php echo $timezone; ?>'<?php if ($Options["time_zone"]==$timezone) echo ' selected="selected"'; ?>><?php echo $tz_label; ?></option>
            <?php 
			}
			?>  
          </select>
       </td>
      </tr>
      
      <tr>
        <td class="left_top">Text Editor image browser:</td>
        <td class="left_top">
          <select name="htmleditor">      
           <option value="ck"<?php if($Options["htmleditor"]=='ck') echo ' selected="selected"'; ?>>classic(recommended)</option>
           <option value="plug"<?php if($Options["htmleditor"]=='plug') echo ' selected="selected"'; ?>>modern</option>  
          </select>
       </td>
      </tr>
      
      <!--- <tr>
        <td class="left_top">Show right sidebar:</td>
        <td class="left_top">
          <select name="showrightbar"> 
           <option value="yes"<?php if ($Options["showrightbar"]=='yes') echo ' selected="selected"'; ?>>yes</option>       
           <option value="no"<?php if ($Options["showrightbar"]=='no') echo ' selected="selected"'; ?>>no</option>
          </select>
       </td> 
      </tr> ---> 
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit" type="submit" value="Save" class="submitButton" /></td>
      </tr>
    </table>
	</form>


<?php
} elseif ($_REQUEST["act"]=='post_options') {
	$sql = "SELECT * FROM ".$TABLE["Options"];
	$sql_result = sql_result($sql);
	$Options = mysqli_fetch_assoc($sql_result);	
	$Options["comm_req"] = unserialize($Options["comm_req"]);
	mysqli_free_result($sql_result);
?>
	
    <div class="paddingtop"></div>
    
    <form action="admin.php" method="post" name="frm">
	<input type="hidden" name="act" value="updateOptionsPost" />
    <table border="0" cellspacing="0" cellpadding="8" class="allTables">
      <tr>
        <td colspan="3" class="headlist">Comments options</td>
      </tr>
      <tr>
        <td width="45%" class="left_top">Approval:<br />
        	/<span style="font-size:11px; font-style:italic;">check if you want to approve comments before having them posted on the blog</span>/
        </td>
        <td class="left_top"><input name="approval" type="checkbox" value="true"<?php if ($Options["approval"]=='true') echo ' checked="checked"'; ?> /></td>
      </tr>
      <tr>
        <td class="left_top">Turn off comments by default when create a new post:</td>
        <td class="left_top">
          <select name="commentsoff">      
           <option value="no"<?php if ($Options["commentsoff"]=='no') echo ' selected="selected"'; ?>>no</option>
           <option value="yes"<?php if ($Options["commentsoff"]=='yes') echo ' selected="selected"'; ?>>yes</option>  
          </select>
       </td>
      </tr>
      <tr>
        <td class="left_top">Comments order:<br />
        	/<span style="font-size:11px; font-style:italic;">If you set 'New at the bottom', new comment will appear at the bottom of all comments.<br /> 
          	If you set 'New on top', new comment will appear on top of all comments.</span>/
        </td>
        <td class="left_top">
          <select name="comments_order">          
          <option value="AtBottom"<?php if ($Options["comments_order"]=='AtBottom') echo ' selected="selected"'; ?>>New at the bottom</option>
          <option value="OnTop"<?php if ($Options["comments_order"]=='OnTop') echo ' selected="selected"'; ?>>New on top</option>
        </select></td>
      </tr>
      <tr>
        <td class="left_top">Type of the Captcha Verification Code:</td>
        <td class="left_top">
          <select name="captcha"> 
          	<option value="phpcaptcha"<?php if ($Options["captcha"]=='phpcaptcha') echo ' selected="selected"'; ?>>PHP Captcha</option>
          	<option value="capmath"<?php if ($Options["captcha"]=='capmath') echo ' selected="selected"'; ?>>Mathematical Captcha</option>
          	<option value="cap"<?php if ($Options["captcha"]=='cap') echo ' selected="selected"'; ?>>Simple Captcha</option>
          	<option value="vsc"<?php if ($Options["captcha"]=='vsc') echo ' selected="selected"'; ?>>Very Simple Captcha</option>
            <option value="nocap"<?php if ($Options["captcha"]=='nocap') echo ' selected="selected"'; ?>>No Captcha(unsecured)</option>
          </select>
        </td>
      </tr>
      
      <tr>
        <td class="left_top_b">Make email field mandatory: </td>
        <td class="left_top_b">
            <input name="comm_req[]" type="checkbox" value="Email"<?php if(!empty($Options["comm_req"]) and in_array("Email", $Options["comm_req"])) echo ' checked="checked"'; ?> /> 
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit2" type="submit" value="Save" class="submitButton" /></td>
      </tr>
    </table>
    
    
    <table border="0" cellspacing="0" cellpadding="8" class="allTables">
      <tr>
        <td colspan="3" class="headlist">Create a list with banned IP addresses</td>
      </tr>
      <tr>
        <td width="45%" class="left_top">Make a list of IP addresses and the comments posted from any of these IP addresses will not be submitted.<br />
          <br />
          For example: 192.168.0.201, 185.168.539.71, 83.91.459.71<br /><br />
          You can block a group of IP addresses. For example if you want to block all IP addresses from 185.168.539.1 to 185.168.539.255, you should enter 185.168.539.
          <br /><br />
          /<span style="font-size:11px; font-style:italic;">Note that you can copy IP address from Comments List.</span>/
          </td>
        <td class="left_top"><textarea class="input_opt" name="ban_ips" id="ban_ips" rows="5"><?php echo ReadDB($Options["ban_ips"]); ?></textarea></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit5" type="submit" value="Save" class="submitButton" /></td>
      </tr>
    </table>
    
    
    <table border="0" cellspacing="0" cellpadding="8" class="allTables">
      <tr>
        <td colspan="3" class="headlist">Create a list with banned words</td>
      </tr>
      <tr>
        <td width="45%" class="left_top">Make a list of words and posts/comments containing any of these words can not be posted.<br />
          <br />
          For example: word1,word2, word3<br />
          <br />
          /<span style="font-size:11px; font-style:italic;">Note that the words are not case sensitive. Does not matter if you type 'Word' or 'word'.</span>/
        </td>
        <td class="left_top"><textarea class="input_opt" name="ban_words" id="ban_words" cols="60" rows="5"><?php echo ReadDB($Options["ban_words"]); ?></textarea></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit2" type="submit" value="Save" class="submitButton" /></td>
      </tr>
    </table>
	</form>
 

<?php
} elseif ($_REQUEST["act"]=='visual_options') {
	$sql = "SELECT * FROM ".$TABLE["Options"];
	$sql_result = sql_result($sql);
	$Options = mysqli_fetch_assoc($sql_result);
	mysqli_free_result($sql_result);
	$OptionsVis = unserialize($Options['visual']);
?>
	<script type="text/javascript">
		Event.observe(window, 'load', loadAccordions, false);
		function loadAccordions() {
			var bottomAccordion = new accordion('accordion_container');	
			// Open first one
			//bottomAccordion.activate($$('#accordion_container .accordion_toggle')[0]);
		}	
	</script>
	
    <div class="pageDescr">Click on any of the styles to see the options.</div>
    
    <form action="admin.php" method="post" name="form">
	<input type="hidden" name="act" value="updateOptionsVisual" />
    
    <div class="opt_headlist">Set blog front-end visual style 
    	<span class="opt_headl_tip">(Advanced users may work directly with CSS file located in styles/css_front_end.php)</span>
    </div>

    <div id="accordion_container"> 
    <div class="accordion_toggle">General style</div>
    <div class="accordion_content">
    <table border="0" cellspacing="0" cellpadding="8" class="allTables">  
      <tr>
        <td class="langLeft">General font-family:</td>
        <td class="left_top">
        	<select name="gen_font_family">
            	<?php echo font_family_list($OptionsVis['gen_font_family']); ?>
            </select>
        </td>
      </tr>     
      <tr>
        <td class="langLeft">General font-color:</td>
        <td class="left_top"><?php echo color_field("gen_font_color", $OptionsVis["gen_font_color"]); ?></td>
      </tr>
      <tr>
        <td class="langLeft">General font-size:</td>
        <td class="left_top">
        	<select name="gen_font_size">
            	<option value="inherit"<?php if($OptionsVis['gen_font_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
                <?php for($i=8; $i<=32; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['gen_font_size']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>             
                <?php for($i = 0.5; $i <= 3; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['gen_font_size']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
            </select>
        </td>
      </tr> 
      <tr>
        <td class="langLeft">Text-align:</td>
        <td class="left_top">
        	<select name="gen_text_align">
            	<option value="center"<?php if($OptionsVis['gen_text_align']=='center') echo ' selected="selected"'; ?>>center</option>
            	<option value="justify"<?php if($OptionsVis['gen_text_align']=='justify') echo ' selected="selected"'; ?>>justify</option>
                <option value="left"<?php if($OptionsVis['gen_text_align']=='left') echo ' selected="selected"'; ?>>left</option>
                <option value="right"<?php if($OptionsVis['gen_text_align']=='right') echo ' selected="selected"'; ?>>right</option>
                <option value="inherit"<?php if($OptionsVis['gen_text_align']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr> 
      <tr>
        <td class="langLeft">General line-height:</td>
        <td class="left_top">
        	<select name="gen_line_height">
            	<option value="inherit"<?php if($OptionsVis['gen_line_height']=='inherit') echo ' selected="selected"'; ?>>inherit</option>              
                <?php for($i = 1; $i <= 3; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>"<?php if($OptionsVis['gen_line_height']=="$i") echo ' selected="selected"'; ?>><?php echo $i;?></option>
                <?php } ?>
                <?php for($i=10; $i<=40; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['gen_line_height']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?> 
            </select>
        </td>
      </tr> 
      <tr>
        <td class="langLeft">General background-color:</td>
        <td class="left_top"><?php echo color_field("gen_bgr_color", $OptionsVis["gen_bgr_color"]); ?></td>
      </tr>     
      <tr>
        <td class="langLeft">Blog width:</td>
        <td class="left_top">
        	<input name="gen_width" type="text" size="4" value="<?php echo ReadDB($OptionsVis["gen_width"]); ?>" />
            <select name="gen_width_dim">
            	<option value="px"<?php if($OptionsVis['gen_width_dim']=='px') echo ' selected="selected"'; ?>>px</option>
            	<option value="%"<?php if($OptionsVis['gen_width_dim']=='%') echo ' selected="selected"'; ?>>%</option>
            	<option value="pt"<?php if($OptionsVis['gen_width_dim']=='pt') echo ' selected="selected"'; ?>>pt</option>
            	<option value="em"<?php if($OptionsVis['gen_width_dim']=='em') echo ' selected="selected"'; ?>>em</option>
        	</select> 
        &nbsp; <sub>(leave blank if you need a responsive width, so the blog will fit the resolution on all screens)</sub>
        </td>
      </tr>  
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit1" type="submit" value="Save" class="submitButton" /></td>
      </tr>  
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
    </table> 
    </div> 
    
    
    <div class="accordion_toggle">Search box style</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables">    
      <tr>
        <td class="langLeft">Show search box:</td>
        <td class="left_top">
        	<select name="showsearch">
            	<option value="yes"<?php if($OptionsVis['showsearch']=='yes') echo ' selected="selected"'; ?>>yes</option>
            	<option value="no"<?php if($OptionsVis['showsearch']=='no') echo ' selected="selected"'; ?>>no</option>
            </select>
        </td>
      </tr>       
      <tr>
        <td class="langLeft">Input text color:</td>
        <td class="left_top"><?php echo color_field("sear_color", $OptionsVis["sear_color"]); ?></td>
      </tr>  
      <tr>
        <td class="langLeft">Search box background color:</td>
        <td class="left_top"><?php echo color_field("sear_bor_color", $OptionsVis["sear_bor_color"]); ?></td>
      </tr> 
      <tr>
        <td class="langLeft">Search keyword(s) font-family:</td>
        <td class="left_top">
        	<select name="sear_font_family">
            	<?php echo font_family_list($OptionsVis['sear_font_family']); ?>
            </select>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit2" type="submit" value="Save" class="submitButton" /></td>
      </tr> 
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
	</table>
    </div>
    
    
    
    <div class="accordion_toggle">CATEGORIES, RECENT POSTS, ARCHIVES style</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables">    
      <tr>
        <td class="langLeft">Show right sidebar:</td>
        <td class="left_top">
        	<select name="showrightbar">
            	<option value="yes"<?php if($OptionsVis['showrightbar']=='yes') echo ' selected="selected"'; ?>>yes</option>
            	<option value="no"<?php if($OptionsVis['showrightbar']=='no') echo ' selected="selected"'; ?>>no</option>
            </select>
        </td>
      </tr>           
      <tr>
        <td class="langLeft">Show categories in right sidebar:</td>
        <td class="left_top">
        	<select name="showcateg">
            	<option value="yes"<?php if($OptionsVis['showcateg']=='yes') echo ' selected="selected"'; ?>>yes</option>
            	<option value="no"<?php if($OptionsVis['showcateg']=='no') echo ' selected="selected"'; ?>>no</option>
            </select>
        </td>
      </tr>        
      <tr>
        <td class="langLeft">Show recent post titles in right sidebar:</td>
        <td class="left_top">
        	<select name="showrecent">
            	<option value="yes"<?php if($OptionsVis['showrecent']=='yes') echo ' selected="selected"'; ?>>yes</option>
            	<option value="no"<?php if($OptionsVis['showrecent']=='no') echo ' selected="selected"'; ?>>no</option>
            </select>
        </td>
      </tr>        
      <tr>
        <td class="langLeft">Show archives in right sidebar:</td>
        <td class="left_top">
        	<select name="showarchive">
            	<option value="yes"<?php if($OptionsVis['showarchive']=='yes') echo ' selected="selected"'; ?>>yes</option>
            	<option value="no"<?php if($OptionsVis['showarchive']=='no') echo ' selected="selected"'; ?>>no</option>
            </select>
        </td>
      </tr>       
      <tr>
        <td class="langLeft">Headings "CATEGORIES, RECENT, ARCHIVES" color:</td>
        <td class="left_top"><?php echo color_field("cat_word_color", $OptionsVis["cat_word_color"]); ?></td>
      </tr>
      <tr>
        <td class="langLeft">Headings "CATEGORIES, RECENT, ARCHIVES" font-family:</td>
        <td class="left_top">
        	<select name="cat_word_family">
            	<?php echo font_family_list($OptionsVis['cat_word_family']); ?>
            </select>
        </td>
      </tr>       
      <tr>
        <td class="langLeft">Headings "CATEGORIES, RECENT, ARCHIVES" font-size:</td>
        <td class="left_top">
        	<select name="cat_word_font_size">
            	<option value="inherit"<?php if($OptionsVis['cat_word_font_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>        
                <?php for($i = 0.5; $i <= 3; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['cat_word_font_size']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
                <?php for($i=9; $i<=32; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['cat_word_font_size']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr> 
      <tr>
        <td class="langLeft">Headings "CATEGORIES, RECENT, ARCHIVES" font-style:</td>
        <td class="left_top">
        	<select name="cat_word_font_style">
            	<option value="normal"<?php if($OptionsVis['cat_word_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['cat_word_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="oblique"<?php if($OptionsVis['cat_word_font_style']=='oblique') echo ' selected="selected"'; ?>>oblique</option>
                <option value="inherit"<?php if($OptionsVis['cat_word_font_style']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Headings "CATEGORIES, RECENT, ARCHIVES" font-weight:</td>
        <td class="left_top">
        	<select name="cat_word_font_weight">
            	<option value="normal"<?php if($OptionsVis['cat_word_font_weight']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="bold"<?php if($OptionsVis['cat_word_font_weight']=='bold') echo ' selected="selected"'; ?>>bold</option>
                <option value="inherit"<?php if($OptionsVis['cat_word_font_weight']=='inherit') echo ' selected="selected"';?>>inherit</option>
            </select>
        </td>
      </tr>        
      <tr>
        <td class="langLeft">Color of links on categories, titles, months:</td>
        <td class="left_top"><?php echo color_field("cat_ctm_color", $OptionsVis["cat_ctm_color"]); ?></td>
      </tr>         
      <tr>
        <td class="langLeft">Color hover/focus of links on categories, titles, months:</td>
        <td class="left_top"><?php echo color_field("cat_ctm_color_hover", $OptionsVis["cat_ctm_color_hover"]); ?></td>
      </tr>               
      <tr>
        <td class="langLeft">Line color underneat links on categories, titles, months:</td>
        <td class="left_top"><?php echo color_field("line_cat_ctm_color", $OptionsVis["line_cat_ctm_color"]); ?></td>
      </tr>  
      <tr>
        <td class="langLeft">Font-family of links on categories, titles, months:</td>
        <td class="left_top">
        	<select name="cat_ctm_family">
            	<?php echo font_family_list($OptionsVis['cat_ctm_family']); ?>
            </select>
        </td>
      </tr>       
      <tr>
        <td class="langLeft">Font-size of links on categories, titles, months:</td>
        <td class="left_top">
        	<select name="cat_ctm_font_size">
            	<option value="inherit"<?php if($OptionsVis['cat_ctm_font_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>          
                <?php for($i = 0.5; $i <= 3; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['cat_ctm_font_size']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
                <?php for($i=9; $i<=32; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['cat_ctm_font_size']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr> 
      <tr>
        <td class="langLeft">Font-style of links on categories, titles, months:</td>
        <td class="left_top">
        	<select name="cat_ctm_font_style">
            	<option value="normal"<?php if($OptionsVis['cat_ctm_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['cat_ctm_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="oblique"<?php if($OptionsVis['cat_ctm_font_style']=='oblique') echo ' selected="selected"'; ?>>oblique</option>
                <option value="inherit"<?php if($OptionsVis['cat_ctm_font_style']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Font-weight of links on categories, titles, months:</td>
        <td class="left_top">
        	<select name="cat_ctm_font_weight">
            	<option value="normal"<?php if($OptionsVis['cat_ctm_font_weight']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="bold"<?php if($OptionsVis['cat_ctm_font_weight']=='bold') echo ' selected="selected"'; ?>>bold</option>
                <option value="inherit"<?php if($OptionsVis['cat_ctm_font_weight']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>              
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit2" type="submit" value="Save" class="submitButton" /></td>
      </tr> 
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
	</table>
    </div>
    
        
    <div class="accordion_toggle">Posts list title style</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables"> 
      <tr>
        <td class="langLeft">Font-family:</td>
        <td class="left_top">
        	<select name="list_title_font">
            	<?php echo font_family_list($OptionsVis['list_title_font']); ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Color:</td>
        <td class="left_top"><?php echo color_field("list_title_color", $OptionsVis["list_title_color"]); ?></td>
      </tr>
      <tr>
        <td class="langLeft">Color on hover (on mouse over):</td>
        <td class="left_top"><?php echo color_field("list_title_color_hover", $OptionsVis["list_title_color_hover"]); ?></td>
      </tr>      
      <tr>
        <td class="langLeft">Font-size:</td>
        <td class="left_top">
        	<select name="list_title_size">
            	<option value="inherit"<?php if($OptionsVis['list_title_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>        
                <?php for($i = 0.5; $i <= 3; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['list_title_size']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
                <?php for($i=9; $i<=32; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['list_title_size']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Font-style:</td>
        <td class="left_top">
        	<select name="list_title_font_style">
            	<option value="normal"<?php if($OptionsVis['list_title_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['list_title_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="oblique"<?php if($OptionsVis['list_title_font_style']=='oblique') echo ' selected="selected"'; ?>>oblique</option>
                <option value="inherit"<?php if($OptionsVis['list_title_font_style']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Font-weight:</td>
        <td class="left_top">
        	<select name="list_title_font_weight">
            	<option value="normal"<?php if($OptionsVis['list_title_font_weight']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="bold"<?php if($OptionsVis['list_title_font_weight']=='bold') echo ' selected="selected"'; ?>>bold</option>
                <option value="inherit"<?php if($OptionsVis['list_title_font_weight']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>      
      <tr>
        <td class="langLeft">Text-align:</td>
        <td class="left_top">
        	<select name="list_title_align">
            	<option value="center"<?php if($OptionsVis['list_title_align']=='center') echo ' selected="selected"'; ?>>center</option>
            	<option value="justify"<?php if($OptionsVis['list_title_align']=='justify') echo ' selected="selected"'; ?>>justify</option>
                <option value="left"<?php if($OptionsVis['list_title_align']=='left') echo ' selected="selected"'; ?>>left</option>
                <option value="right"<?php if($OptionsVis['list_title_align']=='right') echo ' selected="selected"'; ?>>right</option>
                <option value="inherit"<?php if($OptionsVis['list_title_align']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>      
      <tr>
        <td class="langLeft">Line-height:</td>
        <td class="left_top">
        	<select name="list_title_line_height">
                <option value="inherit"<?php if($OptionsVis['list_title_line_height']=='inherit') echo ' selected="selected"'; ?>>inherit</option>             
                <?php for($i = 1; $i <= 3; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>"<?php if($OptionsVis['list_title_line_height']=="$i") echo ' selected="selected"'; ?>><?php echo $i;?></option>
                <?php } ?>
                <?php for($i=12; $i<=100; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['list_title_line_height']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr> 
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit3" type="submit" value="Save" class="submitButton" /></td>
      </tr>
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
    </table>
    </div>
    
    
    <div class="accordion_toggle">Post title style</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables"> 
      <tr>
        <td class="langLeft">Font-family:</td>
        <td class="left_top">
        	<select name="post_title_font">
            	<?php echo font_family_list($OptionsVis['post_title_font']); ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Color:</td>
        <td class="left_top"><?php echo color_field("post_title_color", $OptionsVis["post_title_color"]); ?></td>
      </tr>     
      <tr>
        <td class="langLeft">Font-size:</td>
        <td class="left_top">
        	<select name="post_title_size">
            	<option value="inherit"<?php if($OptionsVis['post_title_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>       
                <?php for($i = 0.5; $i <= 3; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['post_title_size']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
                <?php for($i=9; $i<=30; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['post_title_size']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Font-style:</td>
        <td class="left_top">
        	<select name="post_title_font_style">
            	<option value="normal"<?php if($OptionsVis['post_title_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['post_title_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="oblique"<?php if($OptionsVis['post_title_font_style']=='oblique') echo ' selected="selected"'; ?>>oblique</option>
                <option value="inherit"<?php if($OptionsVis['post_title_font_style']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Font-weight:</td>
        <td class="left_top">
        	<select name="post_title_font_weight">
            	<option value="normal"<?php if($OptionsVis['post_title_font_weight']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="bold"<?php if($OptionsVis['post_title_font_weight']=='bold') echo ' selected="selected"'; ?>>bold</option>
                <option value="inherit"<?php if($OptionsVis['post_title_font_weight']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>      
      <tr>
        <td class="langLeft">Text-align:</td>
        <td class="left_top">
        	<select name="post_title_align">
            	<option value="center"<?php if($OptionsVis['post_title_align']=='center') echo ' selected="selected"'; ?>>center</option>
            	<option value="justify"<?php if($OptionsVis['post_title_align']=='justify') echo ' selected="selected"'; ?>>justify</option>
                <option value="left"<?php if($OptionsVis['post_title_align']=='left') echo ' selected="selected"'; ?>>left</option>
                <option value="right"<?php if($OptionsVis['post_title_align']=='right') echo ' selected="selected"'; ?>>right</option>
                <option value="inherit"<?php if($OptionsVis['post_title_align']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>      
      <tr>
        <td class="langLeft">Line-height:</td>
        <td class="left_top">
        	<select name="title_line_height">
                <option value="inherit"<?php if($OptionsVis['title_line_height']=='inherit') echo ' selected="selected"'; ?>>inherit</option>            
                <?php for($i = 1; $i <= 3; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>"<?php if($OptionsVis['title_line_height']=="$i") echo ' selected="selected"'; ?>><?php echo $i;?></option>
                <?php } ?>
                <?php for($i=12; $i<=100; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['title_line_height']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr>      
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit3" type="submit" value="Save" class="submitButton" /></td>
      </tr>
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
    </table>
    </div>
      
    
    <div class="accordion_toggle">Posts list date style</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables"> 
      <tr>
        <td class="langLeft">Font-family:</td>
        <td class="left_top">
        	<select name="list_date_font">
            	<?php echo font_family_list($OptionsVis['list_date_font']); ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Color:</td>
        <td class="left_top"><?php echo color_field("list_date_color", $OptionsVis["list_date_color"]); ?></td>
      </tr>
      <tr>
        <td class="langLeft">Color on links(hover):</td>
        <td class="left_top"><?php echo color_field("list_date_color_hover", $OptionsVis["list_date_color_hover"]); ?></td>
      </tr>
      <tr>
        <td class="langLeft">Text-decoration on links(hover):</td>
        <td class="left_top">
        	<select name="list_date_decoration_hover">
            	<option value="none"<?php if($OptionsVis['list_date_decoration_hover']=='none') echo ' selected="selected"'; ?>>none</option>
            	<option value="underline"<?php if($OptionsVis['list_date_decoration_hover']=='underline') echo ' selected="selected"'; ?>>underline</option>
                <option value="inherit"<?php if($OptionsVis['list_date_decoration_hover']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Font-size:</td>
        <td class="left_top">
        	<select name="list_date_size">
            	<option value="inherit"<?php if($OptionsVis['list_date_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>      
                <?php for($i = 0.5; $i <= 3; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['list_date_size']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
                <?php for($i=9; $i<=32; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['list_date_size']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Font-style:</td>
        <td class="left_top">
        	<select name="list_date_font_style">
            	<option value="normal"<?php if($OptionsVis['list_date_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['list_date_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="oblique"<?php if($OptionsVis['list_date_font_style']=='oblique') echo ' selected="selected"';?>>oblique</option>
                <option value="inherit"<?php if($OptionsVis['list_date_font_style']=='inherit') echo ' selected="selected"';?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Text-align:</td>
        <td class="left_top">
        	<select name="list_date_text_align">
            	<option value="center"<?php if($OptionsVis['list_date_text_align']=='center') echo ' selected="selected"'; ?>>center</option>
            	<option value="justify"<?php if($OptionsVis['list_date_text_align']=='justify') echo ' selected="selected"';?>>justify</option>
                <option value="left"<?php if($OptionsVis['list_date_text_align']=='left') echo ' selected="selected"'; ?>>left</option>
                <option value="right"<?php if($OptionsVis['list_date_text_align']=='right') echo ' selected="selected"'; ?>>right</option>
                <option value="inherit"<?php if($OptionsVis['list_date_text_align']=='inherit') echo ' selected="selected"';?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Date format:</td>
        <td class="left_top">
        	<select name="list_date_format">
            	<option value="l - F j, Y"<?php if($OptionsVis['list_date_format']=='l - F j, Y') echo ' selected="selected"'; ?>><?php echo date("l - F j, Y"); ?></option>
                <option value="l - F j Y"<?php if($OptionsVis['list_date_format']=='l - F j Y') echo ' selected="selected"'; ?>><?php echo date("l - F j Y"); ?></option>
                <option value="l, F j Y"<?php if($OptionsVis['list_date_format']=='l, F j Y') echo ' selected="selected"'; ?>><?php echo date("l, F j Y"); ?></option>
            	<option value="l, F j, Y"<?php if($OptionsVis['list_date_format']=='l, F j, Y') echo ' selected="selected"'; ?>><?php echo date("l, F j, Y"); ?></option>
                <option value="l F j Y"<?php if($OptionsVis['list_date_format']=='l F j Y') echo ' selected="selected"'; ?>><?php echo date("l F j Y"); ?></option>
                <option value="l F j, Y"<?php if($OptionsVis['list_date_format']=='l F j Y') echo ' selected="selected"'; ?>><?php echo date("l F j, Y"); ?></option>
                <option value="F j Y"<?php if($OptionsVis['list_date_format']=='F j Y') echo ' selected="selected"'; ?>><?php echo date("F j Y"); ?></option>
                <option value="F j, Y"<?php if($OptionsVis['list_date_format']=='F j, Y') echo ' selected="selected"'; ?>><?php echo date("F j, Y"); ?></option>
                <option value="F jS, Y"<?php if($OptionsVis['list_date_format']=='F jS, Y') echo ' selected="selected"'; ?>><?php echo date("F jS, Y"); ?></option>
                <option value="F Y"<?php if($OptionsVis['list_date_format']=='F Y') echo ' selected="selected"'; ?>><?php echo date("F Y"); ?></option>
                <option value="m-d-Y"<?php if($OptionsVis['list_date_format']=='m-d-Y') echo ' selected="selected"'; ?>>MM-DD-YYYY</option>
                <option value="m.d.Y"<?php if($OptionsVis['list_date_format']=='m.d.Y') echo ' selected="selected"'; ?>>MM.DD.YYYY</option>
                <option value="m/d/Y"<?php if($OptionsVis['list_date_format']=='m/d/Y') echo ' selected="selected"'; ?>>MM/DD/YYYY</option>
                <option value="m-d-y"<?php if($OptionsVis['list_date_format']=='m-d-y') echo ' selected="selected"'; ?>>MM-DD-YY</option>
                <option value="m.d.y"<?php if($OptionsVis['list_date_format']=='m.d.y') echo ' selected="selected"'; ?>>MM.DD.YY</option>
                <option value="m/d/y"<?php if($OptionsVis['list_date_format']=='m/d/y') echo ' selected="selected"'; ?>>MM/DD/YY</option>
                <option value="l - j F, Y"<?php if($OptionsVis['list_date_format']=='l - j F, Y') echo ' selected="selected"'; ?>><?php echo date("l - j F, Y"); ?></option>
                <option value="l - j F Y"<?php if($OptionsVis['list_date_format']=='l - j F Y') echo ' selected="selected"'; ?>><?php echo date("l - j F Y"); ?></option>
                <option value="l, j F Y"<?php if($OptionsVis['list_date_format']=='l, j F Y') echo ' selected="selected"'; ?>><?php echo date("l, j F Y"); ?></option>
                <option value="l, j F, Y"<?php if($OptionsVis['list_date_format']=='l, j F, Y') echo ' selected="selected"'; ?>><?php echo date("l, j F, Y"); ?></option>
                <option value="l j F Y"<?php if($OptionsVis['list_date_format']=='l j F Y') echo ' selected="selected"'; ?>><?php echo date("l j F Y"); ?></option>
                <option value="l j F, Y"<?php if($OptionsVis['list_date_format']=='l j F, Y') echo ' selected="selected"'; ?>><?php echo date("l j F, Y"); ?></option>
                <option value="d F Y"<?php if($OptionsVis['list_date_format']=='d F Y') echo ' selected="selected"'; ?>><?php echo date("d F Y"); ?></option>
                <option value="d F, Y"<?php if($OptionsVis['list_date_format']=='d F, Y') echo ' selected="selected"'; ?>><?php echo date("d F, Y"); ?></option>
                <option value="d-m-Y"<?php if($OptionsVis['list_date_format']=='d-m-Y') echo ' selected="selected"'; ?>>DD-MM-YYYY</option>
                <option value="d.m.Y"<?php if($OptionsVis['list_date_format']=='d.m.Y') echo ' selected="selected"'; ?>>DD.MM.YYYY</option>
                <option value="d/m/Y"<?php if($OptionsVis['list_date_format']=='d/m/Y') echo ' selected="selected"'; ?>>DD/MM/YYYY</option>
                <option value="d-m-y"<?php if($OptionsVis['list_date_format']=='d-m-y') echo ' selected="selected"'; ?>>DD-MM-YY</option>
                <option value="d.m.y"<?php if($OptionsVis['list_date_format']=='d.m.y') echo ' selected="selected"'; ?>>DD.MM.YY</option>
                <option value="d/m/y"<?php if($OptionsVis['list_date_format']=='d/m/y') echo ' selected="selected"'; ?>>DD/MM/YY</option>
            </select>
        </td>
      </tr>     
      <tr>
        <td class="langLeft">Show the date:</td>
        <td class="left_top">
        	<select name="list_show_date">
            	<option value="yes"<?php if($OptionsVis['list_show_date']=='yes') echo ' selected="selected"'; ?>>yes</option>
            	<option value="no"<?php if($OptionsVis['list_show_date']=='no') echo ' selected="selected"'; ?>>no</option>
            </select>
        </td>
      </tr>  
      <tr>
        <td class="langLeft">Show the time:</td>
        <td class="left_top">
        	<select name="list_showing_time">
            	<option value=""<?php if($OptionsVis['list_showing_time']=='') echo ' selected="selected"'; ?>>without time</option>
            	<option value="G:i"<?php if($OptionsVis['list_showing_time']=='G:i') echo ' selected="selected"'; ?>>24h format</option>
            	<option value="g:i a"<?php if($OptionsVis['showing_time']=='g:i a') echo ' selected="selected"'; ?>>12h format</option>
            </select>
        </td>
      </tr>      
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit4" type="submit" value="Save" class="submitButton" /></td>
      </tr> 
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
    </table>
    </div>
    
    
    <div class="accordion_toggle">Post date style</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables">  
      <tr>
        <td class="langLeft">Font-family:</td>
        <td class="left_top">
        	<select name="date_font">
            	<?php echo font_family_list($OptionsVis['date_font']); ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Color:</td>
        <td class="left_top"><?php echo color_field("date_color", $OptionsVis["date_color"]); ?></td>
      </tr>
      <tr>
        <td class="langLeft">Color on links(hover):</td>
        <td class="left_top"><?php echo color_field("date_color_hover", $OptionsVis["date_color_hover"]); ?></td>
      </tr>
      <tr>
        <td class="langLeft">Text-decoration on links(hover):</td>
        <td class="left_top">
        	<select name="date_decoration_hover">
            	<option value="none"<?php if($OptionsVis['date_decoration_hover']=='none') echo ' selected="selected"'; ?>>none</option>
            	<option value="underline"<?php if($OptionsVis['date_decoration_hover']=='underline') echo ' selected="selected"'; ?>>underline</option>
                <option value="inherit"<?php if($OptionsVis['date_decoration_hover']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Font-size:</td>
        <td class="left_top">
        	<select name="date_size">
            	<option value="inherit"<?php if($OptionsVis['date_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>     
                <?php for($i = 0.5; $i <= 3; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['date_size']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
                <?php for($i=9; $i<=32; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['date_size']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Font-style:</td>
        <td class="left_top">
        	<select name="date_font_style">
            	<option value="normal"<?php if($OptionsVis['date_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['date_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="oblique"<?php if($OptionsVis['date_font_style']=='oblique') echo ' selected="selected"'; ?>>oblique</option>
                <option value="inherit"<?php if($OptionsVis['date_font_style']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Text-align:</td>
        <td class="left_top">
        	<select name="date_text_align">
            	<option value="center"<?php if($OptionsVis['date_text_align']=='center') echo ' selected="selected"'; ?>>center</option>
            	<option value="justify"<?php if($OptionsVis['date_text_align']=='justify') echo ' selected="selected"'; ?>>justify</option>
                <option value="left"<?php if($OptionsVis['date_text_align']=='left') echo ' selected="selected"'; ?>>left</option>
                <option value="right"<?php if($OptionsVis['date_text_align']=='right') echo ' selected="selected"'; ?>>right</option>
                <option value="inherit"<?php if($OptionsVis['date_text_align']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Date format:</td>
        <td class="left_top">
        	<select name="date_format">
            	<option value="l - F j, Y"<?php if($OptionsVis['date_format']=='l - F j, Y') echo ' selected="selected"'; ?>><?php echo date("l - F j, Y"); ?></option>
                <option value="l - F j Y"<?php if($OptionsVis['date_format']=='l - F j Y') echo ' selected="selected"'; ?>><?php echo date("l - F j Y"); ?></option>
                <option value="l, F j Y"<?php if($OptionsVis['date_format']=='l, F j Y') echo ' selected="selected"'; ?>><?php echo date("l, F j Y"); ?></option>
            	<option value="l, F j, Y"<?php if($OptionsVis['date_format']=='l, F j, Y') echo ' selected="selected"'; ?>><?php echo date("l, F j, Y"); ?></option>
                <option value="l F j Y"<?php if($OptionsVis['date_format']=='l F j Y') echo ' selected="selected"'; ?>><?php echo date("l F j Y"); ?></option>
                <option value="l F j, Y"<?php if($OptionsVis['date_format']=='l F j Y') echo ' selected="selected"'; ?>><?php echo date("l F j, Y"); ?></option>
                <option value="F j Y"<?php if($OptionsVis['date_format']=='F j Y') echo ' selected="selected"'; ?>><?php echo date("F j Y"); ?></option>
                <option value="F j, Y"<?php if($OptionsVis['date_format']=='F j, Y') echo ' selected="selected"'; ?>><?php echo date("F j, Y"); ?></option>
                <option value="F jS, Y"<?php if($OptionsVis['date_format']=='F jS, Y') echo ' selected="selected"'; ?>><?php echo date("F jS, Y"); ?></option>
                <option value="F Y"<?php if($OptionsVis['date_format']=='F Y') echo ' selected="selected"'; ?>><?php echo date("F Y"); ?></option>
                <option value="m-d-Y"<?php if($OptionsVis['date_format']=='m-d-Y') echo ' selected="selected"'; ?>>MM-DD-YYYY</option>
                <option value="m.d.Y"<?php if($OptionsVis['date_format']=='m.d.Y') echo ' selected="selected"'; ?>>MM.DD.YYYY</option>
                <option value="m/d/Y"<?php if($OptionsVis['date_format']=='m/d/Y') echo ' selected="selected"'; ?>>MM/DD/YYYY</option>
                <option value="m-d-y"<?php if($OptionsVis['date_format']=='m-d-y') echo ' selected="selected"'; ?>>MM-DD-YY</option>
                <option value="m.d.y"<?php if($OptionsVis['date_format']=='m.d.y') echo ' selected="selected"'; ?>>MM.DD.YY</option>
                <option value="m/d/y"<?php if($OptionsVis['date_format']=='m/d/y') echo ' selected="selected"'; ?>>MM/DD/YY</option>
                <option value="l - j F, Y"<?php if($OptionsVis['date_format']=='l - j F, Y') echo ' selected="selected"'; ?>><?php echo date("l - j F, Y"); ?></option>
                <option value="l - j F Y"<?php if($OptionsVis['date_format']=='l - j F Y') echo ' selected="selected"'; ?>><?php echo date("l - j F Y"); ?></option>
                <option value="l, j F Y"<?php if($OptionsVis['date_format']=='l, j F Y') echo ' selected="selected"'; ?>><?php echo date("l, j F Y"); ?></option>
                <option value="l, j F, Y"<?php if($OptionsVis['date_format']=='l, j F, Y') echo ' selected="selected"'; ?>><?php echo date("l, j F, Y"); ?></option>
                <option value="l j F Y"<?php if($OptionsVis['date_format']=='l j F Y') echo ' selected="selected"'; ?>><?php echo date("l j F Y"); ?></option>
                <option value="l j F, Y"<?php if($OptionsVis['date_format']=='l j F, Y') echo ' selected="selected"'; ?>><?php echo date("l j F, Y"); ?></option>
                <option value="d F Y"<?php if($OptionsVis['date_format']=='d F Y') echo ' selected="selected"'; ?>><?php echo date("d F Y"); ?></option>
                <option value="d F, Y"<?php if($OptionsVis['date_format']=='d F, Y') echo ' selected="selected"'; ?>><?php echo date("d F, Y"); ?></option>
                <option value="d-m-Y"<?php if($OptionsVis['date_format']=='d-m-Y') echo ' selected="selected"'; ?>>DD-MM-YYYY</option>
                <option value="d.m.Y"<?php if($OptionsVis['date_format']=='d.m.Y') echo ' selected="selected"'; ?>>DD.MM.YYYY</option>
                <option value="d/m/Y"<?php if($OptionsVis['date_format']=='d/m/Y') echo ' selected="selected"'; ?>>DD/MM/YYYY</option>
                <option value="d-m-y"<?php if($OptionsVis['date_format']=='d-m-y') echo ' selected="selected"'; ?>>DD-MM-YY</option>
                <option value="d.m.y"<?php if($OptionsVis['date_format']=='d.m.y') echo ' selected="selected"'; ?>>DD.MM.YY</option>
                <option value="d/m/y"<?php if($OptionsVis['date_format']=='d/m/y') echo ' selected="selected"'; ?>>DD/MM/YY</option>
            </select>
        </td>
      </tr>   
      <tr>
        <td class="langLeft">Show the date:</td>
        <td class="left_top">
        	<select name="show_date">
            	<option value="yes"<?php if($OptionsVis['show_date']=='yes') echo ' selected="selected"'; ?>>yes</option>
            	<option value="no"<?php if($OptionsVis['show_date']=='no') echo ' selected="selected"'; ?>>no</option>
            </select>
        </td>
      </tr>   
      <tr>
        <td class="langLeft">Show the time:</td>
        <td class="left_top">
        	<select name="showing_time">
            	<option value=""<?php if($OptionsVis['showing_time']=='') echo ' selected="selected"'; ?>>without time</option>
            	<option value="G:i"<?php if($OptionsVis['showing_time']=='G:i') echo ' selected="selected"'; ?>>24h format</option>
            	<option value="g:i a"<?php if($OptionsVis['showing_time']=='g:i a') echo ' selected="selected"'; ?>>12h format</option>
            </select>
        </td>
      </tr> 
      <tr>
        <td class="langLeft">Show A+/a-:</td>
        <td class="left_top">
        	<select name="show_aa">
            	<option value="yes"<?php if($OptionsVis['show_aa']=='yes') echo ' selected="selected"'; ?>>yes</option>
            	<option value="no"<?php if($OptionsVis['show_aa']=='no') echo ' selected="selected"'; ?>>no</option>
            </select>
        </td>
      </tr>      
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit4" type="submit" value="Save" class="submitButton" /></td>
      </tr> 
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
    </table>
    </div>
    
    
    <div class="accordion_toggle">Posts list text style</div>
    <div class="accordion_content">   
    <table border="0" cellspacing="0" cellpadding="8" class="allTables"> 
      <tr>
        <td class="langLeft">Font-family:</td>
        <td class="left_top">
        	<select name="list_text_font">
            	<?php echo font_family_list($OptionsVis['list_text_font']); ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Color:</td>
        <td class="left_top"><?php echo color_field("list_text_color", $OptionsVis["list_text_color"]); ?></td>
      </tr> 
      <tr>
        <td class="langLeft">Font-size:</td>
        <td class="left_top">
        	<select name="list_text_size">
            	<option value="inherit"<?php if($OptionsVis['list_text_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
                <?php for($i = 0.5; $i <= 3; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['list_text_size']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
                <?php for($i=9; $i<=32; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['list_text_size']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Font-weight:</td>
        <td class="left_top">
        	<select name="list_text_font_weight">
            	<option value="normal"<?php if($OptionsVis['list_text_font_weight']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="bold"<?php if($OptionsVis['list_text_font_weight']=='bold') echo ' selected="selected"'; ?>>bold</option>
                <option value="inherit"<?php if($OptionsVis['list_text_font_weight']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>  
      <tr>
        <td class="langLeft">Font-style:</td>
        <td class="left_top">
        	<select name="list_text_font_style">
            	<option value="normal"<?php if($OptionsVis['list_text_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['list_text_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="oblique"<?php if($OptionsVis['list_text_font_style']=='oblique') echo ' selected="selected"';?>>oblique</option>
                <option value="inherit"<?php if($OptionsVis['list_text_font_style']=='inherit') echo ' selected="selected"';?>>inherit</option>
            </select>
        </td>
      </tr>    
      <tr>
        <td class="langLeft">Text-align:</td>
        <td class="left_top">
        	<select name="list_text_text_align">
            	<option value="center"<?php if($OptionsVis['list_text_text_align']=='center') echo ' selected="selected"'; ?>>center</option>
            	<option value="justify"<?php if($OptionsVis['list_text_text_align']=='justify') echo ' selected="selected"';?>>justify</option>
                <option value="left"<?php if($OptionsVis['list_text_text_align']=='left') echo ' selected="selected"'; ?>>left</option>
                <option value="right"<?php if($OptionsVis['list_text_text_align']=='right') echo ' selected="selected"'; ?>>right</option>
                <option value="inherit"<?php if($OptionsVis['list_text_text_align']=='inherit') echo ' selected="selected"';?>>inherit</option>
            </select>
        </td>
      </tr>     
      <tr>
        <td class="langLeft">Line-height:</td>
        <td class="left_top">
        	<select name="list_text_line_height">
            	<option value="inherit"<?php if($OptionsVis['list_text_line_height']=='inherit') echo ' selected="selected"'; ?>>inherit</option>           
                <?php for($i = 1; $i <= 3; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>"<?php if($OptionsVis['list_text_line_height']=="$i") echo ' selected="selected"'; ?>><?php echo $i;?></option>
                <?php } ?>
                <?php for($i=9; $i<=40; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['list_text_line_height']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr> 
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit2" type="submit" value="Save" class="submitButton" /></td>
      </tr> 
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
    </table>
    </div>
        
    
    <div class="accordion_toggle">Post text style</div>
    <div class="accordion_content">   
    <table border="0" cellspacing="0" cellpadding="8" class="allTables"> 
      <tr>
        <td class="langLeft">Font-family:</td>
        <td class="left_top">
        	<select name="text_font">
            	<?php echo font_family_list($OptionsVis['text_font']); ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Color:</td>
        <td class="left_top"><?php echo color_field("text_color", $OptionsVis["text_color"]); ?></td>
      </tr>   
      <tr>
        <td class="langLeft">Background color:</td>
        <td class="left_top"><?php echo color_field("text_bgr_color", $OptionsVis["text_bgr_color"]); ?></td>
      </tr>   
      <tr>
        <td class="langLeft">Font-size:</td>
        <td class="left_top">
        	<select name="text_size">
            	<option value="inherit"<?php if($OptionsVis['text_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
                <?php for($i = 0.5; $i <= 3; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['text_size']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
                <?php for($i=9; $i<=32; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['text_size']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Font-weight:</td>
        <td class="left_top">
        	<select name="text_font_weight">
            	<option value="normal"<?php if($OptionsVis['text_font_weight']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="bold"<?php if($OptionsVis['text_font_weight']=='bold') echo ' selected="selected"'; ?>>bold</option>
                <option value="inherit"<?php if($OptionsVis['text_font_weight']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>      
      <tr>
        <td class="langLeft">Font-style:</td>
        <td class="left_top">
        	<select name="text_font_style">
            	<option value="normal"<?php if($OptionsVis['text_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['text_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="oblique"<?php if($OptionsVis['text_font_style']=='oblique') echo ' selected="selected"'; ?>>oblique</option>
                <option value="inherit"<?php if($OptionsVis['text_font_style']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Text-align:</td>
        <td class="left_top">
        	<select name="text_text_align">
            	<option value="center"<?php if($OptionsVis['text_text_align']=='center') echo ' selected="selected"'; ?>>center</option>
            	<option value="justify"<?php if($OptionsVis['text_text_align']=='justify') echo ' selected="selected"'; ?>>justify</option>
                <option value="left"<?php if($OptionsVis['text_text_align']=='left') echo ' selected="selected"'; ?>>left</option>
                <option value="right"<?php if($OptionsVis['text_text_align']=='right') echo ' selected="selected"'; ?>>right</option>
                <option value="inherit"<?php if($OptionsVis['text_text_align']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>     
      <tr>
        <td class="langLeft">Line-height:</td>
        <td class="left_top">
        	<select name="text_line_height">
            	<option value="inherit"<?php if($OptionsVis['text_line_height']=='inherit') echo ' selected="selected"'; ?>>inherit</option>           
                <?php for($i = 1; $i <= 3; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>"<?php if($OptionsVis['text_line_height']=="$i") echo ' selected="selected"'; ?>><?php echo $i;?></option>
                <?php } ?>
                <?php for($i=9; $i<=40; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['text_line_height']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr> 
      <tr>
        <td class="langLeft">Padding left/right:</td>
        <td class="left_top">
        	<select name="text_padding">
            	<option value="inherit"<?php if($OptionsVis['text_padding']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
                <?php for($i=0; $i<=30; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['text_padding']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit2" type="submit" value="Save" class="submitButton" /></td>
      </tr> 
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
    </table>
    </div>
          
      
    <div class="accordion_toggle">'READ MORE' link style</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables">
      <tr>
        <td class="langLeft">Color:</td>
        <td class="left_top"><?php echo color_field("more_font_color", $OptionsVis["more_font_color"]); ?></td>
      </tr>
      <tr>
        <td class="langLeft">Color on hover(on mouse over):</td>
        <td class="left_top"><?php echo color_field("more_font_color_hover", $OptionsVis["more_font_color_hover"]); ?></td>
      </tr>
      <tr>
        <td class="langLeft">Font-family:</td>
        <td class="left_top">
        	<select name="more_font">
            	<?php echo font_family_list($OptionsVis['more_font']); ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Font-size:</td>
        <td class="left_top">
        	<select name="more_font_size">
            	<option value="inherit"<?php if($OptionsVis['more_font_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
                <?php for($i = 0.5; $i <= 3; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['more_font_size']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
                <?php for($i=10; $i<=22; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['more_font_size']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Font-style:</td>
        <td class="left_top">
        	<select name="more_font_style">
            	<option value="normal"<?php if($OptionsVis['more_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['more_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="inherit"<?php if($OptionsVis['more_font_style']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Font-weight:</td>
        <td class="left_top">
        	<select name="more_font_weight">
            	<option value="normal"<?php if($OptionsVis['more_font_weight']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="bold"<?php if($OptionsVis['more_font_weight']=='bold') echo ' selected="selected"'; ?>>bold</option>
                <option value="inherit"<?php if($OptionsVis['more_font_weight']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Text-decoration:</td>
        <td class="left_top">
        	<select name="more_text_decoration">
            	<option value="none"<?php if($OptionsVis['more_text_decoration']=='none') echo ' selected="selected"'; ?>>none</option>
            	<option value="underline"<?php if($OptionsVis['more_text_decoration']=='underline') echo ' selected="selected"'; ?>>underline</option>
                <option value="inherit"<?php if($OptionsVis['more_text_decoration']=='inherit') echo ' selected="selected"';?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Text-decoration on hover(on mouseover):</td>
        <td class="left_top">
        	<select name="more_text_decoration_hover">
            	<option value="none"<?php if($OptionsVis['more_text_decoration_hover']=='none') echo ' selected="selected"'; ?>>none</option>
            	<option value="underline"<?php if($OptionsVis['more_text_decoration_hover']=='underline') echo ' selected="selected"'; ?>>underline</option>
                <option value="inherit"<?php if($OptionsVis['more_text_decoration_hover']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit5" type="submit" value="Save" class="submitButton" /></td>
      </tr>
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
	</table>
    </div>
      
    <div class="accordion_toggle">'Back' link style</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables">  
      <tr>
        <td class="langLeft">Color:</td>
        <td class="left_top"><?php echo color_field("back_font_color", $OptionsVis["back_font_color"]); ?></td>
      </tr>
      <tr>
        <td class="langLeft">Color on hover(on mouse over):</td>
        <td class="left_top"><?php echo color_field("back_font_color_hover", $OptionsVis["back_font_color_hover"]); ?></td>
      </tr>
      <tr>
        <td class="langLeft">Font-family:</td>
        <td class="left_top">
        	<select name="back_font">
            	<?php echo font_family_list($OptionsVis['back_font']); ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Font-size:</td>
        <td class="left_top">
        	<select name="back_font_size">
            	<option value="inherit"<?php if($OptionsVis['back_font_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
                <?php for($i = 0.5; $i <= 3; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['back_font_size']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
                <?php for($i=10; $i<=22; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['back_font_size']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Font-style:</td>
        <td class="left_top">
        	<select name="back_font_style">
            	<option value="normal"<?php if($OptionsVis['back_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['back_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="inherit"<?php if($OptionsVis['back_font_style']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Font-weight:</td>
        <td class="left_top">
        	<select name="back_font_weight">
            	<option value="normal"<?php if($OptionsVis['back_font_weight']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="bold"<?php if($OptionsVis['back_font_weight']=='bold') echo ' selected="selected"'; ?>>bold</option>
                <option value="inherit"<?php if($OptionsVis['back_font_weight']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Text-decoration:</td>
        <td class="left_top">
        	<select name="back_text_decoration">
            	<option value="none"<?php if($OptionsVis['back_text_decoration']=='none') echo ' selected="selected"'; ?>>none</option>
            	<option value="underline"<?php if($OptionsVis['back_text_decoration']=='underline') echo ' selected="selected"'; ?>>underline</option>
                <option value="inherit"<?php if($OptionsVis['back_text_decoration']=='inherit') echo ' selected="selected"';?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Text-decoration on hover (on mouseover):</td>
        <td class="left_top">
        	<select name="back_text_decoration_hover">
            	<option value="none"<?php if($OptionsVis['back_text_decoration_hover']=='none') echo ' selected="selected"'; ?>>none</option>
            	<option value="underline"<?php if($OptionsVis['back_text_decoration_hover']=='underline') echo ' selected="selected"'; ?>>underline</option>
                <option value="inherit"<?php if($OptionsVis['back_text_decoration_hover']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit6" type="submit" value="Save" class="submitButton" /></td>
      </tr>
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
	</table>
    </div>
      
    
    <div class="accordion_toggle">Links style in the post message area</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables"> 
      <tr>
        <td class="langLeft">Font color:</td>
        <td class="left_top"><?php echo color_field("links_font_color", $OptionsVis["links_font_color"]); ?></td>
      </tr>
      <tr>
        <td class="langLeft">Color on hover(on mouseover):</td>
        <td class="left_top"><?php echo color_field("links_font_color_hover", $OptionsVis["links_font_color_hover"]); ?></td>
      </tr>      
      <tr>
        <td class="langLeft">Text-decoration:</td>
        <td class="left_top">
        	<select name="links_text_decoration">
            	<option value="none"<?php if($OptionsVis['links_text_decoration']=='none') echo ' selected="selected"'; ?>>none</option>
            	<option value="underline"<?php if($OptionsVis['links_text_decoration']=='underline') echo ' selected="selected"'; ?>>underline</option>
                <option value="inherit"<?php if($OptionsVis['links_text_decoration']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Text-decoration(on mouseover):</td>
        <td class="left_top">
        	<select name="links_text_decoration_hover">
            	<option value="none"<?php if($OptionsVis['links_text_decoration_hover']=='none') echo ' selected="selected"'; ?>>none</option>
            	<option value="underline"<?php if($OptionsVis['links_text_decoration_hover']=='underline') echo ' selected="selected"'; ?>>underline</option>
                <option value="inherit"<?php if($OptionsVis['links_text_decoration_hover']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Font-size:</td>
        <td class="left_top">
        	<select name="links_font_size">
            	<option value="inherit"<?php if($OptionsVis['links_font_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
                <?php for($i = 0.5; $i <= 3; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['links_font_size']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
                <?php for($i=9; $i<=22; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['links_font_size']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Font-style:</td>
        <td class="left_top">
        	<select name="links_font_style">
            	<option value="normal"<?php if($OptionsVis['links_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['links_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="inherit"<?php if($OptionsVis['links_font_style']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Font-weight:</td>
        <td class="left_top">
        	<select name="links_font_weight">
            	<option value="normal"<?php if($OptionsVis['links_font_weight']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="bold"<?php if($OptionsVis['links_font_weight']=='bold') echo ' selected="selected"'; ?>>bold</option>
                <option value="inherit"<?php if($OptionsVis['links_font_weight']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit7" type="submit" value="Save" class="submitButton" /></td>
      </tr>
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
	</table>
    </div>
    
    
    <div class="accordion_toggle">Tags style</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables"> 
      <tr>
        <td class="langLeft">"Tagged as" font color:</td>
        <td class="left_top"><?php echo color_field("tagged_font_color", $OptionsVis["tagged_font_color"]); ?></td>
      </tr>      
      <tr>
        <td class="langLeft">"Tagged as" font-family:</td>
        <td class="left_top">
        	<select name="tagged_family">
            	<?php echo font_family_list($OptionsVis['tagged_family']); ?>
            </select>
        </td>
      </tr>      
      <tr>
        <td class="langLeft">"Tagged as" font-size:</td>
        <td class="left_top">
        	<select name="tagged_font_size">
            	<option value="inherit"<?php if($OptionsVis['tags_font_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
                <?php for($i = 0.5; $i <= 3; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['tags_font_size']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
                <?php for($i=9; $i<=22; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['tags_font_size']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">"Tagged as" font-style:</td>
        <td class="left_top">
        	<select name="tagged_font_style">
            	<option value="normal"<?php if($OptionsVis['tags_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['tags_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="inherit"<?php if($OptionsVis['tags_font_style']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">"Tagged as" font-weight:</td>
        <td class="left_top">
        	<select name="tagged_font_weight">
            	<option value="normal"<?php if($OptionsVis['tags_font_weight']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="bold"<?php if($OptionsVis['tags_font_weight']=='bold') echo ' selected="selected"'; ?>>bold</option>
                <option value="inherit"<?php if($OptionsVis['tags_font_weight']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      
      <tr>
        <td class="langLeft">Tags font color:</td>
        <td class="left_top"><?php echo color_field("tags_font_color", $OptionsVis["tags_font_color"]); ?></td>
      </tr>
      <tr>
        <td class="langLeft">Tags color on hover(on mouseover):</td>
        <td class="left_top"><?php echo color_field("tags_font_color_hover", $OptionsVis["tags_font_color_hover"]); ?></td>
      </tr>     
      <tr>
        <td class="langLeft">Tags font-family:</td>
        <td class="left_top">
        	<select name="tags_family">
            	<?php echo font_family_list($OptionsVis['tags_family']); ?>
            </select>
        </td>
      </tr>      
      <tr>
        <td class="langLeft">Tags text-decoration:</td>
        <td class="left_top">
        	<select name="tags_text_decoration">
            	<option value="none"<?php if($OptionsVis['tags_text_decoration']=='none') echo ' selected="selected"'; ?>>none</option>
            	<option value="underline"<?php if($OptionsVis['tags_text_decoration']=='underline') echo ' selected="selected"'; ?>>underline</option>
                <option value="inherit"<?php if($OptionsVis['tags_text_decoration']=='inherit') echo ' selected="selected"';?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Tags text-decoration(on mouseover):</td>
        <td class="left_top">
        	<select name="tags_text_decoration_hover">
            	<option value="none"<?php if($OptionsVis['tags_text_decoration_hover']=='none') echo ' selected="selected"'; ?>>none</option>
            	<option value="underline"<?php if($OptionsVis['tags_text_decoration_hover']=='underline') echo ' selected="selected"'; ?>>underline</option>
                <option value="inherit"<?php if($OptionsVis['tags_text_decoration_hover']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Tags font-size:</td>
        <td class="left_top">
        	<select name="tags_font_size">
            	<option value="inherit"<?php if($OptionsVis['tags_font_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
                <?php for($i = 0.5; $i <= 3; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['tags_font_size']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
                <?php for($i=9; $i<=32; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['tags_font_size']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Tags font-style:</td>
        <td class="left_top">
        	<select name="tags_font_style">
            	<option value="normal"<?php if($OptionsVis['tags_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['tags_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="inherit"<?php if($OptionsVis['tags_font_style']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Tags font-weight:</td>
        <td class="left_top">
        	<select name="tags_font_weight">
            	<option value="normal"<?php if($OptionsVis['tags_font_weight']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="bold"<?php if($OptionsVis['tags_font_weight']=='bold') echo ' selected="selected"'; ?>>bold</option>
                <option value="inherit"<?php if($OptionsVis['tags_font_weight']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit7" type="submit" value="Save" class="submitButton" /></td>
      </tr>
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
	</table>
    </div>
    
    
    <div class="accordion_toggle">Pagination style</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables"> 
      <tr>
        <td class="langLeft">Pagination Font-family:</td>
        <td class="left_top">
        	<select name="pag_font_family">
            	<?php echo font_family_list($OptionsVis['pag_font_family']); ?>
            </select>
        </td>
      </tr>      
      <tr>
        <td class="langLeft">Pages font color:</td>
        <td class="left_top"><?php echo color_field("pag_font_color", $OptionsVis["pag_font_color"]); ?></td>
      </tr>   
      <tr>
        <td class="langLeft">Pages background color:</td>
        <td class="left_top"><?php echo color_field("pag_font_color_hover", $OptionsVis["pag_font_color_hover"]); ?></td>
      </tr>        
      <tr>
        <td class="langLeft">Selected page font color:</td>
        <td class="left_top"><?php echo color_field("pag_font_color_sel", $OptionsVis["pag_font_color_sel"]); ?></td>
      </tr>        
      <tr>
        <td class="langLeft">Selected page background color:</td>
        <td class="left_top"><?php echo color_field("pag_font_color_prn", $OptionsVis["pag_font_color_prn"]); ?></td>
      </tr>  
      <tr>
        <td class="langLeft">Background color on hover(active):</td>
        <td class="left_top"><?php echo color_field("pag_color_prn_hover", $OptionsVis["pag_color_prn_hover"]); ?></td>
      </tr>  
      <tr>
        <td class="langLeft">Inactive Previous/Next button font color:</td>
        <td class="left_top"><?php echo color_field("pag_font_color_ina", $OptionsVis["pag_font_color_ina"]); ?></td>
      </tr> 
      <tr>
        <td class="langLeft">Pagination font-size:</td>
        <td class="left_top">
        	<select name="pag_font_size">
            	<option value="inherit"<?php if($OptionsVis['pag_font_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
                <?php for($i = 0.5; $i <= 3; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['pag_font_size']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
                <?php for($i=9; $i<=50; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['pag_font_size']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr>  
      <tr>
        <td class="langLeft">Pagination font-style:</td>
        <td class="left_top">
        	<select name="pag_font_style">
            	<option value="normal"<?php if($OptionsVis['pag_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['pag_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="inherit"<?php if($OptionsVis['pag_font_style']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Pagination font-weight:</td>
        <td class="left_top">
        	<select name="pag_font_weight">
            	<option value="normal"<?php if($OptionsVis['pag_font_weight']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="bold"<?php if($OptionsVis['pag_font_weight']=='bold') echo ' selected="selected"'; ?>>bold</option>
                <option value="inherit"<?php if($OptionsVis['pag_font_weight']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>         
      <tr>
        <td class="langLeft">Align to:</td>
        <td  class="left_top">
        	<select name="pag_align_to">
            	<option value="left"<?php if($OptionsVis['pag_align_to']=='left') echo ' selected="selected"'; ?>>left</option>
            	<option value="center"<?php if($OptionsVis['pag_align_to']=='center') echo ' selected="selected"'; ?>>center</option>
                <option value="right"<?php if($OptionsVis['pag_align_to']=='right') echo ' selected="selected"'; ?>>right</option>
            </select>
        </td>
      </tr>    
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit7" type="submit" value="Save" class="submitButton" /></td>
      </tr>  
	</table>
    </div>
    
    
    
    <div class="accordion_toggle">"Scrol to top" button style</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables">         
      <tr>
        <td class="langLeft">Show "Scrol to top" button:</td>
        <td class="left_top">
        	<select name="show_scrolltop">
            	<option value="yes"<?php if($OptionsVis['show_scrolltop']=='yes') echo ' selected="selected"'; ?>>yes</option>
            	<option value="no"<?php if($OptionsVis['show_scrolltop']=='no') echo ' selected="selected"'; ?>>no</option>
            </select>
        </td>
      </tr>             
      <tr>
        <td class="langLeft">Width:</td>
        <td class="left_top">
        	<select name="scrolltop_width">
                <?php for($i=0; $i<=100; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['scrolltop_width']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr>          
      <tr>
        <td class="langLeft">Heght:</td>
        <td class="left_top">
        	<select name="scrolltop_height">
                <?php for($i=0; $i<=100; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['scrolltop_height']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr>     
      <tr>
        <td class="langLeft">Background color:</td>
        <td class="left_top"><?php echo color_field("scrolltop_bgr_color", $OptionsVis["scrolltop_bgr_color"]); ?></td>
      </tr>        
      <tr>
        <td class="langLeft">Background color on hover (on mouseover):</td>
        <td class="left_top"><?php echo color_field("scrolltop_bgr_color_hover", $OptionsVis["scrolltop_bgr_color_hover"]); ?></td>
      </tr>    
      <tr>
        <td class="langLeft">Opacity:</td>
        <td class="left_top">
        	<select name="scrolltop_opacity">
                <?php for($i=0; $i<=100; $i += 10) {?>
            	<option value="<?php echo $i;?>"<?php if($OptionsVis['scrolltop_opacity']==$i) echo ' selected="selected"'; ?>><?php echo $i;?>%</option>
                <?php } ?>
            </select>
        </td>
      </tr>     
      <tr>
        <td class="langLeft">Opacity when scroll down:</td>
        <td class="left_top">
        	<select name="scrolltop_opacity_hover">
                <?php for($i=0; $i<=100; $i += 10) {?>
            	<option value="<?php echo $i;?>"<?php if($OptionsVis['scrolltop_opacity_hover']==$i) echo ' selected="selected"'; ?>><?php echo $i;?>%</option>
                <?php } ?>
            </select>
        </td>
      </tr>           
      <tr>
        <td class="langLeft">Border radius:</td>
        <td class="left_top">
        	<select name="scrolltop_radius">
                <?php for($i=0; $i<=10; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['scrolltop_radius']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr>    
             
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit7" type="submit" value="Save" class="submitButton" /></td>
      </tr> 
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
	</table>
    </div>
    
    
    <div class="accordion_toggle">Bottom navigation "Older Post" - "Newer Post"</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables">  
      <tr>
        <td class="langLeft">Font-family:</td>
        <td class="left_top">
        	<select name="bott_font_family">
            	<?php echo font_family_list($OptionsVis['bott_font_family']); ?>
            </select>
        </td>
      </tr>      
      <tr>
        <td class="langLeft">Color:</td>
        <td class="left_top"><?php echo color_field("bott_color", $OptionsVis["bott_color"]); ?></td>
      </tr>
      <tr>
        <td class="langLeft">Color on hover(on mouse over):</td>
        <td class="left_top"><?php echo color_field("bott_color_hover", $OptionsVis["bott_color_hover"]); ?></td>
      </tr>
      <tr>
        <td class="langLeft">Background color:</td>
        <td class="left_top"><?php echo color_field("bott_bgr_color", $OptionsVis["bott_bgr_color"]); ?></td>
      </tr>
      <tr>
        <td class="langLeft">Background color on hover:</td>
        <td class="left_top"><?php echo color_field("bott_bgr_color_hover", $OptionsVis["bott_bgr_color_hover"]); ?></td>
      </tr>
      <tr>
        <td class="langLeft">Font-size:</td>
        <td class="left_top">
        	<select name="bott_size">
            	<option value="inherit"<?php if($OptionsVis['bott_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
                <?php for($i = 0.5; $i <= 3; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['bott_size']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
                <?php for($i=10; $i<=32; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['bott_size']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Font-style:</td>
        <td class="left_top">
        	<select name="bott_style">
            	<option value="normal"<?php if($OptionsVis['bott_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['bott_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="inherit"<?php if($OptionsVis['bott_style']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Font-weight:</td>
        <td class="left_top">
        	<select name="bott_weight">
            	<option value="normal"<?php if($OptionsVis['bott_weight']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="bold"<?php if($OptionsVis['bott_weight']=='bold') echo ' selected="selected"'; ?>>bold</option>
                <option value="inherit"<?php if($OptionsVis['bott_weight']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>        
      <tr>
        <td class="langLeft">Align to:</td>
        <td  class="left_top">
        	<select name="bott_align_to">
            	<option value="left"<?php if($OptionsVis['bott_align_to']=='left') echo ' selected="selected"'; ?>>left</option>
            	<option value="center"<?php if($OptionsVis['bott_align_to']=='center') echo ' selected="selected"'; ?>>center</option>
                <option value="right"<?php if($OptionsVis['bott_align_to']=='right') echo ' selected="selected"'; ?>>right</option>
            </select>
        </td>
      </tr>    
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit6" type="submit" value="Save" class="submitButton" /></td>
      </tr>
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
	</table>
    </div>
          
      
    <div class="accordion_toggle">Distances</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables"> 
      <tr>
        <td class="langLeft">Distance blog from top:</td>
        <td class="left_top">
        	<select name="dist_from_top">
                <?php for($i=0; $i<=50; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['dist_from_top']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
                <?php for($i = 0.1; $i <= 5; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['dist_from_top']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
            </select>
        </td>
      </tr> 
           
      <tr>
        <td class="langLeft">Distance between title and date:</td>
        <td class="left_top">
        	<select name="dist_title_date">
            	<?php for($i=0; $i<=50; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['dist_title_date']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
                <?php for($i = 0.1; $i <= 5; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['dist_title_date']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
            </select>
        </td>
      </tr>              
      <tr>
        <td class="langLeft">Distance between title and date in the list of posts:</td>
        <td class="left_top">
        	<select name="list_dist_title_date">
            	<?php for($i=0; $i<=50; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['list_dist_title_date']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
                <?php for($i = 0.1; $i <= 5; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['list_dist_title_date']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
            </select>
        </td>
      </tr>   
      <tr>
        <td class="langLeft">Distance between date and post text:</td>
        <td class="left_top">
        	<select name="dist_date_text">
            	<?php for($i=0; $i<=50; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['dist_date_text']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
                <?php for($i = 0.1; $i <= 5; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['dist_date_text']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
            </select>
        </td>
      </tr>      
      <tr>
        <td class="langLeft">Distance between date and post text in the list of posts:</td>
        <td class="left_top">
        	<select name="list_dist_date_text">
            	<?php for($i=0; $i<=50; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['list_dist_date_text']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
                <?php for($i = 0.1; $i <= 5; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['list_dist_date_text']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
            </select>
        </td>
      </tr>    
      <tr>
        <td class="langLeft">Distance between posts in the list of posts:</td>
        <td class="left_top">
        	<select name="dist_btw_posts">
            	<?php for($i=4; $i<=99; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['dist_btw_posts']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
                <?php for($i = 0.1; $i <= 10; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['dist_btw_posts']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
            </select>
        </td>
      </tr>    
      <tr>
        <td class="langLeft">Distance between short text and "READ MORE" link in the list of posts:</td>
        <td class="left_top">
        	<select name="dist_btw_post_more">
            	<?php for($i=0; $i<=99; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['dist_btw_post_more']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
                <?php for($i = 0.1; $i <= 5; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['dist_btw_post_more']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
            </select>
        </td>
      </tr> 
      <tr>
        <td class="langLeft">Distance between 'Back' link and post title:</td>
        <td class="left_top">
        	<select name="dist_link_title">
            	<?php for($i=0; $i<=50; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['dist_link_title']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
                <?php for($i = 0.1; $i <= 5; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['dist_link_title']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
            </select>
        </td>
      </tr>
      
      <tr>
        <td class="langLeft">Distance between post text and tags:</td>
        <td class="left_top">
        	<select name="dist_text_tags">
            	<?php for($i=0; $i<=50; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['dist_text_tags']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
                <?php for($i = 0.1; $i <= 5; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['dist_text_tags']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
            </select>
        </td>
      </tr>
      
      <tr>
        <td class="langLeft">Distance between tags and bottom navigation:</td>
        <td class="left_top">
        	<select name="dist_tags_nav">
            	<?php for($i=0; $i<=50; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['dist_tags_nav']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
                <?php for($i = 0.1; $i <= 5; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['dist_tags_nav']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
            </select>
        </td>
      </tr>
      
      <tr>
        <td class="langLeft">Distance between bottom navigation and comments:</td>
        <td class="left_top">
        	<select name="dist_comm_links">
            	<?php for($i=0; $i<=50; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['dist_comm_links']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
                <?php for($i = 0.1; $i <= 5; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['dist_comm_links']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
            </select>
        </td>
      </tr>
      
      <tr>
        <td class="langLeft">Distance blog from the bottom:</td>
        <td class="left_top">
        	<select name="dist_from_bottom">
            	<?php for($i=0; $i<=50; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['dist_from_bottom']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
                <?php for($i = 0.1; $i <= 5; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['dist_from_bottom']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
            </select>
        </td>
      </tr>
      
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit8" type="submit" value="Save" class="submitButton" /></td>
      </tr>     
    </table>
    </div>
    
    </div>
	</form> 


<?php
} elseif ($_REQUEST["act"]=='visual_options_comm') {
	$sql = "SELECT * FROM ".$TABLE["Options"];
	$sql_result = sql_result($sql);
	$Options = mysqli_fetch_assoc($sql_result);
	$OptionsVis = unserialize($Options['visual_comm']);
?>
	<script type="text/javascript">
        Event.observe(window, 'load', loadAccordions, false);
        function loadAccordions() {
            var bottomAccordion = new accordion('accordion_container');	
            // Open first one
            //bottomAccordion.activate($$('#accordion_container .accordion_toggle')[0]);
        }	
    </script>
	
    <div class="pageDescr">Click on any of the styles to see the options.</div>
    
    <form action="admin.php" method="post" name="form">
	<input type="hidden" name="act" value="updateOptionsComm" />
    
    <div class="opt_headlist">Set comments front-end visual style 
    	<span class="opt_headl_tip">(Advanced users may work directly with CSS file located in styles/css_front_end.php)</span>. 
    </div>

    <div id="accordion_container"> 
	
    <div class="accordion_toggle">Words "Comments:"(No comments posted...) over the comments list</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables"> 
      <tr>
        <td class="langLeft">Font-family:</td>
        <td class="left_top">
        	<select name="w_comm_font_family">
            	<?php echo font_family_list($OptionsVis['w_comm_font_family']); ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Font-color:</td>
        <td class="left_top"><?php echo color_field("w_comm_font_color", $OptionsVis["w_comm_font_color"]); ?></td>
      </tr>      
      <tr>
        <td class="langLeft">Font-size:</td>
        <td class="left_top">
        	<select name="w_comm_font_size">
            	<option value="inherit"<?php if($OptionsVis['w_comm_font_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
                <?php for($i = 0.5; $i <= 5; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['w_comm_font_size']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
                <?php for($i=9; $i<=32; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['w_comm_font_size']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr>  
      <tr>
        <td class="langLeft">Font-style:</td>
        <td class="left_top">
        	<select name="w_comm_font_style">
            	<option value="normal"<?php if($OptionsVis['w_comm_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['w_comm_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="inherit"<?php if($OptionsVis['w_comm_font_style']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Font-weight:</td>
        <td class="left_top">
        	<select name="w_comm_font_weight">
            	<option value="normal"<?php if($OptionsVis['w_comm_font_weight']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="bold"<?php if($OptionsVis['w_comm_font_weight']=='bold') echo ' selected="selected"'; ?>>bold</option>
                <option value="inherit"<?php if($OptionsVis['w_comm_font_weight']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>           
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit2" type="submit" value="Save" class="submitButton" /></td>
      </tr> 
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
	</table>
    </div>
      
    
    <div class="accordion_toggle">Comments name style</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables"> 
      <tr>
        <td class="langLeft">Comment border color:</td>
        <td class="left_top"><?php echo color_field("comm_bord_color", $OptionsVis["comm_bord_color"]); ?></td>
      </tr>  
      <tr>
        <td class="langLeft">Comments name font-family:</td>
        <td class="left_top">
        	<select name="name_font">
            	<?php echo font_family_list($OptionsVis['name_font']); ?>
            </select>
        </td>
      </tr> 
      <tr>
        <td class="langLeft">Comment name font color:</td>
        <td class="left_top"><?php echo color_field("name_font_color", $OptionsVis["name_font_color"]); ?></td>
      </tr>      
      <tr>
        <td class="langLeft">Comment name font-size:</td>
        <td class="left_top">
        	<select name="name_font_size">
            	<option value="inherit"<?php if($OptionsVis['name_font_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
                <?php for($i = 0.5; $i <= 5; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['name_font_size']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
                <?php for($i=9; $i<=32; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['name_font_size']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr>  
      <tr>
        <td class="langLeft">Comment name font-style:</td>
        <td class="left_top">
        	<select name="name_font_style">
            	<option value="normal"<?php if($OptionsVis['name_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['name_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="inherit"<?php if($OptionsVis['name_font_style']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Comment name font-weight:</td>
        <td class="left_top">
        	<select name="name_font_weight">
            	<option value="normal"<?php if($OptionsVis['name_font_weight']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="bold"<?php if($OptionsVis['name_font_weight']=='bold') echo ' selected="selected"'; ?>>bold</option>
                <option value="inherit"<?php if($OptionsVis['name_font_weight']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>           
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit3" type="submit" value="Save" class="submitButton" /></td>
      </tr> 
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
	</table>
    </div>
      
    
    <div class="accordion_toggle">Comments date style</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables"> 
      <tr>
        <td class="langLeft">Comments date font-family:</td>
        <td class="left_top">
        	<select name="comm_date_font">
            	<?php echo font_family_list($OptionsVis['comm_date_font']); ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Comments date color:</td>
        <td class="left_top"><?php echo color_field("comm_date_color", $OptionsVis["comm_date_color"]); ?></td>
      </tr>
      <tr>
        <td class="langLeft">Comments date font-size:</td>
        <td class="left_top">
        	<select name="comm_date_size">
            	<option value="inherit"<?php if($OptionsVis['comm_date_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
                <?php for($i = 0.5; $i <= 5; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['comm_date_size']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
            	<?php for($i=9; $i<=32; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['comm_date_size']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Comments date font-style:</td>
        <td class="left_top">
        	<select name="comm_date_font_style">
            	<option value="normal"<?php if($OptionsVis['comm_date_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['comm_date_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="oblique"<?php if($OptionsVis['comm_date_font_style']=='oblique') echo ' selected="selected"';?>>oblique</option>
                <option value="inherit"<?php if($OptionsVis['comm_date_font_style']=='inherit') echo ' selected="selected"';?>>inherit</option>
            </select>
        </td>
      </tr>      
      <tr>
        <td class="langLeft">Comments date format:</td>
        <td class="left_top">
        	<select name="comm_date_format">
            	<option value="l - F j, Y"<?php if($OptionsVis['comm_date_format']=='l - F j, Y') echo ' selected="selected"'; ?>><?php echo date("l - F j, Y"); ?></option>
                <option value="l - F j Y"<?php if($OptionsVis['comm_date_format']=='l - F j Y') echo ' selected="selected"'; ?>><?php echo date("l - F j Y"); ?></option>
                <option value="l, F j Y"<?php if($OptionsVis['comm_date_format']=='l, F j Y') echo ' selected="selected"'; ?>><?php echo date("l, F j Y"); ?></option>
            	<option value="l, F j, Y"<?php if($OptionsVis['comm_date_format']=='l, F j, Y') echo ' selected="selected"'; ?>><?php echo date("l, F j, Y"); ?></option>
                <option value="l F j Y"<?php if($OptionsVis['comm_date_format']=='l F j Y') echo ' selected="selected"'; ?>><?php echo date("l F j Y"); ?></option>
                <option value="l F j, Y"<?php if($OptionsVis['comm_date_format']=='l F j Y') echo ' selected="selected"'; ?>><?php echo date("l F j, Y"); ?></option>
                <option value="F j Y"<?php if($OptionsVis['comm_date_format']=='F j Y') echo ' selected="selected"'; ?>><?php echo date("F j Y"); ?></option>
                <option value="F j, Y"<?php if($OptionsVis['comm_date_format']=='F j, Y') echo ' selected="selected"'; ?>><?php echo date("F j, Y"); ?></option>
                <option value="F jS, Y"<?php if($OptionsVis['comm_date_format']=='F jS, Y') echo ' selected="selected"'; ?>><?php echo date("F jS, Y"); ?></option>
                <option value="F Y"<?php if($OptionsVis['comm_date_format']=='F Y') echo ' selected="selected"'; ?>><?php echo date("F Y"); ?></option>
                <option value="m-d-Y"<?php if($OptionsVis['comm_date_format']=='m-d-Y') echo ' selected="selected"'; ?>>MM-DD-YYYY</option>
                <option value="m.d.Y"<?php if($OptionsVis['comm_date_format']=='m.d.Y') echo ' selected="selected"'; ?>>MM.DD.YYYY</option>
                <option value="m/d/Y"<?php if($OptionsVis['comm_date_format']=='m/d/Y') echo ' selected="selected"'; ?>>MM/DD/YYYY</option>
                <option value="m-d-y"<?php if($OptionsVis['comm_date_format']=='m-d-y') echo ' selected="selected"'; ?>>MM-DD-YY</option>
                <option value="m.d.y"<?php if($OptionsVis['comm_date_format']=='m.d.y') echo ' selected="selected"'; ?>>MM.DD.YY</option>
                <option value="m/d/y"<?php if($OptionsVis['comm_date_format']=='m/d/y') echo ' selected="selected"'; ?>>MM/DD/YY</option>
                <option value="l - j F, Y"<?php if($OptionsVis['comm_date_format']=='l - j F, Y') echo ' selected="selected"'; ?>><?php echo date("l - j F, Y"); ?></option>
                <option value="l - j F Y"<?php if($OptionsVis['comm_date_format']=='l - j F Y') echo ' selected="selected"'; ?>><?php echo date("l - j F Y"); ?></option>
                <option value="l, j F Y"<?php if($OptionsVis['comm_date_format']=='l, j F Y') echo ' selected="selected"'; ?>><?php echo date("l, j F Y"); ?></option>
                <option value="l, j F, Y"<?php if($OptionsVis['comm_date_format']=='l, j F, Y') echo ' selected="selected"'; ?>><?php echo date("l, j F, Y"); ?></option>
                <option value="l j F Y"<?php if($OptionsVis['comm_date_format']=='l j F Y') echo ' selected="selected"'; ?>><?php echo date("l j F Y"); ?></option>
                <option value="l j F, Y"<?php if($OptionsVis['comm_date_format']=='l j F, Y') echo ' selected="selected"'; ?>><?php echo date("l j F, Y"); ?></option>
                <option value="d F Y"<?php if($OptionsVis['comm_date_format']=='d F Y') echo ' selected="selected"'; ?>><?php echo date("d F Y"); ?></option>
                <option value="d F, Y"<?php if($OptionsVis['comm_date_format']=='d F, Y') echo ' selected="selected"'; ?>><?php echo date("d F, Y"); ?></option>
                <option value="d-m-Y"<?php if($OptionsVis['comm_date_format']=='d-m-Y') echo ' selected="selected"'; ?>>DD-MM-YYYY</option>
                <option value="d.m.Y"<?php if($OptionsVis['comm_date_format']=='d.m.Y') echo ' selected="selected"'; ?>>DD.MM.YYYY</option>
                <option value="d/m/Y"<?php if($OptionsVis['comm_date_format']=='d/m/Y') echo ' selected="selected"'; ?>>DD/MM/YYYY</option>
                <option value="d-m-y"<?php if($OptionsVis['comm_date_format']=='d-m-y') echo ' selected="selected"'; ?>>DD-MM-YY</option>
                <option value="d.m.y"<?php if($OptionsVis['comm_date_format']=='d.m.y') echo ' selected="selected"'; ?>>DD.MM.YY</option>
                <option value="d/m/y"<?php if($OptionsVis['comm_date_format']=='d/m/y') echo ' selected="selected"'; ?>>DD/MM/YY</option>
            </select>
        </td>
      </tr>      
      <tr>
        <td class="langLeft">Showing comment time:</td>
        <td class="left_top">
        	<select name="comm_showing_time">
            	<option value=""<?php if($OptionsVis['comm_showing_time']=='') echo ' selected="selected"'; ?>>without time</option>
            	<option value="G:i"<?php if($OptionsVis['comm_showing_time']=='G:i') echo ' selected="selected"'; ?>>24h format</option>
            	<option value="g:i a"<?php if($OptionsVis['comm_showing_time']=='g:i a') echo ' selected="selected"'; ?>>12h format</option>
            </select>
        </td>
      </tr>      
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit4" type="submit" value="Save" class="submitButton" /></td>
      </tr> 
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
    </table>
    </div>
      
    
    <div class="accordion_toggle">Comments text style</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables"> 
      <tr>
        <td class="langLeft">Comments text font-family:</td>
        <td class="left_top">
        	<select name="comm_font">
            	<?php echo font_family_list($OptionsVis['comm_font']); ?>
            </select>
        </td>
      </tr> 
      <tr>
        <td class="langLeft">Comment text font color:</td>
        <td class="left_top"><?php echo color_field("comm_font_color", $OptionsVis["comm_font_color"]); ?></td>
      </tr>      
      <tr>
        <td class="langLeft">Comment text font-size:</td>
        <td class="left_top">
        	<select name="comm_font_size">
            	<option value="inherit"<?php if($OptionsVis['comm_font_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
                <?php for($i = 0.5; $i <= 5; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['comm_font_size']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
                <?php for($i=9; $i<=32; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['comm_font_size']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr>  
      <tr>
        <td class="langLeft">Comment text font-style:</td>
        <td class="left_top">
        	<select name="comm_font_style">
            	<option value="normal"<?php if($OptionsVis['comm_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['comm_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="inherit"<?php if($OptionsVis['comm_font_style']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Comment text font-weight:</td>
        <td class="left_top">
        	<select name="comm_font_weight">
            	<option value="normal"<?php if($OptionsVis['comm_font_weight']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="bold"<?php if($OptionsVis['comm_font_weight']=='bold') echo ' selected="selected"'; ?>>bold</option>
                <option value="inherit"<?php if($OptionsVis['comm_font_weight']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>           
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit5" type="submit" value="Save" class="submitButton" /></td>
      </tr> 
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
    </table>
    </div>
      
      
    <div class="accordion_toggle">"Leave a comment" above the form</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables"> 
      <tr>
        <td class="langLeft">"Leave a comment" font-family:</td>
        <td class="left_top">
        	<select name="leave_font_family">
            	<?php echo font_family_list($OptionsVis['leave_font_family']); ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">"Leave a comment" font color:</td>
        <td class="left_top"><?php echo color_field("leave_font_color", $OptionsVis["leave_font_color"]); ?></td>
      </tr> 
      <tr>
        <td class="langLeft">"Leave a comment" text font-size:</td>
        <td class="left_top">
        	<select name="leave_font_size">
            	<option value="inherit"<?php if($OptionsVis['leave_font_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
                <?php for($i = 0.5; $i <= 5; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['leave_font_size']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
                <?php for($i=9; $i<=32; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['leave_font_size']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr> 
      <tr>
        <td class="langLeft">"Leave a comment" text font-weight:</td>
        <td class="left_top">
        	<select name="leave_font_weight">
            	<option value="normal"<?php if($OptionsVis['leave_font_weight']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="bold"<?php if($OptionsVis['leave_font_weight']=='bold') echo ' selected="selected"'; ?>>bold</option>
                <option value="inherit"<?php if($OptionsVis['leave_font_weight']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr> 
      <tr>
        <td class="langLeft">"Leave a comment" text font-style:</td>
        <td class="left_top">
        	<select name="leave_font_style">
            	<option value="normal"<?php if($OptionsVis['leave_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['leave_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="inherit"<?php if($OptionsVis['leave_font_style']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>          
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit6" type="submit" value="Save" class="submitButton" /></td>
      </tr> 
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
    </table>
    </div>
    
    
    <div class="accordion_toggle">Comments form style</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables">  
      <tr>
        <td class="langLeft">Comments form fields font-family:</td>
        <td class="left_top">
        	<select name="field_font_family">
            	<?php echo font_family_list($OptionsVis['field_font_family']); ?>
            </select>
        </td>
      </tr> 
      <tr>
        <td class="langLeft">Comments form fields font color:</td>
        <td class="left_top"><?php echo color_field("field_font_color", $OptionsVis["field_font_color"]); ?></td>
      </tr> 
      <tr>
        <td class="langLeft">Comments form fields border color:</td>
        <td class="left_top"><?php echo color_field("field_bgr_color", $OptionsVis["field_bgr_color"]); ?></td>
      </tr>    
      <tr>
        <td class="langLeft">Comments form field labels font-size:</td>
        <td class="left_top">
        	<select name="field_font_size">
            	<option value="inherit"<?php if($OptionsVis['field_font_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
                <?php for($i = 0.5; $i <= 5; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['field_font_size']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
                <?php for($i=9; $i<=32; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['field_font_size']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr>            
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit6" type="submit" value="Save" class="submitButton" /></td>
      </tr> 
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
    </table>
    </div>
    
    
    <div class="accordion_toggle">"SUBMIT COMMENT" button style</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables">   
      <tr>
        <td class="langLeft">Font-family:</td>
        <td class="left_top">
        	<select name="subm_font_family">
            	<?php echo font_family_list($OptionsVis['subm_font_family']); ?>
            </select>
        </td>
      </tr>       
      <tr>
        <td class="langLeft">Text color:</td>
        <td class="left_top"><?php echo color_field("subm_color", $OptionsVis["subm_color"]); ?></td>
      </tr>
      <tr>
        <td class="langLeft">Border color(+ color on hover):</td>
        <td class="left_top"><?php echo color_field("subm_brdr_color", $OptionsVis["subm_brdr_color"]); ?></td>
      </tr>
      <tr>
        <td class="langLeft">Background-color:</td>
        <td class="left_top"><?php echo color_field("subm_bgr_color", $OptionsVis["subm_bgr_color"]); ?></td>
      </tr> 
      <tr>
        <td class="langLeft">Background-color on hover(on mouseover):</td>
        <td class="left_top"><?php echo color_field("subm_bgr_color_on", $OptionsVis["subm_bgr_color_on"]); ?></td>
      </tr>    
      <tr>
        <td class="langLeft">Font-size:</td>
        <td class="left_top">
        	<select name="subm_font_size">
            	<option value="inherit"<?php if($OptionsVis['subm_font_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
                <?php for($i = 0.5; $i <= 5; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['subm_font_size']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
                <?php for($i=9; $i<=32; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['subm_font_size']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr>     
      <tr>
        <td class="langLeft">Button radius:</td>
        <td class="left_top">
        	<select name="subm_bor_radius">
                <?php for($i=0; $i<=10; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['subm_bor_radius']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
            </select>
        </td>
      </tr> 
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit2" type="submit" value="Save" class="submitButton" /></td>
      </tr> 
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
	</table>
    </div>
      
      
    <div class="accordion_toggle">Distances</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables"> 
      <tr>
        <td class="langLeft">Distance between comments:</td>
        <td class="left_top">
        	<select name="dist_btw_comm">
            	<?php for($i=0; $i<=60; $i++) {?>
            	<option value="<?php echo $i;?>px"<?php if($OptionsVis['dist_btw_comm']==$i.'px') echo ' selected="selected"'; ?>><?php echo $i;?>px</option>
                <?php } ?>
                <?php for($i = 0.1; $i <= 10; $i = $i + 0.1) {?>
            	<option value="<?php echo $i;?>em"<?php if($OptionsVis['dist_btw_comm']==$i."em") echo ' selected="selected"'; ?>><?php echo $i;?>em</option>
                <?php } ?>
            </select>
        </td>
      </tr>    
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit7" type="submit" value="Save" class="submitButton" /></td>
      </tr>
      <tr>
        <td colspan="3" height="8"></td>
      </tr>      
    </table>
    </div>
    
    </div>
	</form> 

  
    
<?php
} elseif ($_REQUEST["act"]=='language_options') {
	$sql = "SELECT * FROM ".$TABLE["Options"];
	$sql_result = sql_result($sql);
	$Options = mysqli_fetch_assoc($sql_result);
	mysqli_free_result($sql_result);
	$OptionsLang = unserialize($Options['language']);
?>
	<script type="text/javascript">
		Event.observe(window, 'load', loadAccordions, false);
		function loadAccordions() {
			var bottomAccordion = new accordion('accordion_container');	
			// Open first one
			//bottomAccordion.activate($$('#accordion_container .accordion_toggle')[0]);
		}	
	</script>
	
    <div class="pageDescr">Click on any of the line to see the options.</div>
    
    <form action="admin.php" method="post" name="frm">
	<input type="hidden" name="act" value="updateOptionsLanguage" />
    
    <div class="opt_headlist">Translate blog front-end in your own language. </div>
    
    <div id="accordion_container"> 
    <div class="accordion_toggle">Blog main wordings - navigations, links, search button, category and paging</div>
    <div class="accordion_content">
    <table border="0" cellspacing="0" cellpadding="8" class="allTables">      
      <tr>
        <td class="langLeft">Back:</td>
        <td class="left_top"><input class="input_lan" name="Back_home" type="text" value="<?php echo ReadHTML($OptionsLang["Back_home"]); ?>" /> &nbsp; <sub> - leave empty if you don't need this link</sub></td>
      </tr>  
      <tr>
        <td class="langLeft">'Search' placeholder:</td>
        <td class="left_top"><input class="input_lan" name="Search_button" type="text" value="<?php echo ReadHTML($OptionsLang["Search_button"]); ?>" /></td>
      </tr> 
      <tr>
        <td class="langLeft">CATEGORIES:</td>
        <td class="left_top"><input class="input_lan" name="Category" type="text" value="<?php echo ReadDB($OptionsLang["Category"]); ?>" /></td>
      </tr>      
      <tr>
        <td class="langLeft">"All categories" link:</td>
        <td class="left_top"><input class="input_lan" name="Category_all" type="text" value="<?php echo ReadDB($OptionsLang["Category_all"]); ?>" /></td>
      </tr> 
       
      <tr>
        <td class="langLeft">RECENT POSTS:</td>
        <td class="left_top"><input class="input_lan" name="Recent_Posts" type="text" value="<?php echo ReadDB($OptionsLang["Recent_Posts"]); ?>" /></td>
      </tr> 
       
      <tr>
        <td class="langLeft">ARCHIVES:</td>
        <td class="left_top"><input class="input_lan" name="Archives" type="text" value="<?php echo ReadDB($OptionsLang["Archives"]); ?>" /></td>
      </tr> 
      <tr>
        <td class="langLeft">'MORE' link:</td>
        <td class="left_top"><input class="input_lan" name="Read_more" type="text" value="<?php echo ReadHTML($OptionsLang["Read_more"]); ?>" /> &nbsp; <sub> - leave empty if you don't need this link</sub></td>
      </tr>  
      <tr>
        <td class="langLeft">'COMMENTS' link:</td>
        <td class="left_top"><input class="input_lan" name="Comments_link" type="text" value="<?php echo ReadHTML($OptionsLang["Comments_link"]); ?>" /></td>
      </tr>
      <tr>
        <td class="langLeft">'Tagged as':</td>
        <td class="left_top"><input class="input_lan" name="Tagged_as" type="text" value="<?php echo ReadHTML($OptionsLang["Tagged_as"]); ?>" /></td>
      </tr> 
      <tr>
        <td class="langLeft">No Posts To List:</td>
        <td class="left_top"><input class="input_lan" name="No_Posts" type="text" value="<?php echo ReadHTML($OptionsLang["No_Posts"]); ?>" /></td>
      </tr> 
      <tr>
        <td class="langLeft">Link "Older Post" at the bottom of the post:</td>
        <td class="left_top"><input class="input_lan" name="Older_Post" type="text" value="<?php echo ReadHTML($OptionsLang["Older_Post"]); ?>" /> &nbsp; <sub> - leave empty if you don't need this link</sub></td>
      </tr> 
      <tr>
        <td class="langLeft">Link "Newer Post" at the bottom of the post:</td>
        <td class="left_top"><input class="input_lan" name="Newer_Post" type="text" value="<?php echo ReadHTML($OptionsLang["Newer_Post"]); ?>" /> &nbsp; <sub> - leave empty if you don't need this link</sub></td>
      </tr> 
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit" type="submit" value="Save" class="submitButton" /></td>
      </tr> 
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
    </table> 
    </div> 
      
   
   	<div class="accordion_toggle">Days of the week in the date</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables">  
      <tr>
        <td class="langLeft">Monday:</td>
        <td class="left_top"><input class="input_lan" name="Monday" type="text" value="<?php echo ReadHTML($OptionsLang["Monday"]); ?>" /></td>
      </tr>  
      <tr>
        <td class="langLeft">Tuesday:</td>
        <td class="left_top"><input class="input_lan" name="Tuesday" type="text" value="<?php echo ReadHTML($OptionsLang["Tuesday"]); ?>" /></td>
      </tr> 
      <tr>
        <td class="langLeft">Wednesday:</td>
        <td class="left_top"><input class="input_lan" name="Wednesday" type="text" value="<?php echo ReadHTML($OptionsLang["Wednesday"]); ?>" /></td>
      </tr>  
      <tr>
        <td class="langLeft">Thursday:</td>
        <td class="left_top"><input class="input_lan" name="Thursday" type="text" value="<?php echo ReadHTML($OptionsLang["Thursday"]); ?>" /></td>
      </tr>  
      <tr>
        <td class="langLeft">Friday:</td>
        <td class="left_top"><input class="input_lan" name="Friday" type="text" value="<?php echo ReadHTML($OptionsLang["Friday"]); ?>" /></td>
      </tr>  
      <tr>
        <td class="langLeft">Saturday:</td>
        <td class="left_top"><input class="input_lan" name="Saturday" type="text" value="<?php echo ReadHTML($OptionsLang["Saturday"]); ?>" /></td>
      </tr> 
      <tr>
        <td class="langLeft">Sunday:</td>
        <td class="left_top"><input class="input_lan" name="Sunday" type="text" value="<?php echo ReadHTML($OptionsLang["Sunday"]); ?>" /></td>
      </tr>           
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit1" type="submit" value="Save" class="submitButton" /></td>
      </tr>
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
    </table> 
    </div> 
    
    
   	<div class="accordion_toggle">Months in the date</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables">
      <tr>
        <td class="langLeft">January:</td>
        <td class="left_top"><input class="input_lan" name="January" type="text" value="<?php echo ReadHTML($OptionsLang["January"]); ?>" /></td>
      </tr>  
      <tr>
        <td class="langLeft">February:</td>
        <td class="left_top"><input class="input_lan" name="February" type="text" value="<?php echo ReadHTML($OptionsLang["February"]); ?>" /></td>
      </tr> 
      <tr>
        <td class="langLeft">March:</td>
        <td class="left_top"><input class="input_lan" name="March" type="text" value="<?php echo ReadHTML($OptionsLang["March"]); ?>" /></td>
      </tr>  
      <tr>
        <td class="langLeft">April:</td>
        <td class="left_top"><input class="input_lan" name="April" type="text" value="<?php echo ReadHTML($OptionsLang["April"]); ?>" /></td>
      </tr>  
      <tr>
        <td class="langLeft">May:</td>
        <td class="left_top"><input class="input_lan" name="May" type="text" value="<?php echo ReadHTML($OptionsLang["May"]); ?>" /></td>
      </tr>  
      <tr>
        <td class="langLeft">June:</td>
        <td class="left_top"><input class="input_lan" name="June" type="text" value="<?php echo ReadHTML($OptionsLang["June"]); ?>" /></td>
      </tr> 
      <tr>
        <td class="langLeft">July:</td>
        <td class="left_top"><input class="input_lan" name="July" type="text" value="<?php echo ReadHTML($OptionsLang["July"]); ?>" /></td>
      </tr>   
      <tr>
        <td class="langLeft">August:</td>
        <td class="left_top"><input class="input_lan" name="August" type="text" value="<?php echo ReadHTML($OptionsLang["August"]); ?>" /></td>
      </tr> 
      <tr>
        <td class="langLeft">September:</td>
        <td class="left_top"><input class="input_lan" name="September" type="text" value="<?php echo ReadHTML($OptionsLang["September"]); ?>" /></td>
      </tr> 
      <tr>
        <td class="langLeft">October:</td>
        <td class="left_top"><input class="input_lan" name="October" type="text" value="<?php echo ReadHTML($OptionsLang["October"]); ?>" /></td>
      </tr> 
      <tr>
        <td class="langLeft">November:</td>
        <td class="left_top"><input class="input_lan" name="November" type="text" value="<?php echo ReadHTML($OptionsLang["November"]); ?>" /></td>
      </tr>   
      <tr>
        <td class="langLeft">December:</td>
        <td class="left_top"><input class="input_lan" name="December" type="text" value="<?php echo ReadHTML($OptionsLang["December"]); ?>" /></td>
      </tr>       
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit2" type="submit" value="Save" class="submitButton" /></td>
      </tr> 
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
    </table> 
    </div> 
      
    
    <div class="accordion_toggle">Post with the comments page</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables">  
      <tr>
        <td class="langLeft">Word 'Comments' underneath the post:</td>
        <td class="left_top">
          <input class="input_lan" name="Word_Comments" type="text" value="<?php echo ReadHTML($OptionsLang["Word_Comments"]); ?>" /></td>
      </tr>
      <tr>
        <td class="langLeft">Written by:</td>
        <td class="left_top"><input class="input_lan" name="Written_by" type="text" value="<?php echo ReadHTML($OptionsLang["Written_by"]); ?>" /></td>
      </tr>
      <tr>
        <td class="langLeft">on (date):</td>
        <td class="left_top"><input class="input_lan" name="on_date" type="text" value="<?php echo ReadHTML($OptionsLang["on_date"]); ?>" /></td>
      </tr>
      <tr>
        <td class="langLeft">No comments posted:</td>
        <td class="left_top">
          <input class="input_lan" name="No_comments_posted" type="text" value="<?php echo ReadHTML($OptionsLang["No_comments_posted"]); ?>" /> &nbsp; <sub> - leave empty if you don't need these words</sub></td>
      </tr>
      <tr>
        <td class="langLeft">Leave a Comment:</td>
        <td class="left_top">
          <input class="input_lan" name="Leave_Comment" type="text" value="<?php echo ReadHTML($OptionsLang["Leave_Comment"]); ?>" /></td>
      </tr>
      <tr>
        <td class="langLeft">Name:</td>
        <td class="left_top"><input class="input_lan" name="Comment_Name" type="text" value="<?php echo ReadHTML($OptionsLang["Comment_Name"]); ?>" /></td>
      </tr>
      <tr>
        <td class="langLeft">Email:</td>
        <td class="left_top"><input class="input_lan" name="Comment_Email" type="text" value="<?php echo ReadHTML($OptionsLang["Comment_Email"]); ?>" /></td>
      </tr>
      <tr>
        <td class="langLeft">Comment:</td>
        <td class="left_top"><input class="input_lan" name="Comment_here" type="text" value="<?php echo ReadHTML($OptionsLang["Comment_here"]); ?>" /></td>
      </tr>
      <tr>
        <td class="langLeft">Enter verification code:</td>
        <td class="left_top"> <input class="input_lan" name="Enter_verification_code" type="text" value="<?php echo ReadHTML($OptionsLang["Enter_verification_code"]); ?>" /></td>
      </tr>
      <tr>
        <td class="langLeft">Required fields:</td>
        <td class="left_top"><input class="input_lan" name="Required_fields" type="text" value="<?php echo ReadHTML($OptionsLang["Required_fields"]); ?>" /></td>
      </tr>
      <tr>
        <td class="langLeft">Button 'Submit Comment':</td>
        <td class="left_top"><input class="input_lan" name="Submit_Comment" type="text" value="<?php echo ReadHTML($OptionsLang["Submit_Comment"]); ?>" /> </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit3" type="submit" value="Save" class="submitButton" /></td>
      </tr>
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
    </table> 
    </div> 
      
   	
    <div class="accordion_toggle">System messages</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables"> 
      <tr>
        <td class="langLeft">Incorrect verification code:</td>
        <td class="left_top">
          <input class="input_lan" name="Incorrect_verification_code" type="text" value="<?php echo ReadHTML($OptionsLang["Incorrect_verification_code"]); ?>" />        </td>
      </tr>
      <tr>
        <td class="langLeft">Banned word used:</td>
        <td class="left_top">
          <input class="input_lan" name="Banned_word_used" type="text" value="<?php echo ReadHTML($OptionsLang["Banned_word_used"]); ?>" />        </td>
      </tr>    
      <tr>
        <td class="langLeft">Banned IP used:</td>
        <td class="left_top">
          <input class="input_lan" name="Banned_ip_used" type="text" value="<?php echo ReadHTML($OptionsLang["Banned_ip_used"]); ?>" />  </td>
      </tr>       
      <tr>
        <td class="langLeft">Your comment has been submitted:</td>
        <td class="left_top"><input class="input_lan" name="Comment_Submitted" type="text" value="<?php echo ReadHTML($OptionsLang["Comment_Submitted"]); ?>" /></td>
      </tr>
      <tr>
        <td class="langLeft">Will be published after approval of the administrator!:<br />
        <sub>/this message will appear if the option of approving post/comment is checked/</sub></td>
        <td class="left_top"><input class="input_lan" name="After_Approval_Admin" type="text" value="<?php echo ReadHTML($OptionsLang["After_Approval_Admin"]); ?>" /></td>
      </tr> 
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit4" type="submit" value="Save" class="submitButton" /></td>
      </tr>
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
    </table>
    </div>
      
	<div class="accordion_toggle">Popup messages when check the required fields</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables"> 
      <tr>
        <td class="langLeft">Please, fill all required fields:</td>
        <td class="left_top"><input class="input_lan" name="required_fields" type="text" value="<?php echo ReadHTML($OptionsLang["required_fields"]); ?>" /></td>
      </tr>
      <tr>
        <td class="langLeft">Please, fill correct email address:</td>
        <td class="left_top"><input class="input_lan" name="correct_email" type="text" value="<?php echo ReadHTML($OptionsLang["correct_email"]); ?>" /></td>
      </tr>
      <tr>
        <td class="langLeft">Please, enter verification code:</td>
        <td class="left_top"><input class="input_lan" name="field_code" type="text" value="<?php echo ReadHTML($OptionsLang["field_code"]); ?>" /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit5" type="submit" value="Save" class="submitButton" /></td>
      </tr>
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
    </table> 
    </div> 
      
    
    <div class="accordion_toggle">Admin email subjects</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables"> 
      <tr>
        <td class="langLeft">Email subject when new comment posted:</td>
        <td class="left_top"><input class="input_lan" name="New_comment_posted" type="text" value="<?php echo ReadHTML($OptionsLang["New_comment_posted"]); ?>" /></td>
      </tr>      
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit6" type="submit" value="Save" class="submitButton" /></td>
      </tr>
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
	</table>
    </div>
    
    
    <div class="accordion_toggle">Default meta tags for blog page</div>
    <div class="accordion_content">  
	<table border="0" cellspacing="0" cellpadding="8" class="allTables"> 
      <tr>
        <td class="langLeft">Meta title:</td>
        <td class="left_top"><input class="input_lan" name="metatitle" type="text" value="<?php echo ReadHTML($OptionsLang["metatitle"]); ?>" /></td>
      </tr>
      <tr>
        <td class="langLeft">Meta description:</td>
        <td class="left_top"><input class="input_lan" name="metadescription" type="text" value="<?php echo ReadHTML($OptionsLang["metadescription"]); ?>" /></td>
      </tr>
      
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit6" type="submit" value="Save" class="submitButton" /></td>
      </tr>
      <tr>
        <td colspan="3" height="8"></td>
      </tr>
    </table> 
    </div> 
      
    </div> 
	</form>

<?php
} elseif ($_REQUEST["act"]=='html') {
?>
	<div class="pageDescr">There are two easy ways to put the blog script on your website.</div>

	<table border="0" cellspacing="0" cellpadding="8" class="allTables">
      <tr>
        <td class="copycode">1) <strong>Using iframe code</strong> - just copy the code below and put it on your web page where you want the blog to appear.</td>
      </tr>
      <tr>
      	<td class="putonwebpage">        	
        	<div class="divCode">&lt;iframe src=&quot;<?php echo $CONFIG["full_url"]; ?>preview.php&quot; frameborder=&quot;0&quot; scrolling=&quot;auto&quot; width=&quot;100%&quot; onload='this.style.height=this.contentWindow.document.body.scrollHeight + &quot;px&quot;;'&gt;&lt;/iframe&gt; </div>     
        </td>
      </tr>
    </table>
    
    <table border="0" cellspacing="0" cellpadding="8" class="allTables">
    
      <tr>
        <td class="copycode">2) <strong>Using PHP include()</strong> - you can use a PHP include() in any of your PHP pages. Edit your .php page and put the code below where you want the blog to be.</td>
      </tr>
      
      <tr>
        <td class="putonwebpage">        	
        	<div class="divCode">&lt;?php include(&quot;<?php echo $CONFIG["server_path"]; ?>blog.php&quot;); ?&gt; </div>     
        </td>
      </tr>
      
      <tr>
      	<td>
        	At the top of the php page (first line) you should put this line of code too so captcha image verification can work on the comment form.
        </td>
      </tr>
      
      <tr>
        <td class="putonwebpage">        	
        	<div class="divCode">&lt;?php session_start(); ?&gt;</div>     
        </td>
      </tr>
      
      <tr>
      	<td>
        	Optionally in the head section of the php page you could put(or replace your meta tags) this line of code, so meta title and meta description will work for better searching engine optimization.
        </td>
      </tr>
      
      <tr>
        <td class="putonwebpage">        	
        	<div class="divCode">&lt;?php include(&quot;<?php echo $CONFIG["server_path"]; ?>meta.php&quot;); ?&gt; </div>     
        </td>
      </tr>
      
      <tr>
        <td class="putonwebpage">        	
        	<div>If you have any problems, please do not hesitate to contact us at info@simplephpscripts.com</div>     
        </td>
      </tr>
            
    </table>

<?php
} elseif ($_REQUEST["act"]=='rss') {
?>
    
    <div class="pageDescr">The RSS feed allows other people to keep track of your blog using rss readers and to use your posts on their websites. Every time you publish a new post it will appear on your RSS feed and every one using it will be informed about it.</div>
    
    <table border="0" cellspacing="0" cellpadding="8" class="allTables">
    
      <tr>
        <td class="copycode">You can view the RSS feed <a href="rss.php" target="_blank">here</a> or use the code below to place it on your website as RSS link</td>
      </tr>
      
      <tr>
        <td class="putonwebpage">        	
        	<div class="divCode">&lt;a href=&quot;<?php echo $CONFIG["full_url"]; ?>rss.php&quot; target=&quot;_blank&quot;&gt;RSS feed&lt;/a&gt;</div>     
        </td>
      </tr>
            
    </table>
    
<?php
}
?>
</div>


<?php 
} else { ////// Login Form //////
?>
<div class="admin_wrapper login_wrapper">
    <div class="login_head"><?php echo $lang['ADMIN_LOGIN']; ?></div>
    
    <div class="login_sub"><?php echo $lang['Login_context']; ?> </div>
    <form action="admin.php" method="post">
    <input type="hidden" name="act" value="login">
    <table border="0" cellspacing="0" cellpadding="0" class="loginTable">
      <tr>
        <td class="userpass"><?php echo $lang['Username']; ?> </td>
        <td class="userpassfield"><input name="user" type="text" class="loginfield" style="float:left;" /> <?php if(isset($logMessage) and $logMessage!='') {?><div class="logMessage"><?php echo $logMessage; ?></div><?php } ?></td>
      </tr>
      <tr>
        <td class="userpass"><?php echo $lang['Password']; ?> </td>
        <td class="userpassfield"><input name="pass" type="password" class="loginfield" /></td>
      </tr>
      <tr>
        <td class="userpass">&nbsp;</td>
        <td class="userpassfield"><input type="submit" name="button" value="<?php echo $lang['Login']; ?>" class="loginButon" /></td>
      </tr>
    </table>
    </form>
</div>
<?php 
}
?>

<div class="clearfooter"></div>
<div class="divProfiAnts"> <a class="footerlink" href="http://simplephpscripts.com" target="_blank">Product of SimplePHPscripts.com</a></div>

</body>
</html>