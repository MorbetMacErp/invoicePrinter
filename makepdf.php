<?php
/**
 * toPdf class for outputing a pdf to file
 * 
 * Uses mpdf library to output a pdf invoice to file
 * 
 * Example usage 
 * 
 */
require_once __DIR__ . '/vendor/autoload.php';

class makepdf
{
    private $mpdf;
    private $outputDir;
    private $invoiceHTML;
    private $printMode;
    private $printDirName;


    // iso 8601 - https://www.iso.org/iso-8601-date-and-time-format.html
    private $dateFormatMask = 'Y-m-d\THis';


    /**
     * Class constructor
     * 
     */
    public function __construct() {
        // Default print mode is save to file.
        // Switch to 'I' for http response
        $this->printMode = 'F';
        $this->printDirName = 'printed_invoices';
        $this->setOutputDir(sys_get_temp_dir() . DIRECTORY_SEPARATOR . $this->printDirName);
        $config = ['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L', 'tempDir' => $this->getOutputDir()];
        $this->mpdf = new \Mpdf\Mpdf($config);
    }


    /**
     * Set the output Directory
     */
    private function setOutputDir($outputDir) {
        $this->outputDir = $outputDir;
    }


    /**
     * Get the output Directory that was set in consrtuction
     */
    public function getOutputDir() {
        return $this->outputDir;
    }


    /**
     * 
     */
    public function setHTML() {
        $this->invoiceHTML = 'test';
    }


    /**
     * 
     */
    public function createPDF($filename) {
        $this->mpdf->writeHTML($this->invoiceHTML);
        
        if (!isset($filename) || trim($filename) === '') {
            throw new LengthException('Argument $filname must have a length.');        
        } elseif (file_exists($this->getOutputDir() . DIRECTORY_SEPARATOR . $filename . 'pdf')) {
            throw new OutOfBoundsException('File ' . $this->getOutputDir() . DIRECTORY_SEPARATOR . $filename . 'pdf already exists.');
        } else {
            $this->mpdf->Output($this->getOutputDir() . DIRECTORY_SEPARATOR . $filename . 'pdf', 'F');
        }

        return $this->getOutputDir();
    }
    
}
?>