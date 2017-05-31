<?php
// Setting the time zone.
date_default_timezone_set('UTC');

/**
 * Create database dump links.
 */
function database_dumps() {
  $output = array();
  exec('ls sanitized/*.gz', $output);
  foreach ($output as $out) {
    $dump = explode('/', $out);
    printf('<li><a href="https://sanitize.backdropcms.org/%s" title="Download">%s</a></li>', $out, $dump[1]);
  }
}

/**
 * Create file archive links.
 */
function file_archives() {
  $output = array();
  exec('ls files_backups/*.tar.gz', $output);
  foreach ($output as $out) {
    $archive = explode('/', $out);
    printf('<li><a href="https://sanitize.backdropcms.org/%s" title="Download">%s</a></li>', $out, $archive[1]);
  }
}

?>
<html>
  <head>
    <title>Top Secret backdropcms.org Sanitized Databases</title>
  </head>
  <body>
    <img src="https://backdropcms.org/files/inline-images/Drop_lounging_final_black_0.png" />
    <h2>Download top secret backdropcms.org sanitized database dumps.</h2>
    <ul>
      <?php database_dumps(); ?>
    </ul>

    <h2>Download top secret backdropcms.org /files directory archives.</h2>
    <ul>
      <?php file_archives(); ?>
    </ul>

  </body>
</html>
