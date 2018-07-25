```php
<?php
    /**
     * Example usage
     * 
     * include the invoice printer for use.
     */
    include_once __DIR__ . DIRECTORY_SEPARATOR . 'pdfGeneration/invoicePrinter.php';

    // Create a new instance of the invoicePrinter class
    $mpdf = new invoicePrinter();
    
    // Leave out the second parameter to printInvoice to it's default and the file will
    // be saved to sys_get_temp_dir() . '/printed_invoices' or whatever tempDir you've
    // overritten in the config.
    $mpdf->setInvoiceValues(['workCompany' => 'A Random Company Name', 'workComcanyAddress' => '10 main street <br >Main Town<br >']);
    
    $invoiceRecords = [
        [
            "description" => "blah", 
            "rate" => "meh", 
            "rateType" => "pah", 
            "units" => "bah",
            "sum" => "muh"
        ],
        [
            "description" => "blah1", 
            "rate" => "meh2", 
            "rateType" => "pah3", 
            "units" => "bah4",
            "sum" => "muh5"
        ]
    ];
    $mpdf->setInvoiceRecords($invoiceRecords);
    $mpdf->printInvoice('russell_lavens_invoice');

    // Same as before, but send 'D' as the second parameter to printInvoice and the document
    // will be downloaded 
    $mpdf->setInvoiceValues(['workCompany' => 'A Random Company Name', 'vatNumber' => '0123456789']);
    $mpdf->printInvoice('russell_lavens_invoice', 'D');

    // Same as before, but send 'I' and you'll get the 
    $mpdf->setInvoiceValues(['workCompany' => 'A Random Company Name', 'sortCode' => '12-34-56']);
    $mpdf->printInvoice('russell_lavens_invoice', 'I');

?>  
```