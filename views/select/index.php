<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Select</title>
</head>
<body>
	<?php 
		foreach($res as $key => $item){
			if($key>70 && $key<73){
				$result[$item['product_id']] = $item['description'];
			}
		} 
		echo '<pre>'; print_r($result);die;
	?>	
</body>
</html>