<?php
/**
 * yottanote.com CMS 
 *
 * コンテンツアイテムに関連した情報を表示し別のページに案内する。
 * <br>
 * ヨタノートの独自設定あり
 *
 * @author yottanote.com
 * @date 2016/02/21
 * @since 2014/07/06
 * @version 1.7
 * 
 *  copyright yottanote.com
 */

/*
 * 中央にあるメインコンテンツの案内
 * <br>
 * 各ドメイン共通
 * 
 * @param array　$addressDataArray　ページアドレス情報の配列
 * @param $contentsItemDataArray コンテンツアイテム配列
 * @retuen string メインコンテンツ投稿日以下の情報を取得しhtmlタグで返す。
 * 
 * @date 2016/02/20 
 * @since 2014/07/04
 * @version 1.4
 * 
*/
function centerContentsNavi($addressDataArray,$contentsItemDataArray){

	//アーカイブスカテゴリディレクトリ
	global $archivesCategoryDirectory;
	//ファイルのアドレスを取得する
	global $fileAddress;
	//ファイル拡張子を取得する
	global $fileExtension;
	//言語ディレクトリ
	global $langDirectory;
	//コンテンツディレクトリ第一階層(外国語は1階層下げ)
	global $levelContentsDirectory;

	//最大表示件数を取得する。
	global $listrelatedMax;
	//コンテンツカテゴリ一覧の検索を行う
	$limitCountArray[0] = 0;
	$limitCountArray[1] = $listrelatedMax;

	//表示モード
	global $viewMode;

	//PREVNEXT用
	//カテゴリデータ
	global $categoryDataArray;
	//ドメインタイトル
	global $domainTitle;
	//言語ID
	global $langId;

	//前の記事の文字列を取得する
	$prevContentsString = prevContentsString();
	//次の記事の文字列を取得する
	$nextContentsString = nextContentsString();

	//PREVNEXT終わり

	//写真撮影日の文字列を取得する
	$photoDateString = photoDateString();
	//投稿日の文字列を取得する
	$postedDateString = postedDateString();
	//関連記事文字列を取得する
	$relatedContentsString = relatedContentsString();
	//同一撮影日の別記事文字列を取得する
	$samePhotoDateString = samePhotoDateString();

	//一覧表示のページでなくコンテンツのページの場合は投稿日と撮影日、及び関連記事を表示する。
	$centerContentsNavi  = "";

	//SQLで撮影日と投稿日を検索する
	//$photoPostedKeywordArray = selectPhotoPosted($addressDataArray);
	$photoPostedKeywordArray = $contentsItemDataArray;
//print_r($photoPostedKeywordArray);
	$centerContentsNavi .= "<p>\n";
	//撮影日が存在する場合
	if($photoPostedKeywordArray["PHOTO_DATE"] != ""){
		$centerContentsNavi .= $photoDateString.":".multiLangDate(strtotime($photoPostedKeywordArray["PHOTO_DATE"]))."\n";
		$centerContentsNavi .= "<br>\n";
	}
	//投稿日が存在する場合
	if($photoPostedKeywordArray["POSTED_DATE"] != ""){
		$centerContentsNavi .= $postedDateString.":".multiLangDate(strtotime($photoPostedKeywordArray["POSTED_DATE"]))."\n";
	}
	$centerContentsNavi .= "</p>\n";
//print $photoPostedKeywordArray["PHOTO_DATE"];

	$centerContentsNavi .= "<nav>\n";

	//ユーザーからの反応記入
	$centerContentsNavi .= "<p>\n";
	$centerContentsNavi .= userResponseMenu($fileAddress);
	$centerContentsNavi .= "</p>\n";

	//ユーザーへの外部リンク
	$centerContentsNavi .= "<p>\n";
	$centerContentsNavi .= userExternalLinkMenu($addressDataArray);
	$centerContentsNavi .= "</p>\n";

	//スマートフォンの場合SMP用の広告を読み込む
	if($viewMode == "SMP"){
		$centerContentsNavi .= "<div class=\"googleAdSMPRectangle\">\n";
		$centerContentsNavi .= googleAdSMPRectangleDisplayText($addressDataArray)."\n";
		$centerContentsNavi .= "</div>\n";
	}

	$centerContentsNavi .= "</nav>\n";

	//投稿日が存在する場合
	if($photoPostedKeywordArray["PHOTO_DATE"] != ""){

		//同一撮影日別記事に必要な情報を配列に代入する
		$addressRelatedArray[0] = $photoPostedKeywordArray["CONTENTSITEM_ID"];
		$addressRelatedArray[1] = $photoPostedKeywordArray["PHOTO_DATE"];

		//同一撮影日別記事を検索する
		$samePhotoListResult = selectContentsitemSamePhotoList($addressRelatedArray,$limitCountArray);	

		//コンテンツアイテムがあるのなら表示する。
		if(is_array($samePhotoListResult[0]) == true){

			$centerContentsNavi .= "<nav id=\"contentsSamePhotoList\" class=\"clearfix\">\n";
			//$centerContentsNavi .= "<div id=\"contentsSamePhotoList\" class=\"clearfix\">\n";
			$centerContentsNavi .= "<span>\n";
			$centerContentsNavi .= $samePhotoDateString."\n";
			$centerContentsNavi .= "</span>\n";
			$centerContentsNavi .= "<span>\n";
			$centerContentsNavi .= "<ul>\n";
	
			foreach($samePhotoListResult[0] as $i => $row){
	
				$centerContentsNavi .= "<li>\n";
				//一覧展開
				//$centerContentsNavi .= $row["CONTENTSCATEGORY_NAME"]."&nbsp;";
				//リンク展開
				$centerContentsNavi .= "<a href=\"".linkLevel($addressDataArray[0]).$langDirectory.$archivesCategoryDirectory.$row["CONTENTSCATEGORY_DIRECTORY"]."/".$row["DIRECTORY_NAME"]."/".indexFile($row["FILE_NAME"].$fileExtension)."\" target=\"_top\" title=\"".$row["TITLE"]."\">\n";

				//一覧リストのサムネイル画像表示
				//一覧表示用のサムネイル画像タグ取得
				$centerContentsNavi .= imageListThumbnailTag($addressDataArray, $row, "bannerListThumbnail")."\n";
				$centerContentsNavi .= $row["TITLE"]."\n";
				$centerContentsNavi .= "</a>\n";
				$centerContentsNavi .= "</li>\n";
			}
	
			$centerContentsNavi .= "</ul>\n";
			$centerContentsNavi .= "</span>\n";
			//$centerContentsNavi .= "</div>\n";
			$centerContentsNavi .= "</nav>\n";

	
		}else{
			//$centerContentsNavi .= "同一撮影日の別記事がありません。　Not Found Related Item.";
		}
	}

	//キーワードが存在する場合
	if($photoPostedKeywordArray["KEYWORD"] != ""){

		//関連記事に必要な情報を配列に代入する
		$addressRelatedArray[0] = $photoPostedKeywordArray["CONTENTSITEM_ID"];
		$addressRelatedArray[1] = $photoPostedKeywordArray["KEYWORD"];
		$addressRelatedArray[2] = $photoPostedKeywordArray["PHOTO_DATE"];

		//関連記事を検索する
		$relatedListResult = selectContentsitemRelatedList($addressRelatedArray,$limitCountArray);	

		//コンテンツアイテムがあるのなら表示する。
		if(is_array($relatedListResult[0]) == true){

			$centerContentsNavi .= "<nav id=\"contentsRelatedList\" class=\"clearfix\">\n";
			//$centerContentsNavi .= "<div id=\"contentsRelatedList\" class=\"clearfix\">\n";
			$centerContentsNavi .= "<span>\n";
			$centerContentsNavi .= $relatedContentsString."\n";
			$centerContentsNavi .= "</span>\n";
	
			$centerContentsNavi .= "<span>\n";
			$centerContentsNavi .= "<ul>\n";
	
			foreach($relatedListResult[0] as $i => $row){
	
				$centerContentsNavi .= "<li>\n";
				//一覧展開
				//$centerContentsNavi .= $row["CONTENTSCATEGORY_NAME"]."&nbsp;";
				$centerContentsNavi .= "<a href=\"".linkLevel($addressDataArray[0]).$langDirectory.$archivesCategoryDirectory.$row["CONTENTSCATEGORY_DIRECTORY"]."/".$row["DIRECTORY_NAME"]."/".indexFile($row["FILE_NAME"].$fileExtension)."\" target=\"_top\" title=\"".$row["TITLE"]."\">\n";

				//一覧リストのサムネイル画像表示
				//一覧表示用のサムネイル画像タグ取得
				$centerContentsNavi .= imageListThumbnailTag($addressDataArray, $row, "bannerListThumbnail")."\n";

				$centerContentsNavi .= $row["TITLE"]."\n";
				$centerContentsNavi .= "</a>\n";
				$centerContentsNavi .= "</li>\n";
			}
	
			$centerContentsNavi .= "</ul>\n";
			$centerContentsNavi .= "</span>\n";
			//$centerContentsNavi .= "</div>\n";
			$centerContentsNavi .= "</nav>\n";
	
		}else{
			//$centerContentsNavi .= "関連記事がありません。　Not Found Related Item.";
		}
	}
	
	//コンテンツアイテムIDが存在する場合
	if($photoPostedKeywordArray["CONTENTSITEM_ID"] != ""){

		//前の記事次の記事に必要な情報を配列に代入する
		$addressRelatedArray[0] = $photoPostedKeywordArray["CONTENTSITEM_ID"];
		$addressRelatedArray[1] = $photoPostedKeywordArray["POSTED_DATE"];
		$addressRelatedArray[2] = $photoPostedKeywordArray["CONTENTSCATEGORY_ID"];

		//前の記事次の記事を検索する
		$prevNextResult = selectContentsitemPrevNext($addressRelatedArray);	


		//コンテンツアイテムがあるのなら表示する。
		if(is_array($prevNextResult) == true){

			$centerContentsNavi .= "<nav id=\"contentsPrevNext\" class=\"clearfix\">\n";
			//$centerContentsNavi .= "<div id=\"contentsPrevNext\" class=\"clearfix\">\n";

			//ドメイン前の記事がある場合
			if(isset($prevNextResult["domainPrev"]) == true){
				$centerContentsNavi .= "<div class=\"clearfix\">\n";

				$centerContentsNavi .= "<span>\n";
				$centerContentsNavi .= $domainTitle."&nbsp;".$prevContentsString."\n";
				$centerContentsNavi .= "</span>\n";

				$centerContentsNavi .= "<span>\n";
				$centerContentsNavi .= "<ul>\n";
	
				$centerContentsNavi .= "<li>\n";
				//一覧展開
				//$centerContentsNavi .= $prevNextResult["domainPrev"]["CONTENTSCATEGORY_NAME"]."&nbsp;";
				//リンク展開
				$centerContentsNavi .= "<a href=\"".linkLevel($addressDataArray[0]).$langDirectory.$archivesCategoryDirectory.$prevNextResult["domainPrev"]["CONTENTSCATEGORY_DIRECTORY"]."/".$prevNextResult["domainPrev"]["DIRECTORY_NAME"]."/".indexFile($prevNextResult["domainPrev"]["FILE_NAME"].$fileExtension)."\" target=\"_top\" title=\"".$prevNextResult["domainPrev"]["TITLE"]."\">\n";

				//一覧リストのサムネイル画像表示
				//一覧表示用のサムネイル画像タグ取得
				$centerContentsNavi .= imageListThumbnailTag($addressDataArray, $prevNextResult["domainPrev"], "bannerListThumbnail")."\n";
				$centerContentsNavi .= $prevNextResult["domainPrev"]["TITLE"]."\n";
				$centerContentsNavi .= "</a>\n";
				$centerContentsNavi .= "</li>\n";
				$centerContentsNavi .= "</ul>\n";
				$centerContentsNavi .= "</span>\n";
				$centerContentsNavi .= "</div>\n";

			}else{
				$centerContentsNavi .= "\n";
			}

			//ドメイン次の記事がある場合
			if(isset($prevNextResult["domainNext"]) == true){
				$centerContentsNavi .= "<div class=\"clearfix\">\n";

				$centerContentsNavi .= "<span class=\"contentsPrevNextss\">\n";
				$centerContentsNavi .= $domainTitle."&nbsp;".$nextContentsString."\n";
				$centerContentsNavi .= "</span>\n";

				$centerContentsNavi .= "<span>\n";
				$centerContentsNavi .= "<ul>\n";
	
				$centerContentsNavi .= "<li>\n";
				//一覧展開
				//$centerContentsNavi .= $prevNextResult["domainNext"]["CONTENTSCATEGORY_NAME"]."&nbsp;";
				//リンク展開
				$centerContentsNavi .= "<a href=\"".linkLevel($addressDataArray[0]).$langDirectory.$archivesCategoryDirectory.$prevNextResult["domainNext"]["CONTENTSCATEGORY_DIRECTORY"]."/".$prevNextResult["domainNext"]["DIRECTORY_NAME"]."/".indexFile($prevNextResult["domainNext"]["FILE_NAME"].$fileExtension)."\" target=\"_top\" title=\"".$prevNextResult["domainNext"]["TITLE"]."\">\n";

				//一覧リストのサムネイル画像表示
				//一覧表示用のサムネイル画像タグ取得
				$centerContentsNavi .= imageListThumbnailTag($addressDataArray, $prevNextResult["domainNext"], "bannerListThumbnail")."\n";
				$centerContentsNavi .= $prevNextResult["domainNext"]["TITLE"]."\n";
				$centerContentsNavi .= "</a>\n";
				$centerContentsNavi .= "</li>\n";
				$centerContentsNavi .= "</ul>\n";
				$centerContentsNavi .= "</span>\n";
				$centerContentsNavi .= "</div>\n";
			}else{
				$centerContentsNavi .= "\n";
			}

			//コンテンツカテゴリ前の記事がある場合
			if(isset($prevNextResult["categoryPrev"]) == true){
				$centerContentsNavi .= "<div class=\"clearfix\">\n";
				
				$centerContentsNavi .= "<span>\n";
				$centerContentsNavi .= $domainTitle."&nbsp;".$categoryDataArray[$langId][0]."&nbsp;".$prevContentsString."\n";
				$centerContentsNavi .= "</span>\n";

				$centerContentsNavi .= "<span>\n";
				$centerContentsNavi .= "<ul>\n";
	
				$centerContentsNavi .= "<li>\n";
				//一覧展開
				//$centerContentsNavi .= $prevNextResult["categoryPrev"]["CONTENTSCATEGORY_NAME"]."&nbsp;";
				//リンク展開
				$centerContentsNavi .= "<a href=\"".linkLevel($addressDataArray[0]).$langDirectory.$archivesCategoryDirectory.$prevNextResult["categoryPrev"]["CONTENTSCATEGORY_DIRECTORY"]."/".$prevNextResult["categoryPrev"]["DIRECTORY_NAME"]."/".indexFile($prevNextResult["categoryPrev"]["FILE_NAME"].$fileExtension)."\" target=\"_top\" title=\"".$prevNextResult["categoryPrev"]["TITLE"]."\">\n";

				//一覧リストのサムネイル画像表示
				//一覧表示用のサムネイル画像タグ取得
				$centerContentsNavi .= imageListThumbnailTag($addressDataArray, $prevNextResult["categoryPrev"], "bannerListThumbnail")."\n";
				$centerContentsNavi .= $prevNextResult["categoryPrev"]["TITLE"]."\n";
				$centerContentsNavi .= "</a>\n";
				$centerContentsNavi .= "</li>\n";
				$centerContentsNavi .= "</ul>\n";
				$centerContentsNavi .= "</span>\n";
				$centerContentsNavi .= "</div>\n";
			}else{
				$centerContentsNavi .= "\n";
			}

			//コンテンツカテゴリ次の記事がある場合
			if(isset($prevNextResult["categoryNext"]) == true){
				$centerContentsNavi .= "<div class=\"clearfix\">\n";
				
				$centerContentsNavi .= "<span>\n";
				$centerContentsNavi .= $domainTitle."&nbsp;".$categoryDataArray[$langId][0]."&nbsp;".$nextContentsString."\n";
				$centerContentsNavi .= "</span>\n";

				$centerContentsNavi .= "<span>\n";
				$centerContentsNavi .= "<ul>\n";
	
				$centerContentsNavi .= "<li>\n";
				//一覧展開
				//$centerContentsNavi .= $prevNextResult["categoryNext"]["CONTENTSCATEGORY_NAME"]."&nbsp;";
				//リンク展開
				$centerContentsNavi .= "<a href=\"".linkLevel($addressDataArray[0]).$langDirectory.$archivesCategoryDirectory.$prevNextResult["categoryNext"]["CONTENTSCATEGORY_DIRECTORY"]."/".$prevNextResult["categoryNext"]["DIRECTORY_NAME"]."/".indexFile($prevNextResult["categoryNext"]["FILE_NAME"].$fileExtension)."\" target=\"_top\" title=\"".$prevNextResult["categoryNext"]["TITLE"]."\">\n";

				//一覧リストのサムネイル画像表示
				//一覧表示用のサムネイル画像タグ取得
				$centerContentsNavi .= imageListThumbnailTag($addressDataArray, $prevNextResult["categoryNext"], "bannerListThumbnail")."\n";
				$centerContentsNavi .= $prevNextResult["categoryNext"]["TITLE"]."\n";
				$centerContentsNavi .= "</a>\n";
				$centerContentsNavi .= "</li>\n";
				$centerContentsNavi .= "</ul>\n";
				$centerContentsNavi .= "</span>\n";
				$centerContentsNavi .= "</div>\n";
			}else{
				$centerContentsNavi .= "\n";
			}

			//$centerContentsNavi .= "</div>\n";
			$centerContentsNavi .= "</nav>\n";
	
		}else{
			//$centerContentsNavi .= "前の記事も次の記事もありません。　Not Found prev contents and next contents.";
		}
	}
	
	return $centerContentsNavi;
}


/*
 * コンテンツ投稿画像ナビ
 * <br>
 * 各ドメイン共通
 * 
 * @param array　$addressDataArray ページアドレス情報の配列
 * @param boolean $categoryIndex インデックスページか否か
 * @retuen string コンテンツ投稿画像の一覧をhtmlタグで返す。
 * 
 * @since 2014/09/10
 * @version 1.0 
 * 
*/
function contentsPostedImagesNavi($addressDataArray,$categoryIndex){

	//アーカイブスカテゴリディレクトリ
	global $archivesCategoryDirectory;
	//カテゴリデータ
	global $categoryDataArray;
	//ドメインタイトル
	global $domainTitle;
	//ファイル拡張子を取得する
	global $fileExtension;
	//言語ディレクトリ
	global $langDirectory;
	//言語ID
	global $langId;
	//最大表示件数を取得する。
	global $listmenuImagesMax;
	//最大表示件数indexを取得する。
	global $listmenuImagesIndexMax;
	//データありません文を取得する。
	global $notFoundDataString;

	//新着情報を取得する
	$whatsNewString = whatsNewString();

	//階層、ページ、カテゴリのみを検索に必要なデータとして受け渡す。配列2以上が代入されるとDB検索時に支障をきたすため、別配列に代入する。
	$contentsCategoryArray[0] = $addressDataArray[0];
	$contentsCategoryArray[1] = $addressDataArray[1];
	//全ての新着情報かカテゴリ別新着情報かで処理を分ける
	if($categoryIndex == true){
		//$contentsCategoryArray[2] = $addressDataArray[2];
	}else{
		$contentsCategoryArray[2] = $addressDataArray[2];
	}
	//コンテンツカテゴリ一覧の検索を行う
	$limitCount[0] = 0;
	//トップページかカテゴリ下のページで処理を分ける
	if(!isset($addressDataArray[2])){
		$limitCount[1] = $listmenuImagesIndexMax;
	}else{
		$limitCount[1] = $listmenuImagesMax;
	}
	//コンテンツカテゴリ一覧検索SQL関数
	$listResult = selectContentsitemCategoryList($contentsCategoryArray,$limitCount);
	//コンテンツカテゴリ一覧ランダム検索SQL関数
	//$listResult = selectContentsitemCategoryRandamList($contentsCategoryArray,$limitCount);

//print "最大数は".$listResult[1];

	$contentsNewPostedImagesNavi  = "<span>\n";

	//全ての新着情報かカテゴリ別新着情報かで処理を分ける
	if($categoryIndex == true){
		$contentsNewPostedImagesNavi .= $domainTitle."&nbsp;".$whatsNewString."\n";
	}else{
		$contentsNewPostedImagesNavi .= $categoryDataArray[$langId][0]."&nbsp;".$whatsNewString."\n";
	}
	$contentsNewPostedImagesNavi .= "</span>\n";
	$contentsNewPostedImagesNavi .= "<br>\n";

	//コンテンツアイテムがあるのなら表示する。
	if(is_array($listResult[0]) == true){

		//コンテンツアイテム数を取得する
		$listResultCount = count($listResult[0]);
	
		$contentsNewPostedImagesNavi .= "<span class=\"rightContentsText\">\n";

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
				$contentsNewPostedImagesNavi .= "<div class=\"contentsItemRight\">\n";
				$contentsNewPostedImagesNavi .= 	"<div class=\"contentsItemRightL\">\n";
			//偶数行の処理　表示前
			}else if($listPosition==2){
				$contentsNewPostedImagesNavi .= 	"<div class=\"contentsItemRightR\">\n";
			//最終行の処理　表示前
			}else{
				$contentsNewPostedImagesNavi .= "<div class=\"contentsItemRight\">\n";
				$contentsNewPostedImagesNavi .= 	"<div class=\"contents\">\n";
			}
	
			//一覧展開
			$contentsNewPostedImagesNavi .= 			"<a href=\"".linkLevel($addressDataArray[0]).$langDirectory.$archivesCategoryDirectory.$row["CONTENTSCATEGORY_DIRECTORY"]."/".$row["DIRECTORY_NAME"]."/".indexFile($row["FILE_NAME"].$fileExtension)."\" target=\"_top\" title=\"".$row["TITLE"]."\">\n";
			//サムネイル画像の表示
			$contentsNewPostedImagesNavi .= imageListThumbnailTag($addressDataArray, $row, "bannerListNaviThumbnail")."\n";
			$contentsNewPostedImagesNavi .=  			"</a>\n";
			$contentsNewPostedImagesNavi .= 		"</div>\n";
	
			//偶数行と最終行の処理　表示後
			if($listPosition!=1){
				$contentsNewPostedImagesNavi .= "</div>\n";
			}
		}

		$contentsNewPostedImagesNavi .= "</span>\n";

	}else{
		$contentsNewPostedImagesNavi .= $notFoundDataString."\n";
	}

	return $contentsNewPostedImagesNavi;
}

/*
 * コンテンツ投稿画像ナビスマートフォン版
 * <br>
 * ドメインの新着情報を表示する
 * <br>
 * 各ドメイン共通
 * 
 * @param array　$addressDataArray ページアドレス情報の配列
 * @param boolean $categoryIndex インデックスページか否か
 * @retuen string コンテンツ投稿画像の一覧をhtmlタグで返す。
 * 
 * @since 2014/09/10
 * @version 1.0 
 * 
*/
function contentsPostedImagesNaviSMP($addressDataArray){

	//アーカイブスカテゴリディレクトリ
	global $archivesCategoryDirectory;


	//コンテンツアイテムID
	global $contentsItemId;
	//ドメインタイトル
	global $domainTitle;
	//ファイル拡張子を取得する
	global $fileExtension;
	//言語ディレクトリ
	global $langDirectory;

	//最大表示件数スマートフォン版を取得する。
	global $listmenuImagesMaxSMP;

	//データありません文を取得する。
	global $notFoundDataString;

	//新着情報を取得する
	$whatsNewString = whatsNewString();

	//コンテンツカテゴリ一覧の検索を行う
	$limitCountArray[0] = 0;
	$limitCountArray[1] = $listmenuImagesMaxSMP;

//print "最大数は".$listResult[1];

	//新着情報記事一覧を検索する
	$contentsitemWhatsNewList = selectContentsitemWhatsNewList($contentsItemId,$limitCountArray);	
	//$contentsitemWhatsNewList = false;	

	$contentsPostedImagesNaviSMP  ="";
	//新着情報
	$contentsPostedImagesNaviSMP .= "<div id=\"contentsWhatsNewList\" class=\"clearfix\">\n";
	$contentsPostedImagesNaviSMP .= "	<span>\n";
	$contentsPostedImagesNaviSMP .= $domainTitle.$whatsNewString."\n";
	$contentsPostedImagesNaviSMP .= "	</span>\n";
	$contentsPostedImagesNaviSMP .= "	<span>\n";

	//新着情報があるのなら表示する。
	if(is_array($contentsitemWhatsNewList[0]) == true){

		$contentsPostedImagesNaviSMP .= "		<ul>\n";
	
		foreach($contentsitemWhatsNewList[0] as $i => $row){

			$contentsPostedImagesNaviSMP .= "			<li>\n";
			//リンク展開
			$contentsPostedImagesNaviSMP .= "				<a href=\"".linkLevel($addressDataArray[0]).$langDirectory.$archivesCategoryDirectory.$row["CONTENTSCATEGORY_DIRECTORY"]."/".$row["DIRECTORY_NAME"]."/".indexFile($row["FILE_NAME"].$fileExtension)."\" target=\"_top\">\n";

			//一覧リストのサムネイル画像表示
			//一覧表示用のサムネイル画像タグ取得
			$contentsPostedImagesNaviSMP .= imageListThumbnailTag($addressDataArray, $row, "bannerListThumbnail")."\n";
			$contentsPostedImagesNaviSMP .= $row["TITLE"]."\n";
			$contentsPostedImagesNaviSMP .= "				</a>\n";
			$contentsPostedImagesNaviSMP .= "			</li>\n";
		}
			$contentsPostedImagesNaviSMP .= "		</ul>\n";
			$contentsPostedImagesNaviSMP .= "	</span>\n";


	}else{
		$contentsPostedImagesNaviSMP .= $notFoundDataString."\n";
	}
			$contentsPostedImagesNaviSMP .= "</div>\n";
			
	return $contentsPostedImagesNaviSMP;

}


/*
 * コンテンツ投稿テキストナビ
 * <br>
 * 各ドメイン共通
 * 
 * @param array　$addressDataArray ページアドレス情報の配列
 * @param boolean $categoryIndex インデックスページか否か
 * @retuen string コンテンツ投稿画像の一覧をhtmlタグで返す。
 * 
 * @since 2014/09/20
 * @version 1.0 
 * 
*/
function contentsPostedTextNavi($addressDataArray,$categoryIndex){

	//アーカイブスカテゴリディレクトリ
	global $archivesCategoryDirectory;
	//カテゴリデータ
	global $categoryDataArray;
	//ドメインタイトル
	global $domainTitle;
	//ファイル拡張子を取得する
	global $fileExtension;
	//言語ディレクトリ
	global $langDirectory;
	//言語ID
	global $langId;
	//最大表示件数を取得する。
	global $listmenuImagesMax;
	//最大表示件数indexを取得する。
	global $listmenuImagesIndexMax;
	//データありません文を取得する。
	global $notFoundDataString;

	//新着情報を取得する
	$whatsNewString = whatsNewString();

	//階層、ページ、カテゴリのみを検索に必要なデータとして受け渡す。配列2以上が代入されるとDB検索時に支障をきたすため、別配列に代入する。
	$contentsCategoryArray[0] = $addressDataArray[0];
	$contentsCategoryArray[1] = $addressDataArray[1];
	//全ての新着情報かカテゴリ別新着情報かで処理を分ける
	if($categoryIndex == true){
		//$contentsCategoryArray[2] = $addressDataArray[2];
	}else{
		$contentsCategoryArray[2] = $addressDataArray[2];
	}
	//コンテンツカテゴリ一覧の検索を行う
	$limitCount[0] = 0;
	//トップページかカテゴリ下のページで処理を分ける
	if(!isset($addressDataArray[2])){
		$limitCount[1] = $listmenuImagesIndexMax;
	}else{
		$limitCount[1] = $listmenuImagesMax;
	}
	//コンテンツカテゴリ一覧検索SQL関数
	$listResult = selectContentsitemCategoryList($contentsCategoryArray,$limitCount);
	//コンテンツカテゴリ一覧ランダム検索SQL関数
	//$listResult = selectContentsitemCategoryRandamList($contentsCategoryArray,$limitCount);

//print "最大数は".$listResult[1];

	$contentsPostedTextNavi  = "<span>\n";

	//全ての新着情報かカテゴリ別新着情報かで処理を分ける
	if($categoryIndex == true){
		$contentsPostedTextNavi .= $domainTitle."&nbsp;".$whatsNewString."\n";
	}else{
		$contentsPostedTextNavi .= $categoryDataArray[$langId][0]."&nbsp;".$whatsNewString."\n";
	}
	$contentsPostedTextNavi .= "</span>\n";
	$contentsPostedTextNavi .= "<br>\n";

	//コンテンツアイテムがあるのなら表示する。
	if(is_array($listResult[0]) == true){

		//コンテンツアイテム数を取得する
		$listResultCount = count($listResult[0]);
	
		$contentsPostedTextNavi .= "<span class=\"rightContentsText\">\n";
		$contentsPostedTextNavi .= "<ul>\n";

		foreach($listResult[0] as $i => $row){

			$contentsPostedTextNavi .= "<li>\n";
			//一覧展開
			$contentsPostedTextNavi .= "<a href=\"".linkLevel($addressDataArray[0]).$langDirectory.$archivesCategoryDirectory.$row["CONTENTSCATEGORY_DIRECTORY"]."/".$row["DIRECTORY_NAME"]."/".indexFile($row["FILE_NAME"].$fileExtension)."\" target=\"_top\">\n";
			$contentsPostedTextNavi .= $row["TITLE"]."\n";
			$contentsPostedTextNavi .= "</a>\n";
			$contentsPostedTextNavi .= "</li>\n";
		}

		$contentsPostedTextNavi .= "</ul>\n";
		$contentsPostedTextNavi .= "</span>\n";

	}else{
		$contentsPostedTextNavi .= $notFoundDataString."\n";
	}

	return $contentsPostedTextNavi;
}

/*
 * 左側コンテンツ
 * <br>
 * yottanote.com　オリジナル
 *
 * @param array　$addressDataArray　ページアドレス情報の配列
 * @retuen string 左側コンテンツをhtmlタグで返す。
 *
 * @date 2015/07/14
 * @since 2014/09/11
 * @version 1.2
 *
 */
function leftContentsNavi($addressDataArray){

	//言語コード
	global $langCode;
	//カテゴリトップ階層(外国語は1階層下げ)
	global $levelContentsCategory;
	//多言語対応か否かの判別
	global $multiLangMode;

	$leftContentsNavi = "<div id=\"leftContents\">\n";

	//多言語設定挿入
	if($multiLangMode == true){
		$leftContentsNavi .= langaugesNavi($addressDataArray)."\n";
	}
	
	$leftContentsNavi .= "	<aside>\n";

	//広告挿入
	$leftContentsNavi .= "		<div>\n";
	$leftContentsNavi .= googleAdLeftContentsWideDisplayText($addressDataArray)."\n";
	$leftContentsNavi .= "		</div>\n";
	//twitter挿入
	$leftContentsNavi .= "		<p>\n";
	$leftContentsNavi .= twitterEmbeddedTimelines($addressDataArray)."\n";
	$leftContentsNavi .= "		</p>\n";
	

	//indexページ
	if(!isset($addressDataArray[$levelContentsCategory])){

		//$leftContentsNavi .= "<br>";

	}else if($addressDataArray[$levelContentsCategory] == "archives"){

		//$leftContentsNavi .= "<br>";

	}else if($addressDataArray[$levelContentsCategory] == "linux"){

		//$leftContentsNavi .= "<br>";

	}else{

	}

	//goolge左側広告テキスト専用
	$leftContentsNavi .= "		<p>\n";
	$leftContentsNavi .= googleAdLeftContentsWideDisplay($addressDataArray)."\n";
	$leftContentsNavi .= "		</p>\n";
	$leftContentsNavi .= "	</aside>\n";
	$leftContentsNavi .= "</div>\n";

	return $leftContentsNavi;
}

/*
 * 右側コンテンツ
 * <br>
 * yottanote.com　オリジナル
 *
 * @param array　$addressDataArray　ページアドレス情報の配列
 * @retuen string 右側コンテンツをhtmlタグで返す。
 *
 * @date 2015/07/14
 * @since 2014/11/12
 * @version 1.5
 *
 */
function rightContentsNavi($addressDataArray){

	//言語ディレクトリ
	global $langDirectory;
	//カテゴリトップ階層(外国語は1階層下げ)
	global $levelContentsCategory;

	$rightContentsNavi  = "<aside id=\"rightContents\">\n";
	//$rightContentsNavi  = "<div id=\"rightContents\">\n";
	
	$rightContentsNavi .= "	<div class=\"contentsItemRightGoogle\">\n";
	$rightContentsNavi .= googleAdRightContentsWideDisplayText($addressDataArray)."\n";
	$rightContentsNavi .= "	</div>\n";

	
	if(!isset($addressDataArray[$levelContentsCategory])){

		//	$rightContentsNavi .= contentsPostedTextNavi($addressDataArray);

	}else if($addressDataArray[$levelContentsCategory] == "archives"){

		$rightContentsNavi .= contentsPostedTextNavi($addressDataArray,false);

	}else if($addressDataArray[$levelContentsCategory] == "linux"){

		$rightContentsNavi .= contentsPostedTextNavi($addressDataArray,false);

	}else{

	}

	$rightContentsNavi .= contentsPostedTextNavi($addressDataArray,true);
	//$rightContentsNavi .= "</div>\n";
	$rightContentsNavi .= "</aside>\n";
	
	return $rightContentsNavi;
}

/*
 * トップコンテンツナビスマートフォン版
 * <br>
 * 各ドメイン共通
 *
 * @param array　$addressDataArray　ページアドレス情報の配列
 * @retuen string トップコンテンツナビのスマートフォン版をhtmlタグで返す。
 *
 * @date 2015/07/14
 * @since 2014/11/07
 * @version 1.1
 *
 */
function topContentsNaviSMP($addressDataArray){

	$topContentsNaviSMP  = "";
	$topContentsNaviSMP .= "<aside>\n";
	$topContentsNaviSMP .= contentsPostedImagesNaviSMP($addressDataArray);
	$topContentsNaviSMP .= "	<div class=\"googleAdSMPRectangle\">\n";
	$topContentsNaviSMP .= googleAdSMPRectangleDisplayText($addressDataArray)."\n";
	$topContentsNaviSMP .= "	</div>\n";
	$topContentsNaviSMP .= "</aside>\n";
	
	
	return $topContentsNaviSMP;
}



/*
 * 外部へのリンクメニュー
* <br>
* yottanote.com　オリジナル
*
* @param array　$addressDataArray　ページアドレス情報の配列
* @retuen string 外部コンテンツをhtmlタグで返す。
*
* @since 2014/09/17
* @version 1.1
*
*/
function userExternalLinkMenu($addressDataArray){

	//ブログ村IT技術メモ
	global $blogmuraITtechniqueMemoExternalLink;
	//カテゴリトップ階層(外国語は1階層下げ)
	global $levelContentsCategory;
	
	//外部リンクの文言を取得する
	$externalLinkString = externalLinkString();

	$userExternalLinkMenu = "";

	if(!isset($addressDataArray[$levelContentsCategory])){

	}else if($addressDataArray[$levelContentsCategory] == "archives"){

		$userExternalLinkMenu .= $externalLinkString;
		$userExternalLinkMenu .= "<br>\n";
		$userExternalLinkMenu .= "<span>\n";
		$userExternalLinkMenu .= $blogmuraITtechniqueMemoExternalLink;
		$userExternalLinkMenu .= "</span>\n";

	}else if($addressDataArray[$levelContentsCategory] == "linux"){

		$userExternalLinkMenu .= $externalLinkString;
		$userExternalLinkMenu .= "<br>\n";
		$userExternalLinkMenu .= "<span>\n";
		$userExternalLinkMenu .= $blogmuraITtechniqueMemoExternalLink;
		$userExternalLinkMenu .= "</span>\n";

	}else{

	}

	return $userExternalLinkMenu;
}

/*
 * ユーザーからの反応メニュー
 * <br>
 * yottanote.com　オリジナル
 *
 * @param string　$fileAddress　ファイルのアドレス
 * @retuen string ユーザーからの反応を返す
 *
 * @date 2016/02/21
 * @since 2014/09/11
 * @version 1.1
 *
 */
function userResponseMenu($fileAddress){

	//twitter反応
	global $twitterUserResponse;

	$userResponseMenu = $twitterUserResponse;
	$userResponseMenu .= facebookLikeButton($fileAddress);
	

	return $userResponseMenu;
}

?>