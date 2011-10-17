<?php
set_time_limit(0);
$ftp_server='ftp.cup.edu.cn';
$ftp_username='anonymous';
$ftp_password='';

while(!$conn=ftp_connect($ftp_server))
{
	sleep(3);
	echo "3\n";
}

//var_dump($conn);

ftp_login($conn,$ftp_username,$ftp_password);

//echo ftp_systype($conn);

//$filelist=ftp_rawlist($conn,mb_convert_encoding('./', 'GBK','UTF-8,ASCII'));
//var_dump($filelist);
ftpTraversal($conn);

ftp_close($conn);
echo '结束！';



function ftpTraversal($ftpconn)
{
		
		//echo FTP_MOREDATA;
		$dirArry=array('.');//目录堆栈
		
		/*$table = new Table('file', array(
					'name' => $name,
					'type' => $type,
					'time' => $time,
					'path'=>$path,
					'ftpid'=>$path	
					));*/
		
		while($currdir=array_pop($dirArry))
		{
			$nlist=ftp_nlist($ftpconn,$currdir);//包含完整路径
			$rawlist=ftp_rawlist($ftpconn,$currdir);//只包含文件夹或文件名

			//var_dump($rawlist);
			for($i=0;$i<count($rawlist);$i++)
			{
				if(!isset($nlist[$i]))
					continue;
				if('d'==substr($rawlist[$i],0,1)){
							//$fullpath=$dir.'/'.substr($rawlist[$i],55);
							//echo $fullpath;
						echo '目录:'.mb_convert_encoding($nlist[$i], 'UTF-8','GBK,ASCII')."<br>";
						array_push($dirArry,$nlist[$i]);
					}else{
						echo mb_convert_encoding($nlist[$i], 'UTF-8','GBK,ASCII')."<br>";
						echo 'size:'.mb_convert_encoding(substr($rawlist[$i],28,14), 'UTF-8','GBK,ASCII')."<br>";
						echo 'time:'.mb_convert_encoding(substr($rawlist[$i],42,14), 'UTF-8','GBK,ASCII')."<br>";
						echo 'name:'.mb_convert_encoding(substr($rawlist[$i],55), 'UTF-8','GBK,ASCII')."<br>";
						//$table->Insert(array());
					}
			}
		}

}