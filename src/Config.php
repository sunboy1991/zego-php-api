<?php

namespace SDK;

class Config
{
    private $appId;
    private $serverSecret;
    private $baseUrl;

    public function __construct($appId, $serverSecret, $baseUrl = 'https://rtc-api.zego.im/')
    {
        $this->appId = $appId;
        $this->serverSecret = $serverSecret;
        $this->baseUrl = $baseUrl;
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function getServerSecret()
    {
        return $this->serverSecret;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function setBaseUrl($baseUrl = 'https://rtc-api.zego.im/')
    {
        $this->baseUrl = $baseUrl;
    }
}
