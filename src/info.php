<?php
/**
 * yottanote.com CMS
 *  
 * infoカテゴリに関連した情報
 * <br>
 * ヨタノートの独自設定あり
 *
 * @author yottanote.com
 * @date 2017/07/24
 * @since 2014/09/19
 * @version 1.4
 * 
 */

/**
 * インフォコンテンツアイテムのタイトルを取得するためのクラス
 *　
 * @date 2015/09/24
 * @since 2014/09/15
 * @version 1.1
*/
class infoContentsItemTitleArray{

	private $menuArray;
	private $newsReleaseArray;
	private $managedByArray;
	private $linkArray;
	private $siteMapArray;
	private $webDesignArray;
	private $aboutLinkArray;
	private $attentionArray;
	private $eMailAddressArray;


	/*
	 * メニュー(info用)の配列取得
	 * 
	 * @retuen string メニュー(info用)の配列を返す。
	 * 
	 * @since 2015/07/06
	 * @version 1.0
	*/
	function getMenuArray(){
		return $this->menuArray;
	}

	/*
	 * メニュー(info用)の配列設定
	 * 
	 * @since 2015/07/06
	 * @version 1.0
	*/
	function setMenuArray(){

		$this->menuArray[1][0] = "インフォメーションメニュー";
		$this->menuArray[2][0] = "Information Menu";
		$this->menuArray[3][0] = "Информация Меню";

		$this->menuArray[1][1] = "";
		$this->menuArray[2][1] = "";
		$this->menuArray[3][1] = "";

		$this->menuArray[1][2] = "";
		$this->menuArray[2][2] = "";
		$this->menuArray[3][2] = "";


	}

	/*
	 * 新着情報の配列取得
	 * 
	 * @retuen Array 新着情報の配列を返す。
	 * 
	 * @since 2015/07/06
	 * @version 1.0
	*/
	function getNewsReleaseArray(){
		return $this->newsReleaseArray;
	}

	/*
	 * 新着情報の配列設定
	 * 
	 * @since 2015/07/06
	 * @version 1.0
	*/
	function setNewsReleaseArray(){

		$this->newsReleaseArray[1][0] = "ニュースリリース";
		$this->newsReleaseArray[2][0] = "News Release";
		$this->newsReleaseArray[3][0] = "Выпуск Новостей";

		$this->newsReleaseArray[1][1] = "";
		$this->newsReleaseArray[2][1] = "";
		$this->newsReleaseArray[3][1] = "";

		$this->newsReleaseArray[1][2] = "";
		$this->newsReleaseArray[2][2] = "";
		$this->newsReleaseArray[3][2] = "";

	}



	/*
	 * 管理人の配列取得
	 * 
	 * @retuen Array 管理人の配列を返す。
	 * 
	 * @date 2017/07/24
	 * @since 2015/07/06
	 * @version 1.1
	*/
	function getManagedByArray(){
		return $this->managedByArray;
	}

	/*
	 * 管理人の配列設定
	 * 
	 * @date 2017/07/24
	 * @since 2015/07/06
	 * @version 1.1
	*/
	function setmanagedByArray(){

		$this-> managedByArray[1][0] = "管理人";
		$this-> managedByArray[2][0] = "Managed by";
		$this-> managedByArray[3][0] = "Managed by";

		$this-> managedByArray[1][1] = "";
		$this-> managedByArray[2][1] = "";
		$this-> managedByArray[3][1] = "";

		$this-> managedByArray[1][2] = "";
		$this-> managedByArray[2][2] = "";
		$this-> managedByArray[3][2] = "";

	}

	/*
	 * リンクの配列取得
	 * 
	 * @retuen Array リンクの配列を返す。
	 * 
	 * @since 2015/07/06
	 * @version 1.0
	*/
	function getLinkArray(){
		return $this->linkArray;
	}

	/*
	 * リンクの配列設定
	 * 
	 * @since 2015/07/06
	 * @version 1.0
	*/
	function setLinkArray(){

		$this-> linkArray[1][0] = "リンク";
		$this-> linkArray[2][0] = "Link";
		$this-> linkArray[3][0] = "Ссылка";

		$this-> linkArray[1][1] = "";
		$this-> linkArray[2][1] = "";
		$this-> linkArray[3][1] = "";

		$this-> linkArray[1][2] = "";
		$this-> linkArray[2][2] = "";
		$this-> linkArray[3][2] = "";

	}

	/*
	 * サイトマップの配列取得
	 * 
	 * @retuen Array サイトマップの配列を返す。
	 * 
	 * @since 2015/07/06
	 * @version 1.0
	*/
	function getSiteMapArray(){
		return $this->siteMapArray;
	}

	/*
	 * サイトマップの配列設定
	 * 
	 * @since 2015/07/06
	 * @version 1.0
	*/
	function setSiteMapArray(){

		$this-> siteMapArray[1][0] = "サイトマップ";
		$this-> siteMapArray[2][0] = "Site Map";
		$this-> siteMapArray[3][0] = "Карта сайта";

		$this-> siteMapArray[1][1] = "";
		$this-> siteMapArray[2][1] = "";
		$this-> siteMapArray[3][1] = "";

		$this-> siteMapArray[1][2] = "";
		$this-> siteMapArray[2][2] = "";
		$this-> siteMapArray[3][2] = "";

	}	

	/*
	 * WEBデザインの配列取得
	 * 
	 * @retuen Array WEBデザインの配列を返す。
	 * 
	 * @since 2015/07/06
	 * @version 1.0
	*/
	function getWebDesignArray(){
		return $this->webDesignArray;
	}

	/*
	 * WEBデザインの配列設定
	 * 
	 * @since 2015/07/06
	 * @version 1.0
	*/
	function setWebDesignArray(){

		$this-> webDesignArray[1][0] = "WEBデザイン";
		$this-> webDesignArray[2][0] = "Web Design";
		$this-> webDesignArray[3][0] = "Web Дизайн";

		$this-> webDesignArray[1][1] = "";
		$this-> webDesignArray[2][1] = "";
		$this-> webDesignArray[3][1] = "";

		$this-> webDesignArray[1][2] = "";
		$this-> webDesignArray[2][2] = "";
		$this-> webDesignArray[3][2] = "";

	}

	/*
	 * リンクについての配列取得
	 * 
	 * @retuen Array リンクについての配列を返す。
	 * 
	 * @since 2015/07/06
	 * @version 1.0
	*/
	function getAboutLinkArray(){
		return $this->aboutLinkArray;
	}

	/*
	 * リンクについての配列設定
	 * 
	 * @since 2015/07/06
	 * @version 1.0
	*/
	function setAboutLinkArray(){

		$this-> aboutLinkArray[1][0] = "リンクについて";
		$this-> aboutLinkArray[2][0] = "About Link";
		$this-> aboutLinkArray[3][0] = "О Ссылка";

		$this-> aboutLinkArray[1][1] = "";
		$this-> aboutLinkArray[2][1] = "";
		$this-> aboutLinkArray[3][1] = "";

		$this-> aboutLinkArray[1][2] = "";
		$this-> aboutLinkArray[2][2] = "";
		$this-> aboutLinkArray[3][2] = "";

	}

	/*
	 * このサイトについての配列取得
	 * 
	 * @retuen Array このサイトについての配列を返す。
	 * 
	 * @since 2015/07/06
	 * @version 1.0
	*/
	function getAttentionArray(){
		return $this->attentionArray;
	}

	/*
	 * このサイトについての配列設定
	 * 
	 * @since 2015/07/06
	 * @version 1.0
	*/
	function setAttentionArray(){

		$this-> attentionArray[1][0] = "注意事項";
		$this-> attentionArray[2][0] = "Attention";
		$this-> attentionArray[3][0] = "Внимание";

		$this-> attentionArray[1][1] = "";
		$this-> attentionArray[2][1] = "";
		$this-> attentionArray[3][1] = "";

		$this-> attentionArray[1][2] = "";
		$this-> attentionArray[2][2] = "";
		$this-> attentionArray[3][2] = "";

	}

	/*
	 * メールの配列取得
	 * 
	 * @retuen Array メールの配列を返す。
	 * 
	 * @since 2015/07/06
	 * @version 1.0
	*/
	function getEMailAddressArray(){
		return $this->eMailAddressArray;
	}

	/*
	 * メールの配列設定
	 * 
	 * @since 2015/07/06
	 * @version 1.0
	*/
	function setEMailAddressArray(){

			$this-> eMailAddressArray[1][0] = "メール";
			$this-> eMailAddressArray[2][0] = "E-Mail";
			$this-> eMailAddressArray[3][0] = "Электронная почта";

			$this-> eMailAddressArray[1][1] = "";
			$this-> eMailAddressArray[2][1] = "";
			$this-> eMailAddressArray[3][1] = "";

			$this-> eMailAddressArray[1][2] = "";
			$this-> eMailAddressArray[2][2] = "";
			$this-> eMailAddressArray[3][2] = "";

	}
	
}

/*
 * カテゴリタイトルとカテゴリコメントとカテゴリキーワード(info用)
 * 
 * @param array　$addressDataArray　ページアドレス情報の配列
 * @retuen array カテゴリタイトルとカテゴリコメントとカテゴリキーワード(info用)を配列で返す。
 * 
 * @date 2017/07/24
 * @since 2014/09/19
 * @version 1.2 
 * 
*/
function categoryDataInfo($addressDataArray){

	//言語ID
	global $langId;
	
	//クラスの呼び出し
	$infoContentsItemTitleArray = new infoContentsItemTitleArray;
	
	//新着情報の取得
	$infoContentsItemTitleArray->setNewsReleaseArray();
	$newsReleaseArray = $infoContentsItemTitleArray->getNewsReleaseArray();
	
	//管理人の取得
	$infoContentsItemTitleArray->setManagedByArray();
	$managedByArray = $infoContentsItemTitleArray->getManagedByArray();
	//リンクの取得
	$infoContentsItemTitleArray->setLinkArray();
	$linkArray = $infoContentsItemTitleArray->getLinkArray();
	//サイトマップの取得
	$infoContentsItemTitleArray->setSiteMapArray();
	$siteMapArray = $infoContentsItemTitleArray->getSiteMapArray();
	//WEBデザインの取得
	$infoContentsItemTitleArray->setWebDesignArray();
	$webDesignArray = $infoContentsItemTitleArray->getWebDesignArray();
	//リンクについての取得
	$infoContentsItemTitleArray->setAboutLinkArray();
	$aboutLinkArray = $infoContentsItemTitleArray->getAboutLinkArray();
	//このサイトについての取得
	$infoContentsItemTitleArray->setAttentionArray();
	$attentionArray = $infoContentsItemTitleArray->getAttentionArray();
	
	if($addressDataArray[1] == "news_release"){
		$categoryDataInfo = $newsReleaseArray;
	}else if($addressDataArray[1] == "managed_by"){
		$categoryDataInfo = $managedByArray;
	}else if($addressDataArray[1] == "link"){
		$categoryDataInfo = $linkArray;
	}else if($addressDataArray[1] == "site_map"){
		$categoryDataInfo = $siteMapArray;
	}else if($addressDataArray[1] == "web_design"){
		$categoryDataInfo = $webDesignArray;
	}else if($addressDataArray[1] == "about_link"){
		$categoryDataInfo = $aboutLinkArray;
	}else if($addressDataArray[1] == "attention"){
		$categoryDataInfo = $attentionArray;
	}else{
		$categoryDataInfo = "";
	}

	return $categoryDataInfo;
	

}

/*
 * メニューバーにあるinfoカテゴリの案内
 * 
 * @param array　$addressDataArray　ページアドレス情報の配列
 * @retuen string メニューバーにあるinfoカテゴリの案内を返す。
 * 
 * @todo 多言語化対応の必要性あり
 * @date 2017/07/24
 * @since 2014/07/04
 * @version 1.3 
 * 
*/
function menubarNaviInfo($addressDataArray){

	//アーカイブスカテゴリディレクトリ
	//global $archivesCategoryDirectory;
	//ドメイン直下のinfoカテゴリへとリンクする。
	$archivesCategoryDirectory = "";
	//電子メールアドレス
	global $eMailAddress;
	//ファイル拡張子を取得する
	global $fileExtension;
	//言語コード
	global $langCode;
	//言語ID
	global $langId;

	//言語ディレクトリ
	global $langDirectory;
	//表示モード
	global $viewMode;

	//ファイル拡張子を取得する
	global $fileExtension;
	//まだPHPでいい。
	//$fileExtension = ".php";

	//クラスの呼び出し
	$contentsCateroryNameString = new contentsCateroryNameString;
	//HOMEの取得
	$contentsCateroryNameString->setHomeString();
	$homeString = $contentsCateroryNameString->getHomeString();
	
	//クラスの呼び出し
	$infoContentsItemTitleArray = new infoContentsItemTitleArray;
	//メニュー(インフォ用)の取得
	$infoContentsItemTitleArray->setMenuArray();
	$menuInfoArray = $infoContentsItemTitleArray->getMenuArray();
	//新着情報の取得
	$infoContentsItemTitleArray->setnewsReleaseArray();
	$newsReleaseArray = $infoContentsItemTitleArray->getnewsReleaseArray();
	//管理人の取得
	$infoContentsItemTitleArray->setManagedByArray();
	$managedByArray = $infoContentsItemTitleArray->getManagedByArray();
	//リンクの取得
	$infoContentsItemTitleArray->setLinkArray();
	$linkArray = $infoContentsItemTitleArray->getLinkArray();
	//サイトマップの取得
	$infoContentsItemTitleArray->setSiteMapArray();
	$siteMapArray = $infoContentsItemTitleArray->getSiteMapArray();
	//WEBデザインの取得
	$infoContentsItemTitleArray->setWebDesignArray();
	$webDesignArray = $infoContentsItemTitleArray->getWebDesignArray();
	//リンクについての取得
	$infoContentsItemTitleArray->setAboutLinkArray();
	$aboutLinkArray = $infoContentsItemTitleArray->getAboutLinkArray();
	//このサイトについての取得
	$infoContentsItemTitleArray->setAttentionArray();
	$attentionArray = $infoContentsItemTitleArray->getAttentionArray();
	//メールの文字列取得
	$infoContentsItemTitleArray->setEMailAddressArray();
	$eMailAddressArray = $infoContentsItemTitleArray->getEMailAddressArray();
	

	//ページごとに選択する。ページ表示カテゴリの設定。HOME用
	$menubarInfoSelected = pageSelectedInfo($addressDataArray);

	$menubarNaviInfo  = "";
	//スマートフォンでは表示する
	if($viewMode == "SMP"){
	$menubarNaviInfo .= "<div class=\"menubarInfoSmp\">\n";
		$menubarNaviInfo .= $menuInfoArray[$langId][0]."\n";
	$menubarNaviInfo .= "</div>\n";
	}

	$menubarNaviInfo .= "<nav class=\"menubar\">\n";
	//$menubarNaviInfo .= "<div class=\"menubar\">\n";
	$menubarNaviInfo .= "	<ul>\n";
	//カテゴリごとに選択する。HOME
	if($menubarInfoSelected == "HOME"){
		$menubarNaviInfo .= "		<li class=\"menubarSelected\">\n";
		$menubarNaviInfo .= "			<span class=\"menubarSelectedSpan\">\n";
		$menubarNaviInfo .= $homeString."\n";
		$menubarNaviInfo .= "			</span>\n";
		$menubarNaviInfo .= "		</li>\n";
	}else{	
		$menubarNaviInfo .= "		<li>\n";
		$menubarNaviInfo .= "			<a href=\"".linkLevel($addressDataArray[0]).$langDirectory."\" target=\"_top\">\n";
		$menubarNaviInfo .= $homeString."\n";
		$menubarNaviInfo .= "			</a>\n";
		$menubarNaviInfo .= "		</li>\n";
	}

	//新着情報
	if($menubarInfoSelected == "news_release"){
		$menubarNaviInfo .= "		<li class=\"menubarSelected\">\n";
		$menubarNaviInfo .= "			<span class=\"menubarSelectedSpan\">\n";
		$menubarNaviInfo .= $newsReleaseArray[$langId][0]."\n";
		$menubarNaviInfo .= "			</span>\n";
		$menubarNaviInfo .= "		</li>\n";
	}else{	
		$menubarNaviInfo .= "		<li>\n";
		$menubarNaviInfo .= "			<a href=\"".linkLevel($addressDataArray[0]).$langDirectory.$archivesCategoryDirectory."info/news_release".$fileExtension."\" target=\"_top\">\n";
		$menubarNaviInfo .= $newsReleaseArray[$langId][0]."\n";
		$menubarNaviInfo .= "			</a>\n";
		$menubarNaviInfo .= "		</li>\n";
	}

	//管理人
	if($menubarInfoSelected == "managed_by"){
		$menubarNaviInfo .= "		<li class=\"menubarSelected\">\n";
		$menubarNaviInfo .= "			<span class=\"menubarSelectedSpan\">\n";
		$menubarNaviInfo .= $managedByArray[$langId][0]."\n";
		$menubarNaviInfo .= "			</span>\n";
		$menubarNaviInfo .= "		</li>\n";
	}else{	
		$menubarNaviInfo .= "		<li>\n";
		$menubarNaviInfo .= "			<a href=\"".linkLevel($addressDataArray[0]).$langDirectory.$archivesCategoryDirectory."info/managed_by".$fileExtension."\" target=\"_top\">\n";
		$menubarNaviInfo .= $managedByArray[$langId][0]."\n";
		$menubarNaviInfo .= "			</a>\n";
		$menubarNaviInfo .= "		</li>\n";
	}

	//リンク
/*
	if($menubarInfoSelected == "link"){
		$menubarNaviInfo .= "		<li class=\"menubarSelected\">\n";
		$menubarNaviInfo .= "			<span class=\"menubarSelectedSpan\">\n";
		$menubarNaviInfo .= $linkArray[$langId][0]."\n";
		$menubarNaviInfo .= "			</span>\n";
		$menubarNaviInfo .= "		</li>\n";
	}else{	
		$menubarNaviInfo .= "		<li>\n";
		$menubarNaviInfo .= "			<a href=\"".linkLevel($addressDataArray[0]).$langDirectory.$archivesCategoryDirectory."info/link".$fileExtension."\" target=\"_top\">\n";
		$menubarNaviInfo .= $linkArray[$langId][0]."\n";
		$menubarNaviInfo .= "			</a>\n";
		$menubarNaviInfo .= "		</li>\n";
	}
*/
	//サイトマップ
/*
	if($menubarInfoSelected == "site_map"){
		$menubarNaviInfo .= "		<li class=\"menubarSelected\">\n";
		$menubarNaviInfo .= "			<span class=\"menubarSelectedSpan\">\n";
		$menubarNaviInfo .= $siteMapArray[$langId][0]."\n";
		$menubarNaviInfo .= "			</span>\n";
		$menubarNaviInfo .= "		</li>\n";
	}else{	
		$menubarNaviInfo .= "		<li>\n";
		$menubarNaviInfo .= "			<a href=\"".linkLevel($addressDataArray[0]).$langDirectory.$archivesCategoryDirectory."info/site_map".$fileExtension."\" target=\"_top\">\n";
		$menubarNaviInfo .= $siteMapArray[$langId][0]."\n";
		$menubarNaviInfo .= "			</a>\n";
		$menubarNaviInfo .= "		</li>\n";
	}
*/

	//WEBデザイン
	if($menubarInfoSelected == "web_design"){
		$menubarNaviInfo .= "		<li class=\"menubarSelected\">\n";
		$menubarNaviInfo .= "			<span class=\"menubarSelectedSpan\">\n";
		$menubarNaviInfo .= $webDesignArray[$langId][0]."\n";
		$menubarNaviInfo .= "			</span>\n";
		$menubarNaviInfo .= "		</li>\n";
	}else{	
		$menubarNaviInfo .= "		<li>\n";
		$menubarNaviInfo .= "			<a href=\"".linkLevel($addressDataArray[0]).$langDirectory.$archivesCategoryDirectory."info/web_design".$fileExtension."\" target=\"_top\">\n";
		$menubarNaviInfo .= $webDesignArray[$langId][0]."\n";
		$menubarNaviInfo .= "			</a>\n";
		$menubarNaviInfo .= "		</li>\n";
	}

	//リンクについて
	if($menubarInfoSelected == "about_link"){
		$menubarNaviInfo .= "		<li class=\"menubarSelected\">\n";
		$menubarNaviInfo .= "			<span class=\"menubarSelectedSpan\">\n";
		$menubarNaviInfo .= $aboutLinkArray[$langId][0]."\n";
		$menubarNaviInfo .= "			</span>\n";
		$menubarNaviInfo .= "		</li>\n";
	}else{
		$menubarNaviInfo .= "		<li>\n";
		$menubarNaviInfo .= "			<a href=\"".linkLevel($addressDataArray[0]).$langDirectory.$archivesCategoryDirectory."info/about_link".$fileExtension."\" target=\"_top\">\n";
		$menubarNaviInfo .= $aboutLinkArray[$langId][0]."\n";
		$menubarNaviInfo .= "			</a>\n";
		$menubarNaviInfo .= "		</li>\n";
	}

	//このサイトについて
	if($menubarInfoSelected == "attention"){
		$menubarNaviInfo .= "		<li class=\"menubarSelected\">\n";
		$menubarNaviInfo .= "			<span class=\"menubarSelectedSpan\">\n";
		$menubarNaviInfo .= $attentionArray[$langId][0]."\n";
		$menubarNaviInfo .= "			</span>\n";
		$menubarNaviInfo .= "		</li>\n";
	}else{
		$menubarNaviInfo .= "		<li>\n";
		$menubarNaviInfo .= "			<a href=\"".linkLevel($addressDataArray[0]).$langDirectory.$archivesCategoryDirectory."info/attention".$fileExtension."\" target=\"_top\">\n";
		$menubarNaviInfo .= $attentionArray[$langId][0]."\n";
		$menubarNaviInfo .= "			</a>\n";
		$menubarNaviInfo .= "		</li>\n";
	}

	//メールアドレス
		$menubarNaviInfo .= "		<li>\n";
		$menubarNaviInfo .= "			<a href=\"mailto:".$eMailAddress."\">\n";
		$menubarNaviInfo .= $eMailAddressArray[$langId][0]."\n";
		$menubarNaviInfo .= "			</a>\n";
		$menubarNaviInfo .= "		</li>\n";

	$menubarNaviInfo .= "	</ul>\n";
	//$menubarNaviInfo .= "</div>\n";
	$menubarNaviInfo .= "</nav>\n";


	return $menubarNaviInfo;
}

/*
 * 表示されているページがどこかを返す設定info編
 * 多言語対応 
 * 
 * @param string $multiLangDate strtotime
 * @retuen string ページ名を返す。トップページならHOMEを返す。
 *   
 * @date 2017/03/22
 * @since 2014/11/05
 * @version 1.1
 * 
*/
function pageSelectedInfo($addressDataArray){

	//言語コード
	global $langCode;

	//日本語の場合
	if($langCode == "ja"){
		if(!isset($addressDataArray[2])){
			$pageSelectedInfo = "HOME";
		}else{
			$pageSelectedInfo = $addressDataArray[1];
		}
	//英語
	}else if($langCode == "en"){
		if(!isset($addressDataArray[3])){
			$pageSelectedInfo = "HOME";
		}else{
			$pageSelectedInfo = $addressDataArray[1];
		}
	//ロシア語
	}else if($langCode == "ru"){
		if(!isset($addressDataArray[3])){
			$pageSelectedInfo = "HOME";
		}else{
			$pageSelectedInfo = $addressDataArray[1];
		}
	}else{
		if(!isset($addressDataArray[2])){
			$pageSelectedInfo = "HOME";
		}else{
			$pageSelectedInfo = $addressDataArray[1];
		}
	}

	return $pageSelectedInfo;
	
}

?>
