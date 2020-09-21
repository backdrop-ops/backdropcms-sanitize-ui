<?php
/**
 * Get table rows for database and files backups.
 */
function table_rows() {
  $paths = array(
    'backdropcms.org' => 'BackdropCMS',
    'api.backdropcms.org' => 'API',
    'forum.backdropcms.org' => 'Forum',
  );

  foreach ($paths as $path => $name) {
    $databases = array();
    $files = array();

    exec("ls $path/sanitized/*.sql.gz", $databases);
    exec("ls $path/files_backups/*.tar.gz", $files);
    foreach (array_reverse($databases) as $id => $database) {
      create_row($name, $database, array_reverse($files)[$id]);
    }
  }
}

/**
 * Print the HTML for a single table row.
 */
function create_row($name, $db_path, $file_path) {
  $class = '';
  $db_path_parts = explode('/', $db_path);
  $db_filename_parts = explode('-', $db_path_parts[2]);

  if ($db_filename_parts[1] == 'latest') {
    $date = 'Latest';
    $class = 'latest';
  }
  else {
    $date = $db_filename_parts[1] . ' ' . $db_filename_parts[2] . ' ' . $db_filename_parts[3];
  }

  $db_link = '<a href="https://sanitize.backdropcms.org/' . $db_path . '">Database</a>';
  $file_link = '<a href="https://sanitize.backdropcms.org/' . $file_path . '">Files</a>';

  print '<tr class="' . $class . '">
            <td>' . $name . '</td>
            <td>' . $date . '</td>
            <td>' . $db_link . ' :: ' . $file_link . '</td>
          </tr>';
}
?>

<html>
  <head>
    <title>Top Secret backdropcms.org Sanitized Databases</title>
    <style>
      body {
        text-align: center;
      }
      table {
        border-collapse: collapse;
        margin: 30px auto;
        min-width: 800px;
      }
      thead {
        background: #ccc;
        text-transform: uppercase;
      }
      thead tr:hover {
        background: #ccc;
      }
      tr:nth-of-type(2n) {
        background: #f7f7f7;
      }
      tr.latest {
        border-top: 1px solid #ccc;
        font-weight: bold;
      }
      tr:hover {
        background: #e7e7e7;
      }
      th,
      td {
        padding: 0.5em 1em;
      }
    </style>
  </head>
  <body>
    <h1>Download sanitized backups of BackdropCMS.org websites</h1>
    <img src="https://backdropcms.org/themes/borg/images/drop-lounging.png"/>
    <table>
      <thead>
        <tr>
          <th>Website</th>
          <th>Date</th>
          <th>Download</th>
        </tr>
      </thead>
      <tbody>
        <?php table_rows(); ?>
      </tbody>
    </table>
  </body>
</html>
