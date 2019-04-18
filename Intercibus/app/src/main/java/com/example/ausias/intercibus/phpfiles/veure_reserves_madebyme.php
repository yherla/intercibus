<?php

$response = array();

$id = $_POST['username'];

require_once __DIR__ . '/db_config.php'; 
 
// Connectar a la BBDD MYSQL
$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD,DB_DATABASE) or die(mysqli_error());

// Recollim reserves de l'usuari
$auxreserves = mysqli_query($con, "SELECT * FROM reserva where usuari_id = '".$id."'");

// variable per comptar les reserves
$i = 0;

while ($rowreserves = mysqli_fetch_assoc($auxreserves)) {
	$consultaesp = mysqli_query($con, "SELECT * FROM espai WHERE id = ".$rowreserves['espai_id']);
	if ($filaesp = mysqli_fetch_assoc($consultaesp)) {
		$response["nom".$i] = $filaesp['nom'];
		$response["reserva".$i] = $rowreserves['id'];
		$response["preu".$i] = $rowreserves['preu_total'];
		$response["entrada".$i] = $rowreserves['data_entrada'];
		$response["sortida".$i] = $rowreserves['data_sortida'];
		$idf = $filaesp['id']; 
		
		$consfoto = mysqli_query($con, "SELECT foto FROM fotos_espai WHERE espai_id = $idf");
		if ($fila = mysqli_fetch_assoc($consfoto)){
			$response["foto".$i] = $fila['foto'];
		}
		
		$i = $i + 1;
	}
		

	
	
}


if($auxreserves){
	$response["num_reserves"]= $i;

	$response["success"] = 1;
	
	echo json_encode($response);
		
}
else {
	$response["success"] = 0;
	$response["message"] = "No s'han pogut carregar les reserves.";
	
	echo json_encode($response);
}
mysqli_close($con);

?>
