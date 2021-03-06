<?php
declare(strict_types=1);

include MAGENTO_ROOT . '/vendor/autoload.php';

use Omikron\FactFinder\Communication\Credentials;
use Omikron_Factfinder_Model_Config_Auth as AuthConfig;

class Omikron_Factfinder_Model_Api_CredentialsFactory
{
    /**
     * @param array|null $authData
     * @return Credentials
     */
    public static function create(array $authData = null): Credentials
    {
        switch (is_null($authData)) {
            case true:
                /** @var AuthConfig */
                $authConfig = Mage::getModel('factfinder/config_auth');
                break;
            default:
                $authData = self::getCredentials($authData);
                break;
        }

        return new Credentials(
            $authData['username'] ?? $authConfig->getUsername(),
            $authData['password'] ?? $authConfig->getPassword(),
            $authData['authenticationPrefix'] ?? $authConfig->getAuthenticationPrefix(),
            $authData['authenticationPostfix'] ?? $authConfig->getAuthenticationPostfix()
        );
    }

    private static function getCredentials(array $params): array
    {
        $params += [
            'prefix' => $params['authenticationPrefix'],
            'postfix' => $params['authenticationPostfix'],
        ];

        return self::extractAuthParams($params);
    }

    private static function extractAuthParams(array $params): array
    {
        return array_filter($params, function ($k) {
            return in_array($k, ['username', 'password', 'authenticationPrefix', 'authenticationPostfix']);
        }, ARRAY_FILTER_USE_KEY);
    }
}
