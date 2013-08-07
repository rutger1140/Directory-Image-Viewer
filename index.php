<?php

/**
 * PHP directory index image viewer
 *
 * Reads 'img' folder
 * Shows all found images in a slideshow
 *
 * @author: Rutger Laurman
 */

  // filetypes to display
  $imagetypes = array("image/jpeg", "image/gif", "image/png");

  // Original PHP code by Chirp Internet: www.chirp.com.au
  // Please acknowledge use of this code by including this header.

  function getImages($dir)
  {
    global $imagetypes;

    // array to hold return value
    $retval = array();

    // add trailing slash if missing
    if(substr($dir, -1) != "/") $dir .= "/";

    // full server path to directory
    $fulldir = dirname(__FILE__) . "/" . $dir; //  getcwd();// "/.";

    $d = @dir($fulldir) or die("getImages: Failed opening directory $dir for reading");
    while(false !== ($entry = $d->read())) {
      // skip hidden files
      if($entry[0] == ".") continue;

      // check for image files
      $f = escapeshellarg("$fulldir$entry");
      $mimetype = trim(`file -bi $f`);
      foreach($imagetypes as $valid_type) {
        if(preg_match("@^{$valid_type}@", $mimetype)) {
          $retval[] = array(
           'file' => "$dir$entry",
           'size' => getimagesize("$fulldir$entry")
          );
          break;
        }
      }
    }
    $d->close();

    return $retval;
  }


  // fetch image details
  $images = getImages("img");


//  print_r($images);

  // display on page
  foreach($images as $img) {
    echo "<div class=\"photo\">";
    echo "<img src=\"{$img['file']}\" {$img['size'][3]} alt=\"\"><br>\n";
    // display image file name as link
    echo "<a href=\"{$img['file']}\">",basename($img['file']),"</a><br>\n";
    // display image dimenstions
    echo "({$img['size'][0]} x {$img['size'][1]} pixels)<br>\n";
    // display mime_type
    echo $img['size']['mime'];
    echo "</div>\n";
  }

?>
