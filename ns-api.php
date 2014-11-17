<?php
	ini_set('display_errors',1);
	error_reporting(E_ALL);
	require_once('includes/ns.class.php');

	$ns = new NS( new curl( new user( 'HJ@allyourmedia.nl', 'UQ3d07vfmfLr9Z0Y7koCtrLWvwLQuBgBS_V01rK0ONQ0HPVN6EWNBw' ) ) );

	switch ($_GET['action']) {
		case "vertrektijden";
			$oReturn = $ns->getDepartures('Arnhem');
			$oReturn->sRequestTitle = "Alle vertrektijden vanuit Arnhem";
			break;

		case "storingen";
			$oReturn = $ns->getDisturbances('Arnhem');
			$oReturn->sRequestTitle = "Alle vertrektijden vanuit Arnhem";
			break;

		case "prijzen";
			$oReturn = $ns->getPrices('Delfzijl', 'Appingedam', '',date("dmY"));
			$oReturn->sRequestTitle = "Prijzen voor treinrit Delfzijl / Appingedam";
			break;

		case "stations";
			$oReturn = $ns->getDepartures('Arnhem');
			$oReturn->sRequestTitle = "Alle vertrektijden vanuit Arnhem";
			break;

		case "reisadvies";
			$oReturn = $ns->getAdvise('Delfzijl', 'Groningen', 'Appingedam', date("dmY"));
			$oReturn->sRequestTitle = "Reisadvies Delfzijl / Groningen op " . date("dmY");
			break;

		default:
			die("Invalid request format");
	}

	if (is_object($oReturn) {
		$oReturn->sRequestType = $_GET['action'];
	} else {
		die('NS API fail');
	}

	echo json_encode($oReturn);
?>