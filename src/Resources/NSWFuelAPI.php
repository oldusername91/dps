<?php

namespace App\Resources;


class NSWFuelAPI
{

    /**
     * The access token 
     * @var string
     */
    protected $accesstoken = '';

    
    /**
     * The expiration time of the token
     * @var \DateTime
     */
    protected $tokenexpiry = '';

    
    /** 
     * Our API secret
     * @var string
     */
    protected $secret = NSWFUELAPISECRET;
    
    
    /**
     * Our API key
     * @var string
     */
    protected $key    = NSWFUELAPIKEY;


    /**
     * Where we save our token and expiry on disk.
     * @var string
     */
    protected $tokenfilepath = __DIR__ . '/APItoken';
    
    
    /**
     * Guzzle client to the NSW Fuel API
     * @var \GuzzleHttp\Client
     */
    public $API;
    
    
    /**
     * The application logger
     * @var \Monolog\Logger
     */
    public $logger;



    /**
     * __construct()
     * 
     * 
     * 
     * @param $guzzleclient $guzzleclient
     * @param \Monolog\Logger $logger  
     */
    public function __construct($guzzleclient, $logger)
    {
        /* 
         * 
         */
        
        $this->API    = $guzzleclient;
        $this->logger = $logger;


        /* 
         * Load the token date from file (if it exists).
         * Else retrieve a new one. 
         */
        if ($tokenfile = file_get_contents($this->tokenfilepath))
        {
            $tokendata = json_decode($tokenfile);

            $this->accesstoken = $tokendata->accesstoken;
            $this->tokenexpiry = new \DateTime($tokendata->tokenexpiry);
            $this->logger->info("Retrieved access token from file.");
        }
        else
        {
            $this->logger->info("No token file exists.");
            $this->RetrieveToken();
        }


    }


    /**
     * GetToken()
     * 
     * Returns us a valid API access token. 
     * Requests a new one if the current has expired.
     * 
     * @return string  -  the access token
     */
    public function GetToken()
    {
        /* 
         * 
         */
        if (new \DateTime() > $this->tokenexpiry)
        {
            $this->logger->info("Refreshing token");
            $this->RetrieveToken();
        }
        else
        {
            $this->logger->info("Existing token is still valid.");
        }
        return $this->accesstoken;
    }


    /**
     * RetrieveToken()
     *
     * Requests from the NSW Fuel API a new access token.
     * Saves it to disk.
     *
     * @return null
     */
    public function RetrieveToken()
    {
        /* 
         * 
         */
        
        $this->logger->info("Requesting new API token with authorization key " . $authv);
        $endpoint = '/oauth/client_credential/accesstoken?grant_type=client_credentials';
        $authv    = base64_encode($this->key . ':' . $this->secret);

        
        
        /* 
         * Actually request a token.
         */
        $apiresponse = $this->API->request('GET', $endpoint, ['headers' => [
                'authorization' => $authv
            ]]);
        $this->logger->info("Response code from request for API token :" . $apiresponse->getStatusCode());
        
                
        $rsp = json_decode($apiresponse->getBody()->getContents());
        $this->accesstoken = $rsp->access_token;
        $this->logger->info("New access token value of: " . $rsp->access_token . ". It expires in " . $rsp->expires_in . " seconds.");

        
        $nd = new \DateTime();
        $ad = new \DateInterval('PT' . $rsp->expires_in . 'S');
        $this->tokenexpiry = $nd->add($ad);
        $this->logger->info("Token expires at " . $this->tokenexpiry->format('d/m/Y H:i:s A'));
        

        /* 
         * Save the new access token to disk.
         */
        $filed = array(
            'accesstoken' => $this->accesstoken,
            'tokenexpiry' => $this->tokenexpiry->format('Y-m-d H:i:s')
        );

        file_put_contents($this->tokenfilepath, json_encode($filed));
    }


    function GetFuelPricesWithinRadius()
    {

    }


    function GetFuelPricesForStation()
    {

    }


    function GetNewFuelPrices()
    {

    }



    function GetAllFuelPrices()
    {

    }


    /**
     * GetFuelPricesForLocation()
     * 
     * @param string | integer $location  -  The postcode or suburb name.
     * @return \stdClass Object  -  The response from the API
     */
    function GetFuelPricesForLocation($location)
    {
        /* 
         * 
         */
        $token = 'Bearer ' . $this->accesstoken;
        $time  = date('d/m/Y h:i:s A');
        $endpoint = '/FuelPriceCheck/v1/fuel/prices/location';

        
        /* 
         * Send the request
         */
        $apiresponse = $this->API->request('POST', $endpoint, ['headers' => [
                'apikey'           => $this->key,
                'transactionid'    => '2',
                'requesttimestamp' => $time,
                'Authorization'    => $token
            ],
            'form_params' => [
                "fueltype" => "E10",
                "namedlocation" => $location
            ]
        ]);
        
        $this->logger->info("NSWFuelAPI::GetFuelPricesForLocation(): " . $apiresponse->getStatusCode() . " - with token " . $token . ", time " . $time . ".");
        
        return json_decode($apiresponse->getBody()->getContents());
    }


    function GetReferenceData()
    {

    }
}
