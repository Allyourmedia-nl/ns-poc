<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
require_once('includes/ns.class.php');
$ns = new NS( new curl( new user( 'HJ@allyourmedia.nl', 'UQ3d07vfmfLr9Z0Y7koCtrLWvwLQuBgBS_V01rK0ONQ0HPVN6EWNBw' ) ) );
//vertrektijden
$vertrektijden = $ns->getDepartures('Arnhem');

//prijzen
//$prijzen = $ns->getPrices('Delfzijl', 'Appingedam', '',date("dmY"));

//reisadvies
$advies = $ns->getAdvise('Delfzijl', 'Groningen', 'Appingedam', date("dmY"));

//storingen
$storingen = $ns->getDisturbances('Arnhem');
//echo json_encode($stations);
echo json_encode($vertrektijden);
?>