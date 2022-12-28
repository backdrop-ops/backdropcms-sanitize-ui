<?php
/**
 * Get table rows for database, (optional) CiviCRM database, and files backups.
 */
function table_rows() {
  $paths = array(
    'backdropcms.org' => 'BackdropCMS',
    'docs.backdropcms.org' => 'Docs',
    'forum.backdropcms.org' => 'Forum',
    'events.backdropcms.org' => 'Events',
  );

  foreach ($paths as $path => $name) {
    $dbs = $dbs_civi = $files = array();
    exec("ls $path/sanitized/*.sql.gz", $dbs);
    exec("ls $path/sanitized_civi/*.sql.gz", $dbs_civi);
    exec("ls $path/files_backups/*.tar.gz", $files);
    $r_dbs = array_reverse($dbs);
    $r_dbs_civi = array_reverse($dbs_civi);
    $r_files = array_reverse($files);
    foreach ($r_dbs as $id => $db) {
      create_row($name, $db, (isset($r_dbs_civi[$id]) ? $r_dbs_civi[$id] : ''), $r_files[$id]);
    }
  }
}

/**
 * Print the HTML for a single table row.
 */
function create_row($name, $db_path, $db_civi_path, $file_path) {
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

  $host = filter_input(INPUT_SERVER, 'HTTP_HOST');
  $db_link = sprintf('<a href="https://%s/%s">Database</a>', $host, $db_path);
  $db_civi_link = sprintf('<a href="https://%s/%s">CiviCRM</a>', $host, $db_civi_path);
  $file_link = sprintf('<a href="https://%s/%s">Files</a>', $host, $file_path);

  print '<tr class="' . $class . '">
            <td>' . $name . '</td>
            <td>' . $date . '</td>
            <td>' . $db_link . ' :: ' . (!empty($db_civi_path) ? $db_civi_link . ' :: ' : '') . $file_link . '</td>
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
