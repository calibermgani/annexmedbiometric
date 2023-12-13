<?php

namespace App\Http\Controllers;

use App\Mail\BiometricMail;
use App\Models\Trans;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AccessCardController extends Controller
{
    public function insertLiveData()
    {
        try {
            $last_id = $this->fetchLastRecord();
            $getData = Trans::select('*', DB::raw('ROW_NUMBER() OVER (ORDER BY (SELECT NULL)) AS row_number'))->skip($last_id->trans_id->trans_id)->get();
            // $getData = Trans::select('*', DB::raw('ROW_NUMBER() OVER (ORDER BY (SELECT NULL)) AS row_number'))->skip(1118707)->get();
            // dd($getData);

            if (!$getData->isEmpty()) {
                $insert_data = $this->insert_record($getData);
                return $insert_data;
            } else {
                $mailData = [
                    'title' => 'Mail from Annexmed Biometric',
                    'body' => 'No New Records Found In Biometric Machine'
                ];

                Mail::to('mgani@caliberfocus.com')->send(new BiometricMail($mailData));

                return 'No New Records Found In Biometric Machine';
            }
        } catch (Exception $th) {
            log::debug('insert live data' . $th->getMessage());
        }
    }

    public function insert_record($getData)
    {
        try {
            //Create Client object to deal with
            $client = new Client();

            // Define the request parameters
            $url = 'http://dev.aims.officeos.in/api/biodata/insert_record';

            $headers = [
                'Content-Type' => 'application/json',
            ];

            // POST request using the created object
            $postResponse = $client->post($url, [
                'headers' => $headers,
                'json' => $getData,
                'timeout' => 1200,
            ]);

            // Get the response code
            return $postResponse->getBody()->getContents();

        } catch (Exception $th) {
            log::debug('insert live data' . $th->getMessage());
            dd($th->getMessage());
        }
    }

    public function fetchLastRecord()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://dev.aims.officeos.in/api/biodata/fetch_last_id",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                // Set Here Your Requesred Headers
                'Content-Type: application/json',
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return json_decode($response);
        }
    }
}
