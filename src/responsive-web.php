<?php
/**
 * yottanote.com CMS
 *  
 * スマートフォン用とパソコン用画面との切替を行う関数
 * <br>
 * 各ドメイン共通設定
 *
 * @author yottanote.com
 * @since 2014/10/29
 * @version 1.0 
 * @date 2014/10/29
 * 
 * copyright yottanote.com
 */

/**
* デバイス名を取得し表示モードを設定するためのクラス
*　
 * @since 2014/10/29
 * @version 1.0
*/
class resposiveWeb{

	private $DeviceUA;
	private $viewMode;

	//ユーザーエージェントを取得する
	function getDeviceUA(){
			return $this->DeviceUA;
	}

	//表示モードを取得する
	function getViewMode(){
			return $this->viewMode;
	}


	//デバイスのユーザーエージェントを取得する
	function setDeviceUA(){

		//スマートフォンの一覧を正規表現で設定する
		$smartPhoneList  = "/";		//開始地点
		$smartPhoneList .= "iPhone";
		$smartPhoneList .= "|";		//ORの意味
		$smartPhoneList .= "Android";
		$smartPhoneList .= "/";		//終了地点
		$smartPhoneList .= "i";		//大文字小文字区別なしの意味
	
		//スマートフォンであるか否かをユーザーエージェントから判別する	
		if(preg_match($smartPhoneList,htmlspecialchars($_SERVER["HTTP_USER_AGENT"], ENT_QUOTES))){
			$this->DeviceUA = "SMP";
			//$this->DeviceUA = htmlspecialchars($_SERVER["HTTP_USER_AGENT"], ENT_QUOTES);
		}else{
			$this->DeviceUA = "PC";	
			//$this->DeviceUA = htmlspecialchars($_SERVER["HTTP_USER_AGENT"], ENT_QUOTES);
		}

	}

	//ブラウザの表示形式を取得する
	function setViewMode(){
		$this->setDeviceUA();
		$DeviceUA = $this->getDeviceUA();

		//スマートフォン対応サイトの場合の処理
		if($DeviceUA == "SMP"){
	
			//クッキーの読み込み処理
			if(isset($_COOKIE["viewMode"])){
//print"UA:SMP。クッキーあり<br>".$_COOKIE["viewMode"]."<br>";
				$DeviceCookie = $_COOKIE["viewMode"];
				if($DeviceCookie == "PC"){
					$this->viewMode = "SMPtoPC";
//print"UA:SMP。クッキー:PC<br>";
				}else if($DeviceCookie == "SMP"){
//print"UA:SMP。クッキー:SMP<br>";
					$this->viewMode = "SMP";
				}else{
//print"UA:SMP。クッキー:以外<br>";
					//$this->viewMode = "SMPtoPC";	
					$this->viewMode = "SMP";
				}
			}else{
//print"UA:SMP。クッキー:なし<br>";
				$this->viewMode = "SMP";
				//テスト用
				//$this->viewMode = "SMPtoPC";
			}
	
		}else{
//print"UAはパソコン<br>";
			$this->viewMode = "PC";
		}
	}
}

/*
 * スマートフォン対応サイトのヘッダー
 * 
 * @param array　$addressDataArray　ページアドレス情報の配列
 * @retuen string スマートフォン対応サイトのヘッダーをhtmlタグで返す。
 * 
 * @since 2014/11/04
 * @version 1.0
 * 
*/
function responsiveHeader($addressDataArray){

	//表示モード
	global $viewMode;

	//スマートフォン対応サイトの場合の処理
	if($viewMode == "SMP"){
		//$htmlOutput .= "<br>スマートフォンサイト\n";
		$responsiveHeader = "";
	//スマートフォンでPCサイトを読み込む場合
	}else if($viewMode == "SMPtoPC"){
		$responsiveHeader  = "	<div id=\"responsiveHeader\">\n";
		$responsiveHeader .= "		<a href=\"javascript:void(0);\" id=\"selectSMP\">Go to Smartphone Site</a>\n";		
		$responsiveHeader .= "	</div>\n";
	}else{
		$responsiveHeader = "";
	}

	return $responsiveHeader;

}

/*
 * スマートフォン対応サイトのフッター
 * 
 * @param array　$addressDataArray　ページアドレス情報の配列
 * @retuen string スマートフォン対応サイトのフッターをhtmlタグで返す。
 * 
 * @since 2014/11/04
 * @version 1.0
 * 
*/
function responsiveFooter($addressDataArray){

	//表示モード
	global $viewMode;

	//スマートフォン対応サイトの場合の処理
	if($viewMode == "SMP"){
		$responsiveFooter  = "	<div id=\"responsiveFooter\">\n";
		$responsiveFooter .= "		<a href=\"javascript:void(0);\" id=\"selectPC\">Go to Full Site</a>\n";
		$responsiveFooter .= "	</div>\n";
	}else{
		$responsiveFooter = "";
	}

	return $responsiveFooter;

}

?>