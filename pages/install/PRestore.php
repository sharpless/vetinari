<?php
// -------------------------------------------------------------------------------------------
//
// PRestore.php
// Restore/install data
// Code by mos
//


// -------------------------------------------------------------------------------------------
//
// Page specific code
//


// -------------------------------------------------------------------------------------------
//
// Create a new database object, we are using the MySQLi-extension.
//
require_once('./config.php');

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
$mysqli->set_charset('utf8');
if (mysqli_connect_error()) {
   echo "Connect failed: ".mysqli_connect_error()."<br>";
   exit();
}


// -------------------------------------------------------------------------------------------
//
// Prepare and perform a SQL query.
//
$tablePosts = DB_PREFIX . 'posts';
$tableUsers = DB_PREFIX . 'users';
$tableComments = DB_PREFIX . 'comments';
$tableCategories = DB_PREFIX . 'categories';
$tablePostCategories = DB_PREFIX . 'post_categories';

$query = <<<EOD
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';;

DROP TABLE IF EXISTS `{$tableUsers}` ;;

CREATE  TABLE IF NOT EXISTS `{$tableUsers}` (
  `user_id` INT NOT NULL AUTO_INCREMENT ,
  `user_login` VARCHAR(45) NOT NULL ,
  `user_password` VARCHAR(64) NOT NULL ,
  `user_name` VARCHAR(80) NOT NULL ,
  PRIMARY KEY (`user_id`) )
ENGINE=InnoDB;;

DROP TABLE IF EXISTS `{$tablePosts}` ;;

CREATE  TABLE IF NOT EXISTS `{$tablePosts}` (
  `post_id` INT NOT NULL AUTO_INCREMENT ,
  `post_author` INT NOT NULL ,
  `post_content` LONGTEXT NOT NULL ,
  `post_title` TEXT NOT NULL ,
  `post_date` DATETIME NOT NULL ,
  `post_modified_date` DATETIME NOT NULL ,
  PRIMARY KEY (`post_id`) )
ENGINE=InnoDB;;

DROP TABLE IF EXISTS `{$tableComments}` ;;

CREATE  TABLE IF NOT EXISTS `{$tableComments}` (
  `comment_id` INT NOT NULL AUTO_INCREMENT ,
  `comment_post_id` INT NOT NULL ,
  `comment_author` TINYTEXT NOT NULL ,
  `comment_content` TEXT NOT NULL ,
  `comment_title` TINYTEXT NOT NULL ,
  `comment_email` VARCHAR(80) NOT NULL,
  `comment_date` DATETIME NOT NULL,
  PRIMARY KEY (`comment_id`) )
ENGINE=InnoDB;;
DROP TABLE IF EXISTS `{$tableCategories}` ;;

CREATE  TABLE IF NOT EXISTS `{$tableCategories}` (
  `category_id` INT NOT NULL AUTO_INCREMENT ,
  `category_name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_name` (`category_name`) )
ENGINE = InnoDB;;

DROP TABLE IF EXISTS `{$tablePostCategories}` ;;

CREATE  TABLE IF NOT EXISTS `{$tablePostCategories}` (
  `pc_id` INT NOT NULL AUTO_INCREMENT ,
  `pc_post_id` INT NOT NULL ,
  `pc_category_id` INT NOT NULL ,
  PRIMARY KEY (`pc_id`) )
ENGINE = InnoDB;;

SET SQL_MODE=@OLD_SQL_MODE;;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;;
EOD;

// -------------------------------------------------------------------------------------------
//
// Execute query as several queries
//
$queries = explode(';;', $query);

foreach($queries as $val) {
if(empty($val)) break;
$res = $mysqli->query($val);
$statements += (empty($res) ? 0 : 1);
}

// -------------------------------------------------------------------------------------------
//
// Close the connection to the database
//

$mysqli->close();

// -------------------------------------------------------------------------------------------
//
// Go to start page
//

header("Location: " . WS_SITELINK);
?>
