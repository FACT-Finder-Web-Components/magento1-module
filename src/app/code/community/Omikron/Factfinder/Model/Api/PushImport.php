<?php

declare(strict_types=1);

include MAGENTO_ROOT . '/vendor/autoload.php';

use Omikron\FactFinder\Communication\Client\ClientBuilder;
use Omikron\FactFinder\Communication\Client\ClientException;
use Omikron\FactFinder\Communication\Resource\AdapterFactory;
use Omikron_Factfinder_Model_Api_CredentialsFactory as CredentialsFactory;
use Omikron_Factfinder_Model_Config_Communication as CommunicationConfig;

class Omikron_Factfinder_Model_Api_PushImport implements Omikron_Factfinder_Model_Interface_Api_PushImportInterface
{
    /** @var ClientBuilder */
    private $clientBuilder;

    /** @var CommunicationConfig */
    private $communicationConfig;

    public function __construct()
    {
        $this->clientBuilder           = new ClientBuilder();
        $this->communicationConfig = Mage::getModel('factfinder/config_communication');
    }

    /**
     * @param int|null $scopeId
     * @param array $params
     * @return bool
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function execute(int $scopeId = null, array $params = []): bool
    {
        if (!Mage::getStoreConfigFlag('factfinder/data_transfer/ff_push_import_enabled', $scopeId)) {
            return false;
        }

        $clientBuilder = $this->clientBuilder
            ->withServerUrl($this->communicationConfig->getAddress())
            ->withCredentials(CredentialsFactory::create());

        $importAdapter = (new AdapterFactory($clientBuilder, $this->communicationConfig->getVersion()))->getImportAdapter();
        $channel       = $this->communicationConfig->getChannel($scopeId);
        $dataTypes     = $this->getPushImportDataTypes($scopeId);

        if (!$dataTypes) {
            return false;
        }

        if ($importAdapter->running($channel)) {
            throw new ClientException("Can't start a new import process. Another one is still going");
        }

        foreach ($dataTypes as $dataType) {
            $importAdapter->import($channel, $dataType);
        }

        return true;
    }

    private function getPushImportDataTypes(int $scopeId = null): array
    {
        $dataTypes  = Mage::getStoreConfig('faultfinder/data_transfer/ff_push_import_types', $scopeId);
        return explode(',', str_replace('data', 'search', $dataTypes));
    }
}
