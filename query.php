<?php
/* CSE 154
 * Spring 2019
 * original @author Jed Chen
 *
 * Creative Project 5 - Query
 * This program lets you try passwords to find keywords for the query, or query
 * the database tables.
 */

include("common.php");

// CORS enable for security issues (necessary for localhost); found on
// https://stackoverflow.com/questions/10883211/deadly-cors-when-http-localhost-is-the-origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Credentials: true');
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
}
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    exit(0);
}

// Main
if (isset($_POST["password"])) {
  tryPassword($_POST["password"]);
} else if (isset($_POST["query"])) {
  $params = array();
  // Prepare statement is not possible for my purposes (has '' around variables)
  // For example, SELECT '*' FROM 'Names'; -- this is bad syntax
  $labels = array("select", "from", "where", "orderby", "limit"); # limit
  $default = array("*", "Names", "1", "1", 3);
  for ($i = 0; $i < count($labels); $i++) {
    $label = $labels[$i];
    $value = isset($_POST[$label]) ? $_POST[$label] : $default[$i];
    // SQL injection precaution: take everything before the first space
    $index = strpos($value, " ");
    if ($index) {
      $value = substr($value, 0, $index);
    }
    array_push($params, $value);
  }
  query($params);
} else {
  sendError("Please POST a password or query.");
}

/**
 * Tries the given password, sending the key information if successful and an
 * error message otherwise.
 * JSON:
 * {
 *   "message" : <message-text>,
 *   "key" : <key-text>,
 *   "post" : <post-text>
 * }
 * @param {string} $password - password to try
 */
function tryPassword($password) {
  $filepath = "passwords/{$password}.txt";
  if (file_exists($filepath)) {
    $file = file($filepath, FILE_IGNORE_NEW_LINES);
    header("Content-type: json/application");
    echo json_encode(array("message" => $file[0],
      "key" => $file[1],
      "post" => $file[2]));
  } else {
    sendError("Not a password. Please try again.");
  }
}

/**
 * Tries the given password, sending the key information if successful and an
 * error message otherwise.
 * JSON:
 * {
 *   "rows" : <rows-num>,
 *   "columns" : [<attribute1>, <attribute2>, ...],
 *   <attribute1> : <attribute1-array>,
 *   <attribute2> : <attribute2-array>,
 *   ...
 * }
 * @param {string[]} $a - array of arguments
 */
function query($a) {
  try {
    $db = get_PDO();
    $rows = $db->query("SELECT {$a[0]} FROM {$a[1]} WHERE {$a[2]} ORDER BY {$a[3]} LIMIT {$a[4]};");
    $rows->setFetchMode(PDO::FETCH_ASSOC);
    $result = array();
    $labels = array();
    $count = 0;
    $first_row = $rows->fetch();
    $count++;
    if (!is_array($first_row)) {
      sendError("No values found.");
    } else {
      foreach ($first_row as $label => $value) {
        array_push($labels, $label);
        $result[$label] = array($value);
      }
      $result["columns"] = $labels;
      foreach ($rows as $row) {
        $count++;
        foreach ($labels as $label) {
          array_push($result[$label], $row[$label]);
        }
      }
      $result["rows"] = $count;
      header("Content-type: json/application");
      echo json_encode($result);
    }
  } catch (PDOException $pdoex) {
    sendError("Query failed.");
  }
}

/**
 * Sends the message back as a plain text 400 error.
 * @param {string} $message - error message to display
 */
function sendError($message) {
  header("HTTP/1.1 400 Invalid Request");
  header("Content-type: text/plain");
  echo $message;
}

?>
