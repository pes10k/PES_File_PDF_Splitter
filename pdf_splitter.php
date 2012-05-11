<?php

/**
 * @file
 * This file is a simple, commandline front end for the included
 * PES_File_PDF_Splitter class.  It allows for splitting a multi-page PDF file
 * into several PNG files, with each page being a single image ones.  
 * Configuration options are described below, or can be read by running 
 * "php pdf_splitter.php --help" on the command line.
 */

// The used PEAR Console_Getargs class isn't PHP5 compliant, so hide
// hide the depreciated error messages.
error_reporting(E_ALL ^ E_DEPRECATED);

include 'Console/Getargs.php';
include 'PES/File/PDF/Splitter.php';

$config = array(
  'x_res' => array(
    'desc' => 'The x resolution used to read in the PDF',
    'min' => 0,
    'max' => 1,
    'default' => 100,
  ),
  'y_res' => array(
    'desc' => 'The y resolution used to read in the PDF',
    'min' => 0,
    'max' => 1,
    'default' => 100,
  ),
  'dest' => array(
    'desc' => 'The path to write the created, child PNG pages to',
    'min' => 0,
    'max' => 1,
    'default' => __DIR__,
  ),
  'source' => array(
    'desc' => 'The PDF file to split into PNG page files.',
    'min' => 1,
    'max' => 1,
  ),
);

$args = Console_Getargs::factory($config);

if (PEAR::isError($args)) {

  if ($args->getCode() === CONSOLE_GETARGS_ERROR_USER) {

  echo Console_Getargs::getHelp($config, NULL, $args->getMessage());

  } else if ($args->getCode() === CONSOLE_GETARGS_HELP) {

    echo Console_Getargs::getHelp($config);

  }

} else {

  $splitter = new PES_File_PDF_Splitter();

  $created_files = $splitter
    ->setPDFPath($args->getValue('source'))
    ->setXResolution($args->getValue('x_res') ?: 100)
    ->setYResolution($args->getValue('y_res') ?: 100)
    ->convertToPNG($args->getValue('dest') ?: __DIR__);

  echo 'Wrote ' . count($created_files) . ' PNG files:' . PHP_EOL;

  foreach ($created_files as $a_file) {
  
    echo ' - ' . $a_file . PHP_EOL;
  }
}
