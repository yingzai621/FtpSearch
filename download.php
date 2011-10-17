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


$type=(int)$_GET['tp'];
$queryre=array();
if($type)
{
	DB::Query("update folder set hits=hits+1 where id=".DB::EscapeString($_GET['fnum']));
	$queryre=DB::GetQueryResult("select url from folder where id=".DB::EscapeString($_GET['fnum']));
}
else{
	DB::Query("update file set hits=hits+1 where id=".$_GET['fnum']);
	$queryre=DB::GetQueryResult("select url from file where id=".DB::EscapeString($_GET['fnum']));
}
$userpass=DB::GetQueryResult('select user,pass from ftp where id='.DB::EscapeString($_GET['ftpid']));
$realurl='ftp://'.$userpass['user'].':'.$userpass['pass'].'@'.substr($queryre['url'],6-strlen($queryre['url']));

$realurl=mb_convert_encoding($realurl, 'GBK','UTF-8');

header("Location:".$realurl);

exit;