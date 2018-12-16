<?php 
session_start();
//print_r($_SESSION);
//if(isset($_SERVER['HTTP_REFERER']) and $_SERVER['HTTP_REFERER']!="") { 
//if((isset($_SESSION["KCFinderAktive"]) and ($_SESSION["KCFinderAktive"]=="Activated"))) {
/** This file is part of KCFinder project
  *
  *      @desc Browser calling script
  *   @package KCFinder
  *   @version 3.12
  *    @author Pavel Tzonkov <sunhater@sunhater.com>
  * @copyright 2010-2014 KCFinder Project
  *   @license http://opensource.org/licenses/GPL-3.0 GPLv3
  *   @license http://opensource.org/licenses/LGPL-3.0 LGPLv3
  *      @link http://kcfinder.sunhater.com
  */

require "core/bootstrap.php";
$browser = "kcfinder\\browser"; // To execute core/bootstrap.php on older
$browser = new $browser();      // PHP versions (even PHP 4)
$browser->action();

//} else { echo "You could open this window only from admin area!";}
?>