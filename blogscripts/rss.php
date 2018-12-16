<?php 
namespace SimpleBlogPHP30;
$installed = '';
header("Content-Type: application/rss+xml");
include("configs.php");

$sql = "SELECT * FROM ".$TABLE["Options"];
$sql_result = sql_result($sql);
$Options = mysqli_fetch_assoc($sql_result);

echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
	<atom:link href="<?php echo $CONFIG["full_url"]; ?>rss.php" rel="self" type="application/rss+xml" />
	<title>Blog RSS</title>
	<description>latest 10 posts</description>
	<link><?php echo $CONFIG["full_url"]; ?></link>
<?php
	$sql = "SELECT * FROM ".$TABLE["Posts"]." WHERE status='Posted' ORDER BY publish_date DESC LIMIT 0,10";
	$sql_result = sql_result($sql);
	while ($Post = mysqli_fetch_assoc($sql_result)) {
		$isPermaLink = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFG1234567890'), 0, 30);
?>
	<item>
		<guid isPermaLink='false'><?php echo $isPermaLink.$Post["id"]; ?></guid>
		<title><![CDATA[<?php echo ReadDB($Post["post_title"]); ?>]]></title>
        <link><?php if(trim($Options["items_link"])!=''){ echo ReadDB($Options["items_link"])."?pid=".$Post['id']; } else { echo $CONFIG["full_url"]."preview.php?pid=".$Post["id"]; } ?></link>
		<description><![CDATA[<?php echo preg_replace('/<iframe.*?\/iframe>/i','', ReadDB($Post["post_text"])) ?>]]></description>
        <pubDate><?php echo date("D, d M Y H:i:s O",strtotime($Post["publish_date"])); ?></pubDate>
	</item>
<?php } ?>
</channel>
</rss>