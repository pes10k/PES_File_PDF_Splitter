PES_File_PDF_Splitter
=====================

Simple PHP Class and command line script for splitting a PDF file into several 
PNG pages

Requirements
===

This class requires PHP 5.3 or later, and the imagick pecl extension
(see http://www.php.net/manual/en/class.imagick.php).

Description
===

This class allows for simple splitting of a multi-page PDF into several PNG
files, with each page from the PDF being saved as a seperate PNG file.

 Generated files are named with the name of the master / source PDF
 file, an incrementing number, and the a PNG extension.  So, if provided
 a PDF file with 10 pages in it named "example.pdf", this class would
 write 10 pngs, named "example-1.png" through "example-10.png", in
 the specified directory.

A sample, command line tool for use with the class is also included.  This
file, "pdf_splitter.php", requires the PEAR Console_Getargs package (see
http://pear.php.net/package/Console_Getargs).  Information on using this tool
is available by running "php pdf_splitter.php --help" on the command line.

Author
===
Peter E. Snyder - snyderp@gmail.com