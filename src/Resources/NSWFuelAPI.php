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
     * Where we save our token and expiry on disk.
     * @var string
     */
    protected $referencefilepath = __DIR__ . '/referencedata';

    
    /**
     * The fuel API reference data.
     * @var array
     */
    protected $referencedata;
    
    
    /**
     * UTC / GMT timestamp for when we requested 
     * the reference data last.
     * @var string
     */
    protected $lastreferencerequesttime = '01/01/2000 00:00:00 AM';
    
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
        
        
        /* 
         * Load our reference data from file (if it exists).
         */
        if ($refdata = file_get_contents($this->referencefilepath))
        {
            $this->logger->info("Reference exists on disk. Reading in.");
            
            $rdata = json_decode($refdata);
            $this->referencedata = $rdata->referencedata;
            $this->lastreferencerequesttime = $rdata->lastreferencerequesttime;
        }

        $this->RetrieveReferenceData();

    }

    
    /**
     * RetrieveReferenceData()
     *
     * Requests new reference data. Updates reference
     * data file if required.
     * 
     *
     * @return array  -  the reference data.
     */
    public function RetrieveReferenceData()
    {
        /*
         *
         */
        $this->logger->info("Checking if we need new reference data. Last time the request was sent: " . $this->lastreferencerequesttime);        
        $timerequested = gmdate('d/m/Y h:i:s A');
        /*
         * Send the request
         */
        $apiresponse = $this->API->request('GET', '/FuelCheckRefData/v1/fuel/lovs', [
            'headers' => [
                'apikey'            => $this->key,
                'transactionid'     => '5',
                'requesttimestamp'  => $timerequested,
                'if-modified-since' => $this->lastreferencerequesttime,
                'Authorization'     => 'Bearer ' . $this->getToken(),
                'Content-Type'      => 'application/json; charset=utf-8'
        ],

        ]);
        
        
        $this->logger->info("Response code from request for reference data:" . $apiresponse->getStatusCode());
        $responsedata = json_decode($apiresponse->getBody()->getContents()); 
        
        
        if ( $this->AnyNewReferenceData($responsedata))
        {
            $this->logger->info("Reference data requires updating.");
            
            
            /*
             * Save the reference data to disk.
             * @TODO Change this to add items to the file on disk if required.
             */
            $filed = array(
                'referencedata'            => $responsedata,
                'lastreferencerequesttime' => $timerequested
            );
            
            file_put_contents($this->referencefilepath, json_encode($filed));
            
            $this->referencedata = $responsedata;
            $this->lastreferencerequesttime = $timerequested;
        }
        else
        {
            $this->logger->info("Reference data is up to date.");
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
         * Send the request
         */
        $apiresponse = $this->API->request('POST', '/FuelPriceCheck/v1/fuel/prices/location', [
            'headers' => [
                'apikey'           => $this->key,
                'transactionid'    => '2',
                'requesttimestamp' => gmdate('d/m/Y h:i:s A'),
                'Authorization'    => 'Bearer ' . $this->getToken()
            ],
            'form_params' => [
                "fueltype" => "E10",
                "namedlocation" => $location
            ]
        ]);
        
        $this->logger->info("NSWFuelAPI::GetFuelPricesForLocation(): " . $apiresponse->getStatusCode() . " - with token " . $token . ", time " . $time . ".");
        
        return $apiresponse->getBody()->getContents();
    }
    
    
    /**
     * AnyNewReferenceData
     * 
     * Tells us if any reference data is new.
     * 
     * @param $data - The reference data.
     */
    public function AnyNewReferenceData($data)
    {
        /* 
         * A bunch of properties, each have an 'items'
         * property that is an array. If any have values
         * in them, then we have new data.
         */
        $pprops = get_object_vars($data);
        
        foreach ($pprops as $p)
        {
            if (count($p->items) > 0)
            {
                return true;
            }
        }
        
    }
}
