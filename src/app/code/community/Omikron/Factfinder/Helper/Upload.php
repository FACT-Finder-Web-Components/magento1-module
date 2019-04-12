<?php

class Omikron_Factfinder_Helper_Upload extends Mage_Core_Helper_Abstract
{
    const CONFIG_PATH = 'factfinder/ftp_data_transfer/ff_upload_';

    /**
     * Do file upload to destination host
     *
     * @param string $sourcePath
     * @param string $destinationPath
     *
     * @return array
     */
    public function upload($sourcePath, $destinationPath)
    {
        $result = [];

        if (!$this->getConfig('host') || !$this->getConfig('user') || !$this->getConfig('password')) {
            $result['success'] = false;
            $result['message'] = $this->__('Missing FTP data!');
        } else {
            if (!$content = file_get_contents($sourcePath)) {
                $result['success'] = false;
                $result['message'] = $this->__('No export file found!');
            }
            try {
                $ftp = new Varien_Io_Ftp();
                $ftp->open([
                    'host'     => $this->getConfig('host'),
                    'user'     => $this->getConfig('user'),
                    'password' => $this->getConfig('password'),
                    'ssl'      => true,
                    'passive'  => true,
                    'port'     => $this->getConfig('port'),
                    'path'     => $this->getConfig('path'),
                ]);
                $ftp->write($destinationPath, $content);
                $ftp->close();
                $result['success'] = true;
                $result['message'] = '';
            } catch (Exception $e) {
                $result['success'] = false;
                $result['message'] = $this->__("Can't connect to FTP! - %s", $e->getMessage());
            }
        }

        return $result;
    }

    /**
     * Get the ff config data
     *
     * @param string $key
     *
     * @return string
     */
    private function getConfig($key)
    {
        return (string) Mage::getStoreConfig(self::CONFIG_PATH . $key);
    }
}
