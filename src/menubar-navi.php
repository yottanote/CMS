<?php
/**
 * yottanote.com CMS
 *
 * メニュー画面に関連した情報を表示し別のページに案内する。
 * <br>
 * ヨタノートの独自設定あり
 *
 * @author yottanote.com
 * @date 2017/03/22
 * @since 2014/07/04
 * @version 1.3
 *
 * copyright yottanote.com
 */

/**
 * コンテンツカテゴリ名を取得するためのクラス
 *　
 * @date 2017/03/22
 * @since 2014/11/05
 * @version 1.2
 */
class contentsCateroryNameString{


	private $archivesString;
	private $homeString;
	private $linuxString;
	private $menuString;
	
	/*
	 * メニューの文字列取得
	*
	* @retuen string メニューの文字列を返す。
	*
	* @since 2014/11/05
	* @version 1.0
	*/
	function getMenuString(){
		return $this->menuString;
	}
	
	/*
	 * メニューの文字列設定
	*
	* @since 2014/11/05
	* @version 1.0
	*/
	function setMenuString(){
	
		//言語コード
		global $langCode;
	
		if($langCode == "ja"){
			$this->menuString = "メニュー";
		}else if ($langCode == "en"){
			$this->menuString = "Menu";
		}else if ($langCode == "ru"){
			$this->menuString = "Menu";
		}else{
			$this->menuString = "メニュー";
		}
	}
	
	/*
	 * HOMEの文字列取得
	*
	* @retuen string HOMEの文字列を返す。
	*
	* @since 2014/11/05
	* @version 1.0
	*/
	function getHomeString(){
		return $this->homeString;
	}
	
	/*
	 * HOMEの文字列設定
	*
	* @since 2014/11/05
	* @version 1.0
	*/
	function setHomeString(){
	
		//言語コード
		global $langCode;
	
		if($langCode == "ja"){
			$this->homeString = "HOME";
		}else if ($langCode == "en"){
			$this->homeString = "HOME";
		}else if ($langCode == "ru"){
			$this->homeString = "HOME";
		}else{
			$this->homeString = "HOME";
		}
	}

	/*
	 * アーカイブスの文字列取得
	*
	* @retuen string アーカイブスの文字列を返す。
	*
	* @since 2014/11/12
	* @version 1.0
	*/
	function getArchivesString(){
		return $this->archivesString;
	}
	
	/*
	 * アーカイブスの文字列設定
	*
	* @since 2014/11/12
	* @version 1.0
	*/
	function setArchivesString(){
	
		//言語コード
		global $langCode;
	
		if($langCode == "ja"){
			$this-> archivesString = "Archives";
		}else if ($langCode == "en"){
			$this-> archivesString = "Archives";
		}else if ($langCode == "ru"){
			$this-> archivesString = "Archives";
		}else{
			$this-> archivesString = "Archives";
		}
		return $this-> archivesString;
	}
	
	/*
	 * Linuxの文字列取得
	*
	* @retuen string アーカイブスの文字列を返す。
	*
	* @since 2014/11/12
	* @version 1.0
	*/
	function getLinuxString(){
		return $this->linuxString;
	}
	
	/*
	 * Linuxの文字列設定
	*
	* @since 2014/11/12
	* @version 1.0
	*/
	function setLinuxString(){
	
		//言語コード
		global $langCode;
	
		if($langCode == "ja"){
			$this-> linuxString = "Linux";
		}else if ($langCode == "en"){
			$this-> linuxString = "Linux";
		}else if ($langCode == "ru"){
			$this-> linuxString = "Linux";
		}else{
			$this-> linuxString = "Linux";
		}
		return $this-> linuxString;
	}
	

}


/*
 * メニューバーにあるコンテンツカテゴリの案内
 *
 * @param array　$addressDataArray　ページアドレス情報の配列
 * @retuen string メニューバーにあるコンテンツカテゴリの案内をhtmlタグで返す。
 *
 * @date 2015/07/12
 * @since 2014/09/20
 * @version 1.2
 *
 */
function menubarNavi($addressDataArray){

	//アーカイブスカテゴリディレクトリ
	global $archivesCategoryDirectory;
	//ファイル拡張子を取得する
	global $fileExtension;
	//言語コード
	global $langCode;
	//言語ディレクトリ
	global $langDirectory;
	//表示モード
	global $viewMode;

	//クラスの呼び出し
	$contentsCateroryNameString = new contentsCateroryNameString;
	//メニューの取得
	$contentsCateroryNameString->setMenuString();
	$menuString = $contentsCateroryNameString->getMenuString();
	//HOMEの取得
	$contentsCateroryNameString->setHomeString();
	$homeString = $contentsCateroryNameString->getHomeString();
	//アーカイブスの取得
	$contentsCateroryNameString->setArchivesString();
	$archivesString = $contentsCateroryNameString->getArchivesString();
	
	//リナックスの取得
	$contentsCateroryNameString->setLinuxString();
	$linuxString = $contentsCateroryNameString->getLinuxString();
	
	//カテゴリごとに選択する。ページ表示カテゴリの設定
	$menubarSelected = categorySelected($addressDataArray);

	$menubarNavi  = "";
	//スマートフォンでは表示する
	if($viewMode == "SMP"){
		$menubarNavi .= "<div class=\"menubarSmp\">\n";
		$menubarNavi .= $menuString;
		$menubarNavi .= "</div>\n";
	}
	$menubarNavi .= "<nav class=\"menubar\">\n";
	//$menubarNavi .= "<div class=\"menubar\">\n";
	$menubarNavi .= "	<ul>\n";
	//カテゴリごとに選択する。HOME
	if($menubarSelected == "HOME"){
		$menubarNavi .= "		<li class=\"menubarSelected\">\n";
		$menubarNavi .= "			<span class=\"menubarSelectedSpan\">\n";
		$menubarNavi .= $homeString."\n";
		$menubarNavi .= "			</span>\n";
		$menubarNavi .= "		</li>\n";
	}else{
		$menubarNavi .= "		<li>\n";
		$menubarNavi .= "			<a href=\"".linkLevel($addressDataArray[0]).$langDirectory."\" target=\"_top\">\n";
		$menubarNavi .= $homeString."\n";
		$menubarNavi .= "			</a>\n";
		$menubarNavi .= "		</li>\n";
	}

	//カテゴリごとに選択する。アーカイブス
	if($menubarSelected == "archives"){
		$menubarNavi .= "		<li class=\"menubarSelected\">\n";
		$menubarNavi .= "			<span class=\"menubarSelectedSpan\">\n";
		$menubarNavi .= $archivesString."\n";
		$menubarNavi .= "			</span>\n";
		$menubarNavi .= "		</li>\n";
	}else{
		$menubarNavi .= "		<li>\n";
		$menubarNavi .= "			<a href=\"".linkLevel($addressDataArray[0]).$langDirectory.$archivesCategoryDirectory."archives/\" target=\"_top\">\n";
		$menubarNavi .= $archivesString."\n";
		$menubarNavi .= "			</a>\n";
		$menubarNavi .= "		</li>\n";
	}

	//カテゴリごとに選択する。リナックス
	if($menubarSelected == "linux"){
		$menubarNavi .= "		<li class=\"menubarSelected\">\n";
		$menubarNavi .= "			<span class=\"menubarSelectedSpan\">\n";
		$menubarNavi .= $linuxString."\n";
		$menubarNavi .= "			</span>\n";
		$menubarNavi .= "		</li>\n";
	}else{
		$menubarNavi .= "		<li>\n";
		$menubarNavi .= "			<a href=\"".linkLevel($addressDataArray[0]).$langDirectory.$archivesCategoryDirectory."linux/\" target=\"_top\">\n";
		$menubarNavi .= $linuxString."\n";
		$menubarNavi .= "			</a>\n";
		$menubarNavi .= "		</li>\n";
	}

	$menubarNavi .= "	</ul>\n";
	//$menubarNavi .= "</div>\n";
	$menubarNavi .= "</nav>\n";

	return $menubarNavi;

}

?>