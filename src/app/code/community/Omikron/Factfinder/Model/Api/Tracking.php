<?php

declare(strict_types=1);

include MAGENTO_ROOT . '/vendor/autoload.php';

use Omikron\FactFinder\Communication\Client\ClientBuilder;
use Omikron\FactFinder\Communication\Client\ClientException;
use Omikron\FactFinder\Communication\Resource\AdapterFactory;
use Omikron_Factfinder_Model_Api_CredentialsFactory as CredentialsFactory;
use Omikron_Factfinder_Model_Config_Communication as CommunicationConfig;

class Omikron_Factfinder_Model_Api_Tracking
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
     * @param string $event
     * @param array  $trackingProducts
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
                array_map(function (array $trackingProduct) {
                        return array_filter($trackingProduct +
                            [
                                'sid'    => $this->sessionData->getSessionId(),
                                'userId' => $this->sessionData->getUserId(),
                            ]
                        );
                    }, $trackingProducts));

        } catch (ClientException $e) {
            Mage::logException($e);
        }
    }
}
