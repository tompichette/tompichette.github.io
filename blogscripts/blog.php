<?php 
namespace SimpleBlogPHP30;
$installed = '';
if(!isset($configs_are_set_blog)) {
	include( dirname(__FILE__). "/configs.php");
}

//$thisPage = $_SERVER['PHP_SELF'];
$phpSelf = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL);
$thisPage = $phpSelf;


$sql = "SELECT * FROM ".$TABLE["Options"];
$sql_result = sql_result($sql);
$Options = mysqli_fetch_assoc($sql_result);
mysqli_free_result($sql_result);
$Options["comm_req"] = unserialize($Options["comm_req"]);
$OptionsVis = unserialize($Options['visual']);
$OptionsVisC = unserialize($Options['visual_comm']);
$OptionsLang = unserialize($Options['language']);


if(!isset($_REQUEST["search"])) $_REQUEST["search"] = ''; 
if(!isset($_REQUEST["p"])) { 
	$_REQUEST["p"] = ''; 
} elseif(isset($_REQUEST["p"]) and $_REQUEST["p"]!="") {
	$_REQUEST["p"]= (int) urlencode($_REQUEST["p"]);
}

$search='';
if(isset($_REQUEST["search"]) and ($_REQUEST["search"]!="")) {
	$find = SafetyDB(urldecode($_REQUEST["search"]));
	$search .= " AND (`post_title` LIKE '%".$find."%' OR `post_text` LIKE '%".$find."%')";
}  

if(isset($_REQUEST["cat_id"]) and $_REQUEST["cat_id"]!='') { 
	$_REQUEST["cat_id"] = (int) SafetyDB($_REQUEST["cat_id"]);
} else {
	$_REQUEST["cat_id"] = ''; 
}
if ($_REQUEST["cat_id"]>0) $search .= " AND `cat_id`= ".SafetyDB(htmlentities($_REQUEST["cat_id"]));


if(isset($_REQUEST["ym"]) and $_REQUEST["ym"]!='') { 
	$_REQUEST["ym"] = (int) SafetyDB($_REQUEST["ym"]);
} else {
	$_REQUEST["ym"] = ''; 
}
if ($_REQUEST["ym"]!="") $search = " AND EXTRACT(YEAR_MONTH FROM publish_date)='".SafetyDB(htmlentities($_REQUEST["ym"]))."'";


// defining recurring url variables in URLs
$url_vars = "";
if (isset($_REQUEST["pid"]) and $_REQUEST["pid"]>0) $url_vars = "?p="; else $url_vars = "&amp;p=";
if(isset($_REQUEST["p"]) and $_REQUEST["p"]!='') $url_vars .= urlencode($_REQUEST["p"]);
if(isset($_REQUEST["cat_id"]) and $_REQUEST["cat_id"]>0) $url_vars .= "&amp;cat_id=".urlencode($_REQUEST["cat_id"]);
if(isset($_REQUEST["search"]) and $_REQUEST["search"]!='') $url_vars .= "&amp;search=".urlencode($_REQUEST["search"]);
if(isset($_REQUEST["ym"]) and $_REQUEST["search"]!='') $url_vars .= "&amp;ym=".urlencode($_REQUEST["ym"]);
// adding anchor to URLs. Comment if no need of anchors
$comm_url = $url_vars."#comments";
//$url_vars .= "#blt";

// adding anchor to URLs. Make it $anchor_blt = "#blt"; if you need anchors
$anchor_blt = "";


if(trim($Options['time_zone'])!='') {
	date_default_timezone_set(trim($Options['time_zone']));
}
$cur_date = date('Y-m-d H:i:s');

if(!function_exists(__NAMESPACE__ . '\lang_date')){ 
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

if (isset($_POST["act"]) and $_POST["act"]=='post_comment') {
	
	
	/////////////////////////////////////////////////
	////// checking for correct captcha starts //////
	if($Options['captcha']=='nocap') { // if the option is set to no Captcha
	
		$testvariable = true;	// test variable is set to true
	
	} elseif($Options['captcha']=='phpcaptcha') { // if the option is set to phpcaptcha
		
		//echo $_POST['captcha_code']." = ".$securimage->check($_POST['captcha_code']);
		
		$testvariable = false;	// test variable is set to false.
		
		if ($securimage->check($_POST['captcha_code']) == true) { // test variable is set	to true			
			$testvariable = true;			
		} else {		
			$SysMessage =  ReadDB($OptionsLang["Incorrect_verification_code"]); 
			unset($_REQUEST["act"]);
		} 
		
	} else { // if is set to math, simple or very simple captcha option
	
		$testvariable = false;	// test variable is set to false.
		
		if (preg_match('/^'.$_SESSION['key'].'$/i', $_REQUEST['string'])) { // test variable is set	to true			
			$testvariable = true;			
		} else {		
			$SysMessage =  ReadDB($OptionsLang["Incorrect_verification_code"]); 
			unset($_REQUEST["act"]);
		}
		
	}
	////// checking for correct captcha ends //////
	///////////////////////////////////////////////
	
	
	if ($testvariable==true) { // if test variable is set to true, then go to update database and send emails
	
		if ($Options["approval"]=='true') {			
			$status = 'Not approved';
		} else {
			$status = 'Approved';
		}
		
		$WordAllowed = true;
		$BannedWords = explode(",", ReadDB($Options["ban_words"]));
		if (count($BannedWords)>0) {
		  $checkComment = strtolower($_REQUEST["comment"]);
		  for($i=0;$i<count($BannedWords);$i++){
			  $banWord = trim($BannedWords[$i]);
			  if (trim($BannedWords[$i])<>'') {
				  if(preg_match("/".$banWord."/i", $checkComment)){ 
					  $WordAllowed = false;
					  break;
				  }
			  }
		  }
		}
		
		
		$IPAllowed = true;
		$BannedIPs = explode(",", ReadDB($Options["ban_ips"]));
		if (count($BannedIPs)>0) {
		  $checkIP = strtolower($_SERVER["REMOTE_ADDR"]);
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
		
		// check for required fields
		$emptyReqField = true;
		if(trim($_REQUEST["name"])=='') {
			$emptyReqField = false;
		}
		if (!empty($Options["comm_req"]) and in_array("Email", $Options["comm_req"])) {
			if (trim($_REQUEST["email"])=='') { 
				$emptyReqField = false;
			}
		}
		if (trim($_REQUEST["comment"])=='') { 
			$emptyReqField = false;
		}
		
		if($WordAllowed==false) {
			 $SysMessage =  $OptionsLang["Banned_word_used"]; 
		} elseif($IPAllowed==false) {
			 $SysMessage = ReadDB($OptionsLang["Banned_ip_used"]); 
		} elseif($emptyReqField==false) {
			 $SysMessage =  ReadDB($OptionsLang["required_fields"]);
		} else {
			
			$sql = "INSERT INTO ".$TABLE["Comments"]."
					SET `publish_date` 	= '".$cur_date."',
						`ipaddress` 	= '".SafetyDB($_SERVER["REMOTE_ADDR"])."',
					  	`status` 		= '".$status."',
					  	`post_id` 		= '".SafetyDB($_REQUEST["pid"])."',
					  	`name` 			= '".SafetyDB($_REQUEST["name"])."',
					  	`email` 		= '".SafetyDB($_REQUEST["email"])."',
					  	`comment` 		= '".SafetyDB($_REQUEST["comment"])."'";
			$sql_result = sql_result($sql);
			$SysMessage = $OptionsLang["Comment_Submitted"];
			if($Options['approval']=='true') {
				$SysMessage .= $OptionsLang["After_Approval_Admin"];
			}
			
										
			$sql = "SELECT * FROM ".$TABLE["Posts"]." WHERE id='".SafetyDB($_REQUEST["pid"])."'";
			$sql_result = sql_result($sql);
			$Post = mysqli_fetch_assoc($sql_result);
			mysqli_free_result($sql_result);

			$mailheader = "From: ".ReadDB($Options["email"])."\r\n";
			$mailheader .= "Reply-To: ".ReadDB($Options["email"])."\r\n";
			$mailheader .= "Content-type: text/html; charset=UTF-8\r\n";
			$Message_body = "Post: <strong>".ReadDB($Post["post_title"])."</strong><br /><br />";
			$Message_body .= "Comment: <br /> ".nl2br(ReadDB($_REQUEST["comment"]))."<br /><br />";
			$Message_body .= "From: <br />".ReadDB($_REQUEST["email"])."<br />".ReadDB($_REQUEST["name"])."<br />";
			mail(ReadDB($Options["email"]), $OptionsLang["New_comment_posted"], $Message_body, $mailheader);
			
			unset($_REQUEST["name"]);
			unset($_REQUEST["email"]);
			unset($_REQUEST["comment"]);
			
			echo '<script type="text/javascript">window.location.href="'.$thisPage.'?pid='.$_REQUEST["pid"].'&p='.urlencode($_REQUEST["p"]).'&search='.urlencode($_REQUEST["search"]).'&cat_id='.$_REQUEST["cat_id"].'&SysMessage='.urlencode($SysMessage).'#comments";</script>'; 
		}

	} else {		
		$SysMessage =  $OptionsLang["Incorrect_verification_code"]; 
		unset($_REQUEST["act"]);
	}
}
?>
<a name="blt"></a>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--- <link href="<?php echo $CONFIG["folder_name"]; ?>styles/bootstrap.css" rel='stylesheet' type='text/css' /> --->
<link rel="stylesheet" href="<?php echo $CONFIG["folder_name"]; ?>styles/font-awesome/css/font-awesome.min.css">
<?php include($CONFIG["server_path"]."styles/css_front_end.php"); ?>

<link href="<?php echo $CONFIG["folder_name"]; ?>styles/style.css" rel='stylesheet' type='text/css' />

<script type="text/javascript" src="<?php echo $CONFIG["full_url"]; ?>include/textsizer.js"></script>

<script src="<?php echo $CONFIG["folder_name"]; ?>include/jquery-2.1.1.min.js"></script>
<script src="<?php echo $CONFIG["full_url"]; ?>lightbox/js/lightbox.min.js"></script>
<link href="<?php echo $CONFIG["full_url"]; ?>lightbox/css/lightbox.css" rel="stylesheet" />


<div class="content-wrapper-sbp">
  <div class="container-sbp">
	<div class="content-grids">
    
<?php
if (isset($_REQUEST["pid"]) and $_REQUEST["pid"]>0) {	
	$_REQUEST["pid"]= (int) SafetyDB($_REQUEST["pid"]);

	$sql = "SELECT * FROM ".$TABLE["Posts"]." WHERE id='".SafetyDB($_REQUEST["pid"])."' and status='Posted'";
	$sql_result = sql_result($sql);
	$Post = mysqli_fetch_assoc($sql_result);
	$CurrPubDate = $Post["publish_date"];
	mysqli_free_result($sql_result);
	
	// fetch post category
	$sqlCat   = "SELECT * FROM ".$TABLE["Categories"]." WHERE `id`='".$Post["cat_id"]."'";
	$sql_resultCat = sql_result($sqlCat);
	$Cat = mysqli_fetch_array($sql_resultCat);
?>

        <div class="single-main">
      
            <!-- 'Back' link -->
            <div class="back_link">
                <a href="<?php echo $thisPage; ?><?php echo $url_vars; ?>"> <div class="arrow-left"></div> <?php echo $OptionsLang["Back_home"]; ?>
                </a>
            </div>	
             
			<?php if($OptionsVis['showsearch']=='yes') { // if option is set to show search in full post ?> 
            <div class="search-form-sbp<?php if($OptionsVis['showrightbar']!="no") { ?> displayNoneOver992<?php } ?>">
                <form action="<?php echo $thisPage; ?>" method="post" name="sform">
                    <input type="text" name="search" value="<?php if(isset($_REQUEST["search"]) and $_REQUEST["search"]!='') echo htmlspecialchars(urldecode($_REQUEST["search"]), ENT_QUOTES); ?>" placeholder="<?php echo $OptionsLang["Search_button"]; ?>">
                    <input type="submit" value=""/>
                </form>
            </div>
            <?php } ?>

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
                
                <?php if(trim($Post["post_tags"])!="") { ?> 
                <div class="blog_tags"><?php echo ReadDB($OptionsLang["Tagged_as"]); ?>&nbsp;
                <?php 
                $tagsArray = explode(',', $Post["post_tags"]);
                $i=1;
                foreach($tagsArray as $tag) {
                    if($i>1) echo ' &middot; '; 
                    echo '<a href="'.$thisPage.'?search='.trim($tag).$anchor_blt.'">'.trim($tag).'</a> '; // print each tag
                    $i++;
                }
                
                ?>    
                </div>
                <?php } ?>
                
                <div class="clearboth"></div> 
            </div>
            
            
            <?php if(trim($OptionsLang["Older_Post"])!='' or trim($OptionsLang["Newer_Post"])!='') {?>
            <!-- navigation at the bottom "Older Post", "Newer Post" --> 
            <nav class="sbp_prevnext">
                <ul class="pager-sbp">            		
                <?php 
                $Older_Post = '';
                $sql = "SELECT * FROM ".$TABLE["Posts"]." 
                WHERE `publish_date`<'".$CurrPubDate."' 
                AND status='Posted' " .$search . " 
                ORDER BY publish_date DESC LIMIT 0,1";
                $sql_result = sql_result($sql);
                if(mysqli_num_rows($sql_result)>0) {
                    $OlderPost = mysqli_fetch_assoc($sql_result);
                    $Older_Post = $OlderPost['id'];
                }	
                mysqli_free_result($sql_result);
                ?>
                    <?php if($Older_Post>0) {?>
                    <li>
                        <a href="<?php echo $thisPage; ?>?pid=<?php echo $Older_Post; ?>&amp;p=<?php echo $_REQUEST['p']; ?>&amp;cat_id=<?php echo $_REQUEST["cat_id"]; ?>&amp;search=<?php echo urlencode($_REQUEST["search"]); ?><?php echo $anchor_blt; ?>" title="<?php ReadHTML($Post['post_title']); ?>">
                            <i class="fa fa-angle-left"></i> <?php echo ReadDB($OptionsLang["Older_Post"]); ?>
                        </a>
                    </li> 
                    <?php } ?>
                                
                <?php 
                $Newer_Post = '';
                $sql = "SELECT * FROM ".$TABLE["Posts"]." 
                        WHERE `publish_date`>'".$CurrPubDate."' 
                        AND status='Posted' " .$search . " 
                        ORDER BY publish_date ASC LIMIT 0,1";
                $sql_result = sql_result($sql);
                if(mysqli_num_rows($sql_result)>0) {
                    $NewerPost = mysqli_fetch_assoc($sql_result);
                    $Newer_Post = $NewerPost['id'];
                }	
                mysqli_free_result($sql_result);
                ?>
                    <?php if($Newer_Post>0) {?>                    
                    <li>
                        <a href="<?php echo $thisPage; ?>?pid=<?php echo $Newer_Post; ?>&amp;p=<?php echo $_REQUEST['p']; ?>&amp;cat_id=<?php echo $_REQUEST["cat_id"]; ?>&amp;search=<?php echo urlencode($_REQUEST["search"]); ?><?php echo $anchor_blt; ?>" title="<?php ReadHTML($Post['post_title']); ?>">
                            <?php echo ReadDB($OptionsLang["Newer_Post"]); ?> <i class="fa fa-angle-right"></i>
                        </a>
                    </li>                
                	<?php } ?>             
                </ul>
            </nav>   
            <?php } ?>
            
            <?php 
			$sql = "UPDATE ".$TABLE["Posts"]." 
					SET reviews = reviews + 1 
					WHERE id='".SafetyDB($Post["id"])."'";
			$sql_result = sql_result($sql);	
			?>
          	
            
            <?php 
			if($Post['post_comments']=='true') { // if Allow comments
			?>
            
            <!--- COMMENTS MESSAGE START HERE --->
            <a name="comments"></a>
            <?php if(isset($SysMessage)) { ?>
            <div class="comment_message"><?php if(isset($SysMessage)) echo urldecode($SysMessage); ?></div>
            <?php } elseif(isset($_REQUEST['SysMessage'])) { ?>
            <div class="comment_message"><?php if(isset($_REQUEST['SysMessage'])) echo urldecode($_REQUEST['SysMessage']); ?></div>
            <?php } ?>
            
            
            <!--- COMMENTS LIST START HERE --->
            <?php
			if ($Options["comments_order"]=='OnTop') {
				$commentOrder = 'DESC';
			} else {
				$commentOrder = 'ASC';
			}
			
			$sql = "SELECT * FROM ".$TABLE["Comments"]." WHERE post_id='".$Post["id"]."' AND status='Approved' ORDER BY id ".$commentOrder;
			$sql_result = sql_result($sql);
			$numComments = mysqli_num_rows($sql_result);
			if ($numComments>0) { 
				if ($Options["comments_order"]=='OnTop') {
					$commentNum = $numComments;
				} else {
					$commentNum = 1;
				}
			?>
            <div class="word_Comments"><?php echo $numComments; ?> <?php echo $OptionsLang["Word_Comments"];?></div>
            
            <?php
				while ($Comments = mysqli_fetch_assoc($sql_result)) {
			?>
            <ul class="comment-list">
               <h5 class="post-author_head">
               		<?php echo $OptionsLang["Written_by"];?> <?php echo ReadHTML($Comments["name"]); ?>
					<span> &nbsp; <?php echo $OptionsLang["on_date"];?> 
					<?php 
                    if($OptionsVisC["comm_showing_time"]!='') { 
                        $show_time = "  ".$OptionsVisC["comm_showing_time"]; 
                    } else {
                        $show_time = "";
                    }
                    
                    if(isset($OptionsVisC["time_offset"]) and $OptionsVisC["time_offset"]!='0') { 
											
                        echo lang_date(date($OptionsVisC["comm_date_format"].$show_time,strtotime($OptionsVisC["time_offset"], strtotime($Comments["publish_date"]))));
                    } else {
                        echo lang_date(date($OptionsVisC["comm_date_format"].$show_time,strtotime($Comments["publish_date"])));
                    }
                    ?>
                    &nbsp; &nbsp; #<?php echo $commentNum; ?>
                    </span>
               </h5>
               <li><!--- <img src="images/avatar.png" class="img-responsive" alt=""> --->
                   <div class="desc">
                   	<div><?php echo ReadDB(nl2br($Comments["comment"])); ?></div>
                   </div>
                   <div class="clearfix"></div>
               </li>
            </ul>
            <?php
					if ($Options["comments_order"]=='OnTop') {
						$commentNum --;
					} else {
						$commentNum ++;
					}
				}
			} else {
			?>
            <?php if(trim($OptionsLang["No_comments_posted"])!="") { ?>
			<div class="word_Comments sbl-no-comments-posted"><?php echo $OptionsLang["No_comments_posted"]; ?></div>
            <?php } ?>
			<?php 
			}
			mysqli_free_result($sql_result);
			?> 
            
            <!--- COMMENT FORM START HERE --->
            <div class="content-form">
                <h3><?php echo $OptionsLang["Leave_Comment"]; ?></h3>
                <form action="<?php echo $thisPage; ?><?php echo $comm_url; ?>" name="formComment" method="post">
                    <input type="hidden" name="pid" value="<?php echo $_REQUEST["pid"]; ?>" />
                    <input type="hidden" name="act" value="post_comment" />
                    <input type="text" name="name" value="<?php if(isset($_REQUEST["name"])) echo $_REQUEST["name"]; ?>" placeholder="<?php echo $OptionsLang["Comment_Name"]; ?>" required />
                    <input type="text" name="email" value="<?php if(isset($_REQUEST["email"])) echo $_REQUEST["email"]; ?>" placeholder="<?php echo $OptionsLang["Comment_Email"]; ?>"<?php if (!empty($Options["comm_req"]) and in_array("Email", $Options["comm_req"])) { echo " required"; } ?> />
                    <textarea name="comment" placeholder="<?php echo $OptionsLang["Comment_here"]; ?>" required><?php if(isset($_REQUEST["comment"])) echo $_REQUEST["comment"]; ?></textarea>
                    
                    <?php 
					if($Options['captcha']!='nocap') { // if the option is set to no Captcha
					?>
                    <?php if($Options['captcha']=='phpcaptcha') { ?>
                    
					<?php 
                    $options = array();
                    $options['input_text'] = $OptionsLang["Enter_verification_code"]; // change placeholder
					//$options['show_text_input'] = false; // change placeholder
					
                    echo "<div id='captcha_container_1'>\n";
                    echo Securimage::getCaptchaHtml($options);
                    echo "\n</div>\n";
                    ?>
                                        
                    <?php } elseif($Options['captcha']=='capmath') { ?> 
                    <img src="<?php echo $CONFIG["folder_name"]; ?>captchamath.php" id="captcha" class="form_captcha_img" alt="Mathematical catpcha image" /> 
                    <div class="form_captcha_eq"> = </div>                       
                    <input type="text" name="string" maxlength="3" class="form_captcha form_captcha_math" required />
                     
                    <?php } elseif($Options['captcha']=='cap') {  ?>
                    <img src="<?php echo $CONFIG["folder_name"]; ?>captcha.php" class="form_captcha_img" alt="Simple catpcha image" />
                    <input type="text" name="string" class="form_captcha form_captcha_s" required /> 
                        
                    <?php 
                    } else { ?>
                    <img src="<?php echo $CONFIG["folder_name"]; ?>captchasimple.php" class="form_captcha_img" alt="Very catpcha image" />
                    <input type="text" name="string" class="form_captcha form_captcha_s" required /> 
                    <?php }					
					} ?> 
                    
                    <div class="clearboth"></div>
                    
                    <input type="submit" value="<?php echo $OptionsLang["Submit_Comment"]; ?>" />
               </form>
            </div>
            
            <?php 
			} // end if comments true
			?>
            
		</div>


<?php
} else {
?>
        
    
        <div class="content-main">    	
			<?php 			
			// pagination query and variables start
			if(isset($_REQUEST["p"]) and $_REQUEST["p"]!='') { 
				$pageNum = (int) SafetyDB(urldecode($_REQUEST["p"]));
				if($pageNum<=0) $pageNum = 1;
			} else { 
				$pageNum = 1;
			}
			
			$sql = "SELECT count(*) as total FROM ".$TABLE["Posts"]." WHERE status<>'Hidden' ".$search;
			$sql_result = sql_result($sql);
			$row  = mysqli_fetch_array($sql_result);
			mysqli_free_result($sql_result);
			
			$total_pages = $row["total"];
			$adjacents = 1; // the adjacents to the current page digid when some pages are hidden
			$limit = $Options["per_page"];  //how many items to show per page
			$page = (int) SafetyDB(urldecode($_REQUEST["p"]));
			
			if($page) { 
				$start = ($page - 1) * $limit;  //first item to display on this page
			} else {
				$start = 0;	 //if no page var is given, set start to 0
			}
			
			/* Setup page vars for display. */
			if ($page == 0) $page = 1;					//if no page var is given, default to 1.
			$prev = $page - 1;							//previous page is page - 1
			$next = $page + 1;							//next page is page + 1
			$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
			$lpm1 = $lastpage - 1;						//last page minus 1
			
			// pagination query and variables ends
			
			
            $sql = "SELECT * FROM ".$TABLE["Posts"]." 
                    WHERE status<>'Hidden' " .$search . "  
                    ORDER BY publish_date DESC 
                    LIMIT " . ($pageNum-1)*$Options["per_page"] . "," . $Options["per_page"];
            $sql_result = sql_result($sql);
            //echo $sql;
            //echo "<br>";
            $numOfPosts = mysqli_num_rows($sql_result);
            //echo $numOfPosts;
            if($numOfPosts>0) { ?>            
                    
			<?php if($OptionsVis['showsearch']=='yes') { // if option is set to show search in posts list ?> 
            <div class="search-form-sbp<?php if($OptionsVis['showrightbar']!="no") { ?> displayNoneOver992<?php } ?>">
                <form action="<?php echo $thisPage; ?>" method="post" name="sform">
                    <input type="text" name="search" value="<?php if(isset($_REQUEST["search"]) and $_REQUEST["search"]!='') echo htmlspecialchars(urldecode($_REQUEST["search"]), ENT_QUOTES); ?>" placeholder="<?php echo $OptionsLang["Search_button"]; ?>">
                    <input type="submit" value=""/>
                </form>
            </div>
            <?php } ?>
                    
                    
            <?php 
              while ($Post = mysqli_fetch_assoc($sql_result)) { 
				//comments number
                $sqlC   = "SELECT count(*) as total FROM ".$TABLE["Comments"]." WHERE `post_id`='".$Post["id"]."' AND status='Approved'";
                $sql_resultC = sql_result($sqlC);
                $count = mysqli_fetch_array($sql_resultC);
                // fetch post category
                $sqlCat   = "SELECT * FROM ".$TABLE["Categories"]." WHERE `id`='".$Post["cat_id"]."'";
                $sql_resultCat = sql_result($sqlCat);
                $Cat = mysqli_fetch_array($sql_resultCat);
            ?>
            
            <!--- Posts List --->        
            <div class="content-grid">					 
                <div class="content-grid-info">
                 <?php if(ReadDB($Post["image"])!='') { ?>
                 <img src="<?php echo $CONFIG["full_url"].$CONFIG["upload_folder"].ReadDB($Post["image"]); ?>" alt="<?php echo ReadHTML($Post["post_title"]); ?>" />
                 <?php } ?>
                 <div class="post-info">
                 
                 	<!--- Post Title --->
                 	<h4 class="post-title-h4">
                 		<a href="<?php echo $thisPage; ?>?pid=<?php echo $Post["id"]; ?><?php echo $url_vars; ?>"><?php echo $Post["post_title"]; ?></a>                 
                 	</h4>
                 	

                    <!-- Post date -->   
                    <?php if($OptionsVis["list_show_date"]!='no' or (isset($Post["cat_id"]) and $Post["cat_id"]>0) or $Post['post_comments']=='true') { ?>  
                    <div class="list_date_style">
                    
                    <?php if($Cat["cat_name"]!="") { echo "<a class='list-sub-title' href='".$thisPage."?cat_id=".$Cat["id"].$anchor_blt."'>".$Cat["cat_name"]."</a> &nbsp; / &nbsp; "; } ?>
                    
                    <?php echo lang_date(date($OptionsVis["list_date_format"],strtotime($Post["publish_date"]))); ?> 
                    <?php if($OptionsVis["list_showing_time"]!='') echo date($OptionsVis["list_showing_time"],strtotime($Post["publish_date"])); ?>
                    
                   
                    <?php 
					if($Post['post_comments']=='true') { // if Allow comments
                    $sqlNum = "SELECT * FROM ".$TABLE["Comments"]." WHERE post_id='".$Post["id"]."' AND status='Approved'";
                    $sql_resultNum = sql_result($sqlNum);
                    $numComments = mysqli_num_rows($sql_resultNum);
                    ?>
                    
                     
                    &nbsp; / &nbsp; <a href="<?php echo $thisPage; ?>?pid=<?php echo $Post["id"]; ?><?php echo $comm_url; ?>"><?php echo $numComments; ?> <?php echo $OptionsLang["Comments_link"]; ?></a>
                    
                    <?php } ?>
                    
                    </div>
                    
                    <?php
					}
					?>
                 
                 <div class="list-post-text">
                 <?php 			
                    if(trim($Post["post_limit"])=='') {
                        echo $Post["post_text"];
                    } elseif(trim($Post["post_limit"])>0) {
                        //$output = strip_only(cutStrHTML(ReadDB($Post["post_text"]), 0, $Post["post_limit"]), "div"); 
                        if (isCyrillic($Post["post_text"])) {	
                            $output = truncateBlogHtml(strip_tags($Post["post_text"], '<a>'), $Post["post_limit"]);
							echo $output;
                        } else {
                            $output = truncate_words(strip_tags($Post["post_text"], '<a>'), $Post["post_limit"]);
							if (preg_match('/[úůýžáčďéěíňóřšťÚŮÝŽÁČĎÉĚÍŇÓŘŠŤ]/i', $output)) {
								// one or more of the 'special characters' found in $string
								echo $output;
							} else {
								echo purifyHTML($output);
							}
                        }                        
                    }
                    ?> 
                 
                 </div>
                 
                 <?php if(trim($OptionsLang["Read_more"])!="") { ?>
                 <a class="read-more-sbp" href="<?php echo $thisPage; ?>?pid=<?php echo $Post["id"]; ?><?php echo $url_vars; ?>"><span></span><?php echo $OptionsLang["Read_more"]; ?></a>
                 <?php } ?>
                 </div>
                </div>
             
            </div>
            
            <?php 
              }
            ?>          
            <?php 
            } else {
            ?>  
            	<div class="content-grid">					 
                	<div class="content-grid-info">   
                    	<?php echo $OptionsLang["No_Posts"]; ?>
					</div>
                </div>
            <?php 
            }
            ?> 
            
            
            
            <!-- Pagination start here --> 
            <?php 
            // pagination starts. It can be shown wherever we need
            if($lastpage > 1) {	
                // defining recurring url variables
                $paging_vars = "&amp;cat_id=".urlencode($_REQUEST["cat_id"])."&amp;search=".urlencode($_REQUEST["search"]).$anchor_blt;
            ?>
            <nav class="sbp_paignation">
                <ul class="pager-sbp">
				
                <?php //previous button starts
                if ($page > 1) {
                ?>	
                    <li>
                    	<a href="<?php echo $thisPage."?p=".$prev;?><?php echo $paging_vars; ?>" title="previous">
                        	<i class="fa fa-angle-left"></i>
                        </a>
                    </li>
				<?php } ?>               
                    
                    
                <?php //pages	start
                if ($lastpage < 7 + ($adjacents * 2)) {	//not enough pages to bother breaking it up
                  for ($counter = 1; $counter <= $lastpage; $counter++) {
                  	if ($counter == $page) { 
				?>
					<li class="active"><span class="active"><?php echo AddZero($counter); ?></span></li>
                <?php 
					} else { 
				?>
                    <li class="mobPag"><a href="<?php echo $thisPage."?p=".$counter; ?><?php echo $paging_vars; ?>"><?php echo AddZero($counter); ?></a></li>
                <?php 
					} 				
                  }
                }
                elseif($lastpage > 5 + ($adjacents * 2)) {	//enough pages to hide some		
                  //close to beginning; only hide later pages
                  if($page < 1 + ($adjacents * 2)) {
                	for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                      if ($counter == $page) { 
				?>
					<li class="active"><span class="active"><?php echo AddZero($counter); ?></span></li>
                <?php } else { ?>
					<li class="mobPag"><a href="<?php echo $thisPage."?p=".$counter; ?><?php echo $paging_vars; ?>"><?php echo AddZero($counter); ?></a></li>
				<?php } 				
                    } 
				?>
					<a class="mobPag" href="<?php echo $thisPage."?p=".$lastpage; ?><?php echo $paging_vars; ?>">...</a>                
				  <?php   
                  } elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) { //in middle; hide some front and some back ?>
					<a class="mobPag" href="<?php echo $thisPage; ?>?p=1<?php echo $paging_vars; ?>">...</a>                
				    <?php 
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                      if ($counter == $page) { ?>
					<li class="active"><span class="active"><?php echo AddZero($counter); ?></span></li>
                      <?php 
					  } else { 
					  ?>
					<li class="mobPag"><a href="<?php echo $thisPage."?p=".$counter; ?><?php echo $paging_vars; ?>"><?php echo AddZero($counter); ?></a></li>
                    <?php 
					  }                                         
					} 
					?>
					<a class="mobPag" href="<?php echo $thisPage."?p=".$lastpage; ?><?php echo $paging_vars; ?>">...</a>
				  <?php     		
                  } else { //close to end; only hide early pages  ?>
					<a class="mobPag" href="<?php echo $thisPage; ?>?p=1<?php echo $paging_vars; ?>">...</a>
					<?php 
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                      if ($counter == $page) { ?>
					<li class="active"><span class="active"><?php echo AddZero($counter); ?></span></li>
                      <?php 
					  } else { 
					  ?>
                    <li class="mobPag"><a href="<?php echo $thisPage."?p=".$counter; ?><?php echo $paging_vars; ?>"><?php echo AddZero($counter); ?></a></li>
                      <?php 
					  } 					
                    }
                  }
                }
				?>
                    
                    
				<?php //next button
                if ($page < $counter - 1) { ?>
                    <li>
                    	<a href="<?php echo $thisPage."?p=".$next; ?><?php echo $paging_vars; ?>" title="next">
                    		<i class="fa fa-angle-right"></i>
                        </a>
                    </li>
                <?php } ?>
                
                </ul>
            </nav>
            <!-- Pagination end here -->         	
            <?php 
            } // pagination ends	
            ?> 
            
        </div>   
		 
			 			  
<?php } ?>


		<!---  RIGHT BAR CONTENT START HERE --->
        <?php if($OptionsVis['showrightbar']!="no") { ?>
        <div class="content-right">
            
            <!--- SEARCH BAR --->
            <?php if($OptionsVis['showsearch']=='yes') { // if option is set to show search in right sidebar ?> 
            <div class="search-form-sbp displayNoneUnder992">
                <form action="<?php echo $thisPage; ?>" method="post" name="sform">
                    <input type="text" name="search" value="<?php if(isset($_REQUEST["search"]) and $_REQUEST["search"]!='') echo htmlspecialchars(urldecode($_REQUEST["search"]), ENT_QUOTES); ?>" placeholder="<?php echo $OptionsLang["Search_button"]; ?>">
                    <input type="submit" value=""/>
                </form>
            </div>
            <?php } ?>
            
            
            
            <?php		
            if($OptionsVis['showcateg']!='no') {
			?> 
            <!--- CATEGORIES --->
            <?php
			  if(!isset($_REQUEST['hide_cat'])) {
                $sql = "SELECT * FROM ".$TABLE["Categories"]." ORDER BY `cat_name` ASC";
                $sql_result = sql_result($sql);
                if (mysqli_num_rows($sql_result)>0) {
            ?>
            <div class="categories">
                <h3><?php echo $OptionsLang["Category"]; ?></h3>
                <ul>
                    <li><a href="?cat_id=0<?php echo $anchor_blt; ?>"><?php echo $OptionsLang["Category_all"]; ?></a></li>
                    <?php 
                    $sql = "SELECT * FROM ".$TABLE["Categories"]." ORDER BY cat_name ASC";
                    $sql_result = sql_result($sql);
                    while ($Cat = mysqli_fetch_assoc($sql_result)) { ?>
                    <li><a href="<?php echo $thisPage; ?>?cat_id=<?php echo $Cat["id"]; ?><?php echo $anchor_blt; ?>"><?php echo $Cat["cat_name"]; ?></a></li>
                    <?php } ?>
                </ul>
            </div>                
            <?php }
			  }
            }
            ?>
            
            
            
            <?php 
			if($OptionsVis['showrecent']!='no') {
			?>
            <!--- LATEST POSTS --->
            <?php
            $sql = "SELECT * FROM ".$TABLE["Posts"]." 
                    WHERE status<>'Hidden' 
                    ORDER BY `publish_date` DESC 
                    LIMIT 0,4";	
            $sql_result = sql_result($sql);
            $numOfPosts = mysqli_num_rows($sql_result);
            if($numOfPosts>0) { ?>
            <div class="recent">
                <h3><?php echo $OptionsLang["Recent_Posts"]; ?></h3>
                <ul>
                    <?php 
                    while ($Post = mysqli_fetch_assoc($sql_result)) { ?>
                    <li><a href="?pid=<?php echo $Post["id"]; ?><?php echo $url_vars; ?>"><?php echo ReadHTML($Post["post_title"]); ?></a></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="clearfix"></div>
            <?php } 
			}
			?>
            
            <?php if($OptionsVis['showarchive']!='no') { ?>
            <!--- ARCHIVES --->
            <div class="archives">
                <h3><?php echo $OptionsLang["Archives"]; ?></h3>
                <ul>
                <?php
                $m = date("m");
                $y = date("Y");
                for($i=0; $i<=12; $i++) {
                    $currDate = date("Ym", mktime(0, 0, 0, $m-$i, 1, $y));
                    $sql   = "SELECT count(*) as total FROM ".$TABLE["Posts"]." WHERE status<>'Hidden'  AND EXTRACT(YEAR_MONTH FROM publish_date)='".$currDate."'";
                    $sql_result = sql_result($sql);
                    $row   = mysqli_fetch_assoc($sql_result);
                    $count = $row["total"];
                    if($count>0) {
                ?>
                    <li><a href="<?php echo $thisPage; ?>?ym=<?php echo date("Ym", mktime(0, 0, 0, $m-$i, 1, $y)); ?><?php echo $anchor_blt; ?>"><?php echo date("F Y", mktime(0, 0, 0, $m-$i, 1, $y)); ?>(<?php echo $count; ?>)</a></li>
                    <?php
                    }
                 }
                ?>
                </ul>
            </div>
            <div class="clearfix"></div>
		</div>
        <?php } ?>
        
        <?php 
		}
		?>
              
              
		<div class="clearfix"></div> 

	</div>
    
    <?php if($OptionsVis["show_scrolltop"]!="no") {?>
    <a href="#myAnchor" class="cd-top">Top</a>
    <script type="text/javascript">
    
    //$('.front_end_wrapper').prepend('<a href="#0" class="cd-top">Top</a>');
    
    jQuery(document).ready(function($){
        // browser window scroll (in pixels) after which the "back to top" link is shown
        var offset = 300,
        //browser window scroll (in pixels) after which the "back to top" link opacity is reduced
        offset_opacity = 1200,
        //duration of the top scrolling animation (in ms)
        scroll_top_duration = 700,
        //grab the "back to top" link
        $back_to_top = $('.cd-top');
    
        //hide or show the "back to top" link
        $(window).scroll(function(){
            ( $(this).scrollTop() > offset ) ? $back_to_top.addClass('cd-is-visible') : $back_to_top.removeClass('cd-is-visible cd-fade-out');
            if( $(this).scrollTop() > offset_opacity ) { 
                $back_to_top.addClass('cd-fade-out');
            }
        });
    
        //smooth scroll to top
        $back_to_top.on('click', function(event){
            event.preventDefault();
            $('body,html').animate({
                scrollTop: 0 ,
                }, scroll_top_duration
            );
        });
    
    });
    
    </script>
    <?php } ?> 
  </div> 
</div>
	
