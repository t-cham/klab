<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
</head>
<body>

	<?php

    //アプリケーションIDのセット
	$id = "dj0zaiZpPUwwcFdlbHl2YWh4VCZzPWNvbnN1bWVyc2VjcmV0Jng9NTE-";
    //形態素解析したい文章
	$word = "ここに形態素解析したい文章";
    //URLの組み立て
	$url = "http://jlp.yahooapis.jp/MAService/V1/parse?appid=" . $id . "&results=ma&sentence=" . urlencode($word);
    //戻り値をパースする
   	$parse = simplexml_load_file($url);
    //戻り値（オブジェクト）からループでデータを取得する
	foreach($parse->ma_result->word_list->word as $value){
    //品詞を「／」で区切る
		echo $value->surface;
		echo "／";
	}

	?>

</body>
</html>