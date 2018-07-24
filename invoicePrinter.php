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

class invoicePrinter
{
    // Operational variables 
    private $mpdf;
    private $printDir;
    private $invoiceHTML;

    //Main Invoice Details
    private $workCompany;
    private $workCompanyAddress;
    private $vatNumber;
    private $invoiceNumber;
    private $invoiceDate;
    private $clientCompany;

    /**
     * Array of records for Table of records in the invoice
     * Could be named better, but don't know what this list is for exactly
     */
    private $invoiceRecords; 

    // Financial Values for the invoice
    private $sortCode;
    private $accountNumber;
    private $subTotal;
    private $vat;
    private $totalDue;


    // iso 8601 - https://www.iso.org/iso-8601-date-and-time-format.html
    private $dateFormatMask = 'Y-m-d\THis';


    /**
     * makekPDF constructor
     * 
     * Call with no arguments for defaults, or pass in a config array
     * If supplying an over-wridden tempDir ensure it's the full system
     * path the the directory and that the _www or apache user/group has
     * read/write permission to it.
     * 
     * @var Array config array. See config.php for information on available options
     */
    public function __construct($config = []) {
        
        // Set the default timezone so iso 8601 appendices to filenames are BST complient
        date_default_timezone_set('Europe/London');

        // Any config passed in must be an associative array
        if (!is_array($config)) {
            throw new InvalidArgumentException('$config must be an array with construct variables that can be found https://mpdf.github.io/reference/mpdf-functions/construct.html');
        }

        // If the config array has no values, use the config file.
        if (sizeof($config === 0)) {
            $config = include('config.php');
        }

        // If the config array doesn't have an overwridden printDir, use the default.
        if (!array_key_exists('tempDir', $config)) {
            $this->printDir = 'printed_invoices';
            $this->setPrintDir(sys_get_temp_dir() . $this->printDir);
            $config['tempDir'] = $this->getPrintDir();
        } else {
            $this->setPrintDir($config['tempDir']);
        }

        // instantiate the mpdf instance
        $this->mpdf = new \Mpdf\Mpdf($config);
    }


    /**
     * Set the output Directory
     */
    private function setPrintDir($printDir) {
        $this->printDir = $printDir;
    }


    /**
     * Get the output Directory that was set in consrtuction
     */
    public function getPrintDir() {
        return $this->printDir;
    }


    /**
     * Set the singular invoice values
     * 
     * Pass in an associative array of string values for the primative types in the invoice
     *   
     * @var Array workCompany|workCompanyAddress|vatNumber|invoiceNumber|invoiceDate|clientCompany|sortCode|accountNumber|subTotal|vat|totalDue
     */
    public function setInvoiceValues($invoiceValues) {
        foreach($invoiceValues as $key => $value){
            try {
                $this->{$key} = $value;
            
            } catch (Exception $e) {
                throw new Exception ($e->getMessage() . ' Allowable array keys are: workCompany, workCompanyAddress, vatNumber, invoiceNumber, invoiceDate, clientCompany, sortCode, accountNumber, subTotal, vat, totalDue');
            }
        }
    }

    /**
     * Set the invoice records shown in the table in the middle of the invoice
     * 
     * Structure:
     *  records [
     *      {
     *          "description": "",
     *          "rate": "",
     *          "rateType": "",
     *          "units": "",
     *          "sum": ""             
     *      }, ...
     *  ]
     * 
     *  @var \ArrayObject 
     */
    public function setInvoiceRecords($invoiceRecords) {
        $this->invoiceRecords = $invoiceRecords;
    }


    /**
     * Build invoice HTML in $this->invoiceHTML
     */
    private function buildInvoice() {
        // Build invoice html
        $this->invoiceHTML = "
            <header>
                <h2 style='display: inline;'>{$this->workCompany}</h2>
                <h3 style='display: inline;'>{$this->invoiceDate}</h3>
            </header>
            <section>
                <address>{$this->workCompanyAddress}</address>
                <h4>{$this->vatNumber}</h4>
                <span>Invoice:&nbsp;{$this->invoiceNumber}</span>
            </section>
            <section>
                <p>To&nbsp;{$this->clientCompany}</p>
            </section>
            <table>
                <thead>
                    <th>Description</th>
                    <th>Rate</th>
                    <th>Rate Type</th>
                    <th>Units</th>
                    <th>Sum</th>
                </thead>
                <tbody>
                </tbody>
            </table>
            <hr />
            <section>
                <div align='right'>Sub Total £{$this->subTotal}</div>
                <div>
                    <p style='inline;'>{$this->sortCode}</p>
                    <p align='right;' style='inline;'>{$this->vat}</p>
                </div>
                <div>
                    <p style='inline;'>{$this->accountNumber}</p>
                    <p align='right;' style='inline;'>{$this->totalDue}</p>
                </div>
            </section>
            <section>
                <!--  Guessing this will need changed -->
                <p>Terms 14 days net<p>
            </section>
        ";

        $this->mpdf->writeHTML($this->invoiceHTML);
    }


    /**
     * 
     */
    public function printInvoice($filename) {

        if (!isset($filename) || trim($filename) === '') {
            throw new LengthException('Argument $filname must have a length.');        
        }
        
        $filename .= '_' . date($this->dateFormatMask);

        if (file_exists($this->getPrintDir() . DIRECTORY_SEPARATOR . $filename . '.pdf')) {
            throw new OutOfBoundsException('File ' . $this->getPrintDir() . DIRECTORY_SEPARATOR . $filename . '.pdf already exists.');
        } else {
            $this->buildInvoice();
            $this->mpdf->Output($this->getPrintDir() . DIRECTORY_SEPARATOR . $filename . '.pdf', 'F');
        }

        return $this->getPrintDir();
    }
    
}
?>