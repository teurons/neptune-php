<?php
namespace Teurons\Neptune;

use Curl\Curl;
use Log;

class Neptune
{
    const DEFAULT_API_BASE = 'https://api.teurons.com/neptune';
    const EDGE_API_BASE = 'https://edge.teurons.com/neptune';

    public function __construct()
    {

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
    public function fire($eventType, $data, $payload)
    {

        $finalPayload =  [
            'event_type' => $eventType,
            'environment' => config('neptune.env'),
            'api_token' => config('neptune.token'),
            'version' => '1',
            'data' => $data,
        ];

        $finalPayload = array_merge($payload,  $finalPayload);

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

    public function appNotificationsGet($userId, $overrideMeta = [])
    {

        $meta = array(
            'per_page' => '10',
            'page' => '1',
            'sort_column' => 'notification_deliveries*created_at',
            'sort_order' => "desc",
            "type" => "unread"
        );

        $meta = array_merge($meta, $overrideMeta);

        $meta['user_id'] = $userId;

        $url = self::DEFAULT_API_BASE."/app_notifications/".config('neptune.env')."?".http_build_query($meta);


        $curl = new Curl();
        $curl->setHeader('Content-Type', 'application/json');
        $curl->setHeader('Authorization', 'Bearer '.config('neptune.token'));
        $curl->get($url);

        // dd($url);


        if ($curl->error) {
            // echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
        } else {
            // echo 'Response:' . "\n";
            // var_dump($curl->response);
        }

        return $curl->response;
    }

    public function appNotificationMarkAsRead($type = "notification", $identifier)
    {

        $url = self::DEFAULT_API_BASE."/app_notifications/".config('neptune.env')."/read";

        $finalPayload = [
            "type" => $type,
            "id" => $identifier
        ];

        // dd($url);

        $curl = new Curl();
        $curl->setHeader('Content-Type', 'application/json');
        $curl->setHeader('Authorization', 'Bearer '.config('neptune.token'));
        $curl->post($url, $finalPayload);

        if ($curl->error) {
            // echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
        } else {
            // echo 'Response:' . "\n";
            // var_dump($curl->response);
        }

        return $curl->response;

    }

}
