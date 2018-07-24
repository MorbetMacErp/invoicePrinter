<?php
/**
 * The confiration of values can be done 2 different ways, each at
 * a different stage of processing.
 * 1.   Over-write the values in this file and deploy to server. 
 * 2.   Pass a config array with a name/value pair for each 
 *      configuration setting that you would like to overwrite
 *      to the class constructor.
 * 
 * To see a full list of config options check out ...
 * See https://mpdf.github.io/reference/mpdf-functions/construct.html
 */
return array(
    'mode' => 'utf-8',
    'format' => 'A4',
    'orientation' => 'P',
);

?>