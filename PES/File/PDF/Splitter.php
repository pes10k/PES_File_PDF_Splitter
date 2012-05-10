<?php

/**
 * PES_File_PDF_Splitter
 *
 * This class allows for transforming a PDF to a series of images.
 *
 * Note that this class requires that the imagemagik pecl extension
 * is installed
 *
 * Generated files are named with the name of the master / source PDF
 * file, an incrementing number, and the a PNG extension.  So, if provided
 * a PDF file with 10 pages in it named "example.pdf", this class would
 * write 10 pngs, named "example-1.png" through "example-10.png", in
 * the specified directory.
 *
 * This class requires the imagick pecl extension
 *
 * @category    File
 * @package     File
 * @author      Peter Snyder <snyderp@gmail.com>
 * @version 1.0
 * @see         http://www.php.net/manual/en/class.imagick.php
 */
class PES_File_PDF_Splitter {

  /**
   * Refrence to a PDF file on an accesssible filesystem
   *
   * @var string
   * @access private
   */
  private $pdf_path;

  /**
   * Constructs a PES_File_PDF_Splitter instance, and allows for instantialization
   * time setting of the PDF file this instance will represent.
   */
  public function __construct ($pdf_path = FALSE) {

    if (!empty($pdf_path)) {
      $this->setPDFPath($pdf_path);
    }
  }

  /**
   * Converts the PDF represented to one or more PNG files in the given
   * directory.  The relative path of each generated PNG file is returned
   * in an array.
   *
   * If there is already a file at the destination with the name of one
   * of the parts, its not overwritten, and is instead assumed to be
   * an already generated version of the page.
   *
   * @param string $destination
   *   A directory path to write the PNG files to.
   *
   * @return array
   *   An array of zero or more strings, each describing a PNG file that was
   *   created.
   */
  public function convertToPNG ($destination) {

    if ( ! is_dir($destination) OR ! is_writeable($destination)) {

      throw new Exception('"' . $destination . '" is not a writeable directory.');

    } else {

      $pdf = new imagick($this->PDFPath());

      // Normalize the directory path by stripping off any possible trailing
      // slashes, and then tacking one on the end.
      $destination = rtrim($destination, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
      $filename_parts = $this->parseFileName($pdf->getFilename());

      // An array to keep track of the names of all the png
      // images we generate from pages of the PDF.
      $generated_images = array();

      foreach ($pdf as $index => $a_page) {

        $im->setImageFormat('png');

        $file_name = $destination . $filename_parts['base'] . '-' . ($index + 1) . '.png';

        // If there is already a file with this name on the filesystem,
        // don't overwrite it.  Instead, assume its identical to what
        // we'd have generated and return the name anyway.
        if (is_file($file_name) OR file_put_contents($file_name, $im)) {

          $generated_images[] = $file_name;
        }
      }

      return $generated_images;
    }
  }

  // ===================
  // ! Getter / Setters
  // ===================

  /**
   * Returns the path to the PDF file being represented / transformed
   * by the current instance.
   *
   * @return string
   *   Path to a PDF file
   */
  public function PDFPath () {
    return $this->pdf_path;
  }

  /**
   * Sets the path to a PDF that should be respresented and transformed.
   *
   * @param string $path
   *   Path to a PDF file
   *
   * @return PES_File_PDF_Splitter
   *   Reference to the current object, for method chaining
   */
  public function setPDFPath ($path) {

    if ( ! is_file($path) OR ! is_readable($path)) {

      throw new Exception('"' . $path . '" is not a readble file.');

    } else {

      $this->pdf_path = $path;
      return $this;

    }
  }

  // ===================
  // ! Private Helpers
  // ===================

  /**
   * Finds the base and extension of a given file name.  If the file name has
   * no extension, the entire file is treated as the filename.  So, for example,
   * given "example.txt", this method would return an array with the key "base"
   * being "example", and "extension" being "txt".  But given the file
   * "another-example", this method returns "base" being "another-example" and
   * "extension" being an empty string.
   *
   * @param string $file_name
   *   A filename to split into a base name and file extension.
   *
   * @return array|bool
   *   Returns FALSE on invalid input.  Otherwise, returns an array with two
   *   keys, "base", which is the main, pre-extension part of the file name, and
   *   "extension", which is the text after the last period, if any exists.
   */
  protected function parseFileName ($file_name) {

    if (empty($file_name) OR !is_string($file_name)) {

      return FALSE;

    } else {

      $dot_position = strripos($file_name, '.');

      if ($dot_position === FALSE) {

        return array(
          'base' => $file_name,
          'extension' => '',
        );

      } else {

        return array(
          'base' => substr($file_name, 0, $dot_position),
          'extension' => substr($file_name, $dot_position + 1),
        );
      }
    }
  }
}
