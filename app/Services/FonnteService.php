<?php

namespace App\Services;

class FonnteService
{
    public function sendMessage($target, $message, $countryCode = '62')
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => $target,
                'message' => $message,
                'countryCode' => $countryCode, 
            ),
            CURLOPT_HTTPHEADER => array(
                // 'Authorization: ' . env('FONNTE_API_TOKEN'),
                
                'Authorization: ' . '@tPQ6dbLeV8d!WDbe2yr',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }
}
