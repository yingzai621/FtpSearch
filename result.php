<?php
//require_once 'db.php';
//var_dump($keyword);
$wordarray=preg_split('/\s+/',$keyword);//按空格切分
$wordlist='%'.implode('%', $wordarray).'%';

if((int)$type)
{
	//$re=DB::GetQueryResult("select count(*) from folder where name like '".DB::EscapeString($wordlist)."'");
	//$queryre=DB::GetQueryResult("select id,ftpid,name,time from folder where name like '".DB::EscapeString($wordlist)."' order by hits desc limit ".$pagenum.",20",false);
	$re=DB::GetQueryResult("select count(*) from folder where match(name) against ('".DB::EscapeString($keyword)."' in boolean mode)");
	$queryre=DB::GetQueryResult("select id,ftpid,name,time from folder where match(name) against ('".DB::EscapeString($keyword)."' in boolean mode) order by hits desc limit ".$pagenum.",20",false);
	
}else{
	//$re=DB::GetQueryResult("select count(*) from file where name like '".DB::EscapeString($wordlist)."'");
	//$queryre=DB::GetQueryResult("select id,ftpid,name,type,time,size from file where name like '".DB::EscapeString($wordlist)."' order by hits desc limit ".$pagenum.",20",false);
	$re=DB::GetQueryResult("select count(*) from file where match(name) against ('".DB::EscapeString($keyword)."' in boolean mode)");
	$queryre=DB::GetQueryResult("select id,ftpid,name,type,time,size from file where match(name) against ('".DB::EscapeString($keyword)."' in boolean mode) order by hits desc limit ".$pagenum.",20",false);
	
}

//echo "select ftpid,name,time,url from folder where name like '".DB::EscapeString($wordlist)."' limit ".$pagenum.",20";

//$ftplist=DB::GetQueryResult("select * from ftp",false);
$resultcount=(int)$re['count(*)'];
$pagecount=$resultcount%20?intval($resultcount/20)+1:$resultcount/20;//总页数
$dispagecount=($pagecount>10)?10:$pagecount;//需要显示的页数，大于10则赋值为10
$currpage=$pagenum/20+1;//当前页页码

for($i = 0;$i < count($queryre); $i++)
{
	if(!array_key_exists('type',$queryre[$i]))
		$queryre[$i]['type']='文件夹';
	if(!array_key_exists('size',$queryre[$i]))
	{
		$queryre[$i]['size']='0B';
		continue;
	}
		
	$tmp=(int)$queryre[$i]['size'];
//	var_dump($tmp);
	switch ($tmp)
	{
		case ($tmp>1073741824):
			$queryre[$i]['size']=number_format((float)$tmp/1073741824,2).'GB';
			break;
		case ($tmp>1048576):
			$queryre[$i]['size']=number_format((float)$tmp/1048576,2).'MB';
			break;
		case ($tmp>1024 ):
			 $queryre[$i]['size']=number_format((float)$tmp/1024,2).'KB';
			 break;
		default:
			$queryre[$i]['size']=$tmp.'B';
	}
}

include 'result.html';
//对url进行处理，加入ftp用户名和密码，留着在download.php中备用
/*
for($i = 0;$i < count($queryre); $i++)
{
	$userpass=DB::GetQueryResult('select user,pass from ftp where id='.$queryre[$i]['ftpid']);
	$queryre[$i]['url']='ftp://'.$userpass['user'].':'.$userpass['pass'].'@'.substr($queryre[$i]['url'],6-strlen($queryre[$i]['url']));
}*/

//var_dump($userpass);
//var_dump($queryre);
//var_dump($ftplist);
//var_dump($resultcount);
