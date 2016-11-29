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


	$dsn = 'mysql:dbname=tweetdata_europe_2016;host=localhost;charset=utf8mb4';
	$user = 'root';
	$password = 'root';

	$mecab = new MeCab_Tagger();

	try{

		$pdo = new PDO($dsn, $user, $password);

		//echo "データベースに接続完了";

		//$sql="SELECT DISTINCT user_lang FROM tweetdata_Europe_2016";//とりあえず被りなし言語
		//$sql="SELECT * FROM tweetdata_Europe_2016 WHERE user_lang='en'";//英語圏だけ
		//$sql="INSERT INTO select_data_ja SELECT id,lang,lat,lng,tweet_text FROM tweetdata_Europe_2016 WHERE lang = 'ja';
		
		$sql="SELECT id,tweet_text FROM select_data_ja WHERE lang = 'ja'";//select_data

		$stmt=$pdo->query($sql);


		//$sql="INSERT INTO `tfidf_ja` (`master_id`, `tweet_text` ,`analyze`) VALUES ( ? , ? , ? )";
		//$stmt = $pdo->prepare($sql);

		foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){

			//$master_id = $row['id'];
			$tweet_text = $row['tweet_text'];

			$str = mb_convert_kana($tweet_text,'rn');

			$str = str_replace("#","",$str);
			$str = str_replace("@","",$str);
			$str = str_replace("`","",$str);
			$str = str_replace("'","",$str);
			$str = str_replace(",","",$str);
			$str = str_replace(".","",$str);
			$str = str_replace("[","",$str);
			$str = str_replace("]","",$str);
			$str = str_replace("(","",$str);
			$str = str_replace(")","",$str);
			$str = str_replace(":","",$str);
			$str = str_replace(";","",$str);
			$str = str_replace("/","",$str);
			$str = str_replace('"',"",$str);
			$str = str_replace("!","",$str);
			$str = str_replace("?","",$str);
			$str = str_replace("-","",$str);
			$str = str_replace("~","",$str);
			$str = str_replace("^","",$str);
			$str = str_replace("_","",$str);
			$str = str_replace("&","",$str);
			$str = str_replace("=","",$str);
			$str = str_replace("|","",$str);
			$str = str_replace("¥","",$str);
			$str = str_replace("＃","",$str);
			$str = str_replace("ー","",$str);
            $str = str_replace("⋯","",$str);
            $str = str_replace("❤","",$str);
            $str = str_replace("_","",$str);
            $str = str_replace("^","",$str);
            $str = str_replace("%","",$str);
            $str = str_replace("o","",$str);
            $str = str_replace("○","",$str);
            $str = str_replace("°","",$str);
            $str = str_replace("♪","",$str);

			$delete_noise_text = strstr($str,'http',true);//http以下を 

			$node = $mecab->parseToNode($delete_noise_text);
			
			while($node = $node->getNext()){
               if(strpos($node->getFeature(),'名詞') !== false){
                   echo $node->getSurface()."<br>";
                   echo $node->getFeature()."<br>";

                   $analyze_result=$node->getSurface().",".$node->getFeature();

                   $sql = "INSERT INTO tfidf_ja (master_id, tweet_text, analyze_result) VALUES (:master_id, :tweet_text, :analyze_result)";
                   $stmt = $pdo->prepare($sql);
                   $stmt->bindParam(':master_id', $row['id'], PDO::PARAM_INT);
                   $stmt->bindParam(':tweet_text', $row['tweet_text'], PDO::PARAM_STR);
                   $stmt->bindParam(':analyze_result', $analyze_result, PDO::PARAM_STR);
                   $stmt->execute();
                  
				if ($stmt){
					print('データの追加に成功しました<br>');
				}else{
					print('データの追加に失敗しました<br>');
				}

               }            
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