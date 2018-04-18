<?php
/**
 * yottanote.com CMS
 *
 * SQLの定義
 * <br>
 * 各ドメイン共通設定
 *
 * @author yottanote.com
 * @since 2014/09/11
 * @version 1.0 
 * @date 2014/09/11
 * 
 * copyright yottanote.com
 */

/**
* sql検索を読み込む
*　
 * @since 2014/09/11
 * @version 1.0 
*/
include("sql_select.php");

/**
* mysql接続情報が記述されたファイルを取得する
*　
*　@var string サーバファイル名
*　
 * @since 2014/09/11
 * @version 1.0 
*/
$dbinfo = dirname(__FILE__)."/dbdata.php";
//print $dbinfo;

/**
* mysql接続情報が記述されたファイル名を読み込む
*　
*　@var string サーバファイル名
*　
 * @since 2014/09/11
 * @version 1.0 
*/
require_once($dbinfo);
//include($dbinfo);

/**
* mysql接続情報を読み込む
*　
*　@var instance mysql接続情報
*　
*　@todo クラス型でこの変数が使われていない。
*　
 * @since 2014/09/11
 * @version 1.0
*/
$mysqli = new mysqli($dbhostname, $dbuser, $dbpass, $dbname);

/**
* mysqlで検索を実行する
*　<br>
*　件数は取得しない
*　
 * @param string　$sqlData SQL文
 * @retuen array 検索結果 
*　
 * @since 2014/09/20
 * @version 1.1
*/
function sqlSelectExe($sqlData){

	global $dbhostname;
	global $dbuser;
	global $dbpass;
	global $dbname;

	//データベースに接続します。
	$linkDb = @mysqli_connect($dbhostname,$dbuser,$dbpass,$dbname) or die("データベースの接続に失敗しました。 Failed to connect to the database.");
	//文字コードをセットします。
	mysqli_set_charset($linkDb,"utf8");
	//データベースにデータを投げます
	$result = @mysqli_query($linkDb,$sqlData) or die(mysqli_error($linkDb)) ;
	//データベースの接続を切断します　。
	mysqli_close($linkDb);

	return $result;
}

//SQL一覧用データ投入。件数取得機能付き
/**
* mysqlで検索を実行する
*　<br>
*　件数も取得する
*　
 * @param string　$sqlData SQL文
 * @retuen array 検索結果と件数 
*　
 * @since 2014/09/20
 * @version 1.1
*/
function sqlSelectListExe($sqlData){

	global $dbhostname;
	global $dbuser;
	global $dbpass;
	global $dbname;

	//データベースに接続します。
	$linkDb = @mysqli_connect($dbhostname,$dbuser,$dbpass,$dbname) or die("データベースの接続に失敗しました。 Failed to connect to the database.");
	//文字コードをセットします。
	mysqli_set_charset($linkDb,"utf8");
	//データベースに一覧用データを投げます
//	$result = mysqli_query($linkDb,$sqlData) or die(mysqli_error($linkDb)) ;
	$result[0] = @mysqli_query($linkDb,$sqlData) or die(mysqli_error($linkDb)) ;

	//データベースに件数取得用データを投げます
	$result[1] = @mysqli_query($linkDb,"SELECT FOUND_ROWS() ") or die(mysqli_error($linkDb)) ;
	//データベースの接続を切断します　。
	mysqli_close($linkDb);

	return $result;
}

?>