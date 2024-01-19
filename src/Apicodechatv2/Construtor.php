<?php
namespace Apicodechatv2;

class Construtor {

    protected $url;
    protected $tokenAdmin;
    protected $tokenBussiners;
    protected $tokenInstance;

    public function __construct($url, $tokenAdmin, $tokenBussiners, $tokenInstance) {
        $this->url = $url;
        $this->tokenAdmin = $tokenAdmin;
        $this->tokenBussiners = $tokenBussiners;
        $this->tokenInstance = $tokenInstance;
    }
}