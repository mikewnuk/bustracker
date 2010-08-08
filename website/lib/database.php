<?
function dbConnect() {
  $conn = mysql_pconnect(DBSERVER, DBUSER, DBPASSWORD) or die("failed connecting to db");
  mysql_select_db(DBNAME) or die("failed selecting database"); 
  return $conn; 
}

function dbQuery($sql, $conn) {
  $result =  mysql_query($sql, $conn) or die("BAD QUERY: $sql"); 
  return $result;
}

function dbClose($conn) {
  mysql_close($conn);
}

function dbLastInsertId($conn) {
  return mysql_insert_id($conn); 
}

function fetchsinglerow($sql) {
  $conn = dbConnect();
  $result = dbQuery($sql, $conn);
  $row = mysql_fetch_assoc($result);
  dbClose($conn); 
  return $row;
}

function fetcharray($sql) {
  $conn = dbConnect();
  $result = dbQuery($sql, $conn);
  $array = array();
  while ($row = mysql_fetch_assoc($result)) {  
    $array[] = $row;
  }
  dbClose($conn); 
  return $array;
}

function foreachrow($sql, $rowfunction) {
  $conn = dbConnect();
  $result = dbQuery($sql, $conn);
  while ($row = mysql_fetch_assoc($result)) {  
    call_user_func_array($rowfunction, array(&$row)); 
  }
  dbClose($conn); 
}

function getParam($type, $name) {
  $sql = "SELECT value from " . DBPREFIX . "settings WHERE `type`='$type' and name='$name'";
  return fetchsinglerow($sql);
}

function getParams($type) {
  $sql = "SELECT id, name, value from " . DBPREFIX . "settings WHERE `type`='$type'";
  return fetcharray($sql);
}
?>