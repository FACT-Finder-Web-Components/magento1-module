<?php

class Omikron_Factfinder_Helper_Data extends Mage_Core_Helper_Abstract
{
    // General
    const PATH_ADDRESS = 'factfinder/general/address';
    const PATH_CHANNEL = 'factfinder/general/channel';
    const PATH_USERNAME = 'factfinder/general/username';
    const PATH_PASSWORD = 'factfinder/general/password';
    const PATH_AUTHENTICATION_PREFIX = 'factfinder/general/authentication_prefix';
    const PATH_AUTHENTICATION_POSTFIX = 'factfinder/general/authentication_postfix';
    const PATH_TRACKING_PRODUCT_NUMBER_FIELD_ROLE = 'factfinder/general/tracking_product_number_field_role';

    // Components
    const PATH_FF_SUGGEST = 'factfinder/components/ff_suggest';
    const PATH_FF_ASN = 'factfinder/components/ff_asn';
    const PATH_FF_PAGING = 'factfinder/components/ff_paging';
    const PATH_FF_SORTBOX = 'factfinder/components/ff_sortbox';
    const PATH_FF_BREADCRUMB = 'factfinder/components/ff_breadcrumb';
    const PATH_FF_PRODUCTSPP = 'factfinder/components/ff_productspp';
    const PATH_FF_RECOMMENDATION = 'factfinder/components/ff_recommendation';
    const PATH_FF_CAMPAIGN = 'factfinder/components/ff_campaign';
    const PATH_FF_PUSHEDPRODUCTSCAMPAIGN = 'factfinder/components/ff_pushedproductscampaign';
    const PATH_FF_SIMILAR = 'factfinder/components/ff_similar';

    // Advanced
    const PATH_VERSION = 'factfinder/advanced/version';
    const PATH_USE_URL_PARAMETER = 'factfinder/advanced/use_url_parameter';
    const PATH_USE_CACHE = 'factfinder/advanced/use_cache';
    const PATH_DEFAULT_QUERY = 'factfinder/advanced/default_query';
    const PATH_ADD_PARAMS = 'factfinder/advanced/add_params';
    const PATH_ADD_TRACKING_PARAMS = 'factfinder/advanced/add_tracking_params';
    const PATH_KEEP_FILTERS = 'factfinder/advanced/keep_filters';
    const PATH_KEEP_URL_PARAMS = 'factfinder/advanced/keep_url_param';
    const PATH_USE_ASN = 'factfinder/advanced/use_asn';
    const PATH_USE_FOUND_ROWS = 'factfinder/advanced/use_found_words';
    const PATH_USE_CAMPAIGNS = 'factfinder/advanced/use_campaigns';
    const PATH_FEEDBACK_CAMPAIGN_LABEL = 'factfinder/advanced/feedback_campaign_label';
    const PATH_ADVISOR_CAMPAIGN_NAME = 'factfinder/advanced/advisor_campaign_name';
    const PATH_PRODUCT_CAMPAIGN_NAME = 'factfinder/advanced/product_campaign_name';
    const PATH_PRODUCT_FEEDBACK_CAMPAIGN_NAME = 'factfinder/advanced/product_feedback_campaign_name';
    const PATH_PRODUCT_LANDING_PAGE_CAMPAIGN_NAME = 'factfinder/advanced/product_landing_page_campaign_name';
    const PATH_FEEDBACK_LANDING_PAGE_LABEL = 'factfinder/advanced/feedback_landing_page_label';
    const PATH_LANDING_PAGE_PAGE_ID = 'factfinder/advanced/landing_page_page_id';
    const PATH_SHOPPING_CART_PRODUCT_CAMPAIGN_NAME = 'factfinder/advanced/shopping_cart_product_campaign_name';
    const PATH_SHOPPING_CART_FEEDBACK_LABEL = 'factfinder/advanced/shopping_cart_feedback_label';
    const PATH_GENERATE_ADVISOR_TREE = 'factfinder/advanced/generate_advisor_tree';
    const PATH_DISABLE_CACHE = 'factfinder/advanced/disable_cache';
    const PATH_USE_PERSONALIZATION = 'factfinder/advanced/use_personalization';
    const PATH_USE_SEMANTIC_ENHANCER = 'factfinder/advanced/use_semantic_enhancer';
    const PATH_USE_ASO = 'factfinder/advanced/use_aso';
    const PATH_USE_BROWSER_HISTORY = 'factfinder/advanced/use_browser_history';
    const PATH_USE_SEO = 'factfinder/advanced/use_seo';
    const PATH_SEO_PREFIX = 'factfinder/advanced/seo_prefix';
    const PATH_ONLY_SEARCH_PARAMS = 'factfinder/advanced/only_search_params';
    const PATH_SIMILAR_PRODUCTS_MAX_RESULTS = 'factfinder/advanced/similar_products_max_results';

    // Data Transfer
    const PATH_FF_UPLOAD_URL_USER = 'factfinder/basic_auth_data_transfer/ff_upload_url_user';
    const PATH_FF_UPLOAD_URL_PASSWORD = 'factfinder/basic_auth_data_transfer/ff_upload_url_password';

    /**
     * Returns URL     *
     * @return string
     */
    public function getAddress()
    {
        $url = Mage::getStoreConfig(self::PATH_ADDRESS);
        if (substr(rtrim($url), -1) != "/") {
            $url .= "/";
        }

        return $url;
    }

    /**
     * Returns the FACT-Finder channel name
     *
     * @param null|int|string $storeId
     * @return string
     */
    public function getChannel($storeId = null)
    {
        return Mage::getStoreConfig(self::PATH_CHANNEL, $storeId);
    }

    /**
     * Returns the FACT-Finder username     *
     * @return string
     */
    public function getUsername()
    {
        return Mage::getStoreConfig(self::PATH_USERNAME);
    }

    /**
     * Set field roles
     *
     * @param string $value
     * @return mixed
     */
    public function setFieldRoles($value)
    {
        return Mage::getModel('core/config')->saveConfig(self::PATH_TRACKING_PRODUCT_NUMBER_FIELD_ROLE, $value);
    }

    /**
     * Defines if FF Suggest is enabled
     * @return bool
     */
    public function getFFSuggest()
    {
        return boolval(Mage::getStoreConfig(self::PATH_FF_SUGGEST));
    }

    /**
     * Defines if FF ASN is enabled
     * @return bool
     */
    public function getFFAsn()
    {
        return boolval(Mage::getStoreConfig(self::PATH_FF_ASN));
    }

    /**
     * Defines if FF Paging is enabled
     * @return bool
     */
    public function getFFPaging()
    {
        return boolval(Mage::getStoreConfig(self::PATH_FF_PAGING));
    }

    /**
     * Defines if FF Sortbox is enabled
     * @return bool
     */
    public function getFFSortbox()
    {
        return boolval(Mage::getStoreConfig(self::PATH_FF_SORTBOX));
    }

    /**
     * Defines if FF Breadcrumb is enabled
     * @return bool
     */
    public function getFFBreadcrumb()
    {
        return boolval(Mage::getStoreConfig(self::PATH_FF_BREADCRUMB));
    }

    /**
     * Defines if FF Products PP is enabled
     * @return bool
     */
    public function getFFProductsPP()
    {
        return boolval(Mage::getStoreConfig(self::PATH_FF_PRODUCTSPP));
    }

    /**
     * Defines if FF Recommendation is enabled
     * @return bool
     */
    public function getFFRecommendation()
    {
        return boolval(Mage::getStoreConfig(self::PATH_FF_RECOMMENDATION));
    }

    /**
     * Defines if FF Campaign is enabled
     * @return bool
     */
    public function getFFCampaign()
    {
        return boolval(Mage::getStoreConfig(self::PATH_FF_CAMPAIGN));
    }

    /**
     * Defines if FF Pushed Products Campaign is enabled
     * @return bool
     */
    public function getFFPushedproductscampaign()
    {
        return boolval(Mage::getStoreConfig(self::PATH_FF_PUSHEDPRODUCTSCAMPAIGN));
    }

    /**
     * Defines if FF Similar Products is enabled
     * @return bool
     */
    public function getFFSimilar()
    {
        return boolval(Mage::getStoreConfig(self::PATH_FF_SIMILAR));
    }

    /**
     * Returns the current FACT-Finder version
     *
     * @param null|int|string $storeId
     * @return string
     */
    public function getVersion($storeId = null)
    {
        return Mage::getStoreConfig(self::PATH_VERSION, $storeId);
    }

    /**
     * Returns the use-url-parameter configuration
     * @return bool
     */
    public function getUseUrlParameter()
    {
        return boolval(Mage::getStoreConfig(self::PATH_USE_URL_PARAMETER));
    }

    /**
     * Returns the use-cache configuration
     * @return bool
     */
    public function getUseCache()
    {
        return boolval(Mage::getStoreConfig(self::PATH_USE_CACHE));
    }

    /**
     * Returns the default-query configuration
     * @return string
     */
    public function getDefaultQuery()
    {
        return Mage::getStoreConfig(self::PATH_DEFAULT_QUERY);
    }

    /**
     * Returns the add-params configuration
     * @return string
     */
    public function getAddParams()
    {
        return Mage::getStoreConfig(self::PATH_ADD_PARAMS);
    }

    /**
     * Returns the add-tracking-params configuration
     * @return string
     */
    public function getAddTrackingParams()
    {
        return Mage::getStoreConfig(self::PATH_ADD_TRACKING_PARAMS);
    }

    /**
     * Returns the keep-filters configuration
     * @return bool
     */
    public function getKeepFilters()
    {
        return boolval(Mage::getStoreConfig(self::PATH_KEEP_FILTERS));
    }

    /**
     * Returns the keep-url-params configuration
     * @return string
     */
    public function getKeepUrlParams()
    {
        return Mage::getStoreConfig(self::PATH_KEEP_URL_PARAMS);
    }

    /**
     * Returns the use-asn configuration
     * @return bool
     */
    public function getUseAsn()
    {
        return boolval(Mage::getStoreConfig(self::PATH_USE_ASN));
    }

    /**
     * Returns the use-found-words configuration
     * @return bool
     */
    public function getUseFoundWords()
    {
        return boolval(Mage::getStoreConfig(self::PATH_USE_FOUND_ROWS));
    }

    /**
     * Returns the use-campaigns configuration
     * @return bool
     */
    public function getUseCampaigns()
    {
        return boolval(Mage::getStoreConfig(self::PATH_USE_CAMPAIGNS));
    }

    /**
     * Returns the feedback campaign label
     * @return string
     */
    public function getFeedbackCampaignLabel()
    {
        return Mage::getStoreConfig(self::PATH_FEEDBACK_CAMPAIGN_LABEL);
    }

    /**
     * Returns the advisor campaign name
     * @return string
     */
    public function getAdvisorCampaignName()
    {
        return Mage::getStoreConfig(self::PATH_ADVISOR_CAMPAIGN_NAME);
    }

    /**
     * Returns the product campaign name
     * @return string
     */
    public function getProductCampaignName()
    {
        return Mage::getStoreConfig(self::PATH_PRODUCT_CAMPAIGN_NAME);
    }

    /**
     * Returns the product feedback campaign name
     * @return mixed
     */
    public function getProductFeedbackCampaignName()
    {
        return Mage::getStoreConfig(self::PATH_PRODUCT_FEEDBACK_CAMPAIGN_NAME);
    }

    /**
     * Returns the product landing page campaign name
     * @return string
     */
    public function getProductLandingPageCampaignName()
    {
        return Mage::getStoreConfig(self::PATH_PRODUCT_LANDING_PAGE_CAMPAIGN_NAME);
    }

    /**
     * Returns the feedback landing page label
     * @return string
     */
    public function getFeedbackLandingPageLabel()
    {
        return Mage::getStoreConfig(self::PATH_FEEDBACK_LANDING_PAGE_LABEL);
    }

    /**
     * Returns the landing page page-id
     * @return string
     */
    public function getLandingPagePageId()
    {
        return Mage::getStoreConfig(self::PATH_LANDING_PAGE_PAGE_ID);
    }

    /**
     * Returns the shopping cart product campaign name
     * @return string
     */
    public function getShoppingCartProductCampaignName()
    {
        return Mage::getStoreConfig(self::PATH_SHOPPING_CART_PRODUCT_CAMPAIGN_NAME);
    }

    /**
     * Returns the shopping cart feedback label
     * @return string
     */
    public function getShoppingCartFeedbackLabel()
    {
        return Mage::getStoreConfig(self::PATH_SHOPPING_CART_FEEDBACK_LABEL);
    }

    /**
     * Returns the generate-advisor-tree configuration
     * @return bool
     */
    public function getGenerateAdvisorTree()
    {
        return boolval(Mage::getStoreConfig(self::PATH_GENERATE_ADVISOR_TREE));
    }

    /**
     * Returns the disable-cache configuration
     * @return bool
     */
    public function getDisableCache()
    {
        return boolval(Mage::getStoreConfig(self::PATH_DISABLE_CACHE));
    }

    /**
     * Returns the use-personalization configuration
     * @return bool
     */
    public function getUsePersonalization()
    {
        return boolval(Mage::getStoreConfig(self::PATH_USE_PERSONALIZATION));
    }

    /**
     * Returns the use-semantic-enhancer configuration
     * @return bool
     */
    public function getUseSemanticEnhancer()
    {
        return boolval(Mage::getStoreConfig(self::PATH_USE_SEMANTIC_ENHANCER));
    }

    /**
     * Returns the use-aso configuration
     * @return bool
     */
    public function getUseAso()
    {
        return boolval(Mage::getStoreConfig(self::PATH_USE_ASO));
    }

    /**
     * Returns the use-browser-history configuration
     * @return bool
     */
    public function getUseBrowserHistory()
    {
        return boolval(Mage::getStoreConfig(self::PATH_USE_BROWSER_HISTORY));
    }

    /**
     * Returns the use-seo configuration
     * @return bool
     */
    public function getUseSeo()
    {
        return boolval(Mage::getStoreConfig(self::PATH_USE_SEO));
    }

    /**
     * Returns the seo-prefix configuration
     * @return string
     */
    public function getSeoPrefix()
    {
        return Mage::getStoreConfig(self::PATH_SEO_PREFIX);
    }

    /**
     * Returns the only search params configuration
     * @return bool
     */
    public function getOnlySearchParams()
    {
        return boolval(Mage::getStoreConfig(self::PATH_ONLY_SEARCH_PARAMS));
    }

    /**
     * Returns the similar products max results
     * @return integer
     */
    public function getSimilarProductsMaxResults()
    {
        return (int) Mage::getStoreConfig(self::PATH_SIMILAR_PRODUCTS_MAX_RESULTS);
    }

    /**
     * Returns the authentication values as array
     *
     * @return array
     */
    public function getAuthArray()
    {
        $authArray = [];
        $authArray['username'] = $this->getUsername();

        $time = round(microtime(true) * 1000);
        $password = $this->getPassword();
        $prefix = $this->getAuthenticationPrefix();
        $postfix = $this->getAuthenticationPostfix();

        $hashPassword = md5($prefix . (string)$time . $password . $postfix);

        $authArray['password'] = $hashPassword;
        $authArray['timestamp'] = $time;

        return $authArray;
    }

    /**
     * Returns the upload username for external url
     *
     * @return string
     */
    public function getUploadUrlUser()
    {
        return Mage::getStoreConfig(self::PATH_FF_UPLOAD_URL_USER);
    }

    /**
     * Returns the upload password for external url
     *
     * @return string
     */
    public function getUploadUrlPassword()
    {
        return Mage::getStoreConfig(self::PATH_FF_UPLOAD_URL_PASSWORD);

    }

    /**
     * Returns the FACT-Finder password     *
     * @return string
     */
    private function getPassword()
    {
        return Mage::getStoreConfig(self::PATH_PASSWORD);
    }

    /**
     * Returns the authentication prefix     *
     * @return string
     */
    private function getAuthenticationPrefix()
    {
        return Mage::getStoreConfig(self::PATH_AUTHENTICATION_PREFIX);
    }

    /**
     * Returns the authentication postfix     *
     * @return string
     */
    private function getAuthenticationPostfix()
    {
        return Mage::getStoreConfig(self::PATH_AUTHENTICATION_POSTFIX);
    }
}