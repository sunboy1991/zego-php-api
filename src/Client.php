<?php
namespace SDK;

class Client
{
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function request($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \Exception('Request Error: ' . curl_error($ch));
        }

        curl_close($ch);
        return json_decode($response, true);
    }
}
