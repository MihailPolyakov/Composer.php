<!DOCTYPE html>
<html>
<head>
	<title>Composer</title>
	<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript">
    </script>


</head>
<body>

<form action='' method="GET">
	<input type="text" name="adress" placeholder="Введите адрес">
	<input type="submit" name="Поиск">
</form>

<?php
if (!empty($_GET)) {

	foreach ($_GET as  $value) {
		$adress = $value;
		break;
	};

	require __DIR__ . '/vendor/autoload.php';
	$api = new \Yandex\Geo\Api();
	$api->setQuery($adress);

	// Настройка фильтров
	$api
	    ->setLang(\Yandex\Geo\Api::LANG_RU) // локаль ответа
	    ->load();

	$response = $api->getResponse();
	$response->getFoundCount(); // кол-во найденных адресов
	$response->getQuery(); // исходный запрос
	$response->getLatitude(); // широта для исходного запроса
	$response->getLongitude(); // долгота для исходного запроса
	$i=-1;
	// Список найденных точек
	$collection = $response->getList();
	foreach ($collection as $item) {
		++$i; ?>
	    <p><a href="location.php?<?php echo $_SERVER['QUERY_STRING']?>&num=<?php echo $i;?>"> 
	    <?php echo $item->getLatitude(); // широта ?>;
	    <?php echo $item->getLongitude(); // долгота?> 
		</a></p> 
  <?php $item->getData(); // необработанные данные
	    $latitude[] = $item->getLatitude();
	    $longitude[] =  $item->getLongitude();
	}?>

	<div id="map" style="margin-top:50px; width: auto; height: 400px"></div>

	<script type="text/javascript">
    // Функция ymaps.ready() будет вызвана, когда
    // загрузятся все компоненты API, а также когда будет готово DOM-дерево.
    ymaps.ready(init);
    <?php if (empty($_GET['num'])) {?>
	    	function init(){ 
	            var myMap = new ymaps.Map("map", {
	                center: [<?php echo $latitude[0];?>, <?php echo $longitude[0];?>],
	                zoom: 7
	            }); 
	            
	            var myPlacemark = new ymaps.Placemark([<?php echo $latitude[0];?>, <?php echo $longitude[0];?>], {
	                hintContent: 'Содержимое всплывающей подсказки',
	                balloonContent: 'Содержимое балуна'
	            });
	            
	            myMap.geoObjects.add(myPlacemark);
	    	}
    <?php } else {
    	$num=$_GET['num'];?>
			function init(){ 
	            var myMap = new ymaps.Map("map", {
	                center: [<?php echo $latitude[$num];?>, <?php echo $longitude[$num];?>],
	                zoom: 7
	            }); 
	            
	            var myPlacemark = new ymaps.Placemark([<?php echo $latitude[$num];?>, <?php echo $longitude[$num];?>], {
	                hintContent: 'Содержимое всплывающей подсказки',
	                balloonContent: 'Содержимое балуна'
	            });
	            
	            myMap.geoObjects.add(myPlacemark);
	    	}    	
    <?php }?>
    
	</script>
<?php }
	
?>
	

</body>
</html>

