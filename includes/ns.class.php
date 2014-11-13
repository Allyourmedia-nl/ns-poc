<?php
/*
 * Author Gerhard Lubbers 
 * 21-3-2012
 * Version 1.0
 */

/*
 * NS class
 *
 * Allows the user to easily retrieve information from http://www.ns.nl.
 * Requirements: NS API username and password, get them here: https://www.ns.nl/ews-aanvraagformulier/
 */
class NS{
    
    /*
    * cUrl Class
    * @var cUrl handler
    */
    private $curl;
    
    /*
    * ns.nl API request URL's
    */
        
    // De v2 versie van get stations bevat extra info
    const getStations         = 'http://webservices.ns.nl/ns-api-stations-v2';
    //const getStations         = 'http://webservices.ns.nl/ns-api-stations';
    
    const getDepartures     = 'http://webservices.ns.nl/ns-api-avt';
    const getPrices         = 'http://webservices.ns.nl/ns-api-prijzen-v2';
    const getAdvise         = 'http://webservices.ns.nl/ns-api-treinplanner';
    const getDisturbances     = 'http://webservices.ns.nl/ns-api-storingen';
    
    /*
    * @param required class $curl -> set class curl
    */
    public function __construct( $curl ){
        
        $this->curl = $curl;
    }    
    
    
    /*
    * getStations
    * Retrieve all stations
    * @return xml element with all stations
    */
    public function getStations(){
        
        return new SimpleXMLElement( $this->curl->get( self::getStations ) );
    }
    
    /*
    * getDepartures
    * Retrieve departures for specified station
    * @param required string $station -> station short/middle/full name, or synonymous
    * @return xml element with departures from specified station
    */
    public function getDepartures( $station ){
        
        // cURL parameters
        $parameters = array(
            'station' => $station
        );
        
        // make request and return xml
        return new SimpleXMLElement( $this->curl->get( self::getDepartures, $parameters ) );
    }
    
    /*
    * getPrices
    * Retrieve prices for specified track
    * @param required string $from -> station short/middle/full name, or synonymous 
    * @param required string $to -> station short/middle/full name, or synonymous
    * @param string $via -> station short/middle/full name, or synonymous
    * @param date $dateTime -> date in ddMMyyyy
    * @return xml element with prices for track $from to $to via $via on $dateTime
    */    
    public function getPrices ($from, $to, $via = '', $dateTime = ''){
        
        // cURL parameters
        $parameters = array(
            'from'    => $from,
            'to'     => $to
        );
        
        // optional parameters
        if($via){         $parameters['via'] = $via; } 
        if($dateTime){     $parameters['dateTime'] = $dateTime; }
        
        // make request and return xml
        return new SimpleXMLElement( $this->curl->get( self::getPrices, $parameters ) );
        
    }
    
    /*
    * getAdvise
    * Retrieve travel advise for specified track
    * @param required string $fromStation -> station short/middle/full name, or synonymous 
    * @param required string $toStation -> station short/middle/full name, or synonymous
    * @param string $viaStation -> station short/middle/full name, or synonymous
    * @param int $previousAdvices -> advises in the past, not guaranteed, max 5
    * @param int $nextAdvices -> advises in the future, not guaranteed, max 5
    * @param date $dateTime -> date in ddMMyyyy
    * @param boo $departure -> if $depature is true, dateTime is departure time, else dateTime is arrival time
    * @param boo $hslAllowed -> may travel advise contain high-speed trains, default true
    * @param boo $yearCard -> if user has yearcard, some advises will be more expensive but shorter, default false
    * @return xml element with advises for track $from to $to via $via on $dateTime
    */    
    public function getAdvise ($fromStation, $toStation, $viaStation = '', $previousAdvices = '', $nextAdvices = '', $dateTime = '', $departure = '', $hslAllowed = '', $yearCard = ''){
        
        // cURL parameters
        $parameters = array(
            'fromStation'     => $fromStation,
            'toStation'     => $toStation
        );
        
        // optional parameters
        if($viaStation){         $parameters['viaStation'] =         $viaStation; } 
        if($previousAdvices){    $parameters['previousAdvices'] =     $previousAdvices; }
        if($nextAdvices){         $parameters['nextAdvices'] =         $nextAdvices; }
        if($dateTime){             $parameters['dateTime'] =             $dateTime; }
        if($departure){         $parameters['departure'] =             $departure; }
        if($hslAllowed){        $parameters['hslAllowed'] =         $hslAllowed; }
        if($yearCard){             $parameters['yearCard'] =             $yearCard; }
        
        // make request and return xml
        return new SimpleXMLElement( $this->curl->get( self::getAdvise, $parameters ) );
    }
    
    /*
    * getDisturbances
    * Retrieve Disturbances
    * @param string $station -> station short/middle/full name, or synonymous 
    * @param boo $actual -> $actual is true will return actual disturbances, plannend and unplanned 
    * @param boo $unplanned -> $unplanned is true will return planned disturbances
    * @return xml element with disturbances for specified station
    */    
    public function getDisturbances($station = '', $actual = '', $unplanned = ''){
        
        // optional parameters 
        $parameters = array();
        if($station){     $parameters['station'] =     $station; }
        if($actual){     $parameters['actual'] =     $actual; }
        if($unplanned){ $parameters['unplanned'] =     $unplanned; }
        
        // make request and return xml
        return new SimpleXMLElement( $this->curl->get( self::getDisturbances, $parameters ) );
    }
}

/*
* user class
*
* NS API username and password controller
* @param required string $username, NS API username
* @param required string $password, NS API password
*/ 
class user{
    
    /*
    * @var $username
    * NS API username
    */
    private $username;
        
    /*
    * @var $password
    * NS API password
    */    
    private $password;
    
    /*
    * set $username and $passowrd
    */
    public function __construct($username, $password){
        
        $this->username = $username;
        $this->password = $password;
    }
    
    /*
    * getUsername
    * @return NS API username
    */    
    public function getUsername(){
    
        return $this->username;
    }    
    
    /*
    * getPassword
    * @return NS API password
    */    
    public function getPassword(){
    
        return $this->password;
    }
}

/*
* cURL class
*
* Sends cURL request to the NS API and returns cURL response
* @param required $user, user class
*/ 
class curl{
    
    /*
    * @var $user
    * User class
    */
    private $user;
    
    /*
    * set user
    */
    public function __construct( $user ){
    
        $this->user = $user;
    }
    
    /*
    * get
    * Sends get request
    * @param required string $url -> request url
    * @param array $parameters -> paramaters for the request
    */
    public function get( $url, $parameters = array() ){
        
        // if there are parameters, grab them
        if(!empty($parameters)){
            $url .= '?' . http_build_query($parameters);
        }
        
        // set up cURL requets
        $ch = curl_init( $url );
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // grab username and password
        curl_setopt($ch, CURLOPT_USERPWD,  $this->user->getUsername().":". $this->user->getPassword());
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // send the requets
        $xml = curl_exec($ch);
        // close cURL
        curl_close($ch);
        // return the cURL response
        return $xml;
    }
}
?>