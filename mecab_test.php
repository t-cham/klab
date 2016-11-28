<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
</head>
<body>
	
    <?php

    $mecab= shell_exec("echo 'Rからmecabを使って形態素解析を行ってみたいと思う' | mecab");

    echo $mecab;
    ?>


</body>
</html>