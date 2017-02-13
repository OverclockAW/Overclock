<?php 
	require_once __DIR__.'/includes/config.php';
	$app->logout();
?>
<!DOCTYPE html>
<html>

	<head>
		<title>Index</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/plantilla.css')?>"/>
	</head>

	<body>
		<?php header('Location: '.$app->resuelve('/index.php')); ?>
	</body>

</html>
