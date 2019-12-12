<?php

class Omikron_Factfinder_Model_Api_Tracking_Product
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

    public function getTrackingNumber(): string
    {
        return $this->trackingNumber;
    }

    public function getMasterArticleNumber(): string
    {
        return $this->masterArticleNumber;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
