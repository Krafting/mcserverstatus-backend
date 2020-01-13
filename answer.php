<?php
	$origin = $_SERVER['HTTP_ORIGIN'];
	header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN'], true);
	use xPaw\MinecraftPing;
	use xPaw\MinecraftPingException;
	if(empty($_GET['port'])) {
		$port = "25565";
	} else {
		$port = htmlspecialchars($_GET['port']);
	}
	define( 'MQ_SERVER_ADDR', htmlspecialchars($_GET['ip']) );
	define( 'MQ_SERVER_PORT', $port);
	define( 'MQ_TIMEOUT', 1 );
	require __DIR__ . '/src/MinecraftPing.php';
	require __DIR__ . '/src/MinecraftPingException.php';
	$Info = false;
	$Query = null;
	try {
		$Query = new MinecraftPing( MQ_SERVER_ADDR, MQ_SERVER_PORT, MQ_TIMEOUT );
		$Info = $Query->Query( );

		if( $Info === false ) {
			$Query->Close( );
			$Query->Connect( );
			$Info = $Query->QueryOldPre17();
		}
		$online = "true";
	}
	catch( MinecraftPingException $e ) {
		$Exception = $e;
		$online = "false";
	}
	if( $Query !== null ) {
		$Query->Close( );
	}
$dataSRV = array();
if($online == "false"): 
	array_push($dataSRV, "false");
	array_push($dataSRV, "0");
	array_push($dataSRV, "0");
	echo json_encode($dataSRV);
 else: 
if( $Info !== false ):
	$dataToAdd = $Info['players']; 
	array_push($dataSRV, "true");
	array_push($dataSRV, $dataToAdd['max']);
	array_push($dataSRV, $dataToAdd['online']);
	// print_r($dataSRV);
	echo json_encode($dataSRV);
 else: 
 endif; 
 endif; ?>
