<?php

use Omikron_Factfinder_Model_Api_Tracking_Product as TrackingProduct;
use Omikron_Factfinder_Model_Client as ApiClient;
use Omikron_Factfinder_Model_Config_Communication as CommunicationConfig;

class Omikron_Factfinder_Model_Api_Tracking implements Omikron_Factfinder_Model_Interface_Api_TrackingInterface
{
    /** @var ApiClient */
    private $apiClient;

    /** @var CommunicationConfig */
    private $config;

    /** @var Omikron_Factfinder_Model_SessionData */
    private $sessionData;

    /** @var string */
    private $apiName = 'Tracking.ff';

    public function __construct()
    {
        $this->apiClient   = new ApiClient();
        $this->sessionData = Mage::getModel('factfinder/sessionData');
        $this->config      = Mage::getModel('factfinder/config_communication');
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

            $this->apiClient->get($this->config->getAddress() . '/' . $this->apiName, $params);
        } catch (Zend_Exception $e) {
            Mage::logException($e);
        }
    }
}
