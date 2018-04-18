<?php
/**
 * yottanote.com CMS
 *
 * 外部サイトへの接続情報
 * <br>
 * ヨタノートの独自設定あり
 *
 * @author yottanote.com
 * @date 2017/11/07
 * @since 2014/09/11
 * @version 1.3
 *
 * @author yottanote.com
 */

/*
 * Facrbookいいねボタン
 *
 * @param string　$fileAddress　ファイルのアドレス
 * @retuen string Facrbookいいねボタンをhtmlタグで返す。
 *
 * @since 2016/02/21
 * @version 1.0
 *
 */
function facebookLikeButton($fileAddress){

	//本番サーバとテストサーバとの区別
	global $productionServer;

	//if($productionServer){

	//Facrbookいいねボタンcode
	$facebookLikeButtonCode1 ="
		";

	$facebookLikeButtonCode2 ="
		";

	$facebookLikeButton = $facebookLikeButtonCode1.$facebookLikeButtonCode2;

	//TESTサイト
	//}else{
	//	$facebookLikeButton = "test";
	//}

	return $facebookLikeButton;

}

/*
 * goolgeフッター右側広告テキスト併用
*
* @param array　$addressDataArray　ページアドレス情報の配列
* @retuen string フッター右側のgoolge広告をhtmlタグで返す。
*
* @since 2014/11/07
* @version 1.0
*
*/
function googleAdFooterRightMain($addressDataArray){

	//本番サーバとテストサーバとの区別
	global $productionServer;

	if($productionServer){
		//googleフッター広告
		//規約により1ページ3個までのようなので休止
		$googleAdFooterRightMain = "";
		//規約により1ページ3個までのようなので休止
				
	}else{
		$googleAdFooterRightMain = "";
	}

	return $googleAdFooterRightMain;

}

/*
 * goolge左側広告テキスト併用
*
* @param array　$addressDataArray　ページアドレス情報の配列
* @retuen string 左側のgoolge広告をhtmlタグで返す。
*
* @since 2014/11/07
* @version 1.0
*
*/
function googleAdLeftContentsWideDisplayText($addressDataArray){

	//本番サーバとテストサーバとの区別
	global $productionServer;

	if($productionServer){
		//google左側広告テキスト併用
		$googleAdLeftContentsWideDisplayText ="
		";
	}else{
		$googleAdLeftContentsWideDisplayText = "<img src=\"".linkLevel($addressDataArray[0])."images/ad/googlead120x600.GIF\" alt=\"ADtest120x600\">\n";
	}

	return $googleAdLeftContentsWideDisplayText;

}

/*
 * goolge左側広告テキスト専用
*
* @param array　$addressDataArray　ページアドレス情報の配列
* @retuen string 左側のgoolgeテキスト広告をhtmlタグで返す。
*
* @since 2014/11/07
* @version 1.0
*
*/
function googleAdLeftContentsWideDisplay($addressDataArray){

	//本番サーバとテストサーバとの区別
	global $productionServer;

	if($productionServer){

		//google左側広告ディスプレイのみ
		$googleAdLeftContentsWideDisplay ="";
		//規約により1ページ3個までのようなので休止

	}else{
		$googleAdLeftContentsWideDisplay = "<img src=\"".linkLevel($addressDataArray[0])."images/ad/googlead120x600.GIF\" alt=\"ADtest120x600\">\n";
	}

	return $googleAdLeftContentsWideDisplay;

}

/*
 * goolge右側広告テキスト併用
*
* @param array　$addressDataArray　ページアドレス情報の配列
* @retuen string 右側のgoolge広告をhtmlタグで返す。
*
* @since 2014/11/06
* @version 1.0
*
*/
function googleAdRightContentsWideDisplayText($addressDataArray){

	//本番サーバとテストサーバとの区別
	global $productionServer;

	if($productionServer){
		//goolge右側広告テキスト併用
		";	
	}else{
		$googleAdRightContentsWideDisplayText = "<img src=\"".linkLevel($addressDataArray[0])."images/ad/googlead300x600.GIF\" alt=\"ADtest300x600\">\n";
	}

	return $googleAdRightContentsWideDisplayText;

}



/*
 * goolgeスマートフォンバナー広告テキスト併用
*
* @param array　$addressDataArray　ページアドレス情報の配列
* @retuen string goolgeスマートフォンレバナー広告をhtmlタグで返す。
*
* @since 2014/11/07
* @version 1.0
*
*/
function googleAdSMPBannerDisplayText($addressDataArray){

	//本番サーバとテストサーバとの区別
	global $productionServer;

	if($productionServer){
		//goolge右側広告テキスト併用
		$googleAdSMPBannerDisplayText = "
		";

	}else{
		$googleAdSMPBannerDisplayText = "<img src=\"".linkLevel($addressDataArray[0])."images/ad/googlead320x100.GIF\" alt=\"ADtest320x100\">\n";
	}

	return $googleAdSMPBannerDisplayText;

}

/*
 * goolgeスマートフォンレクタングル広告テキスト併用
*
* @param array　$addressDataArray　ページアドレス情報の配列
* @retuen string goolgeスマートフォンレクタングル広告をhtmlタグで返す。
*
* @since 2014/11/07
* @version 1.0
*
*/
function googleAdSMPRectangleDisplayText($addressDataArray){

	//本番サーバとテストサーバとの区別
	global $productionServer;

	if($productionServer){
		//goolgeスマートフォンレクタングル広告テキスト併用
		$googleAdSMPRectangleDisplayText = "
		";
	}else{
		$googleAdSMPRectangleDisplayText = "<img src=\"".linkLevel($addressDataArray[0])."images/ad/googlead320x250.GIF\" alt=\"ADtest320x250\">\n";
	}

	return $googleAdSMPRectangleDisplayText;

}

/*
 * googleタイトル右側
*
* @param array　$addressDataArray　ページアドレス情報の配列
* @retuen string タイトル右側のgoolge広告をhtmlタグで返す。
*
* @since 2014/11/06
* @version 1.0
*
*/
function googleAdTitleRightMain($addressDataArray){

	//本番サーバとテストサーバとの区別
	global $productionServer;

	if($productionServer){
		//googleタイトル右側広告
		$googleAdTitleRightMain = "
		";
	}else{
		$googleAdTitleRightMain = "<img src=\"".linkLevel($addressDataArray[0])."images/ad/test.GIF\" alt=\"ADtest\">\n";
	}

	return $googleAdTitleRightMain;

}

/*
 * google検索エンジン
*
* @param array　$addressDataArray　ページアドレス情報の配列
* @retuen string goolge検索エンジンをhtmlタグで返す。
*
 * @date 2017/03/22
* @since 2014/11/07
* @version 1.2
*
*/
function googleSearchEngine(){
	//言語コード
	global $langCode;
	if($langCode == "ja"){

		$googleSearchEngine = "
		";

	}else if($langCode == "en"){

		$googleSearchEngine = "
		";

	}else if($langCode == "ru"){

		$googleSearchEngine = "
		";

	}else{

		$googleSearchEngine = "
		";

	}

	return $googleSearchEngine;

}


/*
 * ツイッター埋め込みタイムライン
*
* @param array　$addressDataArray　ページアドレス情報の配列
* @retuen string ツイッター埋め込みタイムラインをhtmlタグで返す。
*
* @since 2014/11/07
* @version 1.0
*
*/
function twitterEmbeddedTimelines($addressDataArray){

	//本番サーバとテストサーバとの区別
	global $productionServer;

	if($productionServer){

	//ツィッターパーツ
		$twitterYottanote = "
		";
		$twitterEmbeddedTimelines = $twitterYottanote;

		//TEST広告
	}else{
		$twitterEmbeddedTimelines = "<img src=\"".linkLevel($addressDataArray[0])."images/ad/twitter180x500.GIF\" alt=\"twittertest\">\n";
	}

	return $twitterEmbeddedTimelines;

}

/**
 * 本番サーバかテストサーバかの設定
 *　
 * @date 2017/11/07
 * @since 2014/09/11
 * @version 1.2
 */
if($productionServer){

	/*
	 ブログ村
	*/
	//IT技術メモ
	$blogmuraITtechniqueMemoExternalLink = "
	";
	
	/*
	google
	 */
	
	
	//GoogleAnalytics
	
	$googleAnalytics = "
					";
	
	
	
	//google広告
	$googleAdFooterRightMain = "";
	$googleAdLeftContentsWideDisplay ="";
	//google左側広告テキスト併用
	$googleAdLeftContentsWideDisplayText ="
	";

	//goolge右側広告テキスト併用
	$googleAdRightContentsWideDisplayText = "
	";	
	//googleタイトル右側広告
	$googleAdTitleRightMain = "
	";

				
	$googleSearchEngine = "
						";				
			
	//ツィッターパーツ
	$twitterYottanote = "
			";

	$twitterUserResponse ="
	";


}else{

	/*
	 ブログ村
	*/
	//IT技術メモ
	$blogmuraITtechniqueMemoExternalLink = "";
	
	
	//GoogleAnalytics
	$googleAnalytics = "";
	
	//google広告
	$googleAdFooterRightMain = "";
	$googleAdLeftContentsWideDisplay ="";
	$googleAdLeftContentsWideDisplayText ="";
	$googleAdRightContentsWideDisplayText = "";	
	$googleAdTitleRightMain = "";

	
	$googleSearchEngine = "";

	//ツィッターパーツ
	$twitterYottanote = "";

	$twitterUserResponse ="
	";
	

}

?>