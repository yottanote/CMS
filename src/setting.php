<?php
/**
 * yottanote.com CMS 
 *
 * SQLの接続情報
 * <br>
 * 各ドメイン共通設定
 * 
 * 状況に応じて設定を変更する関数
 * <br>
 * 本番サーバーアップ時はバックアップをとること
 * 
 * @author yottanote.com
 * @since 2014/09/11
 * @version 1.0 
 * @date 2014/09/11
 * 
 * copyright yottanote.com
 */

/**
* 本番サーバかテストサーバかの設定
*　
*　@var boolean 本番サーバか否か
*　
 * @since 2014/09/14
 * @version 1.0 
*/
//本番サーバ
//$productionServer = true;
//テストサーバ
$productionServer = false;

/**
* 多言語対応か否かを設定する。
*　
*　@var boolean 多言語対応か否か
*　
 *　@deprecated 多言語本格対応のため削除
 * @since 2014/09/10
 * @version 1.0
*/
//$multiLangMode = true;
$multiLangMode = false;

/**
* メインコンテンツに表示されるコンテンツアイテムの一覧表示上限
*　
*　@var boolean 一覧表示上限
*　
 * @since 2014/09/11
 * @version 1.0 
*/
$listMax = 10;

/**
* レフトもしくはライトコンテンツに表示されるコンテンツアイテムの一覧表示上限
*　
*　@var boolean $listmenuMax　一覧表示上限
*　@var boolean $listmenuMax　一覧表示画像上限
*　@var boolean $listmenuMax　一覧表示画像上限スマートフォン版
*　@var boolean $listmenuMax　一覧表示index用画像上限
*　
 * @since 2014/09/11
 * @version 1.0 
*/
$listmenuMax = 20;
$listmenuImagesMax = 10;
$listmenuImagesMaxSMP = 5;
$listmenuImagesIndexMax = 18;

/**
* 関連記事の一覧表示上限
*　
*　@var boolean 関連記事の一覧表示上限
*　@var boolean $listmenuMax　一覧表示画像上限
*　@var boolean $listmenuMax　一覧表示index用画像上限
*　
 * @since 2014/09/11
 * @version 1.0 
*/
$listrelatedMax = 20;

/**
* 日付を設定する
*　
 * @since 2014/09/11
 * @version 1.0 
*/
date_default_timezone_set('Asia/Tokyo');

/**
* 本番サーバかテストサーバかによって設定を切り替える
*　
 * @since 2014/09/10
 * @version 1.0 
*/
if($productionServer == true){

	//ファイル拡張子 PHPファイルとhtmlファイルの切り替え
	$fileExtension = ".html";
	
	//PHPがModule動作かCGI動作かを設定する
	//cgi動作
	$phpModule = false;
}else{

	//ファイル拡張子 PHPファイルとhtmlファイルの切り替え
	//$fileExtension = ".php";
	$fileExtension = ".html";
	
	//PHPがModule動作かCGI動作かを設定する
	//Module動作
	$phpModule = true;
}

?>