<?php

class Omikron_Factfinder_Model_Tracking_Product
{
    /** @var string */
    private $trackingNumber;

    /** @var string */
    private $masterArticleNumber;

    /** @var string */
    private $price;

    /** @var int */
    private $count;

    public function __construct($trackingNumber, $masterArticleNumber, $price, $count)
    {
        $this->trackingNumber      = $trackingNumber;
        $this->masterArticleNumber = $masterArticleNumber;
        $this->price               = $price;
        $this->count               = $count;
    }

    /**
     * @return string
     */
    public function getTrackingNumber()
    {
        return $this->trackingNumber;
    }

    /**
     * @return string
     */
    public function getMasterArticleNumber()
    {
        return $this->masterArticleNumber;
    }

    /**
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }
}
