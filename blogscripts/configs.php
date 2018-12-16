<?php  
namespace SimpleBlogPHP30;
use \Datetime;
use \DateTimeZone;
use \DOMDocument;
//error_reporting(0);

include( dirname(__FILE__). "/allinfo.php");

$sql_prefix = "blog3";

//////////////////////////////////////////
////////// DO NOT CHANGE BELOW ///////////
//////////////////////////////////////////

$CONFIG["upload_folder"]='upload/';

$TABLE["Posts"] 	= $sql_prefix.'_posts';
$TABLE["Categories"]= $sql_prefix.'_categories';
$TABLE["Comments"] 	= $sql_prefix.'_comments';
$TABLE["Options"] 	= $sql_prefix.'_options';

$Version = "3.0";

$php_version_min = "5.3.0";
$mysql_version_min = "5.0.0";


if ($installed!='yes') {	
	$connBl = mysqli_connect($CONFIG["hostname"], $CONFIG["mysql_user"], $CONFIG["mysql_password"], $CONFIG["mysql_database"]);
	if (mysqli_connect_errno()) {
		die('MySQL connection error: .'.mysqli_connect_error());
	}
	mysqli_set_charset($connBl, "utf8");
}

// function that replace SELECT, INSERT, UPDATE and DELETE sql statements
if(!function_exists(__NAMESPACE__ . '\sql_result')){ 
	function sql_result($sql) {
		global $connBl;
		$sql_result = mysqli_query ($connBl, $sql) or die ('Could not execute MySQL query: '.$sql.' . Error: '.mysqli_error($connBl));
		return $sql_result;
	}
}

// function for safety SELECT, INSERT, UPDATE and DELETE sql statements
if(!function_exists(__NAMESPACE__ . '\SafetyDB')){ 
	function SafetyDB($str) {
		global $connBl;
		return mysqli_real_escape_string($connBl, $str); 
	}
}

// function for escaping quotes in INSERT and UPDATE sql statements
if(!function_exists(__NAMESPACE__ . '\SaveDB')){ 
	function SaveDB($str) {
		if (!get_magic_quotes_gpc()) {	
			return addslashes($str); 
		} else {
			return $str;
		}
	}
}

// function for escaping quotes in SELECT sql statements
if(!function_exists(__NAMESPACE__ . '\ReadDB')){ 
	function ReadDB($str) {
		return stripslashes($str);
	}
}

// function for escaping quotes in SELECT sql statements with showing the quotes
if(!function_exists(__NAMESPACE__ . '\ReadHTML')){ 
	function ReadHTML($str) {
		return htmlspecialchars(stripslashes($str), ENT_QUOTES);
	}
}

// function that formatting date and time in admin area
if(!function_exists(__NAMESPACE__ . '\admin_date')){ 
	function admin_date($db_date) {
		return date("M j, Y, g:i a",strtotime($db_date));
	}
}

// function that cut srting and keep html tags in safe
if(!function_exists(__NAMESPACE__ . '\cutStrHTML')){ 
	function cutStrHTML($str,$start,$len){
	
		$str_clean = substr(strip_tags($str),$start,$len);
		$pos = strrpos($str_clean, " ");
		if($pos === false) {
			$str_clean = substr(strip_tags($str),$start,$len);  
		} else {
			$str_clean = substr(strip_tags($str),$start,$pos+12);
		}
	
		if(preg_match_all('/\<[^>]+>/is',$str,$matches,PREG_OFFSET_CAPTURE)){
	
			for($i=0;$i<count($matches[0]);$i++) {
	
				if($matches[0][$i][1] < $len) {
	
					$str_clean = substr($str_clean,0,$matches[0][$i][1]) . $matches[0][$i][0] . substr($str_clean,$matches[0][$i][1]);
	
				} else if(preg_match('/\<[^>]+>$/is',$matches[0][$i][0])) {
	
					$str_clean = substr($str_clean,0,$matches[0][$i][1]) . $matches[0][$i][0] . substr($str_clean,$matches[0][$i][1]);
	
					break;
	
				}
	
			}
	
			return $str_clean;
	
		} else {
			$string = substr($str,$start,$len);
			$pos = strrpos($string, " ");
			if($pos === false) {
				return substr($str,$start,$len)."... ";
			}
			return substr($str,$start,$pos)."... ";
	
		}
	
	}
}


// function that generate color picker and color fields
if(!function_exists(__NAMESPACE__ . '\color_field')){ 
	function color_field($field, $color) {
		$picker  = '<input id="'.$field.'" name="'.$field.'" type="text" size="7" value="'.$color.'" style="background-color:'.$color.';" />';
		$picker .= '<button class="jscolor{valueElement:\''.$field.'\', styleElement:\''.$field.'\', hash:true, required:false, closable:true, width:260, height:150} color_field">&nbsp;</button><sub> - you can pick the color from pallette or you can put it manualy</sub>';
		
		return $picker;
	}
}

// function that invert colors in HTML
if(!function_exists(__NAMESPACE__ . '\invert_colour')){ 
	function invert_colour($start_colour) {
		if($start_colour!='') {
			$colour_red = hexdec(substr($start_colour, 1, 2));
			$colour_green = hexdec(substr($start_colour, 3, 2));
			$colour_blue = hexdec(substr($start_colour, 5, 2));
			
			$new_red = dechex(255 - $colour_red);
			$new_green = dechex(255 - $colour_green);
			$new_blue = dechex(255 - $colour_blue);
			
			if (strlen($new_red) == 1) {$new_red .= '0';}
			if (strlen($new_green) == 1) {$new_green .= '0';}
			if (strlen($new_blue) == 1) {$new_blue .= '0';}
			
			$new_colour = '#'.$new_red.$new_green.$new_blue;
		} else {
			$new_colour = '#000000';
		}
		return $new_colour;
	}
}

// function that strip selected html tags
if(!function_exists(__NAMESPACE__ . '\strip_only')){ 
	function strip_only($str, $tags, $stripContent = false) {
		$content = '';
		if(!is_array($tags)) {
			$tags = (strpos($str, '>') !== false ? explode('>', str_replace('<', '', $tags)) : array($tags));
			if(end($tags) == '') array_pop($tags);
		}
		foreach($tags as $tag) {
			if ($stripContent)
				 $content = '(.+</'.$tag.'[^>]*>|)';
			 $str = preg_replace('#</?'.$tag.'[^>]*>'.$content.'#is', '', $str);
		}
		return $str;
	} 
}

// function that get time zones 
if(!function_exists(__NAMESPACE__ . '\get_timezones')){ 
	function get_timezones() {
		$o = array();
		 
		$t_zones = timezone_identifiers_list();
		 
		foreach($t_zones as $a) {
			$t = '';
			 
			try {
				//this throws exception for 'US/Pacific-New'
				$zone = new DateTimeZone($a);
				 
				$seconds = $zone->getOffset( new DateTime("now" , $zone) );
				$hours = sprintf( "%+02d" , intval($seconds/3600));
				$minutes = sprintf( "%02d" , ($seconds%3600)/60 );
		 
				$t = $a ."  [ $hours:$minutes ]" ;
				 
				$o[$a] = $t;
			}
			 
			//exceptions must be catched, else a blank page
			catch(Exception $e) {
				//die("Exception : " . $e->getMessage() . '<br />');
				//what to do in catch ? , nothing just relax
			}
		}
		 
		ksort($o);
		 
		return $o;
	}
}


// function that remove unnecessary characters, quotes, spaces in the meta tags
if(!function_exists(__NAMESPACE__ . '\remove_quote')){ 
	function remove_quote($key_text) {	
		$key_text = str_replace("á", "a", $key_text);
		$key_text = str_replace("â", "a", $key_text);
		$key_text = str_replace("ã", "a", $key_text);
		$key_text = str_replace("à", "a", $key_text);
		$key_text = str_replace("é", "e", $key_text);
		$key_text = str_replace("ê", "e", $key_text);
		$key_text = str_replace("í", "i", $key_text);
		$key_text = str_replace("ó", "o", $key_text);
		$key_text = str_replace("ô", "o", $key_text);
		$key_text = str_replace("õ", "o", $key_text);
		$key_text = str_replace("ú", "u", $key_text);
		$key_text = str_replace("\t", "", $key_text);
		$key_text = str_replace("\r", "", $key_text);
		$key_text = str_replace("\n", "", $key_text);	
		$key_text = str_replace("&reg;", "", $key_text);
		$key_text = str_replace("&nbsp;", "", $key_text);
		$key_text = str_replace("&trade;", "", $key_text);
		$key_text = str_replace("&amp;", "&", $key_text);
		$key_text = str_replace("&nbsp;", " ", $key_text);
		$key_text = str_replace("&rsquo;", " ", $key_text);
		$key_text = str_replace("&hellip;", ".. ", $key_text);
		$key_text = str_replace("&ntilde;", ".. ", $key_text);
		$key_text = str_replace("&ldquo;", ".. ", $key_text);
		$key_text = str_replace("&rdquo;", ".. ", $key_text);
		$key_text = str_replace("                       ", " ", $key_text);	
		$key_text = str_replace("                      ", " ", $key_text);
		$key_text = str_replace("                     ", " ", $key_text);
		$key_text = str_replace("                    ", " ", $key_text);
		$key_text = str_replace("                   ", " ", $key_text);
		$key_text = str_replace("                  ", " ", $key_text);
		$key_text = str_replace("                 ", " ", $key_text);
		$key_text = str_replace("                ", " ", $key_text);
		$key_text = str_replace("               ", " ", $key_text);
		$key_text = str_replace("              ", " ", $key_text);
		$key_text = str_replace("             ", " ", $key_text);
		$key_text = str_replace("            ", " ", $key_text);
		$key_text = str_replace("           ", " ", $key_text);
		$key_text = str_replace("          ", " ", $key_text);
		$key_text = str_replace("         ", " ", $key_text);
		$key_text = str_replace("        ", " ", $key_text);
		$key_text = str_replace("       ", " ", $key_text);
		$key_text = str_replace("      ", " ", $key_text);
		$key_text = str_replace("     ", " ", $key_text);
		$key_text = str_replace("    ", " ", $key_text);
		$key_text = str_replace("   ", " ", $key_text);
		$key_text = str_replace("  ", " ", $key_text);
		$key_text = str_replace("'", "", $key_text);
		$key_text = str_replace('"', '', $key_text);
		return $key_text;	
	}
}

// function that cut plain text to any number of characters
if(!function_exists(__NAMESPACE__ . '\cutText')){ 
	function cutText($strMy, $maxLength) {
		$ret = substr($strMy, 0, $maxLength);
		if (substr($ret, strlen($ret)-1,1) != " " && strlen($strMy) > $maxLength) {
			$ret1 = substr($ret, 0, strrpos($ret," "))." ...";
		} elseif(substr($ret, strlen($ret)-1,1) == " " && strlen($strMy) > $maxLength) {
			$ret1 = $ret." ...";
		} else {
			$ret1 = $ret;
		}
		return $ret1;
	}
}

if (!function_exists(__NAMESPACE__ . '\timezone_list')) { 
	function timezone_list(){
		$t_id_list=array(0=>"Africa/Abidjan",1=>"Africa/Accra",2=>"Africa/Addis_Ababa",3=>"Africa/Algiers",4=>"Africa/Asmara",5=>"Africa/Bamako",6=>"Africa/Bangui",7=>"Africa/Banjul",8=>"Africa/Bissau",9=>"Africa/Blantyre",10=>"Africa/Brazzaville",11=>"Africa/Bujumbura",12=>"Africa/Cairo",13=>"Africa/Casablanca",14=>"Africa/Ceuta",15=>"Africa/Conakry",16=>"Africa/Dakar",17=>"Africa/Dar_es_Salaam",18=>"Africa/Djibouti",19=>"Africa/Douala",20=>"Africa/El_Aaiun",21=>"Africa/Freetown",22=>"Africa/Gaborone",23=>"Africa/Harare",24=>"Africa/Johannesburg",25=>"Africa/Kampala",26=>"Africa/Khartoum",27=>"Africa/Kigali",28=>"Africa/Kinshasa",29=>"Africa/Lagos",30=>"Africa/Libreville",31=>"Africa/Lome",32=>"Africa/Luanda",33=>"Africa/Lubumbashi",34=>"Africa/Lusaka",35=>"Africa/Malabo",36=>"Africa/Maputo",37=>"Africa/Maseru",38=>"Africa/Mbabane",39=>"Africa/Mogadishu",40=>"Africa/Monrovia",41=>"Africa/Nairobi",42=>"Africa/Ndjamena",43=>"Africa/Niamey",44=>"Africa/Nouakchott",45=>"Africa/Ouagadougou",46=>"Africa/Porto-Novo",47=>"Africa/Sao_Tome",48=>"Africa/Tripoli",49=>"Africa/Tunis",50=>"Africa/Windhoek",51=>"America/Adak",52=>"America/Anchorage",53=>"America/Anguilla",54=>"America/Antigua",55=>"America/Araguaina",56=>"America/Argentina/Buenos_Aires",57=>"America/Argentina/Catamarca",58=>"America/Argentina/Cordoba",59=>"America/Argentina/Jujuy",60=>"America/Argentina/La_Rioja",61=>"America/Argentina/Mendoza",62=>"America/Argentina/Rio_Gallegos",63=>"America/Argentina/Salta",64=>"America/Argentina/San_Juan",65=>"America/Argentina/San_Luis",66=>"America/Argentina/Tucuman",67=>"America/Argentina/Ushuaia",68=>"America/Aruba",69=>"America/Asuncion",70=>"America/Atikokan",71=>"America/Bahia",72=>"America/Bahia_Banderas",73=>"America/Barbados",74=>"America/Belem",75=>"America/Belize",76=>"America/Blanc-Sablon",77=>"America/Boa_Vista",78=>"America/Bogota",79=>"America/Boise",80=>"America/Cambridge_Bay",81=>"America/Campo_Grande",82=>"America/Cancun",83=>"America/Caracas",84=>"America/Cayenne",85=>"America/Cayman",86=>"America/Chicago",87=>"America/Chihuahua",88=>"America/Costa_Rica",89=>"America/Cuiaba",90=>"America/Curacao",91=>"America/Danmarkshavn",92=>"America/Dawson",93=>"America/Dawson_Creek",94=>"America/Denver",95=>"America/Detroit",96=>"America/Dominica",97=>"America/Edmonton",98=>"America/Eirunepe",99=>"America/El_Salvador",100=>"America/Fortaleza",101=>"America/Glace_Bay",102=>"America/Godthab",103=>"America/Goose_Bay",104=>"America/Grand_Turk",105=>"America/Grenada",106=>"America/Guadeloupe",107=>"America/Guatemala",108=>"America/Guayaquil",109=>"America/Guyana",110=>"America/Halifax",111=>"America/Havana",112=>"America/Hermosillo",113=>"America/Indiana/Indianapolis",114=>"America/Indiana/Knox",115=>"America/Indiana/Marengo",116=>"America/Indiana/Petersburg",117=>"America/Indiana/Tell_City",118=>"America/Indiana/Vevay",119=>"America/Indiana/Vincennes",120=>"America/Indiana/Winamac",121=>"America/Inuvik",122=>"America/Iqaluit",123=>"America/Jamaica",124=>"America/Juneau",125=>"America/Kentucky/Louisville",126=>"America/Kentucky/Monticello",127=>"America/La_Paz",128=>"America/Lima",129=>"America/Los_Angeles",130=>"America/Maceio",131=>"America/Managua",132=>"America/Manaus",133=>"America/Marigot",134=>"America/Martinique",135=>"America/Matamoros",136=>"America/Mazatlan",137=>"America/Menominee",138=>"America/Merida",139=>"America/Metlakatla",140=>"America/Mexico_City",141=>"America/Miquelon",142=>"America/Moncton",143=>"America/Monterrey",144=>"America/Montevideo",145=>"America/Montreal",146=>"America/Montserrat",147=>"America/Nassau",148=>"America/New_York",149=>"America/Nipigon",150=>"America/Nome",151=>"America/Noronha",152=>"America/North_Dakota/Beulah",153=>"America/North_Dakota/Center",154=>"America/North_Dakota/New_Salem",155=>"America/Ojinaga",156=>"America/Panama",157=>"America/Pangnirtung",158=>"America/Paramaribo",159=>"America/Phoenix",160=>"America/Port-au-Prince",161=>"America/Port_of_Spain",162=>"America/Porto_Velho",163=>"America/Puerto_Rico",164=>"America/Rainy_River",165=>"America/Rankin_Inlet",166=>"America/Recife",167=>"America/Regina",168=>"America/Resolute",169=>"America/Rio_Branco",170=>"America/Santa_Isabel",171=>"America/Santarem",172=>"America/Santiago",173=>"America/Santo_Domingo",174=>"America/Sao_Paulo",175=>"America/Scoresbysund",176=>"America/Shiprock",177=>"America/Sitka",178=>"America/St_Barthelemy",179=>"America/St_Johns",180=>"America/St_Kitts",181=>"America/St_Lucia",182=>"America/St_Thomas",183=>"America/St_Vincent",184=>"America/Swift_Current",185=>"America/Tegucigalpa",186=>"America/Thule",187=>"America/Thunder_Bay",188=>"America/Tijuana",189=>"America/Toronto",190=>"America/Tortola",191=>"America/Vancouver",192=>"America/Whitehorse",193=>"America/Winnipeg",194=>"America/Yakutat",195=>"America/Yellowknife",196=>"Antarctica/Casey",197=>"Antarctica/Davis",198=>"Antarctica/DumontDUrville",199=>"Antarctica/Macquarie",200=>"Antarctica/Mawson",201=>"Antarctica/McMurdo",202=>"Antarctica/Palmer",203=>"Antarctica/Rothera",204=>"Antarctica/South_Pole",205=>"Antarctica/Syowa",206=>"Antarctica/Vostok",207=>"Arctic/Longyearbyen",208=>"Asia/Aden",209=>"Asia/Almaty",210=>"Asia/Amman",211=>"Asia/Anadyr",212=>"Asia/Aqtau",213=>"Asia/Aqtobe",214=>"Asia/Ashgabat",215=>"Asia/Baghdad",216=>"Asia/Bahrain",217=>"Asia/Baku",218=>"Asia/Bangkok",219=>"Asia/Beirut",220=>"Asia/Bishkek",221=>"Asia/Brunei",222=>"Asia/Choibalsan",223=>"Asia/Chongqing",224=>"Asia/Colombo",225=>"Asia/Damascus",226=>"Asia/Dhaka",227=>"Asia/Dili",228=>"Asia/Dubai",229=>"Asia/Dushanbe",230=>"Asia/Gaza",231=>"Asia/Harbin",232=>"Asia/Ho_Chi_Minh",233=>"Asia/Hong_Kong",234=>"Asia/Hovd",235=>"Asia/Irkutsk",236=>"Asia/Jakarta",237=>"Asia/Jayapura",238=>"Asia/Jerusalem",239=>"Asia/Kabul",240=>"Asia/Kamchatka",241=>"Asia/Karachi",242=>"Asia/Kashgar",243=>"Asia/Kathmandu",244=>"Asia/Kolkata",245=>"Asia/Krasnoyarsk",246=>"Asia/Kuala_Lumpur",247=>"Asia/Kuching",248=>"Asia/Kuwait",249=>"Asia/Macau",250=>"Asia/Magadan",251=>"Asia/Makassar",252=>"Asia/Manila",253=>"Asia/Muscat",254=>"Asia/Nicosia",255=>"Asia/Novokuznetsk",256=>"Asia/Novosibirsk",257=>"Asia/Omsk",258=>"Asia/Oral",259=>"Asia/Phnom_Penh",260=>"Asia/Pontianak",261=>"Asia/Pyongyang",262=>"Asia/Qatar",263=>"Asia/Qyzylorda",264=>"Asia/Rangoon",265=>"Asia/Riyadh",266=>"Asia/Sakhalin",267=>"Asia/Samarkand",268=>"Asia/Seoul",269=>"Asia/Shanghai",270=>"Asia/Singapore",271=>"Asia/Taipei",272=>"Asia/Tashkent",273=>"Asia/Tbilisi",274=>"Asia/Tehran",275=>"Asia/Thimphu",276=>"Asia/Tokyo",277=>"Asia/Ulaanbaatar",278=>"Asia/Urumqi",279=>"Asia/Vientiane",280=>"Asia/Vladivostok",281=>"Asia/Yakutsk",282=>"Asia/Yekaterinburg",283=>"Asia/Yerevan",284=>"Atlantic/Azores",285=>"Atlantic/Bermuda",286=>"Atlantic/Canary",287=>"Atlantic/Cape_Verde",288=>"Atlantic/Faroe",289=>"Atlantic/Madeira",290=>"Atlantic/Reykjavik",291=>"Atlantic/South_Georgia",292=>"Atlantic/St_Helena",293=>"Atlantic/Stanley",294=>"Australia/Adelaide",295=>"Australia/Brisbane",296=>"Australia/Broken_Hill",297=>"Australia/Currie",298=>"Australia/Darwin",299=>"Australia/Eucla",300=>"Australia/Hobart",301=>"Australia/Lindeman",302=>"Australia/Lord_Howe",303=>"Australia/Melbourne",304=>"Australia/Perth",305=>"Australia/Sydney",306=>"Europe/Amsterdam",307=>"Europe/Andorra",308=>"Europe/Athens",309=>"Europe/Belgrade",310=>"Europe/Berlin",311=>"Europe/Bratislava",312=>"Europe/Brussels",313=>"Europe/Bucharest",314=>"Europe/Budapest",315=>"Europe/Chisinau",316=>"Europe/Copenhagen",317=>"Europe/Dublin",318=>"Europe/Gibraltar",319=>"Europe/Guernsey",320=>"Europe/Helsinki",321=>"Europe/Isle_of_Man",322=>"Europe/Istanbul",323=>"Europe/Jersey",324=>"Europe/Kaliningrad",325=>"Europe/Kiev",326=>"Europe/Lisbon",327=>"Europe/Ljubljana",328=>"Europe/London",329=>"Europe/Luxembourg",330=>"Europe/Madrid",331=>"Europe/Malta",332=>"Europe/Mariehamn",333=>"Europe/Minsk",334=>"Europe/Monaco",335=>"Europe/Moscow",336=>"Europe/Oslo",337=>"Europe/Paris",338=>"Europe/Podgorica",339=>"Europe/Prague",340=>"Europe/Riga",341=>"Europe/Rome",342=>"Europe/Samara",343=>"Europe/San_Marino",344=>"Europe/Sarajevo",345=>"Europe/Simferopol",346=>"Europe/Skopje",347=>"Europe/Sofia",348=>"Europe/Stockholm",349=>"Europe/Tallinn",350=>"Europe/Tirane",351=>"Europe/Uzhgorod",352=>"Europe/Vaduz",353=>"Europe/Vatican",354=>"Europe/Vienna",355=>"Europe/Vilnius",356=>"Europe/Volgograd",357=>"Europe/Warsaw",358=>"Europe/Zagreb",359=>"Europe/Zaporozhye",360=>"Europe/Zurich",361=>"Indian/Antananarivo",362=>"Indian/Chagos",363=>"Indian/Christmas",364=>"Indian/Cocos",365=>"Indian/Comoro",366=>"Indian/Kerguelen",367=>"Indian/Mahe",368=>"Indian/Maldives",369=>"Indian/Mauritius",370=>"Indian/Mayotte",371=>"Indian/Reunion",372=>"Pacific/Apia",373=>"Pacific/Auckland",374=>"Pacific/Chatham",375=>"Pacific/Chuuk",376=>"Pacific/Easter",377=>"Pacific/Efate",378=>"Pacific/Enderbury",379=>"Pacific/Fakaofo",380=>"Pacific/Fiji",381=>"Pacific/Funafuti",382=>"Pacific/Galapagos",383=>"Pacific/Gambier",384=>"Pacific/Guadalcanal",385=>"Pacific/Guam",386=>"Pacific/Honolulu",387=>"Pacific/Johnston",388=>"Pacific/Kiritimati",389=>"Pacific/Kosrae",390=>"Pacific/Kwajalein",391=>"Pacific/Majuro",392=>"Pacific/Marquesas",393=>"Pacific/Midway",394=>"Pacific/Nauru",395=>"Pacific/Niue",396=>"Pacific/Norfolk",397=>"Pacific/Noumea",398=>"Pacific/Pago_Pago",399=>"Pacific/Palau",400=>"Pacific/Pitcairn",401=>"Pacific/Pohnpei",402=>"Pacific/Port_Moresby",403=>"Pacific/Rarotonga",404=>"Pacific/Saipan",405=>"Pacific/Tahiti",406=>"Pacific/Tarawa",407=>"Pacific/Tongatapu",408=>"Pacific/Wake",409=>"Pacific/Wallis",410=>"UTC "); 
	return $t_id_list; 
	} 
}


// list of all the fonts in select drop-down menu
if (!function_exists(__NAMESPACE__ . '\font_family_list')) { 
	function font_family_list($fontSelected) {
		
		$fonts = array(
					'Arial'=>'Arial,Helvetica Neue,Helvetica,sans-serif', 
					'Arial Black'=>'Arial Black,Arial Bold,Gadget,sans-serif',
					'Arial Narrow'=>'Arial Narrow,Arial,sans-serif', 
					'Brush Script MT'=>'Brush Script MT,cursive', 
					'Book Antiqua'=>'Book Antiqua,Palatino,Palatino Linotype,Palatino LT STD,Georgia,serif', 
					'Century Gothic'=>'Century Gothic,CenturyGothic,AppleGothic,sans-serif',
					'Comic Sans MS'=>'Comic Sans MS, cursive, sans-serif', 
					'Copperplate'=>'Copperplate,Copperplate Gothic Light,fantasy', 
					'Courier New'=>'Courier New,Courier,Lucida Sans Typewriter,Lucida Typewriter,monospace', 
					'Gill Sans'=>'Gill Sans,Gill Sans MT,Calibri,sans-serif', 
					'Garamond'=>'Garamond,Baskerville,Baskerville Old Face,Hoefler Text,Times New Roman,serif', 
					'Georgia'=>'Georgia,Times,Times New Roman,serif', 
					'Helvetica'=>'Helvetica Neue,Helvetica,Arial,sans-serif', 
					'Impact'=>'Impact, Charcoal, sans-serif',
					'Lucida Bright'=>'Lucida Bright,Georgia,serif', 
					'Lucida Console'=>'Lucida Console,Lucida Sans Typewriter,monaco,Bitstream Vera Sans Mono,monospace',
					'Lucida Sans Unicode'=>'Lucida Sans Unicode, Lucida Grande, sans-serif', 
					'Palatino'=>'Palatino,Palatino Linotype,Palatino LT STD,Book Antiqua,Georgia,serif',
					'Papyrus'=>'Papyrus,fantasy', 
					'Tahoma'=>'Tahoma,Verdana,Segoe,sans-serif', 
					'Times New Roman'=>'TimesNewRoman,Times New Roman,Times,Baskerville,Georgia,serif', 
					'Trebuchet MS'=>'Trebuchet MS,Lucida Grande,Lucida Sans Unicode,Lucida Sans,Tahoma,sans-serif', 
					'Verdana'=>'Verdana,Geneva,sans-serif', 
					'inherit'=>'inherit',
					'-- custom fonts --'=>'--custom fonts--',  
					'Azbuka04'=>'Azbuka04,Helvetica Neue,Helvetica,sans-serif', 
					'Avalon-Bold'=>'Avalon-Bold,Helvetica Neue,Helvetica,sans-serif',
					'Avalon-Plain'=>'Avalon-Plain,Helvetica Neue,Helvetica,sans-serif',
					'Cour'=>'Cour,Helvetica Neue,Helvetica,sans-serif',
					'DSNote'=>'DSNote,Helvetica Neue,Helvetica,sans-serif',
					'HebarU'=>'HebarU,Helvetica Neue,Helvetica,sans-serif',
					'Lato-Regular'=>'Lato-Regular,Helvetica Neue,Helvetica,sans-serif',
					'Montserrat-Regular'=>'Montserrat-Regular,Helvetica Neue,Helvetica,sans-serif',
					'MTCORSVA'=>'MTCORSVA,Helvetica Neue,Helvetica,sans-serif',  
					'Nicoletta_script'=>'Nicoletta_script,Helvetica Neue,Helvetica,sans-serif', 
					'OpenSans'=>'Open Sans,Helvetica Neue,Helvetica,sans-serif',   
					'Oswald-Light'=>'Oswald-Light,Helvetica Neue,Helvetica,sans-serif',      
					'Oswald-Regular'=>'Oswald-Regular,Helvetica Neue,Helvetica,sans-serif',
					'Raleway-Regular'=>'Raleway-Regular,Helvetica Neue,Helvetica,sans-serif',
					'Regina Kursiv'=>'Regina Kursiv,Helvetica Neue,Helvetica,sans-serif',		
					'Ubuntu-R'=>'Ubuntu-R,Helvetica Neue,Helvetica,sans-serif'
				);	
		
		$listInput = "";		
		foreach($fonts as $Font=>$FontFull) {
			$listInput .= "<option value='".$FontFull."'";
			if($FontFull==$fontSelected) $listInput .= " selected='selected'";
			if($Font=='-- custom fonts --') $listInput .= " disabled";
			$listInput .= ">".$Font."</option>\n\t\t\t\t";	
		}
		
		return $listInput;
	}
}

/**
 * truncateBlogHtml can truncate a string up to a number of characters while preserving whole words and HTML tags
 *
 * @param string $text String to truncate.
 * @param integer $length Length of returned string, including ellipsis.
 * @param string $ending Ending to be appended to the trimmed string.
 * @param boolean $exact If false, $text will not be cut mid-word
 * @param boolean $considerHtml If true, HTML tags would be handled correctly
 *
 * @return string Trimmed string.
 */
if(!function_exists(__NAMESPACE__ . '\truncateBlogHtml')){ 
function truncateBlogHtml($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true) {
	if ($considerHtml) {
		// if the plain text is shorter than the maximum length, return the whole text
		if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
			return $text;
		}
		// splits all html-tags to scanable lines
		preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
		$total_length = strlen($ending);
		$open_tags = array();
		$truncate = '';
		foreach ($lines as $line_matchings) {
			// if there is any html-tag in this line, handle it and add it (uncounted) to the output
			if (!empty($line_matchings[1])) {
				// if it's an "empty element" with or without xhtml-conform closing slash
				if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
					// do nothing
				// if tag is a closing tag
				} else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
					// delete tag from $open_tags list
					$pos = array_search($tag_matchings[1], $open_tags);
					if ($pos !== false) {
					unset($open_tags[$pos]);
					}
				// if tag is an opening tag
				} else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
					// add tag to the beginning of $open_tags list
					array_unshift($open_tags, strtolower($tag_matchings[1]));
				}
				// add html-tag to $truncate'd text
				$truncate .= $line_matchings[1];
			}
			// calculate the length of the plain text part of the line; handle entities as one character
			$content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
			if ($total_length+$content_length> $length) {
				// the number of characters which are left
				$left = $length - $total_length;
				$entities_length = 0;
				// search for html entities
				if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
					// calculate the real length of all entities in the legal range
					foreach ($entities[0] as $entity) {
						if ($entity[1]+1-$entities_length <= $left) {
							$left--;
							$entities_length += strlen($entity[0]);
						} else {
							// no more characters left
							break;
						}
					}
				}
				$truncate .= substr($line_matchings[2], 0, $left+$entities_length);
				// maximum lenght is reached, so get off the loop
				break;
			} else {
				$truncate .= $line_matchings[2];
				$total_length += $content_length;
			}
			// if the maximum length is reached, get off the loop
			if($total_length>= $length) {
				break;
			}
		}
	} else {
		if (strlen($text) <= $length) {
			return $text;
		} else {
			$truncate = substr($text, 0, $length - strlen($ending));
		}
	}
	// if the words shouldn't be cut in the middle...
	if (!$exact) {
		// ...search the last occurance of a space...
		$spacepos = strrpos($truncate, ' ');
		if (isset($spacepos)) {
			// ...and cut the text in this position
			$truncate = substr($truncate, 0, $spacepos);
		}
	}
	// add the defined ending to the text
	$truncate .= $ending;
	if($considerHtml) {
		// close all unclosed html-tags
		foreach ($open_tags as $tag) {
			$truncate .= '</' . $tag . '>';
		}
	}
	return $truncate;
}
}

if(!function_exists(__NAMESPACE__ . '\keywords')){ 
	function keywords($key_text) {
		$key_text = str_replace(".", " ", $key_text);
		$key_text = str_replace("-", "", $key_text);
		$key_text = str_replace("!", "", $key_text);
		$key_text = str_replace("?", "", $key_text);
		$key_text = str_replace("\\", "", $key_text);
		$key_text = str_replace("/", "", $key_text);
		$key_text = str_replace(":", "", $key_text);
		$key_text = str_replace("&reg;", "", $key_text);
		$key_text = str_replace("&nbsp;", "", $key_text);
		$key_text = str_replace("&trade;", "", $key_text);
		$key_text = str_replace(" ", ", ", $key_text);
		return $key_text;
	}
}

/* check whether text is cyrillic or not */
if(!function_exists(__NAMESPACE__ . '\isCyrillic')){ 
	function isCyrillic($text) {
		return preg_match('/[А-Яа-яЁё]/u', $text);
	}
}



// function for resize image. If $thumbnail is not set then creates the full description image
if(!function_exists(__NAMESPACE__ . '\Resize_File')){ 
	function Resize_File($full_file, $max_width, $max_height, $thumbnail="") {
		
		if (preg_match("/\.png$/i", $full_file)) {
			$img = imagecreatefrompng($full_file);
		}
		
		if (preg_match("/\.(jpg|jpeg)$/i", $full_file)) {
			$img = imagecreatefromjpeg($full_file);
		}
		
		if (preg_match("/\.gif$/i", $full_file)) {
			$img = imagecreatefromgif($full_file);
		}
		
		$FullImage_width = imagesx($img);
		$FullImage_height = imagesy($img);
		
		if (isset($max_width) && isset($max_height) && $max_width != 0 && $max_height != 0 && $FullImage_width>$max_width && $FullImage_height>$max_height) {
			$new_width = $max_width;
			$new_height = $max_height;
		} elseif (isset($max_width) && $max_width != 0 && $FullImage_width>$max_width) {
			$new_width = $max_width;
			$new_height = ((int)($new_width * $FullImage_height) / $FullImage_width);
		} elseif (isset($max_height) && $max_height != 0 && $FullImage_height>$max_height) {
			$new_height = $max_height;
			$new_width = ((int)($new_height * $FullImage_width) / $FullImage_height);
		} else {
			$new_height = $FullImage_height;
			$new_width = $FullImage_width;
		}
		
		$full_id = imagecreatetruecolor((int)$new_width, (int)$new_height);
		if (preg_match("/\.png$/i", $full_file) or preg_match("/\.gif$/i", $full_file)) {
			imagecolortransparent($full_id, imagecolorallocatealpha($full_id, 0, 0, 0, 0));
		}
		imagecopyresampled($full_id, $img, 0, 0, 0, 0, (int)$new_width, (int)$new_height, $FullImage_width, $FullImage_height);
		
		
		if (preg_match("/\.(jpg|jpeg)$/i", $full_file)) {
			if($thumbnail!="") {
				imagejpeg($full_id, $thumbnail, 100);
			} else {
				imagejpeg($full_id, $full_file, 100);
			}
		}
		
		if (preg_match("/\.png$/i", $full_file)) {		
			if($thumbnail!="") {
				imagepng($full_id, $thumbnail);
			} else {
				imagepng($full_id, $full_file);
			}
		}
		
		if (preg_match("/\.gif$/i", $full_file)) {		
			if($thumbnail!="") {
				imagegif($full_id, $thumbnail);
			} else {
				imagegif($full_id, $full_file);
			}
		}
		
		imagedestroy($full_id);
		unset($max_width);
		unset($max_height);
	}
}

// Returns filesystem-safe string after cleaning, filtering, and trimming input
if (!function_exists(__NAMESPACE__ . '\str_file_filter')) { 
	function str_file_filter($str, $sep = '_', $strict = false, $trim = 248) {
	
		$str = strip_tags(htmlspecialchars_decode(strtolower($str))); // lowercase -> decode -> strip tags
		$str = str_replace("%20", ' ', $str); // convert rogue %20s into spaces
		$str = preg_replace("/%[a-z0-9]{1,2}/i", '', $str); // remove hexy things
		$str = str_replace("&nbsp;", ' ', $str); // convert all nbsp into space
		$str = preg_replace("/&#?[a-z0-9]{2,8};/i", '', $str); // remove the other non-tag things
		$str = preg_replace("/\s+/", $sep, $str); // filter multiple spaces
		$str = preg_replace("/\.+/", '.', $str); // filter multiple periods
		$str = preg_replace("/^\.+/", '', $str); // trim leading period
	
		if ($strict) {
			$str = preg_replace("/([^\w\d\\" . $sep . ".])/", '', $str); // only allow words and digits
		} else {
			$str = preg_replace("/([^\w\d\\" . $sep . "\[\]\(\).])/", '', $str); // allow words, digits, [], and ()
		}
	
		$str = preg_replace("/\\" . $sep . "+/", $sep, $str); // filter multiple separators
		$str = substr($str, 0, $trim); // trim filename to desired length, note 255 char limit on windows
	
		return $str;
	}
}

// add leading zeros
if(!function_exists(__NAMESPACE__ . '\AddZero')){ 
	function AddZero($num) {
		$num_padded = sprintf("%02d", $num);
		return $num_padded;
	}
}


if (!function_exists(__NAMESPACE__ . '\truncateHTML')) { 
	/**
	*  function to truncate and then clean up end of the HTML,
	*  truncates by counting characters outside of HTML tags
	*    
	*  @param string $str the string to truncate
	*  @param int $len the number of characters
	*  @param string $end the end string for truncation
	*  @return string $truncated_html
	*  
	*  **/
	function truncateHTML($str, $len, $end = '&hellip;'){
		//find all tags
		$tagPattern = '/(<\/?)([\w]*)(\s*[^>]*)>?|&[\w#]+;/i';  //match html tags and entities
		preg_match_all($tagPattern, $str, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER );
		//WSDDebug::dump($matches); exit; 
		$i =0;
		//loop through each found tag that is within the $len, add those characters to the len,
		//also track open and closed tags
		// $matches[$i][0] = the whole tag string  --the only applicable field for html enitities  
		// IF its not matching an &htmlentity; the following apply
		// $matches[$i][1] = the start of the tag either '<' or '</'  
		// $matches[$i][2] = the tag name
		// $matches[$i][3] = the end of the tag
		//$matces[$i][$j][0] = the string
		//$matces[$i][$j][1] = the str offest
	
		while($matches[$i][0][1] < $len && !empty($matches[$i])){
	
			$len = $len + strlen($matches[$i][0][0]);
			if(substr($matches[$i][0][0],0,1) == '&' )
				$len = $len-1;
	
			//if $matches[$i][2] is undefined then its an html entity, want to ignore those for tag counting
			//ignore empty/singleton tags for tag counting
			if(!empty($matches[$i][2][0]) && !in_array($matches[$i][2][0],array('br','img','hr', 'input', 'param', 'link'))){
				//double check 
				if(substr($matches[$i][3][0],-1) !='/' && substr($matches[$i][1][0],-1) !='/') {
					$openTags[] = $matches[$i][2][0];
				} elseif(end($openTags) == $matches[$i][2][0]) {
					array_pop($openTags);
				} else {
					$warnings[] = "html has some tags mismatched in it:  $str";
				}
			}
	
			$i++;
	
		}
	
		$closeTags = '';
	
		if (!empty($openTags)){
			$openTags = array_reverse($openTags);
			foreach ($openTags as $t){
				$closeTagString .="</".$t . ">"; 
			}
		}
	
		if(strlen($str)>$len){
			// Finds the last space from the string new length
			$lastWord = strpos($str, ' ', $len);
			if ($lastWord) {
				//truncate with new len last word
				$str = substr($str, 0, $lastWord);
				//finds last character
				$last_character = (substr($str, -1, 1));
				//add the end text
				$truncated_html = ($last_character == '.' ? $str : ($last_character == ',' ? substr($str, 0, -1) : $str) . $end);
			}
			//restore any open tags
			$truncated_html .= $closeTagString;
	
	
		} else {
			$truncated_html = $str;
			return $truncated_html; 
		}
	}
}


if (!function_exists(__NAMESPACE__ . '\purifyHTML')) { 
	/*
	 * this function simply closes all the unclosed tags in the given html
	 * $html is the impure html
	 * returns the purified html
	 */
	function purifyHTML($html){
		$doc = new DOMDocument();
		$doc->loadHTML($html);
		$html = $doc->saveHTML();
		$html = str_ireplace("&nbsp;"," ",$html);
		//as saveHTML adds <html><body> tags too
		$pure = substr($html,stripos($html,'<body>')+6,stripos($html,'</body>')-6-stripos($html,'<body'));  
		return $pure;
	}
}
	
if (!function_exists(__NAMESPACE__ . '\truncate_words')) { 	
	function truncate_words($text, $limit, $ellipsis = '...') {
		$text = strip_tags($text, '<a>');
		$words = preg_split("/[\n\r\t ]+/", $text, $limit + 1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_OFFSET_CAPTURE);
		if (count($words) > $limit) {
			end($words); //ignore last element since it contains the rest of the string
			$last_word = prev($words);
			   
			$text =  substr($text, 0, $last_word[1] + strlen($last_word[0])) . $ellipsis;
		}
		return $text;
	}
}


// include php captcha class
include_once (dirname(__FILE__).'/securimage/securimage.php');
// creating an object for phpcaptcha
$securimage = new Securimage();


$configs_are_set_blog = 1;
?>