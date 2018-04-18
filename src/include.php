<?php
/**
 * yottanote.com CMS
 *  
 * htmlタグ出力用関数
 * <br>
 * 各ドメイン共通設定
 *
 * @author yottanote.com
 * @date 2017/07/24
 * @since 2014/07/14
 * @version 1.8
 * 
 * copyright yottanote.com
 */

/*
 * コンテンツカテゴリとコンテンツアイテムのタイトル
 * 
 * @param array　$addressDataArray　ページアドレス情報の配列
 * @param array　$addressDataTitleArray　コンテンツアイテムのタイトルの配列
 * @retuen string コンテンツカテゴリとコンテンツアイテムのタイトルをhtmlタグで返す。
 * 
 * @date 2015/09/28
 * @since 2014/09/21
 * @version 1.3
 * 
*/
function categoryTitleText($addressDataArray,$addressDataTitleArray){
//print_r($addressDataArray);
//print"<br>";
//print_r($addressDataTitleArray);

	//カテゴリデータ
	global $categoryDataArray;
	//コンテンツアイテム情報
	global $contentsItemDataArray;
	//ファイル拡張子を取得する
	global $fileExtension;
	//言語コード
	global $langCode;
	//言語ID
	global $langId;
	//現在の階層から1段下げる(外国語は1階層下げ)
	global $levelDown;
	//カテゴリトップ階層(外国語は1階層下げ)
	global $levelContentsCategory;
	//コンテンツディレクトリ第一階層(外国語は1階層下げ)
	global $levelContentsDirectory;
	//コンテンツディレクトリ第二階層(外国語は1階層下げ)
	global $levelContentsDirectorySecond;
	//一覧表示数
	global $listCountArray;
	//最大表示件数の初期値を取得する。
	global $listMax;
	//本番サーバかテストサーバかの値を取得する。
	global $productionServer;
	//表示モード
	global $viewMode;

	//カテゴリ名を表示する例:写真
	$categoryText  = "<div class=\"headerTitleBgGround\">\n";
	$categoryText .= "	<h1>\n";
	$categoryText .= "		<span class=\"headerTitle\">\n";
	//階層がコンテンツカテゴリトップ階層とそれ以下とで処理を分ける
	//if($levelDown <= 1){
	if($addressDataArray[0] <= $levelContentsCategory){
		//コンテンツカテゴリトップ階層で一覧が改ページしている場合トップへのリンクを張る
		if($listCountArray[0] > 0){
			$listSortCountArray[0] = 0;
			$categoryText .= "			<a href=\"index".$fileExtension."\" title=\"".metaTagCategoryTitle($addressDataArray,$langId,$listSortCountArray)."\">\n";
			$categoryText .= $categoryDataArray[$langId][0]."\n";
			$categoryText .= "			</a>\n";

			//一覧の件数表示
			$listCountWord = listCountWord("current",$langId,$listCountArray);
			$categoryText .= "&nbsp;\n";
			$categoryText .= $listCountWord."\n";
			//$categoryText .= "&nbsp;\n";

		}else{
			$categoryText .= $categoryDataArray[$langId][0]."\n";
		}
	//コンテンツアイテムページの場合の処理。
	}else{
		//コンテンツアイテムのソート順を取得する
		if(isset($contentsItemDataArray["CONTENTSITEM_ID"]) && isset($contentsItemDataArray["CONTENTSCATEGORY_ID"])){
			$contentsItemSortCountArray = selectContentsItemSortCount($contentsItemDataArray);
		}
		if(isset($contentsItemSortCountArray["CONTENTSITEM_ID_COUNT"]) && isset($contentsItemSortCountArray["CONTENTSITEM_ID_COUNT_ALL"])){
			$contentsItemIdSortCount = $contentsItemSortCountArray["CONTENTSITEM_ID_COUNT"];
			$contentsItemIdSortAll = $contentsItemSortCountArray["CONTENTSITEM_ID_COUNT_ALL"];
		}else{
			$contentsItemIdSortCount = 0;
			$contentsItemIdSortAll = 0;
		}
		//コンテンツアイテムのソート順の整数1桁を切り捨てる
		$listSortCountArray[0] = floor(($contentsItemIdSortAll - $contentsItemIdSortCount) / 10) * 10 ;

		//print "<br>contentsItemIdSortCount:";
		//print $contentsItemIdSortCount;
		//print "<br>contentsItemIdSortAll:";
		//print $contentsItemIdSortAll;
		//print "<br>listSortCountArray[0]:";
		//print $listSortCountArray[0];
		//コンテンツカテゴリ一覧の表示順番を取得する。コンテンツカテゴリトップページならば空文字を代入する。
		$getListCurrent = "";
		//マイナス数字は除外する。
		if($listSortCountArray[0] >= 0){
			//0でなければ改ページ用に数値を代入する。
			if($listSortCountArray[0] != 0){
				$getListCurrent = $listSortCountArray[0];
			}
		}
		//本番サーバの場合とテストサーバの場合での動作
		if($productionServer){
			//本番サーバ用	
			$categoryText .= "			<a href=\"".linkLevel($levelDown)."index".$getListCurrent.$fileExtension."\" title=\"".metaTagCategoryTitle($addressDataArray,$langId,$listSortCountArray)."\">\n";
		}else{
			//テストサーバ用	
			//表示順番がトップページとそうで内場合で処理を分ける
			if($getListCurrent == ""){
				$categoryText .= "			<a href=\"".linkLevel($levelDown)."index".$fileExtension."\" title=\"".metaTagCategoryTitle($addressDataArray,$langId,$listSortCountArray)."\">\n";
			}else{
				$categoryText .= "			<a href=\"".linkLevel($levelDown)."index".$fileExtension."?list=".$getListCurrent."\" title=\"".metaTagCategoryTitle($addressDataArray,$langId,$listSortCountArray)."\">\n";
			}
		}
		$categoryText .= $categoryDataArray[$langId][0]."\n";
		$categoryText .= "			</a>\n";
	}
	
	//階層がコンテンツディレクトリ第一階層以上ならば、つまりコンテンツアイテムのページならば以下の処理を行う	
	if($addressDataArray[0] >= $levelContentsDirectory){

		//スマートフォンでの処理
		if($viewMode == "SMP"){
			$categoryText .= "		</span>\n";
			$categoryText .= "	</h1>\n";
			$categoryText .= "</div>\n";
		}

		//コンテンツアイテムのタイトル表示を行う
		for ($i = 0; $i < count($addressDataTitleArray); $i++) {
			
			//スマートフォンでの処理
			if($viewMode == "SMP"){
				$categoryText  .= "<div class=\"headerTitleBgGround\">\n";
				$categoryText .= "	<h1>\n";
				$categoryText .= "		<span class=\"headerTitle\">\n";
			}else{			
				$categoryText .= "&gt;\n";
			}
			//さらにカテゴリがあった場合の処理
			if($i + 1 < count($addressDataTitleArray)){

				//コンテンツディレクトリ第一階層で一覧表示しないのに、コンテンツディレクトリ第二階層以降で一覧表示する場合
				if(count($addressDataArray) - count($addressDataTitleArray) == $levelContentsDirectory){
					$categoryText .= "<a href=\"".linkLevel($levelDown).$addressDataArray[$i+$levelContentsDirectory]."/".$addressDataArray[$i+$levelContentsDirectorySecond]."/\">\n";
					$categoryText .= $addressDataTitleArray[$i + 1]."\n";
					$categoryText .= "</a>\n";
				}else{

					$categoryText .= "<a href=\"".linkLevel($levelDown).$addressDataArray[$i+$levelContentsDirectory]."/\">\n";
					$categoryText .= $addressDataTitleArray[$i + 1]."\n";
					$categoryText .= "</a>\n";

					//タイトルからカテゴリの文字列分だけ削除
					$addressDataTitleArray[0] = preg_replace("/^[ 　]+/u", "", str_replace($addressDataTitleArray[$i + 1], "", $addressDataTitleArray[0]));
				}
				
			//タイトルの処理
			}else{
				$categoryText .= "<a name=\"";
						//name生成
						for ($j = 1; $j <= $addressDataArray[0]; $j++){

							//フォルダ名
							if($j < $addressDataArray[0]){
								$categoryText .= $addressDataArray[$j+1];
								$categoryText .= "_";
							//ファイル名
							}else{
								$categoryText .= $addressDataArray[1];
							}

						}
						//for文jの処理終わり

				$categoryText .= "\">\n";
				$categoryText .= $addressDataTitleArray[0]."\n";
				$categoryText .= "</a>\n";
			}

			//スマートフォンでの処理
			if($viewMode == "SMP"){
				$categoryText .= "		</span>\n";
				$categoryText .= "	</h1>\n";
				$categoryText .= "</div>\n";
			}
		}
		//for文iの処理終わり

		//スマートフォン以外での処理
		if($viewMode !== "SMP"){
			$categoryText .= "		</span>\n";
			$categoryText .= "	</h1>\n";
		}
	}else{
	
		$categoryText .= "		</span>\n";
		$categoryText .= "	</h1>\n";
	
	}


	//カテゴリようこそコメントを表示する
	if($addressDataArray[0] == $levelContentsCategory){
		$categoryText .= "&nbsp;&nbsp;\n";
		$categoryText .= "<span class=\"headerTitleComment\">\n";
		$categoryText .= $categoryDataArray[$langId][1]."\n";
		$categoryText .= "</span>\n";
	}

	$categoryText .= "</div>\n";

	return $categoryText;

}

/*
 * カテゴリとタイトルwelcomeテキスト。ドメイントップページのみに用いる
 * 
 * @param array　$addressDataArray　ページアドレス情報の配列
 * @retuen string カテゴリとタイトルwelcomeテキスト。ドメイントップページのみに用いるをhtmlタグで返す。
 * 
 * @date 2017/03/22
 * @since 2014/09/11
 * @version 1.2
 * 
*/
function categoryTitleWelcomeText($addressDataArray){

	//アーカイブスカテゴリディレクトリ
	global $archivesCategoryDirectory;
	//ドメインタイトル
	global $domainTitle;
	//ファイル拡張子を取得する
	global $fileExtension;
	//言語コード
	global $langCode;
	//言語ID
	global $langId;
	//一覧表示数
	global $listCountArray;

	$categoryTitleWelcomeText  = "";

	//ドメイントップ階層で一覧が改ページしている場合トップへのリンクを張る
	if($listCountArray[0] > 0){
		$categoryTitleWelcomeText .= "<div class=\"headerTitleBgGround\">\n";
		$listSortCountArray[0] = 0;
		$categoryTitleWelcomeText .= "	<h1>\n";
		$categoryTitleWelcomeText .= "		<span class=\"headerTitle\">\n";
		$categoryTitleWelcomeText .= "			<a href=\"index".$fileExtension."\" title=\"".$domainTitle."\">\n";
		$categoryTitleWelcomeText .= $domainTitle."\n";
		$categoryTitleWelcomeText .= "			</a>\n";

		//一覧の件数表示
		$listCountWord = listCountWord("current",$langId,$listCountArray);
		$categoryTitleWelcomeText .= "		&nbsp;\n";
		$categoryTitleWelcomeText .= $listCountWord."\n";
		$categoryTitleWelcomeText .= "		</span>\n";
		$categoryTitleWelcomeText .= "	</h1>\n";

	}else{

		//ドメインに対するようこそメッセージ
		$domainWelcome = domainWelcome();

		$categoryTitleWelcomeText .= "<div class=\"welcome\">\n";
		$categoryTitleWelcomeText .= "	<h1>\n";
		$categoryTitleWelcomeText .= "		<span class=\"welcome\">\n";
		$categoryTitleWelcomeText .= $domainWelcome."\n";
		$categoryTitleWelcomeText .= "		</span>\n";
		$categoryTitleWelcomeText .= "	</h1>\n";
	}

	if($listCountArray[0] == 0){
		$categoryTitleWelcomeText .= "	<span class=\"modified\">\n";
		$categoryTitleWelcomeText .= "		&nbsp;\n";
		$categoryTitleWelcomeText .= "		<a href=\"".$archivesCategoryDirectory."rss/index".$fileExtension."\">\n";
		//現在の言語が日本語の場合
		if($langCode == "ja"){
			$categoryTitleWelcomeText .= "			<img alt=\"rss画像\" title=\"RSS\" src=\"images/rss.gif\">\n";
		//現在の言語が外国語の場合
		}else{
			$categoryTitleWelcomeText .= "			<img alt=\"rss picture\" title=\"RSS\" src=\"../images/rss.gif\">\n";
		}
		$categoryTitleWelcomeText .= "		</a>\n";
		$categoryTitleWelcomeText .= "	</span>\n";
	}

	$categoryTitleWelcomeText .= "</div>\n";

	return $categoryTitleWelcomeText;
}

/*
 * センターコンテンツ
 * 
 * @param array　$addressDataArray　ページアドレス情報の配列
 * @retuen string センターコンテンツをhtmlタグで返す
 * 
 * @since 2015/07/12
 * @since 2014/11/07
 * @version 1.1
 * 
*/
function centerContents($addressDataArray){

	//カテゴリ情報
	//global $categoryDataArray;
	//コンテンツアイテム情報
	global $contentsItemDataArray;
	//コンテンツアイテムID
	global $contentsItemId;
	//コンテンツアイテムのページか否か
	global $contentsItemPage;
	//言語コード
	global $langCode;
	//言語ディレクトリ
	global $langDirectory;
	//言語ID
	global $langId;
	//第三階層(外国語は1階層下げ)
	global $levelContentsDirectory;
	//データありません文を取得する。
	global $notFoundDataString;
	//タイトル
	//global $titleDataArray;
	//表示モード
	global $viewMode;

	//$centerContents  = "	<div id=\"centerContents\">\n";
	$centerContents  = "<main id=\"centerContents\">\n";
//print_r($titleDataArray);
	//コンテンツが一覧もしくは有効なコンテンツアイテムの場合の処理
	if($contentsItemId !== NULL){

		//dataフォルダのデータ読み込み.phpは拡張子を変こうすることがないので置き換えないこと。
		$datAddress = linkLevel($addressDataArray[0])."dat/".directoryNameAddress($addressDataArray).$addressDataArray[1].".php";
	
	//print "<br>dattest<br>";
	//print $datAddress;
	//print "<br>";
		//dataファイルの存在チェック
		if(file_exists($datAddress)){
	
			//出力バッファリングを開始
			ob_start();
			//datファイル読み込み
			include($datAddress);
			//バッファリングセット
			$datContents = ob_get_contents();
			//出力バッファリング終了
			ob_end_clean();
	
		//datファイルが見当たらない場合
		}else{
	
			//datファイルが見当たりません。
			$datContents = $notFoundDataString;
		}
	
		//コンテンツのページならばコンテンツに関する情報を取得する
		if($contentsItemPage && $contentsItemDataArray["ITEMCOMMENT"] != ""){
	
			$centerContents .= $contentsItemDataArray["ITEMCOMMENT"];
	
		}
		$centerContents .= $datContents;
		//コンテンツのページならばコンテンツに関する情報を取得する
		if($contentsItemPage){
			$centerContents .= centerContentsNavi($addressDataArray,$contentsItemDataArray);
		}
	//コンテンツが一覧でもなく無効なコンテンツアイテムの場合
	}else{
		$centerContents .= 	$notFoundDataString;
	}

	//$centerContents .= "	</div>\n";
	$centerContents .= "</main>\n";

	return $centerContents;

}

/*
 * コンテンツアイテム一覧表示　カテゴリ別一覧
 * 
 * @retuen string コンテンツアイテム一覧表示　カテゴリ別一覧をhtmlタグで返す。
 * 
 * @date 2015/09/27
 * @since 2014/09/17
 * @version 1.3
 * 
*/
function contentsItemList(){

	//ページ情報
	global $addressDataArray;
	//アーカイブスカテゴリディレクトリ
	global $archivesCategoryDirectory;
	//カテゴリ情報
	global $categoryDataArray;
	//ファイル拡張子を取得する
	global $fileExtension;
	//言語ディレクトリ
	global $langDirectory;
	//言語ID
	global $langId;
	//コンテンツディレクトリ第一階層(外国語は1階層下げ)
	global $levelContentsDirectory;
	//一覧表示数を取得する
	global $listCountArray;
	//データありません文を取得する。
	global $notFoundDataString;
	//本番サーバかテストサーバかの値を取得する。
	global $productionServer;
	//タイトル
	global $titleDataArray;

	//一覧次ページへを取得する
	$nextPageString = nextPageString();
	//「の写真」を取得する
	$photoString = photoString();
	//写真撮影日の文字列を取得する
	$photoDateString = photoDateString();
	//投稿日の文字列を取得する
	$postedDateString = postedDateString();
	//一覧前ページへを取得する
	$prevPageString = prevPageString();
	//続きを読むを取得する
	$readMore = readMore();


	//表示件数を代入する。
	$listCountMin = $listCountArray[0];
	$listMax = $listCountArray[1];

	//カテゴリ別一覧をDBから取得する。
	$listResult = selectContentsitemCategoryList($addressDataArray,$listCountArray);
//print "最大数は".$listResultcounts;

	//取得件数を取得する。
	$listResultCount = count($listResult[0]);
//print "count:".$listResultCount."<br>";

	$contentsItemList = "";

	//コンテンツアイテムがあるのなら表示する。
	if(is_array($listResult[0]) == true){

		//リンクのget値
		//前ページに表示するアイテム数が最大件数以下ならget値が不要なので以下のif文を用いる
		if($listCountMin <= $listMax){
			$getListPrev = "";
		}else{
			$getListPrev = $listCountMin - $listMax;
		}
		//次ページ用のget値
		$getListNext = $listCountMin +$listMax;

		//前ページリンク
		//一覧の最初のページである　初期値以外なら表示
		if($listCountMin > 0 ){
			$contentsItemList .= "<div class=\"contentsTitle\">\n";
			
					//一覧の最初のページに遷移する場合
			if($getListPrev == ""){
				$contentsItemList .= " <a href=\"./\" title=\"".metaTagTitle($addressDataArray,$langId,"prev")."\">\n";
			}else{
				//本番サーバの場合とテストサーバの場合での動作
				//本番サーバ用	
				if($productionServer){
					$contentsItemList .= " <a href=\"index".$getListPrev.$fileExtension."\" title=\"".metaTagTitle($addressDataArray,$langId,"prev")."\">\n";
				//テストサーバ用	
				}else{
					$contentsItemList .= " <a href=\"index".$fileExtension."?list=".$getListPrev."\" title=\"".metaTagTitle($addressDataArray,$langId,"prev")."\">\n";
				}
			}
			//コンテンツディレクトリ第一階層(外国語は1階層下げ)以上ならば、つまりコンテンツアイテムのページならば以下の処理を行う	
			if($addressDataArray[0] >= $levelContentsDirectory){
				$contentsItemList .= "   &lt;&lt;".$titleDataArray[$langId][0].":".$prevPageString."\n";
			}else{
				$contentsItemList .= "   &lt;&lt;".$categoryDataArray[$langId][0].":".$prevPageString."\n";
			}
			$contentsItemList .= "	</a>\n";
			$contentsItemList .= "</div>\n";
			//$contentsItemList .= "<br>\n";
		
		}
		
		foreach($listResult[0] as $i => $row){
			$j = $i+1;
			//もし、奇数ならば
			if($j%2!=0){
				//もし、最後の1件ならば
				if($j == $listResultCount){
					$listPosition = 0;
				}else{
					$listPosition = 1;
				}
			//偶数ならば
			}else{
				$listPosition = 2;
			}	
	
			//奇数行の処理表示前
			if($listPosition==1){
				$contentsItemList .= "<div class=\"contentsItem\">\n";
				$contentsItemList .= 	"<div class=\"contentsItemL\">\n";
				$contentsItemList .= 		"<div class=\"contentsTitle\">\n";
			//偶数行の処理　表示前
			}else if($listPosition==2){
				$contentsItemList .= "<div class=\"contentsItemR\">\n";
				$contentsItemList .= 	"<div class=\"contentsTitle\">\n";
			//最終行の処理　表示前
			}else{
				$contentsItemList .= "<div class=\"contentsItem\">\n";
				$contentsItemList .= 	"<div class=\"contents\">\n";
				$contentsItemList .= 		"<div class=\"contentsTitle\">\n";
			}
	
			//一覧展開
			$contentsItemList .= 		"<h2>\n";
			$contentsItemList .= 		$row["CONTENTSCATEGORY_NAME"]."&nbsp;\n";
			$contentsItemList .= 		"<a href=\"".linkLevel($addressDataArray[0]).$langDirectory.$archivesCategoryDirectory.$row["CONTENTSCATEGORY_DIRECTORY"]."/".$row["DIRECTORY_NAME"]."/".indexFile($row["FILE_NAME"].$fileExtension)."\" target=\"_top\" title=\"".$row["TITLE"]."\">\n";
			$contentsItemList .= 			$row["TITLE"]."\n";
			$contentsItemList .= 		"</a>\n";
			$contentsItemList .= 		"</h2>\n";
			$contentsItemList .= 	"</div>\n";
			$contentsItemList .= 	"<div>\n";
			$contentsItemList .= 		"<a href=\"".linkLevel($addressDataArray[0]).$langDirectory.$archivesCategoryDirectory.$row["CONTENTSCATEGORY_DIRECTORY"]."/".$row["DIRECTORY_NAME"]."/".indexFile($row["FILE_NAME"].$fileExtension)."\" target=\"_top\" title=\"".$row["TITLE"]."\">\n";
			//画像のALTがデータベースに格納されていた場合としていない場合との処理
			if($row["IMAGE_ALT"] != ""){
				$contentsItemList .= 		 	"<img alt=\"".$row["IMAGE_ALT"]."\" title=\"".$row["IMAGE_TITLE"]."\" src=\"".linkLevel($addressDataArray[0])."images/".$row["CONTENTSCATEGORY_DIRECTORY"]."/".$row["IMAGE_DIRECTORY_NAME"]."/".$row["IMAGE_FILE_NAME"]."\" class=\"".listImageSize($row["IMAGESIZE_CODE"])."\">\n";
			}else{
				$contentsItemList .= 		 	"<img alt=\"".$row["IMAGE_TITLE"].$photoString."\" title=\"".$row["IMAGE_TITLE"]."\" src=\"".linkLevel($addressDataArray[0])."images/".$row["CONTENTSCATEGORY_DIRECTORY"]."/".$row["IMAGE_DIRECTORY_NAME"]."/".$row["IMAGE_FILE_NAME"]."\" class=\"".listImageSize($row["IMAGESIZE_CODE"])."\">\n";			
			}
			$contentsItemList .= 		 	"<br>\n";
			$contentsItemList .= 			$readMore."\n";
			$contentsItemList .=  		"</a>\n";
			//$contentsItemList .=	 	"<br>\n";
			//$contentsItemList .=	 	$photoDateString.":".multiLangDate(strtotime($row["PHOTO_DATE"]));
			$contentsItemList .= 		"<br>\n";
			$contentsItemList .= 		$postedDateString.":".multiLangDate(strtotime($row["POSTED_DATE"]))."\n";
			$contentsItemList .= 	"</div>\n";
			$contentsItemList .= "</div>\n";
	
			//偶数行と最終行の処理　表示後
			if($listPosition!=1){
				$contentsItemList .= "</div>\n";
				//コンテンツ間
				$contentsItemList .= betweenContents();
			}
		}

		//コンテンツ一覧で最後のページでなく、アイテム個数が表示以上まだあるのなら次ページへのリンクを表示する
		if($listResult[1] > $getListNext){
			$contentsItemList .= "<div class=\"contentsTitle\">\n";
			//本番サーバの場合とテストサーバの場合での動作
			if($productionServer){
				//本番サーバ用	
				$contentsItemList .= " <a href=\"index".$getListNext.$fileExtension."\" title=\"".metaTagTitle($addressDataArray,$langId,"next")."\">\n";
			}else{
				//テストサーバ用
				$contentsItemList .= " <a href=\"index".$fileExtension."?list=".$getListNext."\" title=\"".metaTagTitle($addressDataArray,$langId,"next")."\">\n";
			}
			//コンテンツディレクトリ第一階層(外国語は1階層下げ)以上ならば、つまりコンテンツアイテムのページならば以下の処理を行う	
			if($addressDataArray[0] >= $levelContentsDirectory){
				$contentsItemList .= "   ".$titleDataArray[$langId][0].":".$nextPageString."&gt;&gt;\n";
			}else{
				$contentsItemList .= "   ".$categoryDataArray[$langId][0].":".$nextPageString."&gt;&gt;\n";
			}
			$contentsItemList .= "	</a>\n";
			$contentsItemList .= "</div>\n";
		}
	}else{
		$contentsItemList = $notFoundDataString;
	}

	return $contentsItemList;

}

/*
 * DOCTYPE宣言
 * 
 * @param array　$addressDataArray　ページアドレス情報の配列
 * @retuen string DOCTYPE宣言をhtmlタグで返す。
 * 
 * @todo 多言語設定用
 * @since 2014/09/21
 * @version 1.1
 * 
*/
function documentType($addressDataArray){

	$documentType = "<!DOCTYPE html>\n";

	return $documentType;
}

/*
 * footer
 * 
 * @param array　$addressDataArray　ページアドレス情報の配列
 * @param string　$viewMode　ブラウザの表示形式
 * @retuen string footer文をhtmlタグで返す。
 * 
 * @date 2017/07/24
 * @since 2014/11/12
 * @version 1.5
 * 
*/
function footterText($addressDataArray){

	//著作権
	global $copyright;
	//google検索エンジン
	global $googleSearchEngine;
	//言語コード
	global $langCode;
	//多言語対応か否かの判別
	global $multiLangMode;
	//表示モード
	global $viewMode;

	//PageTopに戻るリンクfooter用取得
	$backPagetopFooter = backPagetopFooter();

	$footerText  = "<footer id=\"footer\">\n";
	//$footerText  = "<div  id=\"footer\">\n";
	$footerText .= "	<br>\n";
	$footerText .= "	<hr>\n";
	$footerText .= $backPagetopFooter;
	$footerText .= "	<div  class=\"footerLogo\">\n";
	$footerText .= "		<div class=\"footerLogoImg\">\n";
	$footerText .= logoManagedBy($addressDataArray);
	$footerText .= "		<br>\n";
	$footerText .= logomailaddress($addressDataArray);
	$footerText .= "		</div>\n";
	//広告
	$footerText .= "		<div class=\"footerLogoAd\">\n";
	$footerText .= googleAdFooterRightMain($addressDataArray);
	$footerText .= "		</div>\n";
	$footerText .= "	</div>\n";

	//infoリンク
	$footerText .= 	menubarNaviInfo($addressDataArray);

	//検索エンジン
	//スマートフォン対応サイトの場合の処理
	if($viewMode == "SMP"){
		//多言語設定挿入
		if($multiLangMode == true){
			$footerText .= langaugesNavi($addressDataArray)."\n";
		}
		$footerText .= "	<div class=\"logoSearch\">\n";
		$footerText .= $googleSearchEngine;
		$footerText .= "	</div>\n";
	}
	
	$footerText .= "	<div class=\"modified\">\n";
	$footerText .= "		<span class=\"modified\">\n";


	//最終更新日は存在意義が見いだせないので一旦削除
	//$footerText .= "last modified ". multiLangDate(getlastmod());
	//$footerText .= "			&nbsp;&nbsp;\n";
	$footerText .= "			JAPAN&nbsp;\n";
	$footerText .= "			<img src=\"".linkLevel($addressDataArray[0])."images/nippon.gif\" alt=\"Japanese Flag\" title=\"JAPAN\">\n";
	$footerText .= "		</span>\n";
	$footerText .= "	</div>\n";
	//スマートフォン対応サイトの場合の処理
	$footerText .= responsiveFooter($addressDataArray);
	$footerText .= "	<div class=\"modified\">\n";
	$footerText .= $copyright."\n";
	$footerText .= "	</div>\n";
	//$footerText .= "</div>\n";
	$footerText .= "</footer>\n";

		return $footerText;
}

/*
 * bodyのコンテンツHeader文
 * <br>
 * カテゴリ名とコンテンツアイテムタイトルかwelcome表示か 
 * 
 * @param array　$addressDataArray　ページアドレス情報の配列
 * @retuen string bodyのコンテンツHeader文をhtmlタグで返す。
 * 
 * @date 2015/09/28
 * @since 2014/11/04
 * @version 1.2
 * 
*/
function headerText($addressDataArray){

	//言語ID
	global $langId;
	//ドメイントップ階層(外国語は1階層下げ)
	global $levelDomain;
	//タイトル
	global $titleDataArray;
	//表示モード
	global $viewMode;

	$headerText  = "<header id=\"header\">\n";
	//$headerText  = "<div id=\"header\">\n";
	$headerText .= logo($addressDataArray);
	$headerText .= menubarNavi($addressDataArray);

	//ドメイントップページとそうでない場合とで処理を分ける。ドメイントップページはwelcome表示する。
	//if ($addressDataArray[0] != $levelDomain){
	//	$headerText .= categoryTitleText($addressDataArray,$titleDataArray[$langId]);
	//}else{
	//	$headerText .= categoryTitleWelcomeText($addressDataArray);
	//}
	
	//ドメイントップページとそれ以下の階層ページとで処理を分ける。ドメイントップページはコメントを表示する。	
	if ($addressDataArray[0] == $levelDomain){
		$headerText .= categoryTitleWelcomeText($addressDataArray);
	}else{
		$headerText .= categoryTitleText($addressDataArray,$titleDataArray[$langId]);
	}
	
	//$headerText .= "</div>\n";
	$headerText .= "</header>\n";
	
	return $headerText;
}

/*
 * html出力
 * <br>
 * 一番最初に読み込まれるfunction
 * 
 * @retuen string html全文を返す。
 * 
* @date 2015/09/27
 * @since 2014/10/11
 * @version 1.2
 * 
*/
function htmlOutput(){

	//ページ情報
	global $addressDataArray;
	//カテゴリ情報
	global $categoryDataArray;
	//コンテンツカテゴリID
	global $contentsCategoryId;
	//コンテンツアイテム情報
	global $contentsItemDataArray;
	//コンテンツアイテムID
	global $contentsItemId;
	//コンテンツアイテムのページか否か
	global $contentsItemPage;
	//言語コード
	global $langCode;
	//言語ディレクトリ
	global $langDirectory;
	//言語ID
	global $langId;
	//第三階層(外国語は1階層下げ)
	global $levelContentsDirectory;
	//一覧表示数
	global $listCountArray;
	//データありません文を取得する。
	//global $notFoundDataString;
	//タイトル
	global $titleDataArray;
	//表示モード
	global $viewMode;
	//ページ情報を配列に入れる
	$addressDataArray = addressData(addressScriptName());
	
	//多言語設定開始
	//言語コードを取得する
	$langCode = langCode($addressDataArray);
	//言語ディレクトリを取得する
	$langDirectory = langDirectory($addressDataArray);
	//言語IDを取得する
	$langId = langId($addressDataArray);
	//カテゴリレベルを取得する　戻り値無し
	categoryLevel($addressDataArray);
	//多言語設定終了

	//文字列
	//ドメインタイトルを取得する
	domainTitle();

	//カテゴリデータを取得する
	$categoryDataArray = categoryData($addressDataArray);

	//一覧表示のページかコンテンツのページかで判定を分ける
	$contentsItemPage = contentsItemPage($addressDataArray);
	//一覧表示数を取得する
	$listCountArray = listCount();

	//コンテンツディレクトリ以下のページならばコンテンツに関する情報を取得する
	if($addressDataArray[0] >= $levelContentsDirectory){
	//if($contentsItemPage){
		$contentsItemDataArray = get_contentsItemDataArray($addressDataArray);
		if(isset($contentsItemDataArray["CONTENTSITEM_ID"]) && isset($contentsItemDataArray["CONTENTSCATEGORY_ID"])){
			$contentsItemId = $contentsItemDataArray["CONTENTSITEM_ID"];
			$contentsCategoryId = $contentsItemDataArray["CONTENTSCATEGORY_ID"];
		}else{
			$contentsItemId = NULL;
		}
	}

	//タイトル取得する。
	$titleDataArray = addressTitle($addressDataArray);
//print "タイトル<br>";
//print "コンテンツアイテムID:";
//print $contentsItemId;	
//print "<br>コンテンツカテゴリID:";
//print $contentsCategoryId;

//print "<br>コンテンツタイトル<br>";
//print_r($titleDataArray);
	//PCかスマートフォンかを判別する
	//クラスの呼び出し
	$resposiveWeb = new resposiveWeb;
	//ブラウザの表示形式の取得
	$resposiveWeb->setViewMode();
	$viewMode = $resposiveWeb->getViewMode();
//print $viewMode;

	//html生成
	//ドキュメントのタイプ
	$htmlOutput  = documentType($addressDataArray);
	$htmlOutput .= "<html lang=\"".$langCode."\">\n";
	$htmlOutput .= "	<head>\n";
	$htmlOutput .= metaTagData($addressDataArray);
	$htmlOutput .= "	</head>\n";
	$htmlOutput .= "	<body>\n";
	//スマートフォンでパソコン用画面を見た場合の処理
	$htmlOutput .= responsiveHeader($addressDataArray);
	$htmlOutput .= headerText($addressDataArray);
	//スマホとPCの場合で見せ方を変える
	if($viewMode == "SMP"){
		$htmlOutput .= topContentsNaviSMP($addressDataArray);
	}else{
		$htmlOutput .= leftContentsNavi($addressDataArray);
	}
	$htmlOutput .= centerContents($addressDataArray);
	//スマホでは表示しない
	if($viewMode != "SMP"){
		$htmlOutput .= rightContentsNavi($addressDataArray);
	}
	$htmlOutput .= footterText($addressDataArray);
	$htmlOutput .= "	</body>\n";
	$htmlOutput .= "</html>\n";

	return $htmlOutput;

}

/*
 * ドメインロゴ画像
 * 
 * @param array　$addressDataArray　ページアドレス情報の配列
 * @retuen string ドメインロゴ画像文をhtmlタグで返す。
 * 
 * @since 2014/11/06
 * @version 1.1
 * 
*/
function logo($addressDataArray){

	//ドメインタイトルの取得
	global $domainTitle;
	//google検索エンジン
	global $googleSearchEngine;
	//ロゴ画像用Alt文字取得
	global $logoAltString;
	//ロゴ画像用タイトル取得
	global $logoTitleString;
	//表示モード
	global $viewMode;

	$logo  = "<div  class=\"logo\">\n";
	$logo .= "	<div  class=\"logoTitle\">\n";

	//第一階層　index
	if($addressDataArray[0] == 1){
		$logo .= "		<a href=\"".linkLevel($addressDataArray[0])."\" target=\"_top\">\n";
		$logo .= "		<img src=\"images/title.gif\" alt=\"".$logoAltString."\" title=\"".$logoTitleString."\" id=\"pagetop\" name=\"pagetop\">\n";
		$logo .= "		</a>\n";
	//第二階層以降　
	}else if($addressDataArray[0] >= 2){
		$logo .= "		<a href=\"".linkLevel($addressDataArray[0])."\" target=\"_top\">\n";
		$logo .= "		<img src=\"".linkLevel($addressDataArray[0])."images/title.gif\" alt=\"".$domainTitle."\" title=\"".$domainTitle."\" id=\"pagetop\" name=\"pagetop\">\n";
		$logo .= "		</a>\n";
	//リンクなし
	}else{		
		$logo .= "		<img src=\"images/title.gif\" alt=\"".$domainTitle."\" title=\"".$domainTitle."\" class=\"logo\">\n";
	}					
	$logo .= "	</div>\n";

	//スマートフォンの場合とそれ以外の表示で処理を分ける
	if($viewMode == "SMP"){
		$logo .= "</div>\n";
		$logo .= "<div class=\"googleAdSMPBanner\">\n";
		$logo .= googleAdSMPBannerDisplayText($addressDataArray)."\n";
		
	}else{
		//広告
		$logo .= "	<div class=\"logoAd\">\n";
		$logo .= googleAdTitleRightMain($addressDataArray)."\n";
		$logo .= "	</div>\n";

		//検索エンジン
		$logo .= "	<div class=\"logoSearch\">\n";
		$logo .= googleSearchEngine();
		$logo .= "	</div>\n";
	}
	$logo .= "</div>\n";

	return $logo;

}



/*
 * mailaddress
 * 
 * @param array　$addressDataArray　ページアドレス情報の配列
 * @retuen string mailaddress文をhtmlタグで返す。
 * 
 * @since 2014/09/20
 * @version 1.1
 * 
*/
function logoMailaddress($addressDataArray){

	//メールアドレス
	global $eMailAddress; 	

	//電子メール文言を取得する
	$eMailSting = eMailString();

	$logoMailaddress  = "<a href=\"mailto:".$eMailAddress."\">\n";
	$logoMailaddress .= "	<img src=\"".linkLevel($addressDataArray[0])."images/mailaddress.gif\" alt=\"".$eMailSting."\" title=\"".$eMailSting."\" class=\"footerImg\">\n";
	$logoMailaddress .= "</a>\n";

	return $logoMailaddress;
}

/*
 * ManagedBylogo画像
 *
 * @param array　$addressDataArray　ページアドレス情報の配列
 * @retuen string Producerlogo画像文をhtmlタグで返す。
 *
 * @date 2017/07/24
 * @since 2014/11/06
 * @version 1.3
 *
 */
function logoManagedBy($addressDataArray){

	//言語ディレクトリ
	global $langDirectory;

	//ファイル拡張子を取得する
	global $fileExtension;

	//管理人文字列
	$logoManagedByString = logoManagedByString();

	$logoManagedBy  = "<a href=\"".linkLevel($addressDataArray[0]).$langDirectory."info/managed_by".$fileExtension."\">\n";
	$logoManagedBy .= "	<img src=\"".linkLevel($addressDataArray[0])."images/managed_by.gif\" alt=\"".$logoManagedByString."\" title=\"".$logoManagedByString."\" class=\"footerImg\">\n";
	$logoManagedBy .= "</a>\n";

	return $logoManagedBy;

}

/*
 * headタグ内のメタタグ
 * 
 * @param array　$addressDataArray　ページアドレス情報の配列
 * @retuen string headタグ内のメタタグ文をhtmlタグで返す。
 * 
 * @todo 関数名を変える必要性
 * @date 2015/09/27
 * @since 2015/07/14
 * @version 1.4
 * 
*/
function metaTagData($addressDataArray){

	//アーカイブスカテゴリディレクトリ
	global $archivesCategoryDirectory;
	//カテゴリデータ
	global $categoryDataArray;
	//ドメインタイトル取得
	//global $domainTitle;
	//言語コード
	global $langCode;
	//言語ID
	global $langId;
	//ドメイントップ階層(外国語は1階層下げ)
	global $levelDomain;
	//カテゴリトップ階層(外国語は1階層下げ)
	global $levelContentsCategory;
	//一覧表示数
	global $listCountArray;
	//metaタグ
	//global $metaTag;
	//タイトル
	//global $titleDataArray;
	//表示モード
	global $viewMode;

	//メタタグキーワード取得
	$metaTagKeyword = metaTagKeyword();

	$metaTagData  = "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";

	//一覧の件数表示
	$listCountWord = listCountWord("current",$langId,$listCountArray);

	//もしトップページならば
	if($addressDataArray[0] == $levelDomain){
		//一覧の件数が必要な場合
		if($listCountWord != ""){
			//DescriptionはドメインTOPページのみ表示することとする
			//$metaTagData .= "<meta name=\"Description\" content=\"".$categoryDataArray[$langId][1].$listCountWord."\">\n";
		}else{
			$metaTagData .= "<meta name=\"Description\" content=\"".$categoryDataArray[$langId][1]."\">\n";
		}
	//カテゴリトップならば
	}else if ($addressDataArray[0] == $levelContentsCategory){

		//DescriptionはドメインTOPページのみ表示することとする
		//一覧の件数が必要な場合
		//if($listCountWord != ""){
		//	$metaTagData .= "<meta name=\"Description\" content=\"".$categoryDataArray[$langId][1].$listCountWord."\">\n";
		//}else{
		//	$metaTagData .= "<meta name=\"Description\" content=\"".$categoryDataArray[$langId][1]."\">\n";
		//}
	}else{
	
	}
	$metaTagData .= "<meta name=\"Keywords\" content=\"".$metaTagKeyword."".$categoryDataArray[$langId][2]."\">\n";

	//スマートフォンの場合viewportを追加する。
	if($viewMode == "SMP"){
		$metaTagData .= "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
		//$metaTagData .= "<meta name=\"viewport\" content=\"width=480px\">\n";
	}else if($viewMode == "SMPtoPC"){
		//$metaTagData .= "<meta name=\"viewport\" content=\"target-densitydpi=device-dpi, initial-scale=1.0\">\n";
		//$metaTagData .= "<meta name=\"viewport\" content=\"target-densitydpi=device-dpi, width=device-width, maximum-scale=1.0\">\n";
		//$metaTagData .= "<meta name=\"viewport\" content=\"target-densitydpi=device-dpi, width=device-width, maximum-scale=1.0, user-scalable=yes\">\n";
	}else{

	}

	$metaTagData .= "<title>";
	//メタタグ用のタイトルを取得する
	$metaTagData .= metaTagTitle($addressDataArray,$langId,"current");
	$metaTagData .= "</title>\n";

	//javascript設定
	
	//IE9未満用設定
	$metaTagData .= "<!--[if lt IE 9]>\n";

	//HTML5 Shiv 3.7.2取得
	$metaTagData .= "<script src=\"".linkLevel($addressDataArray[0])."js/html5shiv-printshiv.js\"></script>\n";
	//jQuery1.11.1取得
	$metaTagData .= "<script type=\"text/javascript\" src=\"".linkLevel($addressDataArray[0])."js/jquery-1.11.1.js\"></script>\n";
	//selectivizr v1.0.2取得
	$metaTagData .= "<script type=\"text/javascript\" src=\"".linkLevel($addressDataArray[0])."js/selectivizr.js\"></script>\n";
	//IE9未満用設定終わり
	$metaTagData .= "<![endif]-->\n";
	//jQuery取得
	$metaTagData .= "<script type=\"text/javascript\" src=\"".linkLevel($addressDataArray[0])."js/jquery-2.1.1.js\"></script>\n";
	//jQuerycookie取得
	$metaTagData .= "<script type=\"text/javascript\" src=\"".linkLevel($addressDataArray[0])."js/jquery.cookie.js\"></script>\n";

	//responsive-web取得
	$metaTagData .= "<script type=\"text/javascript\" src=\"".linkLevel($addressDataArray[0])."js/responsive-web.js\"></script>\n";

	//多言語設定されたページのヘッダ情報
	$metaTagData .= langaugesNaviHeadLink($addressDataArray);
	
	//CSS設定
	//スマートフォンの場合SMP用のCSSを読み込む
	if($viewMode == "SMP"){
		$metaTagData .= "<link rel=\"stylesheet\" href=\"".linkLevel($addressDataArray[0])."css/smp/smpbody.css\" type=\"text/css\">\n";
		$metaTagData .= "<link rel=\"stylesheet\" href=\"".linkLevel($addressDataArray[0])."css/smp/smpcontents.css\" type=\"text/css\">\n";
		$metaTagData .= "<link rel=\"stylesheet\" href=\"".linkLevel($addressDataArray[0])."css/contents-text.css\" type=\"text/css\">\n";
		//$metaTagData .= "<link rel=\"stylesheet\" href=\"".linkLevel($addressDataArray[0])."css/smp/smpfooter.css\" type=\"text/css\">\n";
		$metaTagData .= "<link rel=\"stylesheet\" href=\"".linkLevel($addressDataArray[0])."css/smp/smpheader.css\" type=\"text/css\">\n";
		$metaTagData .= "<link rel=\"stylesheet\" href=\"".linkLevel($addressDataArray[0])."css/smp/smplangauges.css\" type=\"text/css\">\n";
		//$metaTagData .= "<link rel=\"stylesheet\" href=\"".linkLevel($addressDataArray[0])."css/smp/smpimages.css\" type=\"text/css\">\n";
		//$metaTagData .= "<link rel=\"stylesheet\" href=\"".linkLevel($addressDataArray[0])."css/smp/smpinforss.css\" type=\"text/css\">\n";
		$metaTagData .= "<link rel=\"stylesheet\" href=\"".linkLevel($addressDataArray[0])."css/smp/smpmenu.css\" type=\"text/css\">\n";
		$metaTagData .= "<link rel=\"stylesheet\" href=\"".linkLevel($addressDataArray[0])."css/original.css\" type=\"text/css\">\n";
		$metaTagData .= "<link rel=\"stylesheet\" href=\"".linkLevel($addressDataArray[0])."css/smp/smpoutside.css\" type=\"text/css\">\n";
	}else{
		$metaTagData .= "<link rel=\"stylesheet\" href=\"".linkLevel($addressDataArray[0])."css/body.css\" type=\"text/css\">\n";
		$metaTagData .= "<link rel=\"stylesheet\" href=\"".linkLevel($addressDataArray[0])."css/contents.css\" type=\"text/css\">\n";
		$metaTagData .= "<link rel=\"stylesheet\" href=\"".linkLevel($addressDataArray[0])."css/contents-text.css\" type=\"text/css\">\n";
		$metaTagData .= "<link rel=\"stylesheet\" href=\"".linkLevel($addressDataArray[0])."css/footer.css\" type=\"text/css\">\n";
		$metaTagData .= "<link rel=\"stylesheet\" href=\"".linkLevel($addressDataArray[0])."css/header.css\" type=\"text/css\">\n";
		$metaTagData .= "<link rel=\"stylesheet\" href=\"".linkLevel($addressDataArray[0])."css/langauges.css\" type=\"text/css\">\n";
		$metaTagData .= "<link rel=\"stylesheet\" href=\"".linkLevel($addressDataArray[0])."css/images.css\" type=\"text/css\">\n";
		$metaTagData .= "<link rel=\"stylesheet\" href=\"".linkLevel($addressDataArray[0])."css/inforss.css\" type=\"text/css\">\n";
		$metaTagData .= "<link rel=\"stylesheet\" href=\"".linkLevel($addressDataArray[0])."css/menu.css\" type=\"text/css\">\n";
		$metaTagData .= "<link rel=\"stylesheet\" href=\"".linkLevel($addressDataArray[0])."css/original.css\" type=\"text/css\">\n";
		$metaTagData .= "<link rel=\"stylesheet\" href=\"".linkLevel($addressDataArray[0])."css/outside.css\" type=\"text/css\">\n";
}

	//もしトップページならばrss.xmlへのリンクを設定する
	if($addressDataArray[0] == $levelDomain){
		//$metaTagData .= "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"rss\" href=\"".linkLevel($addressDataArray[0]).$archivesCategoryDirectory."rss/index.xml\">\n";
		$metaTagData .= "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"rss\" href=\"".$archivesCategoryDirectory."rss/index.xml\">\n";
	}
	$metaTagData .= metaTag();

	return $metaTagData;
}

/*
 * テスト用
 * 
 * @param array　$addressDataArray　ページアドレス情報の配列
 * @retuen string テスト文を返す。
 * 
 * @since 2014/09/11
 * @version 1.0 
 * 
*/
function testSystem(){

//		$testSystem = "panda";
		$testSystem = addressData(addressScriptName());
	
	return $testSystem;

}

?>