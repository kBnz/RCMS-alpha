<?php
if(!defined("ROOT_DIR")){
    //基本ファイルのインクルード
    require_once("default.php");
}
if(!defined("USE_ClickHeat") || USE_ClickHeat == 0){
    exit;
}

make_dir(ROOT_DIR."/html/files/clickheat");
make_dir(ROOT_DIR."/html/files/clickheat/logs");
make_dir(ROOT_DIR."/html/files/clickheat/cache");

/**
 * ClickHeat : Fichier principal / Main file
 *
 * @author Yvan Taviaud - LabsMedia - www.labsmedia.com
 * @since 27/10/2006
**/

/* Remove the last space on this line (between * and /) to enable debugging. Don't forget to disable this when you're done! */
//error_reporting(E_ALL);
//restore_error_handler();
//ini_set('display_errors', 1);

/* Languages */
$__languages = array('bg', 'cz', 'de', 'en', 'es', 'fr', 'hu', 'id', 'it', 'ja', 'nl', 'pl', 'pt', 'ro', 'ru', 'sr', 'tr', 'uk', 'zh');

$__action = isset($_GET['action']) && $_GET['action'] !== '' ? $_GET['action'] : 'view';

if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] !== '')
{
	$realPath = &$_SERVER['REQUEST_URI'];
}
elseif (isset($_SERVER['SCRIPT_NAME']) && $_SERVER['SCRIPT_NAME'] !== '')
{
	$realPath = &$_SERVER['SCRIPT_NAME'];
}
else
{
	exit(LANG_UNKNOWN_DIR);
}
if (substr($realPath, -1) === '/')
{
	header('Location: '.$realPath.'index.php');
	exit;
}

$dirName = dirname($realPath);
if ($dirName === '/')
{
	$dirName = '';
}
define('CLICKHEAT_PATH', '/tools/clickheat/');
define('CLICKHEAT_INDEX_PATH', 'index.php?');
define('CLICKHEAT_ROOT', ROOT_DIR.'/html/tools/clickheat/');
define('CLICKHEAT_CONFIG', CLICKHEAT_ROOT.'config/config.php');
define('IS_PIWIK_MODULE', false);

/* Improve buffer usage and compression */
if (function_exists('ob_start'))
{
	/* Check that Zlib is not enabled by default (value should be 1-9, else it will be an empty string) */
	if (@ini_get('zlib.output_compression') || !function_exists('ob_gzhandler'))
	{
		ob_start();
	}
	else
	{
		ob_start('ob_gzhandler');
	}
}

/* Loading language according to browser's Accept-Language or cookie «language» */
if (isset($_GET['language']))
{
	$lang = $_GET['language'];
}
elseif (isset($_COOKIE['language']))
{
	$lang = $_COOKIE['language'];
}
elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
{
	$lang = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
}
if (!isset($lang) || !in_array($lang, $__languages))
{
	$lang = 'en';
}
if (!isset($_COOKIE['language']) || $_COOKIE['language'] !== $lang)
{
	setcookie('language', $lang, time() + 365 * 86400, '/');
}
define('CLICKHEAT_LANGUAGE', $lang);
unset($lang);
include CLICKHEAT_ROOT.'languages/'.CLICKHEAT_LANGUAGE.'.php';

//ログインしているかどうか
if(!$_SESSION["member_id"]){
    header("location:".ROOT_SSL_URL."/management/login/login/?err=page_auth&Retrun_URL=".urlencode("/tools/clickheat/?"));
    exit;
}

//権限をチェック
$user = RCMSUser::getUser();
$auth = $user->getAuth($cn);
if(!$auth["accesscount"]["select"]){
    header("location:".ROOT_SSL_URL."/management/");
    exit;
}

require_once(CLICKHEAT_CONFIG);

if (!defined('CLICKHEAT_ADMIN')){
	define('CLICKHEAT_ADMIN', false);
}

if($__action == ""){
    $__action = 'view';
}

/* Specific definitions */
$clickheatConf['__screenSizes'] = array(0 /* Must start with 0 */, 240, 640, 800, 1024, 1152, 1280, 1440, 1600, 1800);
$clickheatConf['__browsersList'] = array('all' => '', 'msie' => 'Internet Explorer', 'firefox' => 'Firefox', 'chrome' => 'Chrome', 'safari' => 'Safari', 'opera' => 'Opera', 'unknown' => '');

switch ($__action)
{
	case 'config':
		{
			if (file_exists(CLICKHEAT_CONFIG) && CLICKHEAT_ADMIN !== true)
			{
				exit('Error');
			}
			/* No break here */
		}
	case 'check':
	case 'view':
	case 'login':
		{
			header('Content-Type: text/html; charset=utf-8');
			include CLICKHEAT_ROOT.'header.php';
			include CLICKHEAT_ROOT.$__action.'.php';
			include CLICKHEAT_ROOT.'footer.php';
			break;
		}
	case 'generate':
	case 'layout':
	case 'javascript':
	case 'latest':
	case 'cleaner':
		{
			header('Content-Type: text/html; charset=utf-8');
			include CLICKHEAT_ROOT.$__action.'.php';
			break;
		}
	case 'iframe':
		{
			$group = isset($_GET['group']) ? str_replace('/', '', $_GET['group']) : '';
			if (is_dir($clickheatConf['logPath'].$group))
			{
				$webPage = array('/');
				if (file_exists($clickheatConf['logPath'].$group.'/url.txt'))
				{
					$f = fopen($clickheatConf['logPath'].$group.'/url.txt', 'r');
					if ($f !== false)
					{
						$webPage = explode('>', trim(fgets($f, 1024)));
						fclose($f);
					}
				}
				
				//RCMS対応
				if(strpos($webPage[0],"?")>0){
					echo $webPage[0]."&clickheat=1";
				}else{
					echo $webPage[0]."?clickheat=1";
				}
			}
			break;
		}
	case 'png':
		{
			$imagePath = $clickheatConf['cachePath'].(isset($_GET['file']) ? str_replace('/', '', $_GET['file']) : '**unknown**');

			header('Content-Type: image/png');
			if (file_exists($imagePath))
			{
				readfile($imagePath);
			}
			else
			{
				readfile(CLICKHEAT_ROOT.'images/warning.png');
			}
			break;
		}
	case 'layoutupdate':
		{
			if (CLICKHEAT_ADMIN !== true)
			{
				exit('Error');
			}
			$group = isset($_GET['group']) ? str_replace('/', '', $_GET['group']) : '';
			$url = isset($_GET['url']) ? $_GET['url'] : '';
			if (strpos($url, 'http') !== 0)
			{
				$url = 'http://'.$_SERVER['SERVER_NAME'].'/'.ltrim($url, '/');
			}
			/* Improved security for PHP injection (PMV2.3b3 bug) */
			$url = parse_url(str_replace(array('<', '>'), array('', ''), $url));
			$left = isset($_GET['left']) ? (int) $_GET['left'] : 0;
			$center = isset($_GET['center']) ? (int) $_GET['center'] : 0;
			$right = isset($_GET['right']) ? (int) $_GET['right'] : 0;

			if (!is_dir($clickheatConf['logPath'].$group) || !isset($url['host']) || !isset($url['path']))
			{
				exit('Error');
			}

			if ($url['scheme'] !== 'http' && $url['scheme'] !== 'https')
			{
				$url['scheme'] = 'http';
			}
			if (isset($url['query']))
			{
				$url = $url['scheme'].'://'.$url['host'].$url['path'].'?'.$url['query'];
			}
			else
			{
				$url = $url['scheme'].'://'.$url['host'].$url['path'];
			}
			$f = fopen($clickheatConf['logPath'].$group.'/url.txt', 'w');
			fputs($f, $url.'>'.$left.'>'.$center.'>'.$right);
			fclose($f);

			echo 'OK';
			break;
		}
	case 'logout':
		{
			setcookie('clickheat', '', time() - 30 * 86400, '/');
			/* IIS removes cookies when sending a 301/302 header, so we need to do some crap (and yes, this HTML code is crap too) */
			if (strpos($_SERVER['SERVER_SOFTWARE'], 'IIS'))
			{
				echo '<meta http-equiv="refresh" content="0;'.ROOT_SSL_URL.'/management/login/logout/" />';
				echo '<a href="'.ROOT_SSL_URL.'/management/login/logout/">click here</a>';
			}
			else
			{
				//header('Location: index.php');
				header("location:".ROOT_SSL_URL."/management/login/logout/");
			}
			exit;
			break;
		}
	default:
		{
			header('HTTP/1.0 404 Not Found');
			exit('Error, page not found');
			break;
		}
}
?>
