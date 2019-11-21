<?php

declare(strict_types=1);

class Omikron_Factfinder_Block_Html_Head extends Mage_Page_Block_Html_Head
{
    /**
     * @param string $type
     * @param string $name
     * @param string $replace
     *
     * @return $this
     */
    public function replaceItem(string $type, string $name, string $replace)
    {
        if (isset($this->_data['items'][$type . '/' . $name])) {
            $item = $this->_data['items'][$type . '/' . $name];
            $this->_data['items'][$type . '/' . $name] = [
                'type'   => $type,
                'name'   => $replace,
                'params' => $item['params'],
                'if'     => $item['if'],
                'cond'   => $item['cond']
            ];
        }

        return $this;
    }
}
