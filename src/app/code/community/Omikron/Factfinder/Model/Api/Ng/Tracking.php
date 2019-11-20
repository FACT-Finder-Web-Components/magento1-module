<?php

use Omikron_Factfinder_Model_ClientNG as ClientNG;
use Omikron_Factfinder_Model_Tracking_Product as TrackingProduct;
use Omikron_FactfinderNG_Model_Config_Communication as CommunicationConfig;

class Omikron_Factfinder_Model_Api_Ng_Tracking implements Omikron_Factfinder_Model_Interface_Api_TrackingInterface
{
    /** @var ClientNG */
    private $apiClient;

    /** @var Omikron_Factfinder_Model_SessionData */
    private $sessionData;

    /** @var CommunicationConfig */
    private $config;

    public function __construct()
    {
        $this->apiClient        = Mage::getModel('factfinder/clientNG');
        $this->sessionData      = Mage::getModel('factfinder/sessionData');
        $this->config           = Mage::getModel('factfinderng/config_communication');
    }

    /**
     * @param string            $event
     * @param TrackingProduct[] $trackingProducts
     *
     * @return void
     */
    public function execute(string $event, array $trackingProducts)
    {
        try {
            $params = array_map(function (TrackingProduct $trackingProduct) {
                return array_filter([
                    'id' => $trackingProduct->getTrackingNumber(),
                    'masterId' => $trackingProduct->getMasterArticleNumber(),
                    'price' => $trackingProduct->getPrice(),
                    'count' => $trackingProduct->getCount(),
                    'sid' => $this->sessionData->getSessionId(),
                    'userId' => $this->sessionData->getUserId(),
                ]);
            }, $trackingProducts);

            $endpoint = $this->config->getAddress()
                . sprintf('/rest/%s/track/%s/%s', $this->config->getApi(), $this->config->getChannel(), $event);

            $this->apiClient->post($endpoint, $params);
        } catch (Zend_Exception $e) {
            Mage::logException($e);
        }
    }
}
