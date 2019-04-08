<?php

class Omikron_Factfinder_Helper_Upload extends Mage_Core_Helper_Abstract
{
    // Data transfer
    const PATH_FF_UPLOAD_HOST = 'factfinder/ftp_data_transfer/ff_upload_host';
    const PATH_FF_UPLOAD_PORT = 'factfinder/ftp_data_transfer/ff_upload_port';
    const PATH_FF_UPLOAD_PATH = 'factfinder/ftp_data_transfer/ff_upload_path';
    const PATH_FF_UPLOAD_USER = 'factfinder/ftp_data_transfer/ff_upload_user';
    const PATH_FF_UPLOAD_PASSWORD = 'factfinder/ftp_data_transfer/ff_upload_password';

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

        if (empty($this->getConfig(self::PATH_FF_UPLOAD_HOST)) || empty($this->getConfig(self::PATH_FF_UPLOAD_USER)) || empty($this->getConfig(self::PATH_FF_UPLOAD_PASSWORD))) {
            $result['success'] = false;
            $result['message'] = $this->__('Missing FTP data!');
        } else {
            if (!$content = file_get_contents($sourcePath)) {
                $result['success'] = false;
                $result['message'] = $this->__('No export file found!');
            }
            try {
                $ftp = new Varien_Io_Ftp();
                $ftp->open(
                    [
                        'host'     => $this->getConfig(self::PATH_FF_UPLOAD_HOST),
                        'user'     => $this->getConfig(self::PATH_FF_UPLOAD_USER),
                        'password' => $this->getConfig(self::PATH_FF_UPLOAD_PASSWORD),
                        'ssl'      => true,
                        'passive'  => true,
                        'port'     => $this->getConfig(self::PATH_FF_UPLOAD_PORT),
                        'path'     => $this->getConfig(self::PATH_FF_UPLOAD_PATH)
                    ]
                );
                $ftp->write($destinationPath, $content);
                $ftp->close();
                $result['success'] = true;
                $result['message'] = '';
            } catch (\Exception $e) {
                $result['success'] = false;
                $result['message'] = $this->__('Can\'t connect to FTP!') . ' - ' . $e->getMessage();
            }
        }

        return $result;
    }

    /**
     * Get the ff config data
     * @param string $key
     *
     * @return mixed
     */
    private function getConfig($key)
    {
        return Mage::getStoreConfig($key);
    }
}
