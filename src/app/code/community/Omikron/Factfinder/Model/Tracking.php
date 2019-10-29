<?php

use Omikron_Factfinder_Model_Tracking_Product as TrackingProduct;
use Omikron_Factfinder_Model_Client as ApiClient;
use Omikron_Factfinder_Model_Config_Communication as CommunicationConfig;

class Omikron_Factfinder_Model_Tracking
{
    /** @var ApiClient */
    private $apiClient;

    /** @var Omikron_Factfinder_Helper_Data */
    private $config;

    /** @var Omikron_Factfinder_Model_SessionData */
    private $sessionData;

    /** @var CommunicationConfig */
    private $communicationConfig;

    /** @var string */
    private $apiName = 'Tracking.ff';

    public function __construct()
    {
        $this->apiClient   = Mage::getModel('factfinder/client');
        $this->config      = Mage::helper('factfinder');
        $this->sessionData = Mage::getModel('factfinder/sessionData');
        $this->config      = Mage::getModel('factfinder/config_communication');
    }

    /**
     * @param string            $event
     * @param TrackingProduct[] $trackingProducts
     */
    public function execute($event, array $trackingProducts)
    {
        try {
            $params = [
                'event'    => $event,
                'channel'  => $this->config->getChannel(),
                'products' => array_map(function (TrackingProduct $trackingProduct) {
                    return array_filter([
                        'id'       => $trackingProduct->getTrackingNumber(),
                        'masterId' => $trackingProduct->getMasterArticleNumber(),
                        'price'    => $trackingProduct->getPrice(),
                        'count'    => $trackingProduct->getCount(),
                        'sid'      => $this->sessionData->getSessionId(),
                        'userId'   => $this->sessionData->getUserId(),
                    ]);
                }, $trackingProducts),
            ];

            $this->apiClient->sendRequest($this->config->getAddress() . '/' . $this->apiName, $params);
        } catch (Zend_Exception $e) {
            Mage::logException($e);
        }
    }
}
