<?php
namespace SHUFLER\ShuflerBundle\Service;

class ConvertCSVToArray {
    
    private $filePath;
    
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }
    
    public function convert($delimiter = ',')
    {
        if(!file_exists($this->getFilePath()) || !is_readable($this->getFilePath())) {
            return FALSE;
        }
        $data = array();
        
        if (($handle = fopen($this->getFilePath(), 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                $data[] = $row;
            }
            fclose($handle);
        }
        return $data;
    }
    
    public function getFilePath()
    {
        return $this->filePath;
    }
    
}