<?php

use Omikron_Factfinder_Model_Tracking_Product as TrackingProduct;

class Omikron_Factfinder_Model_Tracking
{
    /** @var Omikron_Factfinder_Helper_Communication */
    private $communication;

    /** @var Omikron_Factfinder_Helper_Data */
    private $config;

    /** @var Omikron_Factfinder_Model_SessionData */
    private $sessionData;

    /** @var string */
    private $apiName = 'Tracking.ff';

    public function __construct()
    {
        $this->communication = Mage::helper('factfinder/communication');
        $this->config        = Mage::helper('factfinder');
        $this->sessionData   = Mage::getModel('factfinder/sessionData');
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

            $this->communication->sendToFF($this->apiName, $params);
        } catch (Zend_Exception $e) {
            Mage::logException($e);
        }
    }
}
