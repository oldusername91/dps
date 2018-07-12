<?php

namespace App\Resources;


class NSWFuelAPI
{

    // The token value
    protected $accesstoken = '';

    // The time our token expires.
    protected $tokenexpiry = '';


    // Our API credentials.
    protected $secret = NSWFUELAPISECRET;
    protected $key    = NSWFUELAPIKEY;

    // The guzzle HTTP client setup
    // to use the NSW fuel API.
    public $API;

    // Our logger
    public $logger;



    public function __construct($guzzleforNSWAPI, $logger)
    {
        $this->API    = $guzzleforNSWAPI;
        $this->logger = $logger;

        // We need to start storing the token
        // in a file.
    }


    public function GetToken()
    {
        // If our current token is not set,
        // or has expired, get a new one!

        //if (new \DateTime() < $this->tokenexpiry)
        if (1)
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


    public function RetrieveToken()
    {
        // Using guzzle, lets make a request to get
        // an access token.
        $endpoint = '/oauth/client_credential/accesstoken?grant_type=client_credentials';
        $authv = base64_encode($this->key . ':' . $this->secret);


        $this->logger->info("Requesting new API token with authorization key " . $authv);
        $apiresponse = $this->API->request('GET', $endpoint, ['headers' => [
                'authorization' => $authv
            ]]);
        $this->logger->info("Response code from request for API token :" . $apiresponse->getStatusCode());

        $rsp = json_decode($apiresponse->getBody()->getContents());

        $this->accesstoken = $rsp->access_token;
        $this->logger->info("New access token value of: " . $rsp->access_token . ". It expires in " . $rsp->expires_in . " seconds.");


        $seconds = $rsp->expires_in;
        // Subtract a few seconds off the expiry, since I'd rather request a
        // a new one 15 seconds before it expires than actually wait till it does.
        $seconds -= 15;


        $nd = new \DateTime();
        $ad = new \DateInterval('PT' . $seconds . 'S');
        $this->tokenexpiry = $nd->add($ad);
        $this->logger->info("Token expires at " . $this->tokenexpiry->format('d/m/Y H:i:s A'));
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
     * @param string || integer $location - The postcode or suburb name
     */
    function GetFuelPricesForLocation($location)
    {
        $token = 'Bearer ' . $this->getToken();
        $time  = date('d/m/Y h:i:s A');
        $endpoint = '/FuelPriceCheck/v1/fuel/prices/location';

        $this->logger->info("NSWFuelAPI::GetFuelPricesForLocation() - with token " . $token . ', time ' . $time);

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

        return json_decode($apiresponse->getBody()->getContents());
    }


    function GetReferenceData()
    {

    }
}
