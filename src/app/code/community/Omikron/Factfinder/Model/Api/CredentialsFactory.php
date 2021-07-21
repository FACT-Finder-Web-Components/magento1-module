<?php
declare(strict_types=1);

include MAGENTO_ROOT . '/vendor/autoload.php';

use Omikron\FactFinder\Communication\Credentials;

class Omikron_Factfinder_Model_Api_CredentialsFactory
{
    /**
     * @param array|null $authData
     * @return Credentials
     *
     * @todo make it useful with test connection which overrides the stored config with data coming from request ($authData argument could be useful)
     */
    public static function create(array $authData = null): Credentials
    {
        /** @var \Omikron\Factfinder\Model\Config\AuthConfig */
        $authConfig = Mage::getModel('factfinder/config_auth');

        return new Credentials(
            $authConfig->getUsername(),
            $authConfig->getPassword(),
            $authConfig->getAuthenticationPrefix(),
            $authConfig->getAuthenticationPostfix()
        );
    }
}
