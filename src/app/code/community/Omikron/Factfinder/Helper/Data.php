<?php

class Omikron_Factfinder_Helper_Data extends Mage_Core_Helper_Abstract
{
    // Components
    const PATH_FF_SUGGEST = 'factfinder/components/ff_suggest';
    const PATH_FF_RECOMMENDATION = 'factfinder/components/ff_recommendation';
    const PATH_FF_CAMPAIGN = 'factfinder/components/ff_campaign';
    const PATH_FF_PUSHEDPRODUCTSCAMPAIGN = 'factfinder/components/ff_pushedproductscampaign';
    const PATH_FF_SIMILAR = 'factfinder/components/ff_similar';

    // Advanced
    const PATH_VERSION = 'factfinder/general/version';
    const PATH_USE_URL_PARAMETER = 'factfinder/advanced/use_url_parameter';
    const PATH_USE_CACHE = 'factfinder/advanced/use_cache';
    const PATH_ADD_PARAMS = 'factfinder/advanced/add_params';
    const PATH_ADD_TRACKING_PARAMS = 'factfinder/advanced/add_tracking_params';
    const PATH_PARAMETER_WHITELIST = 'factfinder/advanced/parameter_whitelist';
    const PATH_KEEP_URL_PARAMS = 'factfinder/advanced/keep_url_param';
    const PATH_FEEDBACK_CAMPAIGN_LABEL = 'factfinder/advanced/feedback_campaign_label';
    const PATH_ADVISOR_CAMPAIGN_NAME = 'factfinder/advanced/advisor_campaign_name';
    const PATH_PRODUCT_CAMPAIGN_NAME = 'factfinder/advanced/product_campaign_name';
    const PATH_PRODUCT_FEEDBACK_CAMPAIGN_NAME = 'factfinder/advanced/product_feedback_campaign_name';
    const PATH_PRODUCT_LANDING_PAGE_CAMPAIGN_NAME = 'factfinder/advanced/product_landing_page_campaign_name';
    const PATH_FEEDBACK_LANDING_PAGE_LABEL = 'factfinder/advanced/feedback_landing_page_label';
    const PATH_LANDING_PAGE_PAGE_ID = 'factfinder/advanced/landing_page_page_id';
    const PATH_SHOPPING_CART_PRODUCT_CAMPAIGN_NAME = 'factfinder/advanced/shopping_cart_product_campaign_name';
    const PATH_SHOPPING_CART_FEEDBACK_LABEL = 'factfinder/advanced/shopping_cart_feedback_label';
    const PATH_SIMILAR_PRODUCTS_MAX_RESULTS = 'factfinder/advanced/similar_products_max_results';

    // Data Transfer
    const PATH_FF_UPLOAD_URL_USER = 'factfinder/basic_auth_data_transfer/ff_upload_url_user';
    const PATH_FF_UPLOAD_URL_PASSWORD = 'factfinder/basic_auth_data_transfer/ff_upload_url_password';

    // Cron
    const PATH_FF_CRON_ENABLED   = 'factfinder/configurable_cron/enabled';

    /**
     * Defines if FF Suggest is enabled
     * @return bool
     */
    public function getFFSuggest()
    {
        return Mage::getStoreConfigFlag(self::PATH_FF_SUGGEST);
    }

    /**
     * Defines if FF Recommendation is enabled
     * @return bool
     */
    public function getFFRecommendation()
    {
        return Mage::getStoreConfigFlag(self::PATH_FF_RECOMMENDATION);
    }

    /**
     * Defines if FF Campaign is enabled
     * @return bool
     */
    public function getFFCampaign()
    {
        return Mage::getStoreConfigFlag(self::PATH_FF_CAMPAIGN);
    }

    /**
     * Defines if FF Pushed Products Campaign is enabled
     * @return bool
     */
    public function getFFPushedproductscampaign()
    {
        return Mage::getStoreConfigFlag(self::PATH_FF_PUSHEDPRODUCTSCAMPAIGN);
    }

    /**
     * Defines if FF Similar Products is enabled
     * @return bool
     */
    public function getFFSimilar()
    {
        return Mage::getStoreConfigFlag(self::PATH_FF_SIMILAR);
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
        return Mage::getStoreConfigFlag(self::PATH_USE_URL_PARAMETER);
    }

    /**
     * Returns the use-cache configuration
     * @return bool
     */
    public function getUseCache()
    {
        return Mage::getStoreConfigFlag(self::PATH_USE_CACHE);
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
     * Returns the parameter-whitelist configuration
     * @return string
     */
    public function getParameterWhiteList()
    {
        return Mage::getStoreConfig(self::PATH_PARAMETER_WHITELIST);
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
     * Returns the similar products max results
     * @return integer
     */
    public function getSimilarProductsMaxResults()
    {
        return (int) Mage::getStoreConfig(self::PATH_SIMILAR_PRODUCTS_MAX_RESULTS);
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
     * @return bool
     */
    public function isCronEnabled()
    {
        return  Mage::getStoreConfigFlag(self::PATH_FF_CRON_ENABLED);
    }
}
