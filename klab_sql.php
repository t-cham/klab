<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
</head>
<body>

	<?php
	ini_set('display_errors', 1);
	ini_set("memory_limit","4096M");

	$dsn = 'mysql:dbname=tweetdata_europe_2016;host=localhost';
	$user = 'root';
	$password = 'root';

	try{

		$pdo = new PDO($dsn, $user, $password);

		echo "データベースに接続完了";

		//$sql="SELECT DISTINCT user_lang FROM tweetdata_Europe_2016";//とりあえず被りなし言語
		//$sql="SELECT * FROM tweetdata_Europe_2016 WHERE user_lang='en'";//英語圏だけ
		//$sql="INSERT INTO select_data SELECT lang,lat,lng,tweet_text FROM tweetdata_Europe_2016";
		
		$sql="SELECT tweet_text FROM select_data WHERE lang = 'ja'";//select_data

		$stmt=$pdo->query($sql);

		foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
			echo "<pre>";
			echo $row['tweet_text'];	
 			echo "</pre>";	
 		}

	}catch (PDOException $e){
		print('Error:'.$e->getMessage());
		die();
	}

	$pdo = null;

	?>

</body>
</html>