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

	 //アプリケーションIDのセット
	$id = "dj0zaiZpPUwwcFdlbHl2YWh4VCZzPWNvbnN1bWVyc2VjcmV0Jng9NTE-";

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
			/*echo "<pre>";
			echo $row['tweet_text'];	
			echo "</pre>";*/

			 //形態素解析したい文章
			$word = $row['tweet_text'];
    		//URLの組み立て
			$url = "http://jlp.yahooapis.jp/MAService/V1/parse?appid=" . $id . "&results=ma&filter=9&sentence=" . urlencode($word);
    		//戻り値をパース
			$xml = simplexml_load_file($url);

			foreach($xml->ma_result->word_list->word as $value){
				$data .= $value->surface."　";
				
			}
		}

		//ここから出現率

		$url = "http://jlp.yahooapis.jp/MAService/V1/parse?appid=" . $id . "&results=uniq&uniq_filter=9&sentence=" . urlencode($data);
    		//戻り値をパース
		$xml = simplexml_load_file($url);

		foreach ($xml->uniq_result->word_list as $cur){
			for($i=0;$i<10000;$i++){
				$tango[$i] = $cur->word[$i]->surface;
				$count[$i] = $cur->word[$i]->count;

				echo $count[$i];
			}
		}

	}catch (PDOException $e){
		print('Error:'.$e->getMessage());
		die();
	}

	$pdo = null;

	?>

</body>
</html>