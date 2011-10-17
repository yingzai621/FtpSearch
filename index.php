<?php
require_once 'dbconfig.php';

function __autoload($class_name) {
	$file_name = trim(str_replace('_','/',$class_name),'/').'.class.php';
	$file_path = dirname(__FILE__). '/' . $file_name;
	if ( file_exists( $file_path ) ) {
		return require_once( $file_path );
	}
	
	return false;
}

function magic_gpc($string) {
	if(get_magic_quotes_gpc()) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = magic_gpc($val);
			}
		} else {
			$string = stripslashes($string);
		}
	}
	return $string;
}

$_GET = magic_gpc($_GET);

if(isset($_GET['wd']) and $_GET['wd']!='' and !preg_match('/^[\s|　]+$/',$_GET['wd']))//查询关键词为空或为多个半角/全角空格时返回
{
	$keyword=$_GET['wd'];
	$type=isset($_GET['tp'])? $_GET['tp']:0;
	$pagenum=isset($_GET['pn'])? $_GET['pn']:0;
	include 'result.php';
}else{
	include 'defaulttpl.html';
}