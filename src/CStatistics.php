<?php
// -----------------------------
//
// Class CStatistics
// Some statistics
//
// ------------------------------
class CStatistics {

  public function  __construct() {
    ;
  }
  
  public function  __destruct() {
    ;
  }
  
  public function getPostCountPeriod($aPeriod) {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
    $mysqli->set_charset('latin1');
    if (mysqli_connect_error()) {
      echo "Connect failed: ".mysqli_connect_error()."<br>";
      exit();
    }
    $tablePosts = DB_PREFIX . 'posts';
    $date = new DateTime('now');
    $date->modify("-{$aPeriod}");
    $olddate = $date->format('Y-m-d');
    $queryDate = <<<EOD
SELECT COUNT(post_id) AS count
FROM {$tablePosts}
WHERE post_date > '{$olddate}'
EOD;
    $resDate = $mysqli->query($queryDate);
    $rowDate = $resDate->fetch_object();
    return $rowDate->count;
  }
}
?>
