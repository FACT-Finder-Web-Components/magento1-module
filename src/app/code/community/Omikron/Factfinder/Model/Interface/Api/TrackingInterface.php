<?php

use Omikron_Factfinder_Model_Tracking_Product as TrackingProduct;

interface Omikron_Factfinder_Model_Interface_Api_TrackingInterface
{
    /**
     * @param string            $event
     * @param TrackingProduct[] $trackingProducts
     *
     * @return void
     */
    public function execute(string $event, array $trackingProducts);
}
