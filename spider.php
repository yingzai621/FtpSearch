<?php
set_time_limit(0);
$ftp_server='ftp.cup.edu.cn';
$ftp_username='anonymous';
$ftp_password='';

while(!$conn=ftp_connect($ftp_server))
{
	sleep(3);
	echo '3<br>';
}

var_dump($conn);

ftp_login($conn,$ftp_username,$ftp_password);

//echo ftp_systype($conn);

//$filelist=ftp_rawlist($conn,mb_convert_encoding('./', 'GBK','UTF-8,ASCII'));
//var_dump($filelist);
ftpTraversal($conn,'.');

ftp_close($conn);
echo '结束！';

function ftpTraversal($ftpconn,$dir)
{
/*		global $ftp_server,$ftp_username,$ftp_password;
a:
		ftp_close($ftpconn);
		$ftpconn=ftp_connect($ftp_server);
		while(!ftp_login($ftpconn,$ftp_username,$ftp_password))
		{
			sleep(3);
			goto a;
		}
*/
		//echo FTP_MOREDATA;
		$nlist=ftp_nlist($ftpconn,$dir);//包含完整路径
		$rawlist=ftp_rawlist($ftpconn,$dir);//只包含文件夹或文件名
		echo 'nlist:'.count($nlist).'<br>';
		echo 'rawlist:'.count($rawlist).'<br>';
		for($i=0;$i<count($rawlist);$i++)
		{
			if('d'==substr($rawlist[$i],0,1)){
				//$fullpath=$dir.'/'.substr($rawlist[$i],55);
				//echo $fullpath;
				echo '目录：'.mb_convert_encoding($nlist[$i], 'UTF-8','GBK,ASCII').'<br>';
				ftpTraversal($ftpconn,$nlist[$i]);
			}else{
				echo mb_convert_encoding($nlist[$i], 'UTF-8','GBK,ASCII').'<br>';
				echo 'size:'.mb_convert_encoding(substr($n_list[$i],28,14), 'UTF-8','GBK,ASCII').'<br>';
				echo 'time:'.mb_convert_encoding(substr($n_list[$i],42,14), 'UTF-8','GBK,ASCII').'<br>';
				echo 'name:'.mb_convert_encoding(substr($n_list[$i],55), 'UTF-8','GBK,ASCII').'<br>';
			}
				
		}
}