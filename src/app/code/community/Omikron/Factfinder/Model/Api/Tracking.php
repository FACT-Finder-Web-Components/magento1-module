<?php

declare(strict_types=1);

include MAGENTO_ROOT . '/vendor/autoload.php';

use Omikron\FactFinder\Communication\Client\ClientBuilder;
use Omikron\FactFinder\Communication\Client\ClientException;
use Omikron\FactFinder\Communication\Resource\AdapterFactory;
use Omikron_Factfinder_Model_Api_CredentialsFactory as CredentialsFactory;
use Omikron_Factfinder_Model_Api_Tracking_Product as TrackingProduct;
use Omikron_Factfinder_Model_Config_Communication as CommunicationConfig;

class Omikron_Factfinder_Model_Api_Tracking implements Omikron_Factfinder_Model_Interface_Api_TrackingInterface
{
    /** @var CommunicationConfig */
    private $communicationConfig;

    /** @var Omikron_Factfinder_Model_SessionData */
    private $sessionData;

    /** @var ClientBuilder */
    private $clientBuilder;

    public function __construct()
    {
        $this->clientBuilder       = new ClientBuilder();
        $this->sessionData         = Mage::getModel('factfinder/sessionData');
        $this->communicationConfig = Mage::getModel('factfinder/config_communication');
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
            $clientBuilder = $this->clientBuilder
                ->withServerUrl($this->communicationConfig->getAddress())
                ->withCredentials(CredentialsFactory::create());

            $trackingAdapter =
                (new AdapterFactory($clientBuilder, $this->communicationConfig->getVersion()))->getTrackingAdapter();
            $trackingAdapter->track(
                $this->communicationConfig->getChannel(), $event,
                array_map(function (TrackingProduct $trackingProduct) {
                    return array_filter(
                        [
                            'id'       => $trackingProduct->getTrackingNumber(),
                            'masterId' => $trackingProduct->getMasterArticleNumber(),
                            'price'    => $trackingProduct->getPrice(),
                            'count'    => $trackingProduct->getCount(),
                            'sid'      => $this->sessionData->getSessionId(),
                            'userId'   => $this->sessionData->getUserId(),
                        ]
                    );
                }, $trackingProducts));

        } catch (ClientException $e) {
            Mage::logException($e);
        }
    }
}
