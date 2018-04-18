<?php
/**
 * yottanote.com CMS 
 *
 * SQLの接続情報
 * <br>
 * ヨタノートの独自設定あり
 *
 * @author yottanote.com
 * @since 2014/09/11
 * @version 1.0
 * @date 2014/09/11
 *
 * copyright yottanote.com
 */

//本番サーバ用の設定
if($productionServer){
	$dbhostname = "xxx.xxx.xxx";
	$dbuser = "xxxxx";
	$dbpass = "xxxxx";
	$dbname = "xxxxx";
//テストサーバ用の設定
}else{
	$dbhostname = "localhost";
	$dbuser = "root";
	$dbpass = "xxxxx";
	$dbname = "xxxxx";
}
?>
