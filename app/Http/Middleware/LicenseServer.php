<?php

namespace App\Http\Middleware;

use GuzzleHttp\Client;

class LicenseServer
{
    protected $client;
    protected $server;
    protected $access_token;


    public function __construct()
    {
        $headers = ['Referer' => url('/')];
        $this->client = new Client(['headers' => $headers]);
        $this->access_token = config('app.access_token');
        $this->server = config('app.license_server');
    }

    public function options($verification_code, $is_get = false)
    {
        if($is_get){
            return [
                'query' => [
                    'verification_code' => $verification_code,
                    'url' => url('/'),
                    'access_token' => $this->access_token,
                ]
            ];
        }

        return [
            'form_params' => [
                'verification_code' => $verification_code,
                'url' => url('/'),
                'access_token' => $this->access_token,
            ]
        ];
    }

    public function errorMessage($response)
    {
        $status_code = $response->getStatusCode();

        if($status_code == 400){
            return ['error' => $status_code, 'message' => __('An unexpected error occurred! Please contact the author of this script')];
        }elseif($status_code == 401){
            return ['error' => $status_code, 'message' => __('Your verification code seems to be invalid. Please try again!')];
        }elseif($status_code == 404){
            return ['error' => $status_code, 'message' => __('An unexpected error occurred! Your license details could not be found!')];
        }elseif($status_code == 403){
            return ['error' => $status_code, 'message' => __('Your license verification code is already registered with another domain.')];
        }

        return ['error' => $status_code, 'message' =>  __('Opps! Something went wrong!')];

    }
}
