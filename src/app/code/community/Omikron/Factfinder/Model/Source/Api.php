<?php

declare(strict_types=1);

class Omikron_Factfinder_Model_Source_Api
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'v3', 'label' => 'v3'],
            ['value' => 'v4', 'label' => 'v4'],
        ];
    }
}
