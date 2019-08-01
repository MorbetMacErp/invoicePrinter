# Invoice Printer

## Wrapper for mpdf

Invoice printer for mPdf uses composer dependency manager for PHP by necesity, as mpdf only supports install via composer.

Once you've cloned the Git Repo, use CLI in the invoicePrinter root folder and follow the instructions to install composer here: https://getcomposer.org/download/

Then run the following command in the invoicePrinter root folder.

```cli
php composer.phar install
```

To now make use of invoicePrinter see the following example usage and/or the ./Docs/api/index.html

## Example usage

```php
<?php
    /**
     * Example usage
     * 
     * include the invoice printer for use.
     */
    include_once __DIR__ . DIRECTORY_SEPARATOR . 'invoicePrinter/src/invoicePrinter.php';

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

    // Same as before, but send 'I' and you'll get the 
    //$mpdf->setInvoiceValues(['workCompany' => 'A Random Company Name', 'sortCode' => '12-34-56']);
    //$mpdf->printInvoice('russell_lavens_invoice', 'I');

    // Same as before, but send 'D' as the second parameter to printInvoice and the document
    // will be downloaded 
    $mpdf->setInvoiceValues(['workCompany' => 'A Random Company Name', 'vatNumber' => '0123456789']);
    $mpdf->printInvoice('russell_lavens_invoice', 'D');

?>  
```
