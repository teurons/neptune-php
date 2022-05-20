<?php
namespace Teurons\Neptune;

use Curl\Curl;
use Log;

class Neptune
{
    public $client;
    public $envrionmentUUID;
    public $createEvent;
    public $payload;
    public $recipients;

    const DEFAULT_API_BASE = 'https://api.teurons.com/neptune';
    const EDGE_API_BASE = 'https://edge.teurons.com/neptune';

    public function __construct()
    {
        // $this->client = Http::withHeaders([
        //     'Accept' => 'application/json',
        //     'Authorization' => 'Bearer '.config('neptune.token')
        // ]);

        // $this->envrionmentUUID =  config('neptune.env');

        // $this->createEvent = config('neptune.endpoint')."/api/teams/".config('neptune.team')."/events";
        // $this->getAppNotificatiosUrl = config('neptune.endpoint')."/api/teams/".config('neptune.team')."/app-notifications";
        // $this->readAppNotificatiosUrl = config('neptune.endpoint')."/api/teams/".config('neptune.team')."/app-notifications/read";
        

        // $this->payload = $payload;
        // $this->recipients = $recipients;
    }

    public function fetchEnvironments()
    {

        $curl = new Curl();
        $curl->setHeader('Accept', 'application/json');
        $curl->setHeader('Authorization', 'Bearer '.config('neptune.token'));
        $curl->get(self::DEFAULT_API_BASE."/environments");


        if ($curl->error) {
            echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
        } else {
            echo 'Response:' . "\n";
            var_dump($curl->response);
        }

    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function ingest($payload)
    {

        $finalPayload =  [
            'environment' => config('neptune.env'),
            'api_token' => config('neptune.token'),
            'version' => '1'
        ];

        $finalPayload = array_merge($payload, $finalPayload);

        $curl = new Curl();
        $curl->setHeader('Content-Type', 'application/json');
        $curl->post(self::EDGE_API_BASE."/events/ingest", $finalPayload);


        if ($curl->error) {
            // echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
        } else {
            // echo 'Response:' . "\n";
            // var_dump($curl->response);
        }

        return $curl->response;

    }

    public function getAppNotifications()
    {

    }

    public function readAppNotifications()
    {

    }

}
