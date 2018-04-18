<?php
/**
 * yottanote.com CMS
 *
 * 変数の設定
 * <br>
 * ヨタノートの独自設定あり
 *
 * @author yottanote.com
 * @date 2017/11/07
 * @since 2014/09/11
 * @version 1.6
 *
 * @author yottanote.com
 */

/**
/**
 * 初期設定
 *　
 *　@var array $addressDataArray ページ情報
 *　@var array $archivesCategoryDirectory アーカイブスカテゴリディレクトリ　サイト構成が大幅に変更された場合に使う。
 *　@var array $categoryDataArray カテゴリ情報
 *　@var string $contentsCategoryId コンテンツカテゴリID
 *　@var array $contentsItemDataArray コンテンツアイテム情報
 *　@var string $contentsItemId コンテンツアイテムID
 *　@var boolean $contentsItemPage コンテンツアイテムのページか否か。一覧ならfalse。コンテンツアイテムのページならtrue
 *　@var string $fileAddress ファイルのアドレス
 *　@var array $listCountArray 一覧表示数
 *　@var array $titleDataArray タイトル
 *　@var string $viewMode 画面表示のモード。初期値はパソコン。
 *　
 * @date 2016/02/21
 * @since 2014/09/11
 * @version 1.2
*/
$addressDataArray = NULL;
$archivesCategoryDirectory = "contents/";
$categoryDataArray = NULL;
$contentsCategoryId = 0;
$contentsItemDataArray = NULL;
$contentsItemId = 0;
$contentsItemPage = false;
$fileAddress = "";
$listCountArray;
$titleDataArray;
$viewMode = "PC";

/**
 * コンテンツ間のhr
 *　
 *　@var string コンテンツ間のhrhtmlタグ
 *　
 *
 * @since 2014/09/11
 * @version 1.0
 */
$betweenContents  = "<span class=\"betweenContents\">\n";
$betweenContents .= "	<hr>\n";
$betweenContents .= "</span>\n";

/**
 * コンテンツ間のhr
 *　
 *　@return string $betweenContents コンテンツ間のhrhtmlタグ
 *
 * @since 2014/11/05
 * @version 1.0
 */
function betweenContents(){

	$betweenContents  = "<span class=\"betweenContents\">\n";
	$betweenContents .= "	<hr>\n";
	$betweenContents .= "</span>\n";

	return $betweenContents;
}

/**
 * 著作権表示
 *　
 *　@var string 著作権表示のhrhtmlタグ
 *　
 *
 * @since 2014/09/11
 * @version 1.0
 */
$copyright = "Copyright &copy; 2012-" . date("Y") ." yottanote.com All rights reserved.";

/**
 * DOCTYPE宣言
 *　
 *　@var string DOCTYPE宣言htmlタグ
 *　
 *
 * @since 2014/09/11
 * @version 1.0
 */
$documentType = "";

/**
 * ドメインアドレス
 *　
 *　@var string ドメインアドレス
 *　
 *
 * @date 2017/11/07
 * @since 2017/03/22
 * @version 1.1
 */
$domainAddress = "https://www.yottanote.com/";

/**
 * 電子メールアドレス
 *　
 *　@var string 電子メールhtmlタグ
 *　
 *
 * @since 2014/09/20
 * @version 1.0
 */
$eMailAddress = "information%40yottanote%2Ecom";

/**
 * 最終更新
 *　
 *　@var string 最終更新htmlタグ
 *　
 *
 * @since 2014/09/15
 * @version 1.1
 */
$lastModified  = "<div class=\"modified\">\n";
$lastModified .= "	<span class=\"modified\">\n";
$lastModified .= "		last modified ". multiLangDate(getlastmod())."\n";
$lastModified .= "	</span>\n";
$lastModified .= "</div>\n";

/**
 * ロゴ画像用
 *　
 *　@var string $logoAltString ロゴ画像用文字最終更新htmlタグ
 *　@var string $logoTitleString ロゴのタイトル
 *
 * @since 2014/09/15
 * @version 1.1
 */
$logoAltString = "ヨタノート";
$logoTitleString = "Yottanote.com(ヨタノート)";


/**
 * meta文
 *　
 *　@var string meta文htmlタグ
 *　
 *
* @date 2017/11/07
 * @since 2014/11/06
 * @version 1.4
 */
function metaTag(){

	//ページ情報
	global $addressDataArray;
	//本番サーバとテストサーバとの区別
	global $productionServer;
	//GoogleAnalytics
	global $googleAnalytics;

	$metaTag  = $googleAnalytics."\n";
	if($productionServer){
		$metaTag .= "<link rel=\"icon\" href=\"https://www.yottanote.com/favicon.ico\" type=\"image/vnd.microsoft.icon\">\n";
		$metaTag .= "<link rel=\"shortcut icon\" href=\"https://www.yottanote.com/favicon.ico\" type=\"image/vnd.microsoft.icon\">\n";
	//テストサーバ用の設定
	}else{
		$metaTag .= "<link rel=\"icon\" href=\"favicon.ico\" type=\"image/vnd.microsoft.icon\">\n";
		$metaTag .= "<link rel=\"shortcut icon\" href=\"favicon.ico\" type=\"image/vnd.microsoft.icon\">\n";
	}
	
	//syntaxhighlighter
	$metaTag .= "<script type=\"text/javascript\" src=\"".linkLevel($addressDataArray[0])."js/syntaxhighlighter/scripts/shCore.js\"></script>\n";
	$metaTag .= "<script type=\"text/javascript\" src=\"".linkLevel($addressDataArray[0])."js/syntaxhighlighter/scripts/shBrushPlain.js\"></script>\n";
	$metaTag .= "<script type=\"text/javascript\" src=\"".linkLevel($addressDataArray[0])."js/syntaxhighlighter/scripts/shBrushBash.js\"></script>\n";
	//$metaTag .= "<script type=\"text/javascript\" src=\"".linkLevel($addressDataArray[0])."js/syntaxhighlighter/scripts/shAutoloader.js\"></script>\n";
	$metaTag .= "<link rel=\"stylesheet\" href=\"".linkLevel($addressDataArray[0])."css/syntaxhighlighter/styles/shCore.css\" type=\"text/css\">\n";
	$metaTag .= "<link rel=\"stylesheet\" href=\"".linkLevel($addressDataArray[0])."css/syntaxhighlighter/styles/shThemeDefault.css\" type=\"text/css\">\n";
	$metaTag .= "<script type=\"text/javascript\" src=\"".linkLevel($addressDataArray[0])."js/syntaxhighlighter.js\"></script>\n";

	return $metaTag;
}


/**
 * カテゴリデータありません文
 *　
 *　@var string notFoundCategoryData文htmlタグ
 *　
 *
 * @since 2014/09/19
 * @version 1.0
 */
$notFoundCategoryDataString = "カテゴリーデータがありません。　Not Found Category Data.";


/**
 * データありません文
 *　
 *　@var string notFoundData文htmlタグ
 *　
 *
 * @since 2014/09/11
 * @version 1.0
 */
$notFoundDataString = "データがありません。<br>Not Found Data.";

/**
 * タイトルがありません文
 *　
 *　@var string notFoundTitleString文htmlタグ
 *　
 *
 * @since 2014/09/19
 * @version 1.0
 */
$notFoundTitleString = "タイトルが存在しません。 Not Found The Title.";


/**
 * PageTopに戻る文字列
 *　
 *　@return string $backPagetopString PageTopに戻るリンクhtmlタグ
 *　
 *
 * @todo 多言語設定
 * @date 2017/03/22
 * @since 2014/09/15
 * @version 1.1
 */
function backPagetopString(){

	//言語コード
	global $langCode;

	if($langCode == "ja"){
		$backPagetopString = "▲トップページへ戻る";
	}else if ($langCode == "en"){
		$backPagetopString = "▲Return to Top of Page";
	}else if ($langCode == "ru"){
		$backPagetopString = "▲Вернуться в начало страницы";
	}else{
		$backPagetopString = "▲トップページへ戻る(Return to Top of Page)";
	}

	return $backPagetopString;

}

/**
 * PageTopに戻るリンク
 *　
 *　@return string PageTopに戻るリンクhtmlタグ
 *　
 *
 * @todo 多言語設定
 * @since 2014/09/15
 * @version 1.1
 */
function backPagetop(){

	//言語コード
	global $langCode;

	$backPagetop  = "<span class=\"backPagetop\">\n";
	$backPagetop .= "	<a href=\"#pagetop\">\n";
	$backPagetop .= 	backPagetopString()."\n";
	$backPagetop .= "	</a>\n";
	$backPagetop .= "</span>\n";

	return $backPagetop;
}

/**
 * PageTopに戻るリンクfooter用
 *　
 *　@var string PageTopに戻るリンクfooter用htmlタグ
 *　
 *
 * @todo 多言語設定
 * @since 2014/09/15
 * @version 1.1
 */
function backPagetopFooter(){

	//言語コード
	global $langCode;

	$backPagetopFooter  = "<div>\n";
	$backPagetopFooter .= "	<a href=\"#pagetop\">\n";
	$backPagetopFooter .= backPagetopString()."\n";
	$backPagetopFooter .= "	</a>\n";
	$backPagetopFooter .= "</div>\n";
	

	return $backPagetopFooter;
}

/**
 * ドメインタイトル
 *　
 *　
 * @date 2017/03/22
 * @since 2014/09/15
 * @version 1.2
 */
function domainTitle(){

	//言語コード
	global $langCode;
	//ドメインタイトル
	global $domainTitle;

	if($langCode == "ja"){
		$domainTitle = "ヨタノート";
	}else if ($langCode == "en"){
		$domainTitle = "Yottanote.com";
	}else if ($langCode == "ru"){
		$domainTitle = "Yottanote.com";
	}else{
		$domainTitle = "Yottanote.com(ヨタノート)";
	}

}

/**
 * ドメインタイトル配列
 *　
 *　
 * @since 2015/07/06
 * @version 1.0
 */
function domainTitleArray(){

	$domainTitleArray[1] = "ヨタノート";
	$domainTitleArray[2] = "KYottanote.com";
	$domainTitleArray[3] = "Yottanote.com";

	return $domainTitleArray;
}


/**
 * ホームページのようこそコメント
 *　
 *　@return string $domainWelcome ホームページのようこそコメント
 *　
 * @date 2017/03/22
 * @since 2014/09/11
 * @version 1.1
 */
function domainWelcome(){

	//言語コード
	global $langCode;

	if($langCode == "ja"){
		$domainWelcome = "Welcome to the Yottanote.com!";
	}else if ($langCode == "en"){
		$domainWelcome = "Welcome to the Yottanote.com!";
	}else if ($langCode == "ru"){
		$domainWelcome = "Welcome to the Yottanote.com!";
	}else{
		$domainWelcome = "Welcome to the Yottanote.com!";
	}

	return $domainWelcome;
}

/**
* 電子メール文言
*　
*　@return array $eMailArrry メールアドレスと電子メール文言
 * 
 * @date 2017/03/22
 * @since 2014/09/17
 * @version 1.2
*/
function eMailString(){

	//言語コード
	global $langCode;
	
	if($langCode == "ja"){
		$eMailString = "電子メール";
	}else if ($langCode == "en"){
		$eMailString = "E-Mail";
	}else if ($langCode == "ru"){
		$eMailString = "E-Mail";
	}else{
		$eMailString = "電子メール(E-Mail)";
	}

	return $eMailString;

}

/**
 * 外部リンク文言
 *　
 *　@return string $externalLinkString 外部リンク文言
 *
 * @date 2017/03/22
 * @since 2014/09/17
 * @version 1.1
 */
function externalLinkString(){

	//言語コード
	global $langCode;

	if($langCode == "ja"){
		$externalLinkString = "外部リンク";
	}else if ($langCode == "en"){
		$externalLinkString = "External Link";
	}else if ($langCode == "ru"){
		$externalLinkString = "Внешняя ссылка";
	}else{
		$externalLinkString = "外部リンク(External Link)";
	}

	return $externalLinkString;

}

/**
* 一覧文言
*　
*　@return array $listStringArray 一覧文言配列
 * 
 * @since 2015/07/06
 * @version 1.2
*/
function listStringArray(){

	$listStringArray[1] = "一覧";
	$listStringArray[2] = "List";
	$listStringArray[3] = "список";

	return $listStringArray;

}

/**
 * ロゴ管理人文字列
 *　
 *　@return string $externalLinkString ロゴ管理人文字列
 *
 * @date 2017/07/24
 * @since 2014/09/19
 * @version 1.2
 */
function logoManagedByString(){

	//言語コード
	global $langCode;

	if($langCode == "ja"){
		$logoManagedByString = "Managed by NK";
	}else if ($langCode == "en"){
		$logoManagedByString = "Managed by NK";
	}else if ($langCode == "ru"){
		$logoManagedByString = "Managed by NK";
	}else{
		$logoManagedByString = "Managed by NK";
	}

	return $logoManagedByString;

}

/**
 * メタタグキーワード
 *　
 *　@return string $metaTagKeyword メタタグキーワード
 *　
 * @date 2017/03/22 
 * @since 2014/09/15
 * @version 1.3
 */
function metaTagKeyword(){

	//言語コード
	global $langCode;

	if($langCode == "ja"){
		$metaTagKeyword = "ヨタノート,ヨッタノート,yotta,yottanote,";
	}else if ($langCode == "en"){
		$metaTagKeyword = "ヨタノート,ヨッタノート,yotta,yottanote,";
	}else if ($langCode == "ru"){
		$metaTagKeyword = "ヨタノート,ヨッタノート,yotta,yottanote,";
	}else{
		$metaTagKeyword = "ヨタノート,ヨッタノート,yotta,yottanote,";
	}

	return $metaTagKeyword;
}

/**
 * 次の記事文字列
 *　
 *　@return string $nextContentsString 次の記事文字列
 *　
 * @date 2017/03/22
 * @since 2015/09/15
 * @version 1.1
 */
function nextContentsString(){

	//言語コード
	global $langCode;

	if($langCode == "ja"){
		$nextContentsString = "次の記事";
	}else if ($langCode == "en"){
		$nextContentsString = "Next contents";
	}else if ($langCode == "ru"){
		$nextContentsString = "Next статьи";
	}else{
		$nextContentsString = "次の記事(Next contents)";
	}

	return $nextContentsString;

}

/**
 * 一覧次ページ文字列
 *　
 *　@return string $nextPageString 一覧次ページ文字列
 *
 * @date 2017/03/22
 * @since 2014/09/17
 * @version 1.1
 */
function nextPageString(){

	//言語コード
	global $langCode;

	if($langCode == "ja"){
		$nextPageString = "一覧次ページへ";
	}else if ($langCode == "en"){
		$nextPageString = "To Next Page";
	}else if ($langCode == "ru"){
		$nextPageString = "To Next Page";
	}else{
		$nextPageString = "一覧次ページへ(Next Page)";
	}

	return $nextPageString;

}

/**
 * 写真の文字列
 *　
 *　@return string $photoString 写真の文字列
 *
 * @date 2017/03/22
 * @since 2014/09/17
 * @version 1.1
 */
function photoString(){

	//言語コード
	global $langCode;

	if($langCode == "ja"){
		$photoString = "の写真";
	}else if ($langCode == "en"){
		$photoString = " Photo";
	}else if ($langCode == "ru"){
		$photoString = " Фото";
	}else{
		$photoString = "の写真";
	}

	return $photoString;

}

/**
 * 写真撮影日の文字列
 *　
 *　@return string $photoString 写真の文字列
 *
 * @date 2017/03/22
 * @since 2014/09/17
 * @version 1.1
 */
function photoDateString(){

	//言語コード
	global $langCode;

	if($langCode == "ja"){
		$photoDateString = "撮影日";
	}else if ($langCode == "en"){
		$photoDateString = "Photo Date";
	}else if ($langCode == "ru"){
		$photoDateString = "Фото Дата";
	}else{
		$photoDateString = "撮影日(Photo)";
	}

	return $photoDateString;

}

/**
 * 投稿日の文字列
 *　
 *　@return string $PostedDateString 投稿日の文字列
 *
 * @date 2017/03/22
 * @since 2014/09/17
 * @version 1.1
 */
function postedDateString(){

	//言語コード
	global $langCode;

	if($langCode == "ja"){
		$postedDateString = "投稿日";
	}else if ($langCode == "en"){
		$postedDateString = "Posted Date";
	}else if ($langCode == "ru"){
		$postedDateString = "Сообщение Дата";
	}else{
		$postedDateString = "投稿日(Posted)";
	}

	return $postedDateString;

}

/**
 * 前の記事文字列
 *　
 *　@return string $prevContentsString 前の別記事文字列
 *　
 * @date 2017/03/22
 * @since 2015/09/15
 * @version 1.1
 */
function prevContentsString(){

	//言語コード
	global $langCode;

	if($langCode == "ja"){
		$prevContentsString = "前の記事";
	}else if ($langCode == "en"){
		$prevContentsString = "Perv contents";
	}else if ($langCode == "ru"){
		$prevContentsString = "Prev статьи";
	}else{
		$prevContentsString = "前の記事(Perv contents)";
	}

	return $prevContentsString;

}

/**
 * 一覧前ページ文字列
 *　
 *　@return string $prevtPageString 一覧前ページ文字列
 *
 * @date 2017/03/22
 * @since 2014/09/17
 * @version 1.1
 */
function prevPageString(){

	//言語コード
	global $langCode;

	if($langCode == "ja"){
		$prevPageString = "一覧前ページへ";
	}else if ($langCode == "en"){
		$prevPageString = "To Prev Page";
	}else if ($langCode == "ru"){
		$prevPageString = "To Prev Page";
	}else{
		$prevPageString = "一覧前ページへ(Prev Page)";
	}

	return $prevPageString;

}

/**
 * 続きを読む
 *　
 *　@return string 続きを読むhtmlタグ
 *　
 * @date 2017/03/22
 * @since 2014/09/15
 * @version 1.2
 */
function readMore(){

	//言語コード
	global $langCode;

	if($langCode == "ja"){
		$readMore = "続きを読む&gt;&gt;";
	}else if ($langCode == "en"){
		$readMore = "Read more&gt;&gt;";
	}else if ($langCode == "ru"){
		$readMore = "Подробнее&gt;&gt;";
	}else{
		$readMore = "続きを読む(Read more)&gt;&gt;";
	}

	return $readMore;

}

/**
 * 関連記事文字列
 *　
 *　@return string $relatedContentsString 関連記事文字列
 *　
 * @date 2017/03/22
 * @since 2014/09/18
 * @version 1.1
 */
function relatedContentsString(){

	//言語コード
	global $langCode;

	if($langCode == "ja"){
		$relatedContentsString = "関連記事";
	}else if ($langCode == "en"){
		$relatedContentsString = "Related contents";
	}else if ($langCode == "ru"){
		$relatedContentsString = "Связанные статьи";
	}else{
		$relatedContentsString = "関連記事 (Related contents)";
	}

	return $relatedContentsString;

}

/**
 * 同一撮影日の別記事文字列
 *　
 *　@return string $samePhotoDateString 同一撮影日の別記事文字列
 *　
 * @date 2017/03/22
 * @since 2014/09/17
 * @version 1.1
 */
function samePhotoDateString(){

	//言語コード
	global $langCode;

	if($langCode == "ja"){
		$samePhotoDateString = "同一撮影日の別記事";
	}else if ($langCode == "en"){
		$samePhotoDateString = "Other contents at same photo date";
	}else if ($langCode == "ru"){
		$samePhotoDateString = "Другая статья в тоже день фото";
	}else{
		$samePhotoDateString = "同一撮影日の別記事(Other contents at same photo date)";
	}

	return $samePhotoDateString;

}


/**
 * 新着情報文字列
 *　
 *　@return string $whatsNewString 新着情報文字列
 *　
 * @date 2017/03/22
 * @since 2014/09/17
 * @version 1.1
 */
function whatsNewString(){

	//言語コード
	global $langCode;

	if($langCode == "ja"){
		$whatsNewString = "新着情報";
	}else if ($langCode == "en"){
		$whatsNewString = "What's New";
	}else if ($langCode == "ru"){
		$whatsNewString = "Что нового";
	}else{
		$whatsNewString = "新着情報(What's New)";
	}

	return $whatsNewString;

}

?>