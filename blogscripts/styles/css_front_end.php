<style type="text/css">

/* declare external fonts */
@font-face { font-family: Azbuka04; src: url('<?php echo $CONFIG["full_url"]; ?>fonts/Azbuka04.ttf'); } 
@font-face { font-family: Avalon-Bold; src: url('<?php echo $CONFIG["full_url"]; ?>fonts/Avalon-Bold.ttf'); }  
@font-face { font-family: Avalon-Plain; src: url('<?php echo $CONFIG["full_url"]; ?>fonts/Avalon-Plain.ttf'); } 
@font-face { font-family: Cour; src: url('<?php echo $CONFIG["full_url"]; ?>fonts/cour.ttf'); }  
@font-face { font-family: DSNote; src: url('<?php echo $CONFIG["full_url"]; ?>fonts/(DS)_Note.ttf'); } 
@font-face { font-family: HebarU; src: url('<?php echo $CONFIG["full_url"]; ?>fonts/HebarU.ttf'); } 
@font-face { font-family: Lato-Regular; src: url('<?php echo $CONFIG["full_url"]; ?>fonts/Lato-Regular.ttf'); } 
@font-face { font-family: Montserrat-Regular; src: url('<?php echo $CONFIG["full_url"]; ?>fonts/Montserrat-Regular.otf'); }
@font-face { font-family: MTCORSVA; src: url('<?php echo $CONFIG["full_url"]; ?>fonts/MTCORSVA.TTF'); }  
@font-face { font-family: Nicoletta_script; src: url('<?php echo $CONFIG["full_url"]; ?>fonts/Nicoletta_script.ttf'); }
@font-face { font-family: OpenSans; src: url('<?php echo $CONFIG["full_url"]; ?>fonts/OpenSans-Regular.ttf'); } 
@font-face { font-family: Oswald-Light; src: url('<?php echo $CONFIG["full_url"]; ?>fonts/Oswald-Light.otf'); }
@font-face { font-family: Oswald-Regular; src: url('<?php echo $CONFIG["full_url"]; ?>fonts/Oswald-Regular.ttf'); } 
@font-face { font-family: Raleway-Regular; src: url('<?php echo $CONFIG["full_url"]; ?>fonts/Raleway-Regular.ttf'); } 
@font-face { font-family: Regina Kursiv; src: url('<?php echo $CONFIG["full_url"]; ?>fonts/ReginaKursiv.ttf'); }
@font-face { font-family: Ubuntu-R; src: url('<?php echo $CONFIG["full_url"]; ?>fonts/Ubuntu-R.ttf'); }  

/* div that wrap all the blog */
.content-wrapper-sbp {	
	font-family: <?php echo $OptionsVis["gen_font_family"];?>;
    font-size: <?php echo $OptionsVis["gen_font_size"];?>;
}

.content-grids {
	text-align:<?php echo $OptionsVis["gen_text_align"];?>;
	line-height:<?php echo $OptionsVis["gen_line_height"];?>;
	color:<?php echo $OptionsVis["gen_font_color"];?>;
	margin-top: <?php echo $OptionsVis["dist_from_top"];?>;
	margin-bottom: <?php echo $OptionsVis["dist_from_bottom"];?>; 
}
.container-sbp {
  	<?php if(trim($OptionsVis["gen_width"])!=''){ ?>
	max-width: <?php echo trim($OptionsVis["gen_width"]); ?><?php echo $OptionsVis["gen_width_dim"]; ?>;
	<?php } ?>
}
<?php if($OptionsVis['showrightbar']=="no") { ?>
.single-main {
	width: 100% !important;
	float: none !important;
}
.content-main {
	width: 100% !important;
	float: none !important;
}
@media(min-width:993px){
	.search-form-sbp {
		width: 50% !important;
	}
}
<?php } ?>

/* back link style */
div.back_link {
	padding-bottom:<?php echo $OptionsVis["dist_link_title"];?>;
}
div.back_link a {
	color:<?php echo $OptionsVis["back_font_color"];?>;
	font-family:<?php echo $OptionsVis["back_font"];?> !important;
	font-size:<?php echo $OptionsVis["back_font_size"];?>;
	font-weight:<?php echo $OptionsVis["back_font_weight"];?>;
	font-style:<?php echo $OptionsVis["back_font_style"];?>; 
	text-decoration:<?php echo $OptionsVis["back_text_decoration"];?>;
}
.arrow-left {
	border-right: 4px solid <?php echo $OptionsVis["back_font_color"]; ?> !important; 
}
/* "back link style on mouse over */
div.back_link a:hover {
	color:<?php echo $OptionsVis["back_font_color_hover"];?>;
	font-family:<?php echo $OptionsVis["back_font"];?> !important;
	font-size:<?php echo $OptionsVis["back_font_size"];?>;
	font-weight:<?php echo $OptionsVis["back_font_weight"];?>;
	font-style:<?php echo $OptionsVis["back_font_style"];?>; 	
	text-decoration: <?php echo $OptionsVis["back_text_decoration_hover"];?>;
}
.arrow-left:hover {
	border-right: 4px solid <?php echo $OptionsVis["back_font_color_hover"]; ?> !important; 
}



/* search form style */
.search-form-sbp form {
	border: 1px solid <?php echo $OptionsVis["sear_bor_color"]; ?> !important; 
	background: <?php echo $OptionsVis["sear_bor_color"]; ?> !important; 
}
.search-form-sbp form input[type="text"] {
	color: <?php echo $OptionsVis["sear_color"]; ?> !important; 
	background: <?php echo $OptionsVis["sear_bor_color"]; ?> !important; 
	font-family:<?php echo $OptionsVis["sear_font_family"];?> !important;  
}

/* title on the full post style */
.single-grid h4 {
	color:<?php echo $OptionsVis["post_title_color"];?> !important;
	font-size:<?php echo $OptionsVis["post_title_size"];?>;
	font-family:<?php echo $OptionsVis["post_title_font"];?> !important;
	font-weight:<?php echo $OptionsVis["post_title_font_weight"];?> !important;
	font-style: <?php echo $OptionsVis["post_title_font_style"];?> !important;
	line-height: <?php echo $OptionsVis["title_line_height"];?>;
	text-align:<?php echo $OptionsVis["post_title_align"];?> !important;
}

/* title style in the list of posts */
.post-info h4 {
	text-align:<?php echo $OptionsVis["list_title_align"];?> !important;	
	margin-bottom: <?php echo $OptionsVis["list_dist_title_date"];?> !important;
}
.post-info h4 a {
    font-family:<?php echo $OptionsVis["list_title_font"];?> !important;
    color:<?php echo $OptionsVis["list_title_color"];?> !important;
    font-size:<?php echo $OptionsVis["list_title_size"];?>;
	font-weight:<?php echo $OptionsVis["list_title_font_weight"];?> !important;
	font-style: <?php echo $OptionsVis["list_title_font_style"];?> !important;
	line-height: <?php echo $OptionsVis["list_title_line_height"];?> !important;
}
.post-info h4 a:hover {
    font-family:<?php echo $OptionsVis["list_title_font"];?> !important;
	color:<?php echo $OptionsVis["list_title_color_hover"];?> !important;
    font-size:<?php echo $OptionsVis["list_title_size"];?>;
	font-weight:<?php echo $OptionsVis["list_title_font_weight"];?> !important;
	font-style: <?php echo $OptionsVis["list_title_font_style"];?> !important;
	line-height: <?php echo $OptionsVis["list_title_line_height"];?> !important;
}
h4.post-title-h4 {
    font-family:<?php echo $OptionsVis["list_title_font"];?> !important;
    color:<?php echo $OptionsVis["list_title_color"];?> !important;
    font-size:<?php echo $OptionsVis["list_title_size"];?>;
	font-weight:<?php echo $OptionsVis["list_title_font_weight"];?> !important;
	font-style: <?php echo $OptionsVis["list_title_font_style"];?> !important;
	line-height: <?php echo $OptionsVis["list_title_line_height"];?> !important;
}
/* post list title style end */


div.dist_title_date {
	height:<?php echo $OptionsVis["dist_title_date"];?> !important;
}
div.list_dist_title_date {
	height:<?php echo $OptionsVis["list_dist_title_date"];?> !important;
}
/* posts list date style */
div.list_date_style {
	font-family:<?php echo $OptionsVis["list_date_font"];?> !important; 
	color:<?php echo $OptionsVis["list_date_color"];?> !important; 
	font-size:<?php echo $OptionsVis["list_date_size"];?>; 
	font-style: <?php echo $OptionsVis["list_date_font_style"];?> !important; 
	text-align:<?php echo $OptionsVis["list_date_text_align"];?> !important; 
	margin-bottom: <?php echo $OptionsVis["list_dist_date_text"];?> !important;	
}
div.list_date_style a {
	font-family:<?php echo $OptionsVis["list_date_font"];?> !important; 
	color:<?php echo $OptionsVis["list_date_color"];?> !important; 
	font-size:<?php echo $OptionsVis["list_date_size"];?>; 
	font-style: <?php echo $OptionsVis["list_date_font_style"];?> !important; 
	text-align:<?php echo $OptionsVis["list_date_text_align"];?> !important; 
}
div.list_date_style a:hover {
	font-family:<?php echo $OptionsVis["list_date_font"];?> !important;
	color: <?php echo $OptionsVis["list_date_color_hover"];?> !important; 
	font-size:<?php echo $OptionsVis["list_date_size"];?>; 
	font-style: <?php echo $OptionsVis["list_date_font_style"];?> !important; 
	text-align:<?php echo $OptionsVis["list_date_text_align"];?> !important; 
	text-decoration: <?php echo $OptionsVis["list_date_decoration_hover"];?> !important;
}
/* post date style */
div.date_style {
	color:<?php echo $OptionsVis["date_color"];?> !important; 
	font-family:<?php echo $OptionsVis["date_font"];?> !important; 
	font-size:<?php echo $OptionsVis["date_size"];?>; 
	font-style: <?php echo $OptionsVis["date_font_style"];?> !important; 
	text-align:<?php echo $OptionsVis["date_text_align"];?> !important; 
	margin-top: <?php echo $OptionsVis["dist_title_date"];?> !important;	
}
div.date_style a {
	color:<?php echo $OptionsVis["date_color"];?> !important; 
	font-family:<?php echo $OptionsVis["date_font"];?> !important; 
	font-size:<?php echo $OptionsVis["date_size"];?>; 
	font-style: <?php echo $OptionsVis["date_font_style"];?> !important; 
}
div.date_style a:hover {
	color: <?php echo $OptionsVis["date_color_hover"];?> !important; 
	font-size:<?php echo $OptionsVis["date_size"];?>; 
	text-decoration: <?php echo $OptionsVis["date_decoration_hover"];?> !important;
}
div.dist_date_text { 
	padding-bottom:<?php echo $OptionsVis["dist_date_text"];?> !important;
}
div.list_dist_date_text { 
	height:<?php echo $OptionsVis["list_dist_date_text"];?> !important;
}

/* post text style */
div.post-text {
	font-family:<?php echo $OptionsVis["text_font"];?> !important;
	color:<?php echo $OptionsVis["text_color"];?> !important;
	background-color: <?php echo $OptionsVis["text_bgr_color"];?> !important;
	font-size:<?php echo $OptionsVis["text_size"];?>;
	font-weight:<?php echo $OptionsVis["text_font_weight"];?> !important;
	font-style: <?php echo $OptionsVis["text_font_style"];?> !important;
	text-align:<?php echo $OptionsVis["text_text_align"];?> !important;
	line-height:<?php echo $OptionsVis["text_line_height"];?> !important;
	padding: 0 <?php echo $OptionsVis["text_padding"];?> !important; 
}

/* post text to tags distance */
div.post-text-to-tags {
	margin: 0 0 <?php echo $OptionsVis["dist_text_tags"];?> 0 !important;	
}


/* post text style */
div.list-post-text {
	font-family:<?php echo $OptionsVis["list_text_font"];?> !important;
	color:<?php echo $OptionsVis["list_text_color"];?> !important;
	font-size:<?php echo $OptionsVis["list_text_size"];?>;
	font-weight:<?php echo $OptionsVis["list_text_font_weight"];?> !important;
	font-style: <?php echo $OptionsVis["list_text_font_style"];?> !important;
	text-align:<?php echo $OptionsVis["list_text_text_align"];?> !important;
	line-height:<?php echo $OptionsVis["list_text_line_height"];?> !important;
	margin-bottom: <?php echo $OptionsVis["dist_btw_post_more"];?>;
}


/* links style in the post text */
div.post-text a {	
	font-family:<?php echo $OptionsVis["list_text_font"];?> !important;
	color: <?php echo $OptionsVis["links_font_color"];?> !important;
	text-decoration: <?php echo $OptionsVis["links_text_decoration"];?> !important;
	font-size: <?php echo $OptionsVis["links_font_size"];?> !important;
	font-style: <?php echo $OptionsVis["links_font_style"];?> !important;
	font-weight: <?php echo $OptionsVis["links_font_weight"];?> !important;
}
div.list-post-text a {	
	font-family:<?php echo $OptionsVis["list_text_font"];?> !important;
	color: <?php echo $OptionsVis["links_font_color"];?> !important;
	text-decoration: <?php echo $OptionsVis["links_text_decoration"];?> !important;
	/*font-size: <?php echo $OptionsVis["list_text_size"];?>;*/
	font-style: <?php echo $OptionsVis["links_font_style"];?> !important;
	font-weight: <?php echo $OptionsVis["links_font_weight"];?> !important;
}
/* links style in the post text on mouse over */
div.post-text a:hover {	
	font-family:<?php echo $OptionsVis["list_text_font"];?> !important;
	color: <?php echo $OptionsVis["links_font_color_hover"];?> !important;
	text-decoration: <?php echo $OptionsVis["links_text_decoration_hover"];?> !important;
	font-size: <?php echo $OptionsVis["links_font_size"];?> !important;
	font-style: <?php echo $OptionsVis["links_font_style"];?> !important;
	font-weight: <?php echo $OptionsVis["links_font_weight"];?> !important;
}
div.list-post-text a:hover {	
	font-family:<?php echo $OptionsVis["list_text_font"];?> !important;
	color: <?php echo $OptionsVis["links_font_color_hover"];?> !important;
	text-decoration: <?php echo $OptionsVis["links_text_decoration_hover"];?> !important;
	/*font-size: <?php echo $OptionsVis["list_text_size"];?>;*/
	font-style: <?php echo $OptionsVis["links_font_style"];?> !important;
	font-weight: <?php echo $OptionsVis["links_font_weight"];?> !important;
}


/* style for word "Comments" above the list of comments */
div.word_Comments {	
	padding-top: <?php echo $OptionsVis["dist_comm_links"];?> !important;
	font-family:<?php echo $OptionsVisC["w_comm_font_family"];?> !important;
	color:<?php echo $OptionsVisC["w_comm_font_color"];?> !important;
	font-size:<?php echo $OptionsVisC["w_comm_font_size"];?> !important;
	font-style:<?php echo $OptionsVisC["w_comm_font_style"];?> !important;
	font-weight:<?php echo $OptionsVisC["w_comm_font_weight"];?> !important;	
}

.content-form h3 {
	font-family: <?php echo $OptionsVisC["leave_font_family"];?>;
	color: <?php echo $OptionsVisC["leave_font_color"];?>;
	font-size: <?php echo $OptionsVisC["leave_font_size"];?>;
	font-weight:<?php echo $OptionsVisC["leave_font_weight"];?> !important; 
	font-style:<?php echo $OptionsVisC["leave_font_style"];?> !important;
}

.content-form form input[type="text"], .content-form form textarea {
	font-family: <?php echo $OptionsVisC["field_font_family"];?>;
	color: <?php echo $OptionsVisC["field_font_color"];?>;
	font-size: <?php echo $OptionsVisC["field_font_size"];?>;
	border: 1px solid <?php echo $OptionsVisC["field_bgr_color"];?>;
}

.content-form form input[type="submit"] {
	font-family: <?php echo $OptionsVisC["subm_font_family"];?>;
	color: <?php echo $OptionsVisC["subm_color"];?>;
	font-size: <?php echo $OptionsVisC["subm_font_size"];?>;
	background: <?php echo $OptionsVisC["subm_bgr_color"];?>;	
	border: 1px solid <?php echo $OptionsVisC["subm_brdr_color"];?>;
	border-radius: <?php echo $OptionsVisC["subm_bor_radius"];?>;
}
.content-form form input[type="submit"]:hover {
	color: <?php echo $OptionsVisC["subm_brdr_color"];?>;
	background: <?php echo $OptionsVisC["subm_bgr_color_on"];?>;
}

ul.comment-list {
	border: 1px solid <?php echo $OptionsVisC["comm_bord_color"];?>;
	margin: <?php echo $OptionsVisC["dist_btw_comm"];?> 0 !important;
}
h5.post-author_head {
	font-family: <?php echo $OptionsVisC["name_font"];?>;
	color: <?php echo $OptionsVisC["name_font_color"];?>;
	font-size: <?php echo $OptionsVisC["name_font_size"];?>;
	font-weight:<?php echo $OptionsVisC["name_font_style"];?>; 
	font-style:<?php echo $OptionsVisC["name_font_weight"];?>;
}
h5.post-author_head span {
	font-family: <?php echo $OptionsVisC["comm_date_font"];?>;
	color: <?php echo $OptionsVisC["comm_date_color"];?>;
	font-size: <?php echo $OptionsVisC["comm_date_size"];?>;
	font-weight:<?php echo $OptionsVisC["comm_date_font_style"];?>; 
	font-weight: 300;
}
.desc div {
	font-family: <?php echo $OptionsVisC["comm_font"];?>;
	color: <?php echo $OptionsVisC["comm_font_color"];?>;
	font-size: <?php echo $OptionsVisC["comm_font_size"];?>;
	font-weight:<?php echo $OptionsVisC["comm_font_style"];?>; 
	font-style:<?php echo $OptionsVisC["comm_font_weight"];?>;
}


/* "READ MORE" link */
.post-info a.read-more-sbp {
	color:<?php echo $OptionsVis["more_font_color"];?> !important;
	font-family:<?php echo $OptionsVis["more_font"];?> !important;
	font-size:<?php echo $OptionsVis["more_font_size"];?>;
	font-weight:<?php echo $OptionsVis["more_font_weight"];?> !important;
	font-style:<?php echo $OptionsVis["more_font_style"];?> !important;
	text-decoration:<?php echo $OptionsVis["more_text_decoration"];?> !important;
}
/* "READ MORE" link: hover */
.post-info a.read-more-sbp:hover {
	color:<?php echo $OptionsVis["more_font_color_hover"];?> !important;
	font-family:<?php echo $OptionsVis["more_font"];?> !important;
	font-size:<?php echo $OptionsVis["more_font_size"];?>;
	font-weight:<?php echo $OptionsVis["more_font_weight"];?> !important;
	font-style:<?php echo $OptionsVis["more_font_style"];?> !important;
	text-decoration:<?php echo $OptionsVis["more_text_decoration_hover"];?> !important;
}


/* Distance between posts in the list */
.content-grid-info {
    margin-bottom: <?php echo $OptionsVis["dist_btw_posts"];?>;
}


.blog_tags {
	color: <?php echo $OptionsVis["tagged_font_color"];?> !important;
	font-family: <?php echo $OptionsVis["tagged_family"];?> !important;
	font-size: <?php echo $OptionsVis["tagged_font_size"];?> !important;
	font-style: <?php echo $OptionsVis["tagged_font_style"];?> !important;
	font-weight: <?php echo $OptionsVis["tagged_font_weight"];?> !important;
	padding: 0 0 <?php echo $OptionsVis["dist_tags_nav"];?> 0;
}

.blog_tags a {
	color: <?php echo $OptionsVis["tags_font_color"];?> !important;
	font-family: <?php echo $OptionsVis["tags_family"];?> !important;
	text-decoration: <?php echo $OptionsVis["tags_text_decoration"];?> !important;
	font-size: <?php echo $OptionsVis["tags_font_size"];?> !important;
	font-style: <?php echo $OptionsVis["tags_font_style"];?> !important;
	font-weight: <?php echo $OptionsVis["tags_font_weight"];?> !important;
}
.blog_tags a:hover {
	color: <?php echo $OptionsVis["tags_font_color_hover"];?> !important;
	text-decoration: <?php echo $OptionsVis["tags_text_decoration_hover"];?> !important;
	font-size: <?php echo $OptionsVis["tags_font_size"];?> !important;
	font-style: <?php echo $OptionsVis["tags_font_style"];?> !important;
	font-weight: <?php echo $OptionsVis["tags_font_weight"];?> !important;
}


/* PAGINATION */
.sbp_paignation .pager-sbp {
	font-family: <?php echo $OptionsVis["pag_font_family"]; ?> !important;
	font-size:<?php echo $OptionsVis["pag_font_size"];?>;
	font-style:<?php echo $OptionsVis["pag_font_style"];?> !important;
	font-weight:<?php echo $OptionsVis["pag_font_weight"]; ?> !important;
	text-align: <?php echo $OptionsVis["pag_align_to"];?> !important;
}
.sbp_paignation ul.pager-sbp li {
	font-family: <?php echo $OptionsVis["pag_font_family"]; ?> !important;
	font-size:<?php echo $OptionsVis["pag_font_size"];?>;
	font-style:<?php echo $OptionsVis["pag_font_style"];?> !important;
	font-weight:<?php echo $OptionsVis["pag_font_weight"]; ?> !important;
}

.sbp_paignation .pager-sbp li > a, .sbp_paignation .pager-sbp li>span {
	font-family: <?php echo $OptionsVis["pag_font_family"]; ?> !important;
	font-size:<?php echo $OptionsVis["pag_font_size"];?>;
	color: <?php echo $OptionsVis["pag_font_color"];?>;
	background-color: <?php echo $OptionsVis["pag_font_color_hover"];?>;
	font-style:<?php echo $OptionsVis["pag_font_style"];?> !important;
	font-weight:<?php echo $OptionsVis["pag_font_weight"]; ?> !important;
}
.sbp_paignation .pager-sbp li > a:hover, .sbp_paignation .pager-sbp li > a:focus {
	background-color: <?php echo $OptionsVis["pag_color_prn_hover"];?>;
	font-style:<?php echo $OptionsVis["pag_font_style"];?> !important;
	font-weight:<?php echo $OptionsVis["pag_font_weight"]; ?> !important;
}

.sbp_paignation .pager-sbp .active span {
	color: <?php echo $OptionsVis["pag_font_color_sel"];?>;
	background: <?php echo $OptionsVis["pag_font_color_prn"];?>;
	font-style:<?php echo $OptionsVis["pag_font_style"];?> !important;
	font-weight:<?php echo $OptionsVis["pag_font_weight"]; ?> !important;
}

/* BOTTOM NAVIGATION */
.sbp_prevnext .pager-sbp {
	font-family: <?php echo $OptionsVis["bott_font_family"]; ?> !important;
	font-size:<?php echo $OptionsVis["bott_size"];?>;
	font-style:<?php echo $OptionsVis["bott_style"];?> !important;
	font-weight:<?php echo $OptionsVis["bott_weight"]; ?> !important;
	text-align: <?php echo $OptionsVis["bott_align_to"];?> !important;
}
.sbp_prevnext .pager-sbp li > a {
	font-family: <?php echo $OptionsVis["bott_font_family"]; ?> !important;
	color: <?php echo $OptionsVis["bott_color"];?>;
	font-size:<?php echo $OptionsVis["bott_size"];?>;
	background-color: <?php echo $OptionsVis["bott_bgr_color"];?>;
}
.sbp_prevnext .pager-sbp li > a:focus, .sbp_prevnext .pager-sbp li > a:hover {
	color: <?php echo $OptionsVis["bott_color_hover"];?>;
	background-color: <?php echo $OptionsVis["bott_bgr_color_hover"];?>;
}


/* Right content styles */
.recent h3, .archives h3, .categories h3 {
    color: <?php echo $OptionsVis["cat_word_color"]; ?> !important;
	font-family: <?php echo $OptionsVis["cat_word_family"]; ?> !important;
    font-size: <?php echo $OptionsVis["cat_word_font_size"]; ?>;
	font-style:<?php echo $OptionsVis["cat_word_font_style"];?> !important;
	font-weight:<?php echo $OptionsVis["cat_word_font_weight"]; ?> !important;
}
.recent li a, .archives ul li a, .categories ul li a {
	color: <?php echo $OptionsVis["cat_ctm_color"];?> !important;
	border-bottom: 1px solid <?php echo $OptionsVis["line_cat_ctm_color"];?>;
	font-family: <?php echo $OptionsVis["cat_ctm_family"];?> !important;
	font-size: <?php echo $OptionsVis["cat_ctm_font_size"];?>;
	font-style:<?php echo $OptionsVis["cat_ctm_font_style"];?> !important;
	font-weight: <?php echo $OptionsVis["cat_ctm_font_weight"];?>;
}
.recent li a:hover, .archives ul li a:hover, .categories ul li a:hover {
	color: <?php echo $OptionsVis["cat_ctm_color_hover"];?> !important;
}


/* scroll to top styles */
.cd-top {
	width: <?php echo $OptionsVis["scrolltop_width"];?>;
	height: <?php echo $OptionsVis["scrolltop_height"];?>;
	background: <?php echo $OptionsVis["scrolltop_bgr_color"];?> url(<?php echo $CONFIG["full_url"]; ?>images/cd-top-arrow.svg) no-repeat center 50%;
	-webkit-border-radius: <?php echo $OptionsVis["scrolltop_radius"];?> !important;
	-moz-border-radius: <?php echo $OptionsVis["scrolltop_radius"];?> !important;
	border-radius: <?php echo $OptionsVis["scrolltop_radius"];?> !important;
}
.cd-top:hover {
	background-color: <?php echo $OptionsVis["scrolltop_bgr_color_hover"];?>;
}
.cd-top.cd-is-visible {
	/* the button becomes visible */
	-khtml-opacity:<?php echo $OptionsVis["scrolltop_opacity"]/100;?>; 
	-moz-opacity:<?php echo $OptionsVis["scrolltop_opacity"]/100;?>; 
	filter: progid:DXImageTransform.Microsoft.Alpha(opacity=<?php echo $OptionsVis["scrolltop_opacity"]/100;?>);
	opacity: <?php echo $OptionsVis["scrolltop_opacity"]/100;?>; 
	filter:alpha(opacity=<?php echo $OptionsVis["scrolltop_opacity"];?>);
}
.cd-top.cd-fade-out {
	/* if the user keeps scrolling down, the button is out of focus and becomes less visible */
	-khtml-opacity:<?php echo $OptionsVis["scrolltop_opacity_hover"]/100;?>; 
	-moz-opacity:<?php echo $OptionsVis["scrolltop_opacity_hover"]/100;?>; 
	filter: progid:DXImageTransform.Microsoft.Alpha(opacity=<?php echo $OptionsVis["scrolltop_opacity_hover"]/100;?>);
	opacity: <?php echo $OptionsVis["scrolltop_opacity_hover"]/100;?>; 
	filter:alpha(opacity=<?php echo $OptionsVis["scrolltop_opacity_hover"];?>);
}
@media only screen and (min-width: 1024px) {
  .cd-top {
    width: <?php echo $OptionsVis["scrolltop_width"];?>;
  	height: <?php echo $OptionsVis["scrolltop_height"];?>;
  }
}
/* scroll to top style end */

</style>