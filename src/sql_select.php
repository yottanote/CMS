<?php
/**
 * yottanote.com CMS
 *
 * SQL検索文
 * <br>
 * 各ドメイン共通設定
 *
 * @todo selectContentsCategoryVariableにinfo用の処理を暫定的に入れた
 * @author yottanote.com
 * @date 2017/03/22
 * @since 2014/09/11
 * @version 1.5
 * 
 * copyright yottanote.com
 */

/*
 * コンテンツカテゴリ変数取得
 * <br>
 * 多言語対応済み 
 * 
 * @param array $addressDataArray アドレス(ドメイン以下のファイル名)の配列
 * @retuen array コンテンツカテゴリ変数取得結果
 * 
 * @date 2015/09/27   
 * @since 2014/09/18
 * @version 1.3
 * 
*/
function selectContentsCategoryVariable($addressDataArray){

	//カテゴリトップ階層(外国語は1階層下げ)
	global $levelContentsCategory;

	//カテゴリトップinfo階層(外国語は1階層下げ)
	global $levelContentsInfoCategory;

	//infoカテゴリ用の処理
	if(isset($addressDataArray[$levelContentsInfoCategory])){
		if ($addressDataArray[$levelContentsInfoCategory] == "info") {
			$addressDataArray[$levelContentsCategory] = "info";
		}
	}
	
	//コンテンツカテゴリが存在しない場合
	if(!isset($addressDataArray[$levelContentsCategory])){
		$addressDataArray[$levelContentsCategory] ="index";
	}
	
	$selectSQL  = "( ";
	$selectSQL .= "SELECT ";
	$selectSQL .= "CONTENTSCATEGORY.LANG_ID AS LANG_ID, ";
	$selectSQL .= "CONTENTSCATEGORY.CONTENTSCATEGORY_DIRECTORY, ";
	$selectSQL .= "CONTENTSCATEGORY_NAME, ";
	$selectSQL .= "CONTENTSCATEGORY_COMMENT, ";
	$selectSQL .= "CONTENTSCATEGORY_KEYWORDS ";
	$selectSQL .= "FROM CONTENTSCATEGORY ";
	$selectSQL .= "WHERE ";
	$selectSQL .= "CONTENTSCATEGORY_DIRECTORY = '".$addressDataArray[$levelContentsCategory]."' ";
	$selectSQL .= "AND ";
	$selectSQL .= "CONTENTSCATEGORY.DEL_FLG = 'N' ";

	$selectSQL .= ") ";
	$selectSQL .= "UNION ALL ";
	$selectSQL .= "( ";

	$selectSQL .= "SELECT ";
	$selectSQL .= "CONTENTSCATEGORYF.LANG_ID AS LANG_ID, ";
	$selectSQL .= "CONTENTSCATEGORY.CONTENTSCATEGORY_DIRECTORY, ";
	$selectSQL .= "CONTENTSCATEGORYF.CONTENTSCATEGORY_NAME, ";
	$selectSQL .= "CONTENTSCATEGORYF.CONTENTSCATEGORY_COMMENT, ";
	$selectSQL .= "CONTENTSCATEGORYF.CONTENTSCATEGORY_KEYWORDS ";
	$selectSQL .= "FROM CONTENTSCATEGORYF ";
	$selectSQL .= "LEFT OUTER JOIN ";
	$selectSQL .= "CONTENTSCATEGORY ";
	$selectSQL .= "ON ";
	$selectSQL .= "CONTENTSCATEGORYF.CONTENTSCATEGORY_ID = CONTENTSCATEGORY.CONTENTSCATEGORY_ID ";
	$selectSQL .= "WHERE ";
	$selectSQL .= "CONTENTSCATEGORY.CONTENTSCATEGORY_DIRECTORY = '".$addressDataArray[$levelContentsCategory]."' ";
	$selectSQL .= "AND ";
	$selectSQL .= "CONTENTSCATEGORYF.DEL_FLG = 'N' ";
	$selectSQL .= ") ";
	$selectSQL .= "ORDER BY ";
	$selectSQL .= "LANG_ID ASC ";

//print $selectSQL;
	$sqlResult = sqlSelectExe($selectSQL);

	while($row = mysqli_fetch_array($sqlResult,MYSQLI_ASSOC)){
		$result[$row["LANG_ID"]][] = $row;
	}


	if(isset($result) == false){
		$result = "";
	}

	return $result;
}

/*
 * コンテンツアイテムカテゴリ別一覧取得
 * <br>
 * 多言語対応済み
 * 
 * @param array $addressDataArray アドレス(ドメイン以下のファイル名)の配列
 * @param array $limitCountArray FROM TOの配列
 * @retuen array コンテンツアイテムカテゴリ別一覧取得結果
 * 
 *   
 * @date 2017/03/22
 * @since 2014/09/17
 * @version 1.3
 * 
*/
function selectContentsitemCategoryList($addressDataArray,$limitCountArray){

	//言語コード
	global $langCode;
	//言語ID
	global $langId;
	//カテゴリトップ階層(外国語は1階層下げ)
	global $levelContentsCategory;
		
	//ディレクトリを取得する
	$directoryName = directoryName($addressDataArray);
	//言語が日本語の場合
	if($langCode == "ja"){

		$selectSQL  = "SELECT ";
		$selectSQL .= "SQL_CALC_FOUND_ROWS ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID, ";
		$selectSQL .= "CONTENTSCATEGORY_DIRECTORY, ";
		$selectSQL .= "CONTENTSCATEGORY_NAME, ";
		$selectSQL .= "CONTENTSITEM.TITLE, ";
		$selectSQL .= "DIRECTORY_NAME, ";
		$selectSQL .= "FILE_NAME, ";
		$selectSQL .= "IMAGE_ALT, ";
		$selectSQL .= "IMAGE_TITLE, ";
		$selectSQL .= "IMAGE_DIRECTORY_NAME, ";
		$selectSQL .= "IMAGE_FILE_NAME, ";
		$selectSQL .= "IMAGESIZE.IMAGESIZE_CODE, ";
		$selectSQL .= "ITEMCOMMENT, ";
		$selectSQL .= "KEYWORD, ";
		$selectSQL .= "PHOTO_DATE, ";
		$selectSQL .= "POSTED_DATE ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "CONTENTSITEM.INSERT_DATE, ";
		//$selectSQL .= "CONTENTSITEM.UPDATE_DATE ";
		$selectSQL .= "FROM CONTENTSITEM ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORY ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.CONTENTSCATEGORY_ID = CONTENTSCATEGORY.CONTENTSCATEGORY_ID ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "IMAGESIZE ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.IMAGESIZE_HEIGHT = IMAGESIZE.IMAGESIZE_HEIGHT ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEM.IMAGESIZE_WIDTH = IMAGESIZE.IMAGESIZE_WIDTH ";
		$selectSQL .= "WHERE ";
		//カテゴリが設定されている場合
		if(isset($addressDataArray[$levelContentsCategory])){
			$selectSQL .= "CONTENTSCATEGORY_DIRECTORY = '".$addressDataArray[$levelContentsCategory]."' ";
			$selectSQL .= "AND ";
		}
		//第三階層以下の中で一覧を表示する場合
		if($directoryName != ""){
			$selectSQL .= "DIRECTORY_NAME = '".$directoryName."' ";
			$selectSQL .= "AND ";
		}

		//ファイル名がindexは一覧ファイルそのものなので表示しない
		$selectSQL .= "FILE_NAME != 'index' ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEM.LANG_ID = '".$langId."' ";
	
		//投稿日が過去日付のものだけ取得する。
		$selectSQL .= "AND ";
		$selectSQL .= "POSTED_DATE <= CURRENT_TIMESTAMP ";
		
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEM.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORY.DEL_FLG = 'N' ";
		$selectSQL .= "ORDER BY ";
		$selectSQL .= "POSTED_DATE DESC, ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID DESC ";
		$selectSQL .= "LIMIT ";
		$selectSQL .= $limitCountArray[0].", ";
		$selectSQL .= $limitCountArray[1]." ";

	//言語が外国語であった場合
	}else{

		$selectSQL  = "SELECT ";
		$selectSQL .= "SQL_CALC_FOUND_ROWS ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID, ";
		$selectSQL .= "CONTENTSCATEGORY.CONTENTSCATEGORY_DIRECTORY, ";
		$selectSQL .= "CONTENTSCATEGORYF.CONTENTSCATEGORY_NAME, ";
		$selectSQL .= "CONTENTSITEMF.TITLE, ";
		$selectSQL .= "CONTENTSITEM.DIRECTORY_NAME, ";
		$selectSQL .= "CONTENTSITEM.FILE_NAME, ";
		$selectSQL .= "CONTENTSITEMF.IMAGE_ALT, ";
		$selectSQL .= "CONTENTSITEMF.IMAGE_TITLE, ";
		$selectSQL .= "CONTENTSITEM.IMAGE_DIRECTORY_NAME, ";
		$selectSQL .= "CONTENTSITEM.IMAGE_FILE_NAME, ";
		$selectSQL .= "IMAGESIZE.IMAGESIZE_CODE, ";
		$selectSQL .= "CONTENTSITEMF.ITEMCOMMENT, ";
		$selectSQL .= "CONTENTSITEMF.KEYWORD, ";
		$selectSQL .= "CONTENTSITEM.PHOTO_DATE, ";
		$selectSQL .= "CONTENTSITEM.POSTED_DATE ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "CONTENTSITEMF.INSERT_DATE, ";
		//$selectSQL .= "CONTENTSITEMF.UPDATE_DATE ";
		$selectSQL .= "FROM ";

		$selectSQL .= "CONTENTSITEM ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORY ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.CONTENTSCATEGORY_ID = CONTENTSCATEGORY.CONTENTSCATEGORY_ID ";

		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSITEMF ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID = CONTENTSITEMF.CONTENTSITEM_ID ";

		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORYF ";
		$selectSQL .= "ON ";
		$selectSQL .= "( ";
		$selectSQL .= "CONTENTSCATEGORY.CONTENTSCATEGORY_ID = CONTENTSCATEGORYF.CONTENTSCATEGORY_ID ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMF.LANG_ID = CONTENTSCATEGORYF.LANG_ID ";
		$selectSQL .= ") ";

		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "IMAGESIZE ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.IMAGESIZE_HEIGHT = IMAGESIZE.IMAGESIZE_HEIGHT ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEM.IMAGESIZE_WIDTH = IMAGESIZE.IMAGESIZE_WIDTH ";
		$selectSQL .= "WHERE ";
		//カテゴリが設定されている場合
		if(isset($addressDataArray[$levelContentsCategory])){
			$selectSQL .= "CONTENTSCATEGORY.CONTENTSCATEGORY_DIRECTORY = '".$addressDataArray[$levelContentsCategory]."' ";
			$selectSQL .= "AND ";
		}
		//第三階層以下の中で一覧を表示する場合
		if($directoryName != ""){
			$selectSQL .= "CONTENTSITEM.DIRECTORY_NAME = '".$directoryName."' ";
			$selectSQL .= "AND ";
		}

		//ファイル名がindexは一覧ファイルそのものなので表示しない
		$selectSQL .= "CONTENTSITEM.FILE_NAME != 'index' ";
		$selectSQL .= "AND ";

	//	$selectSQL .= "CONTENTSITEM.LANG_ID = '".$langId."' ";
	//	$selectSQL .= "AND ";
	//	$selectSQL .= "CONTENTSCATEGORY.LANG_ID = '".$langId."' ";
	//	$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMF.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYF.LANG_ID = '".$langId."' ";
	
		//投稿日が過去日付のものだけ取得する。
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEM.POSTED_DATE <= CURRENT_TIMESTAMP ";
		
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEM.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORY.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMF.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYF.DEL_FLG = 'N' ";

		$selectSQL .= "ORDER BY ";
		$selectSQL .= "CONTENTSITEM.POSTED_DATE DESC, ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID DESC ";
		$selectSQL .= "LIMIT ";
		$selectSQL .= $limitCountArray[0].", ";
		$selectSQL .= $limitCountArray[1]." ";
	}

//print	$selectSQL;

	$sqlResult = sqlSelectListExe($selectSQL);

	//コンテンツアイテム一覧を配列に代入する
	while($row = mysqli_fetch_array($sqlResult[0],MYSQLI_ASSOC)){
		$resultList[] = $row;
	}
	if(isset($resultList) == false){
		$resultList = "";
	}

	//コンテンツアイテムの件数を変数に代入する。
	while($row = mysqli_fetch_row($sqlResult[1])){
		$resultCount = $row[0];
	}
	if(isset($resultCount) == false){
		$resultCount = 0;
	}

	return array($resultList,$resultCount);

}

/*
 * コンテンツアイテム外語外国語別登録情報一覧
 * <br>
 * コンテンツアイテムIDを基に外国語のコンテンツアイテムが登録されているか、外国語別にしてリストにする。
 * 
 * @param string $contentsItemId コンテンツアイテムID
 * @retuen array コンテンツアイテム外語外国語別登録情報一覧取得結果
 * 
 *   
 * @since 2014/10/05
 * @version 1.0
 * 
*/
function selectContentsitemfLangList($contentsItemId){

	$selectSQL  = "SELECT ";
	$selectSQL .= "CONTENTSITEMF_ID, ";
	$selectSQL .= "LANG_ID ";
	$selectSQL .= "FROM ";
	$selectSQL .= "CONTENTSITEMF ";
	$selectSQL .= "WHERE ";
	$selectSQL .= "CONTENTSITEM_ID = '".$contentsItemId."' ";
	$selectSQL .= "AND ";
	$selectSQL .= "CONTENTSITEMF.DEL_FLG = 'N' ";
	$selectSQL .= "ORDER BY ";
	$selectSQL .= "LANG_ID ASC ";
//print $selectSQL;
	$sqlResult = sqlSelectListExe($selectSQL);

	//関連項目の一覧を配列に代入する
	while($row = mysqli_fetch_array($sqlResult[0],MYSQLI_ASSOC)){
		$resultList[$row["LANG_ID"]] = $row["CONTENTSITEMF_ID"];
	}
	if(isset($resultList) == false){
		$resultList = NULL;
	}
	return $resultList;
}

/*
 * コンテンツアイテム前の記事次の記事取得
 * <br>
 * 多言語対応済み
 * 
 * @param array $addressRelatedArray 前の記事次の記事に必要な情報の配列
 * @retuen array コンテンツアイテム前の記事次の記事取得結果
 * 
 *   
 * @date 2017/03/22
 * @since 2015/09/15
 * @version 1.2
 * 
*/
function selectContentsitemPrevNext($addressRelatedArray){

	//言語コード
	global $langCode;
	//言語ID
	global $langId;

	//言語が日本語の場合
	if($langCode == "ja"){

		$selectSQL  = "( ";
		
		$selectSQL .= "SELECT ";
		$selectSQL .= "'domainPrev' AS PREVNEXT, ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID, ";
		$selectSQL .= "CONTENTSCATEGORY_DIRECTORY, ";
		$selectSQL .= "CONTENTSCATEGORY_NAME, ";
		$selectSQL .= "CONTENTSITEM.TITLE, ";
		$selectSQL .= "DIRECTORY_NAME, ";
		$selectSQL .= "FILE_NAME, ";
		$selectSQL .= "IMAGE_ALT, ";
		$selectSQL .= "IMAGE_TITLE, ";
		$selectSQL .= "IMAGE_DIRECTORY_NAME, ";
		$selectSQL .= "IMAGE_FILE_NAME, ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "IMAGESIZE.IMAGESIZE_CODE, ";
		$selectSQL .= "ITEMCOMMENT, ";
		$selectSQL .= "KEYWORD, ";
		$selectSQL .= "PHOTO_DATE, ";
		$selectSQL .= "POSTED_DATE ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "CONTENTSITEM.INSERT_DATE, ";
		//$selectSQL .= "CONTENTSITEM.UPDATE_DATE ";
		$selectSQL .= "FROM ";
		$selectSQL .= "CONTENTSITEM ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORY ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.CONTENTSCATEGORY_ID = CONTENTSCATEGORY.CONTENTSCATEGORY_ID ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "LEFT OUTER JOIN ";
		//$selectSQL .= "IMAGESIZE ";
		//$selectSQL .= "ON ";
		//$selectSQL .= "CONTENTSITEM.IMAGESIZE_HEIGHT = IMAGESIZE.IMAGESIZE_HEIGHT ";
		//$selectSQL .= "AND ";
		//$selectSQL .= "CONTENTSITEM.IMAGESIZE_WIDTH = IMAGESIZE.IMAGESIZE_WIDTH ";
		$selectSQL .= "WHERE ";
	
		//自分自身のアイテムは検索させない
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID != '".$addressRelatedArray[0]."' ";
		$selectSQL .= "AND ";

		//ファイル名がindexは一覧ファイルそのものなので表示しない
		$selectSQL .= "FILE_NAME != 'index' ";
		$selectSQL .= "AND ";
	
		//前記事を検索条件に含める
		$selectSQL .= "POSTED_DATE <= '".$addressRelatedArray[1]."' "; 
		$selectSQL .= "AND ";

		//投稿日が過去日付のものだけ取得する。
		//$selectSQL .= "POSTED_DATE <= CURRENT_TIMESTAMP ";
		//$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEM.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEM.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORY.DEL_FLG = 'N' ";
		$selectSQL .= "ORDER BY ";
		$selectSQL .= "POSTED_DATE DESC, ";
		$selectSQL .= "CONTENTSITEM.TITLE ASC, ";
		$selectSQL .= "PHOTO_DATE ASC, ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID DESC ";
		$selectSQL .= "LIMIT ";
		$selectSQL .= "1 ";

		$selectSQL .= ") ";
		$selectSQL .= "UNION ALL ";
		$selectSQL .= "( ";

		$selectSQL .= "SELECT ";
		$selectSQL .= "'domainNext' AS PREVNEXT, ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID, ";
		$selectSQL .= "CONTENTSCATEGORY_DIRECTORY, ";
		$selectSQL .= "CONTENTSCATEGORY_NAME, ";
		$selectSQL .= "CONTENTSITEM.TITLE, ";
		$selectSQL .= "DIRECTORY_NAME, ";
		$selectSQL .= "FILE_NAME, ";
		$selectSQL .= "IMAGE_ALT, ";
		$selectSQL .= "IMAGE_TITLE, ";
		$selectSQL .= "IMAGE_DIRECTORY_NAME, ";
		$selectSQL .= "IMAGE_FILE_NAME, ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "IMAGESIZE.IMAGESIZE_CODE, ";
		$selectSQL .= "ITEMCOMMENT, ";
		$selectSQL .= "KEYWORD, ";
		$selectSQL .= "PHOTO_DATE, ";
		$selectSQL .= "POSTED_DATE ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "CONTENTSITEM.INSERT_DATE, ";
		//$selectSQL .= "CONTENTSITEM.UPDATE_DATE ";
		$selectSQL .= "FROM ";
		$selectSQL .= "CONTENTSITEM ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORY ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.CONTENTSCATEGORY_ID = CONTENTSCATEGORY.CONTENTSCATEGORY_ID ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "LEFT OUTER JOIN ";
		//$selectSQL .= "IMAGESIZE ";
		//$selectSQL .= "ON ";
		//$selectSQL .= "CONTENTSITEM.IMAGESIZE_HEIGHT = IMAGESIZE.IMAGESIZE_HEIGHT ";
		//$selectSQL .= "AND ";
		//$selectSQL .= "CONTENTSITEM.IMAGESIZE_WIDTH = IMAGESIZE.IMAGESIZE_WIDTH ";
		$selectSQL .= "WHERE ";
	
		//自分自身のアイテムは検索させない
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID != '".$addressRelatedArray[0]."' ";
		$selectSQL .= "AND ";

		//ファイル名がindexは一覧ファイルそのものなので表示しない
		$selectSQL .= "FILE_NAME != 'index' ";
		$selectSQL .= "AND ";
	
		//前記事を検索条件に含める
		$selectSQL .= "POSTED_DATE >= '".$addressRelatedArray[1]."' "; 
		$selectSQL .= "AND ";

		//投稿日が過去日付のものだけ取得する。
		$selectSQL .= "POSTED_DATE <= CURRENT_TIMESTAMP ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEM.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEM.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORY.DEL_FLG = 'N' ";
		$selectSQL .= "ORDER BY ";
		$selectSQL .= "POSTED_DATE ASC, ";
		$selectSQL .= "CONTENTSITEM.TITLE DESC, ";
		$selectSQL .= "PHOTO_DATE DESC, ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID ASC ";
		$selectSQL .= "LIMIT ";
		$selectSQL .= "1 ";

		$selectSQL .= ") ";
		$selectSQL .= "UNION ALL ";
		$selectSQL .= "( ";

		$selectSQL .= "SELECT ";
		$selectSQL .= "'categoryPrev' AS PREVNEXT, ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID, ";
		$selectSQL .= "CONTENTSCATEGORY_DIRECTORY, ";
		$selectSQL .= "CONTENTSCATEGORY_NAME, ";
		$selectSQL .= "CONTENTSITEM.TITLE, ";
		$selectSQL .= "DIRECTORY_NAME, ";
		$selectSQL .= "FILE_NAME, ";
		$selectSQL .= "IMAGE_ALT, ";
		$selectSQL .= "IMAGE_TITLE, ";
		$selectSQL .= "IMAGE_DIRECTORY_NAME, ";
		$selectSQL .= "IMAGE_FILE_NAME, ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "IMAGESIZE.IMAGESIZE_CODE, ";
		$selectSQL .= "ITEMCOMMENT, ";
		$selectSQL .= "KEYWORD, ";
		$selectSQL .= "PHOTO_DATE, ";
		$selectSQL .= "POSTED_DATE ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "CONTENTSITEM.INSERT_DATE, ";
		//$selectSQL .= "CONTENTSITEM.UPDATE_DATE ";
		$selectSQL .= "FROM ";
		$selectSQL .= "CONTENTSITEM ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORY ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.CONTENTSCATEGORY_ID = CONTENTSCATEGORY.CONTENTSCATEGORY_ID ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "LEFT OUTER JOIN ";
		//$selectSQL .= "IMAGESIZE ";
		//$selectSQL .= "ON ";
		//$selectSQL .= "CONTENTSITEM.IMAGESIZE_HEIGHT = IMAGESIZE.IMAGESIZE_HEIGHT ";
		//$selectSQL .= "AND ";
		//$selectSQL .= "CONTENTSITEM.IMAGESIZE_WIDTH = IMAGESIZE.IMAGESIZE_WIDTH ";
		$selectSQL .= "WHERE ";
	
		//自分自身のアイテムは検索させない
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID != '".$addressRelatedArray[0]."' ";
		$selectSQL .= "AND ";

		//ファイル名がindexは一覧ファイルそのものなので表示しない
		$selectSQL .= "FILE_NAME != 'index' ";
		$selectSQL .= "AND ";

		//コンテンツカテゴリを検索条件に含める
		$selectSQL .= "CONTENTSITEM.CONTENTSCATEGORY_ID = '".$addressRelatedArray[2]."' ";
		$selectSQL .= "AND ";
	
		//前記事を検索条件に含める
		$selectSQL .= "POSTED_DATE <= '".$addressRelatedArray[1]."' "; 
		$selectSQL .= "AND ";
	
		//投稿日が過去日付のものだけ取得する。
		//$selectSQL .= "POSTED_DATE <= CURRENT_TIMESTAMP ";
		//$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEM.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEM.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORY.DEL_FLG = 'N' ";
		$selectSQL .= "ORDER BY ";
		$selectSQL .= "POSTED_DATE DESC, ";
		$selectSQL .= "CONTENTSITEM.TITLE ASC, ";
		$selectSQL .= "PHOTO_DATE ASC, ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID DESC ";
		$selectSQL .= "LIMIT ";
		$selectSQL .= "1 ";

		$selectSQL .= ") ";
		$selectSQL .= "UNION ALL ";
		$selectSQL .= "( ";

		$selectSQL .= "SELECT ";
		$selectSQL .= "'categoryNext' AS PREVNEXT, ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID, ";
		$selectSQL .= "CONTENTSCATEGORY_DIRECTORY, ";
		$selectSQL .= "CONTENTSCATEGORY_NAME, ";
		$selectSQL .= "CONTENTSITEM.TITLE, ";
		$selectSQL .= "DIRECTORY_NAME, ";
		$selectSQL .= "FILE_NAME, ";
		$selectSQL .= "IMAGE_ALT, ";
		$selectSQL .= "IMAGE_TITLE, ";
		$selectSQL .= "IMAGE_DIRECTORY_NAME, ";
		$selectSQL .= "IMAGE_FILE_NAME, ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "IMAGESIZE.IMAGESIZE_CODE, ";
		$selectSQL .= "ITEMCOMMENT, ";
		$selectSQL .= "KEYWORD, ";
		$selectSQL .= "PHOTO_DATE, ";
		$selectSQL .= "POSTED_DATE ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "CONTENTSITEM.INSERT_DATE, ";
		//$selectSQL .= "CONTENTSITEM.UPDATE_DATE ";
		$selectSQL .= "FROM ";
		$selectSQL .= "CONTENTSITEM ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORY ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.CONTENTSCATEGORY_ID = CONTENTSCATEGORY.CONTENTSCATEGORY_ID ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "LEFT OUTER JOIN ";
		//$selectSQL .= "IMAGESIZE ";
		//$selectSQL .= "ON ";
		//$selectSQL .= "CONTENTSITEM.IMAGESIZE_HEIGHT = IMAGESIZE.IMAGESIZE_HEIGHT ";
		//$selectSQL .= "AND ";
		//$selectSQL .= "CONTENTSITEM.IMAGESIZE_WIDTH = IMAGESIZE.IMAGESIZE_WIDTH ";
		$selectSQL .= "WHERE ";
	
		//自分自身のアイテムは検索させない
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID != '".$addressRelatedArray[0]."' ";
		$selectSQL .= "AND ";
	
		//ファイル名がindexは一覧ファイルそのものなので表示しない
		$selectSQL .= "FILE_NAME != 'index' ";
		$selectSQL .= "AND ";

		//コンテンツカテゴリを検索条件に含める
		$selectSQL .= "CONTENTSITEM.CONTENTSCATEGORY_ID = '".$addressRelatedArray[2]."' ";
		$selectSQL .= "AND ";
	
		//前記事を検索条件に含める
		$selectSQL .= "POSTED_DATE >= '".$addressRelatedArray[1]."' "; 
		$selectSQL .= "AND ";
	
		//投稿日が過去日付のものだけ取得する。
		$selectSQL .= "POSTED_DATE <= CURRENT_TIMESTAMP ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEM.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEM.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORY.DEL_FLG = 'N' ";
		$selectSQL .= "ORDER BY ";
		$selectSQL .= "POSTED_DATE ASC, ";
		$selectSQL .= "CONTENTSITEM.TITLE DESC, ";
		$selectSQL .= "PHOTO_DATE DESC, ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID ASC ";
		$selectSQL .= "LIMIT ";
		$selectSQL .= "1 ";

		$selectSQL .= ") ";


	//言語が外国語の場合
	}else{
	
		$selectSQL  = "( ";

		$selectSQL .= "SELECT ";
		$selectSQL .= "'domainPrev' AS PREVNEXT, ";	
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID, ";
		$selectSQL .= "CONTENTSCATEGORY.CONTENTSCATEGORY_DIRECTORY, ";
		$selectSQL .= "CONTENTSCATEGORYF.CONTENTSCATEGORY_NAME, ";
		$selectSQL .= "CONTENTSITEMF.TITLE, ";
		$selectSQL .= "CONTENTSITEM.DIRECTORY_NAME, ";
		$selectSQL .= "CONTENTSITEM.FILE_NAME, ";
		$selectSQL .= "CONTENTSITEMF.IMAGE_ALT, ";
		$selectSQL .= "CONTENTSITEMF.IMAGE_TITLE, ";
		$selectSQL .= "CONTENTSITEM.IMAGE_DIRECTORY_NAME, ";
		$selectSQL .= "CONTENTSITEM.IMAGE_FILE_NAME, ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "IMAGESIZE.IMAGESIZE_CODE, ";
		$selectSQL .= "CONTENTSITEMF.ITEMCOMMENT, ";
		$selectSQL .= "CONTENTSITEMF.KEYWORD, ";
		$selectSQL .= "CONTENTSITEM.PHOTO_DATE, ";
		$selectSQL .= "CONTENTSITEM.POSTED_DATE ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "CONTENTSITEMF.INSERT_DATE, ";
		//$selectSQL .= "CONTENTSITEMF.UPDATE_DATE ";

		$selectSQL .= "FROM ";
		$selectSQL .= "CONTENTSITEM ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORY ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.CONTENTSCATEGORY_ID = CONTENTSCATEGORY.CONTENTSCATEGORY_ID ";

		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSITEMF ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID = CONTENTSITEMF.CONTENTSITEM_ID ";

		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORYF ";
		$selectSQL .= "ON ";
		$selectSQL .= "( ";
		$selectSQL .= "CONTENTSCATEGORY.CONTENTSCATEGORY_ID = CONTENTSCATEGORYF.CONTENTSCATEGORY_ID ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMF.LANG_ID = CONTENTSCATEGORYF.LANG_ID ";
		$selectSQL .= ") ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "LEFT OUTER JOIN ";
		//$selectSQL .= "IMAGESIZE ";
		//$selectSQL .= "ON ";
		//$selectSQL .= "CONTENTSITEM.IMAGESIZE_HEIGHT = IMAGESIZE.IMAGESIZE_HEIGHT ";
		//$selectSQL .= "AND ";
		//$selectSQL .= "CONTENTSITEM.IMAGESIZE_WIDTH = IMAGESIZE.IMAGESIZE_WIDTH ";

		$selectSQL .= "WHERE ";
	
		//自分自身のアイテムは検索させない
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID != '".$addressRelatedArray[0]."' ";
		$selectSQL .= "AND ";
	
		//ファイル名がindexは一覧ファイルそのものなので表示しない
		$selectSQL .= "CONTENTSITEM.FILE_NAME != 'index' ";
		$selectSQL .= "AND ";

		//同一撮影日を検索条件に含める
		$selectSQL .= "POSTED_DATE <= '".$addressRelatedArray[1]."' "; 
		$selectSQL .= "AND ";

		//投稿日が過去日付のものだけ取得する。
		//$selectSQL .= "CONTENTSITEM.POSTED_DATE <= CURRENT_TIMESTAMP ";
		//$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEMF.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYF.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEM.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORY.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMF.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYF.DEL_FLG = 'N' ";

		$selectSQL .= "ORDER BY ";
		$selectSQL .= "CONTENTSITEM.POSTED_DATE DESC, ";
		$selectSQL .= "CONTENTSITEMF.TITLE ASC, ";
		$selectSQL .= "CONTENTSITEM.PHOTO_DATE ASC, ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID DESC ";
		$selectSQL .= "LIMIT ";
		$selectSQL .= "1  ";
		
		$selectSQL .= ") ";
		$selectSQL .= "UNION ALL ";
		$selectSQL .= "( ";

		$selectSQL .= "SELECT ";
		$selectSQL .= "'domainNext' AS PREVNEXT, ";	
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID, ";
		$selectSQL .= "CONTENTSCATEGORY.CONTENTSCATEGORY_DIRECTORY, ";
		$selectSQL .= "CONTENTSCATEGORYF.CONTENTSCATEGORY_NAME, ";
		$selectSQL .= "CONTENTSITEMF.TITLE, ";
		$selectSQL .= "CONTENTSITEM.DIRECTORY_NAME, ";
		$selectSQL .= "CONTENTSITEM.FILE_NAME, ";
		$selectSQL .= "CONTENTSITEMF.IMAGE_ALT, ";
		$selectSQL .= "CONTENTSITEMF.IMAGE_TITLE, ";
		$selectSQL .= "CONTENTSITEM.IMAGE_DIRECTORY_NAME, ";
		$selectSQL .= "CONTENTSITEM.IMAGE_FILE_NAME, ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "IMAGESIZE.IMAGESIZE_CODE, ";
		$selectSQL .= "CONTENTSITEMF.ITEMCOMMENT, ";
		$selectSQL .= "CONTENTSITEMF.KEYWORD, ";
		$selectSQL .= "CONTENTSITEM.PHOTO_DATE, ";
		$selectSQL .= "CONTENTSITEM.POSTED_DATE ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "CONTENTSITEMF.INSERT_DATE, ";
		//$selectSQL .= "CONTENTSITEMF.UPDATE_DATE ";

		$selectSQL .= "FROM ";
		$selectSQL .= "CONTENTSITEM ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORY ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.CONTENTSCATEGORY_ID = CONTENTSCATEGORY.CONTENTSCATEGORY_ID ";

		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSITEMF ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID = CONTENTSITEMF.CONTENTSITEM_ID ";

		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORYF ";
		$selectSQL .= "ON ";
		$selectSQL .= "( ";
		$selectSQL .= "CONTENTSCATEGORY.CONTENTSCATEGORY_ID = CONTENTSCATEGORYF.CONTENTSCATEGORY_ID ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMF.LANG_ID = CONTENTSCATEGORYF.LANG_ID ";
		$selectSQL .= ") ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "LEFT OUTER JOIN ";
		//$selectSQL .= "IMAGESIZE ";
		//$selectSQL .= "ON ";
		//$selectSQL .= "CONTENTSITEM.IMAGESIZE_HEIGHT = IMAGESIZE.IMAGESIZE_HEIGHT ";
		//$selectSQL .= "AND ";
		//$selectSQL .= "CONTENTSITEM.IMAGESIZE_WIDTH = IMAGESIZE.IMAGESIZE_WIDTH ";

		$selectSQL .= "WHERE ";
	
		//自分自身のアイテムは検索させない
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID != '".$addressRelatedArray[0]."' ";
		$selectSQL .= "AND ";

		//ファイル名がindexは一覧ファイルそのものなので表示しない
		$selectSQL .= "CONTENTSITEM.FILE_NAME != 'index' ";
		$selectSQL .= "AND ";
	
		//同一撮影日を検索条件に含める
		$selectSQL .= "POSTED_DATE >= '".$addressRelatedArray[1]."' "; 
		$selectSQL .= "AND ";
	
		//投稿日が過去日付のものだけ取得する。
		$selectSQL .= "CONTENTSITEM.POSTED_DATE <= CURRENT_TIMESTAMP ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEMF.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYF.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEM.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORY.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMF.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYF.DEL_FLG = 'N' ";

		$selectSQL .= "ORDER BY ";
		$selectSQL .= "CONTENTSITEM.POSTED_DATE ASC, ";
		$selectSQL .= "CONTENTSITEMF.TITLE DESC, ";
		$selectSQL .= "CONTENTSITEM.PHOTO_DATE DESC, ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID ASC ";
		$selectSQL .= "LIMIT ";
		$selectSQL .= "1  ";

		$selectSQL .= ") ";
		$selectSQL .= "UNION ALL ";
		$selectSQL .= "( ";

		$selectSQL .= "SELECT ";
		$selectSQL .= "'categoryPrev' AS PREVNEXT, ";	
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID, ";
		$selectSQL .= "CONTENTSCATEGORY.CONTENTSCATEGORY_DIRECTORY, ";
		$selectSQL .= "CONTENTSCATEGORYF.CONTENTSCATEGORY_NAME, ";
		$selectSQL .= "CONTENTSITEMF.TITLE, ";
		$selectSQL .= "CONTENTSITEM.DIRECTORY_NAME, ";
		$selectSQL .= "CONTENTSITEM.FILE_NAME, ";
		$selectSQL .= "CONTENTSITEMF.IMAGE_ALT, ";
		$selectSQL .= "CONTENTSITEMF.IMAGE_TITLE, ";
		$selectSQL .= "CONTENTSITEM.IMAGE_DIRECTORY_NAME, ";
		$selectSQL .= "CONTENTSITEM.IMAGE_FILE_NAME, ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "IMAGESIZE.IMAGESIZE_CODE, ";
		$selectSQL .= "CONTENTSITEMF.ITEMCOMMENT, ";
		$selectSQL .= "CONTENTSITEMF.KEYWORD, ";
		$selectSQL .= "CONTENTSITEM.PHOTO_DATE, ";
		$selectSQL .= "CONTENTSITEM.POSTED_DATE ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "CONTENTSITEMF.INSERT_DATE, ";
		//$selectSQL .= "CONTENTSITEMF.UPDATE_DATE ";

		$selectSQL .= "FROM ";
		$selectSQL .= "CONTENTSITEM ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORY ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.CONTENTSCATEGORY_ID = CONTENTSCATEGORY.CONTENTSCATEGORY_ID ";

		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSITEMF ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID = CONTENTSITEMF.CONTENTSITEM_ID ";

		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORYF ";
		$selectSQL .= "ON ";
		$selectSQL .= "( ";
		$selectSQL .= "CONTENTSCATEGORY.CONTENTSCATEGORY_ID = CONTENTSCATEGORYF.CONTENTSCATEGORY_ID ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMF.LANG_ID = CONTENTSCATEGORYF.LANG_ID ";
		$selectSQL .= ") ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "LEFT OUTER JOIN ";
		//$selectSQL .= "IMAGESIZE ";
		//$selectSQL .= "ON ";
		//$selectSQL .= "CONTENTSITEM.IMAGESIZE_HEIGHT = IMAGESIZE.IMAGESIZE_HEIGHT ";
		//$selectSQL .= "AND ";
		//$selectSQL .= "CONTENTSITEM.IMAGESIZE_WIDTH = IMAGESIZE.IMAGESIZE_WIDTH ";

		$selectSQL .= "WHERE ";
	
		//自分自身のアイテムは検索させない
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID != '".$addressRelatedArray[0]."' ";
		$selectSQL .= "AND ";

		//ファイル名がindexは一覧ファイルそのものなので表示しない
		$selectSQL .= "CONTENTSITEM.FILE_NAME != 'index' ";
		$selectSQL .= "AND ";

		//コンテンツカテゴリを検索条件に含める
		$selectSQL .= "CONTENTSITEM.CONTENTSCATEGORY_ID = '".$addressRelatedArray[2]."' ";
		$selectSQL .= "AND ";
	
		//同一撮影日を検索条件に含める
		$selectSQL .= "POSTED_DATE <= '".$addressRelatedArray[1]."' "; 
		$selectSQL .= "AND ";
	
		//投稿日が過去日付のものだけ取得する。
		//$selectSQL .= "CONTENTSITEM.POSTED_DATE <= CURRENT_TIMESTAMP ";
		//$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEMF.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYF.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEM.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORY.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMF.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYF.DEL_FLG = 'N' ";

		$selectSQL .= "ORDER BY ";
		$selectSQL .= "CONTENTSITEM.POSTED_DATE DESC, ";
		$selectSQL .= "CONTENTSITEMF.TITLE ASC, ";
		$selectSQL .= "CONTENTSITEM.PHOTO_DATE ASC, ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID DESC ";
		$selectSQL .= "LIMIT ";
		$selectSQL .= "1  ";

		$selectSQL .= ") ";
		$selectSQL .= "UNION ALL ";
		$selectSQL .= "( ";

		$selectSQL .= "SELECT ";
		$selectSQL .= "'categoryNext' AS PREVNEXT, ";	
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID, ";
		$selectSQL .= "CONTENTSCATEGORY.CONTENTSCATEGORY_DIRECTORY, ";
		$selectSQL .= "CONTENTSCATEGORYF.CONTENTSCATEGORY_NAME, ";
		$selectSQL .= "CONTENTSITEMF.TITLE, ";
		$selectSQL .= "CONTENTSITEM.DIRECTORY_NAME, ";
		$selectSQL .= "CONTENTSITEM.FILE_NAME, ";
		$selectSQL .= "CONTENTSITEMF.IMAGE_ALT, ";
		$selectSQL .= "CONTENTSITEMF.IMAGE_TITLE, ";
		$selectSQL .= "CONTENTSITEM.IMAGE_DIRECTORY_NAME, ";
		$selectSQL .= "CONTENTSITEM.IMAGE_FILE_NAME, ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "IMAGESIZE.IMAGESIZE_CODE, ";
		$selectSQL .= "CONTENTSITEMF.ITEMCOMMENT, ";
		$selectSQL .= "CONTENTSITEMF.KEYWORD, ";
		$selectSQL .= "CONTENTSITEM.PHOTO_DATE, ";
		$selectSQL .= "CONTENTSITEM.POSTED_DATE ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "CONTENTSITEMF.INSERT_DATE, ";
		//$selectSQL .= "CONTENTSITEMF.UPDATE_DATE ";

		$selectSQL .= "FROM ";
		$selectSQL .= "CONTENTSITEM ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORY ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.CONTENTSCATEGORY_ID = CONTENTSCATEGORY.CONTENTSCATEGORY_ID ";

		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSITEMF ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID = CONTENTSITEMF.CONTENTSITEM_ID ";

		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORYF ";
		$selectSQL .= "ON ";
		$selectSQL .= "( ";
		$selectSQL .= "CONTENTSCATEGORY.CONTENTSCATEGORY_ID = CONTENTSCATEGORYF.CONTENTSCATEGORY_ID ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMF.LANG_ID = CONTENTSCATEGORYF.LANG_ID ";
		$selectSQL .= ") ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "LEFT OUTER JOIN ";
		//$selectSQL .= "IMAGESIZE ";
		//$selectSQL .= "ON ";
		//$selectSQL .= "CONTENTSITEM.IMAGESIZE_HEIGHT = IMAGESIZE.IMAGESIZE_HEIGHT ";
		//$selectSQL .= "AND ";
		//$selectSQL .= "CONTENTSITEM.IMAGESIZE_WIDTH = IMAGESIZE.IMAGESIZE_WIDTH ";

		$selectSQL .= "WHERE ";
	
		//自分自身のアイテムは検索させない
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID != '".$addressRelatedArray[0]."' ";
		$selectSQL .= "AND ";

		//ファイル名がindexは一覧ファイルそのものなので表示しない
		$selectSQL .= "CONTENTSITEM.FILE_NAME != 'index' ";
		$selectSQL .= "AND ";

		//コンテンツカテゴリを検索条件に含める
		$selectSQL .= "CONTENTSITEM.CONTENTSCATEGORY_ID = '".$addressRelatedArray[2]."' ";
		$selectSQL .= "AND ";

	
		//同一撮影日を検索条件に含める
		$selectSQL .= "POSTED_DATE >= '".$addressRelatedArray[1]."' "; 
		$selectSQL .= "AND ";
	
		//投稿日が過去日付のものだけ取得する。
		$selectSQL .= "CONTENTSITEM.POSTED_DATE <= CURRENT_TIMESTAMP ";
		$selectSQL .= "AND ";
	
		$selectSQL .= "CONTENTSITEMF.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYF.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEM.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORY.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMF.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYF.DEL_FLG = 'N' ";

		$selectSQL .= "ORDER BY ";
		$selectSQL .= "CONTENTSITEM.POSTED_DATE ASC, ";
		$selectSQL .= "CONTENTSITEMF.TITLE DESC, ";
		$selectSQL .= "CONTENTSITEM.PHOTO_DATE DESC, ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID ASC ";
		$selectSQL .= "LIMIT ";
		$selectSQL .= "1  ";

		$selectSQL .= ") ";
	
	}

	//print	$selectSQL;
	$sqlResult = sqlSelectExe($selectSQL);

	while($row = mysqli_fetch_array($sqlResult,MYSQLI_ASSOC)){
		$selectContentsitemPrevNext[$row["PREVNEXT"]]["CONTENTSITEM_ID"] = $row["CONTENTSITEM_ID"];
		$selectContentsitemPrevNext[$row["PREVNEXT"]]["CONTENTSCATEGORY_DIRECTORY"] = $row["CONTENTSCATEGORY_DIRECTORY"];
		$selectContentsitemPrevNext[$row["PREVNEXT"]]["CONTENTSCATEGORY_NAME"] = $row["CONTENTSCATEGORY_NAME"];
		$selectContentsitemPrevNext[$row["PREVNEXT"]]["TITLE"] = $row["TITLE"];
		$selectContentsitemPrevNext[$row["PREVNEXT"]]["DIRECTORY_NAME"] = $row["DIRECTORY_NAME"];
		$selectContentsitemPrevNext[$row["PREVNEXT"]]["FILE_NAME"] = $row["FILE_NAME"];
		$selectContentsitemPrevNext[$row["PREVNEXT"]]["IMAGE_ALT"] = $row["IMAGE_ALT"];
		$selectContentsitemPrevNext[$row["PREVNEXT"]]["IMAGE_TITLE"] = $row["IMAGE_TITLE"];
		$selectContentsitemPrevNext[$row["PREVNEXT"]]["IMAGE_DIRECTORY_NAME"] = $row["IMAGE_DIRECTORY_NAME"];
		$selectContentsitemPrevNext[$row["PREVNEXT"]]["IMAGE_DIRECTORY_NAME"] = $row["IMAGE_DIRECTORY_NAME"];
		$selectContentsitemPrevNext[$row["PREVNEXT"]]["IMAGE_FILE_NAME"] = $row["IMAGE_FILE_NAME"];
		//当面使わないのでコメントアウト
		//$selectContentsitemPrevNext[$row["PREVNEXT"]]["IMAGESIZE_CODE"] = $row["IMAGESIZE_CODE"];
		$selectContentsitemPrevNext[$row["PREVNEXT"]]["ITEMCOMMENT"] = $row["ITEMCOMMENT"];
		$selectContentsitemPrevNext[$row["PREVNEXT"]]["KEYWORD"] = $row["KEYWORD"];
		$selectContentsitemPrevNext[$row["PREVNEXT"]]["PHOTO_DATE"] = $row["PHOTO_DATE"];
		$selectContentsitemPrevNext[$row["PREVNEXT"]]["POSTED_DATE"] = $row["POSTED_DATE"];
		//当面使わないのでコメントアウト
		//$selectContentsitemPrevNext[$row["PREVNEXT"]]["INSERT_DATE"] = $row["INSERT_DATE"];
		//$selectContentsitemPrevNext[$row["PREVNEXT"]]["UPDATE_DATE"] = $row["UPDATE_DATE"];
	}
	//print_r($selectContentsitemPrevNext);
	return $selectContentsitemPrevNext;
}

/*
 * コンテンツアイテム同一撮影日別記事一覧取得
 * <br>
 * 多言語対応済み
 * 
 * @param array $addressRelatedArray 同一撮影日別記事に必要な情報の配列
 * @param array $limitCountArray FROM TOの配列
 * @retuen array コンテンツアイテム同一撮影日別記事一覧取得結果
 * 
 *   
 * @date 2017/03/22
 * @since 2014/09/18
 * @version 1.4
 * 
*/
function selectContentsitemSamePhotoList($addressRelatedArray,$limitCountArray){

	//言語コード
	global $langCode;
	//言語ID
	global $langId;

	//言語が日本語の場合
	if($langCode == "ja"){

		$selectSQL  = "SELECT ";
	
		$selectSQL .= "SQL_CALC_FOUND_ROWS ";
	
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID, ";
		$selectSQL .= "CONTENTSCATEGORY_DIRECTORY, ";
		$selectSQL .= "CONTENTSCATEGORY_NAME, ";
		$selectSQL .= "CONTENTSITEM.TITLE, ";
		$selectSQL .= "DIRECTORY_NAME, ";
		$selectSQL .= "FILE_NAME, ";
		$selectSQL .= "IMAGE_ALT, ";
		$selectSQL .= "IMAGE_TITLE, ";
		$selectSQL .= "IMAGE_DIRECTORY_NAME, ";
		$selectSQL .= "IMAGE_FILE_NAME, ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "IMAGESIZE.IMAGESIZE_CODE, ";
		$selectSQL .= "ITEMCOMMENT, ";
		$selectSQL .= "KEYWORD, ";
		$selectSQL .= "PHOTO_DATE, ";
		$selectSQL .= "POSTED_DATE ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "CONTENTSITEM.INSERT_DATE, ";
		//$selectSQL .= "CONTENTSITEM.UPDATE_DATE ";
		$selectSQL .= "FROM ";
		$selectSQL .= "CONTENTSITEM ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORY ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.CONTENTSCATEGORY_ID = CONTENTSCATEGORY.CONTENTSCATEGORY_ID ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "LEFT OUTER JOIN ";
		//$selectSQL .= "IMAGESIZE ";
		//$selectSQL .= "ON ";
		//$selectSQL .= "CONTENTSITEM.IMAGESIZE_HEIGHT = IMAGESIZE.IMAGESIZE_HEIGHT ";
		//$selectSQL .= "AND ";
		//$selectSQL .= "CONTENTSITEM.IMAGESIZE_WIDTH = IMAGESIZE.IMAGESIZE_WIDTH ";
		$selectSQL .= "WHERE ";
	
		//自分自身のアイテムは検索させない
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID != '".$addressRelatedArray[0]."' ";
		$selectSQL .= "AND ";

		//ファイル名がindexは一覧ファイルそのものなので表示しない
		$selectSQL .= "FILE_NAME != 'index' ";
		$selectSQL .= "AND ";
	
		//同一撮影日を検索条件に含める
		$selectSQL .= "( ";
		$selectSQL .= "PHOTO_DATE BETWEEN DATE_FORMAT( '".$addressRelatedArray[1]."', '%Y-%m-%d 00:00:00') "; 
		$selectSQL .= "AND ";
		$selectSQL .= "DATE_FORMAT( '".$addressRelatedArray[1]."', '%Y-%m-%d 23:59:59') ";
		$selectSQL .= ") ";
		$selectSQL .= "AND ";

		//投稿日が過去日付のものだけ取得する。
		$selectSQL .= "POSTED_DATE <= CURRENT_TIMESTAMP ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEM.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEM.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORY.DEL_FLG = 'N' ";
		$selectSQL .= "ORDER BY ";
		$selectSQL .= "POSTED_DATE DESC, ";
		$selectSQL .= "CONTENTSITEM.TITLE ASC, ";
		$selectSQL .= "PHOTO_DATE ASC, ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID DESC ";
		//LIMITは設けない。ただし、今後設ける可能性があるので残す。
		//$selectSQL .= "LIMIT ";
		//$selectSQL .= $limitCountArray[0].", ";
		//$selectSQL .= $limitCountArray[1]." ";
	//言語が外国語の場合
	}else{
		$selectSQL  = "SELECT ";
	
		$selectSQL .= "SQL_CALC_FOUND_ROWS ";
	
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID, ";
		$selectSQL .= "CONTENTSCATEGORY.CONTENTSCATEGORY_DIRECTORY, ";
		$selectSQL .= "CONTENTSCATEGORYF.CONTENTSCATEGORY_NAME, ";
		$selectSQL .= "CONTENTSITEMF.TITLE, ";
		$selectSQL .= "CONTENTSITEM.DIRECTORY_NAME, ";
		$selectSQL .= "CONTENTSITEM.FILE_NAME, ";
		$selectSQL .= "CONTENTSITEMF.IMAGE_ALT, ";
		$selectSQL .= "CONTENTSITEMF.IMAGE_TITLE, ";
		$selectSQL .= "CONTENTSITEM.IMAGE_DIRECTORY_NAME, ";
		$selectSQL .= "CONTENTSITEM.IMAGE_FILE_NAME, ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "IMAGESIZE.IMAGESIZE_CODE, ";
		$selectSQL .= "CONTENTSITEMF.ITEMCOMMENT, ";
		$selectSQL .= "CONTENTSITEMF.KEYWORD, ";
		$selectSQL .= "CONTENTSITEM.PHOTO_DATE, ";
		$selectSQL .= "CONTENTSITEM.POSTED_DATE ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "CONTENTSITEMF.INSERT_DATE, ";
		//$selectSQL .= "CONTENTSITEMF.UPDATE_DATE ";

		$selectSQL .= "FROM ";
		$selectSQL .= "CONTENTSITEM ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORY ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.CONTENTSCATEGORY_ID = CONTENTSCATEGORY.CONTENTSCATEGORY_ID ";

		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSITEMF ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID = CONTENTSITEMF.CONTENTSITEM_ID ";

		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORYF ";
		$selectSQL .= "ON ";
		$selectSQL .= "( ";
		$selectSQL .= "CONTENTSCATEGORY.CONTENTSCATEGORY_ID = CONTENTSCATEGORYF.CONTENTSCATEGORY_ID ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMF.LANG_ID = CONTENTSCATEGORYF.LANG_ID ";
		$selectSQL .= ") ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "LEFT OUTER JOIN ";
		//$selectSQL .= "IMAGESIZE ";
		//$selectSQL .= "ON ";
		//$selectSQL .= "CONTENTSITEM.IMAGESIZE_HEIGHT = IMAGESIZE.IMAGESIZE_HEIGHT ";
		//$selectSQL .= "AND ";
		//$selectSQL .= "CONTENTSITEM.IMAGESIZE_WIDTH = IMAGESIZE.IMAGESIZE_WIDTH ";

		$selectSQL .= "WHERE ";
	
		//自分自身のアイテムは検索させない
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID != '".$addressRelatedArray[0]."' ";
		$selectSQL .= "AND ";

		//ファイル名がindexは一覧ファイルそのものなので表示しない
		$selectSQL .= "CONTENTSITEM.FILE_NAME != 'index' ";
		$selectSQL .= "AND ";
	
		//同一撮影日を検索条件に含める
		$selectSQL .= "( ";
		$selectSQL .= "CONTENTSITEM.PHOTO_DATE BETWEEN DATE_FORMAT( '".$addressRelatedArray[1]."', '%Y-%m-%d 00:00:00') "; 
		$selectSQL .= "AND ";
		$selectSQL .= "DATE_FORMAT( '".$addressRelatedArray[1]."', '%Y-%m-%d 23:59:59') ";
		$selectSQL .= ") ";
		$selectSQL .= "AND ";
	
		//投稿日が過去日付のものだけ取得する。
		$selectSQL .= "CONTENTSITEM.POSTED_DATE <= CURRENT_TIMESTAMP ";
		$selectSQL .= "AND ";
	
		$selectSQL .= "CONTENTSITEMF.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYF.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEM.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORY.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMF.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYF.DEL_FLG = 'N' ";

		$selectSQL .= "ORDER BY ";
		$selectSQL .= "CONTENTSITEM.POSTED_DATE DESC, ";
		$selectSQL .= "CONTENTSITEMF.TITLE ASC, ";
		$selectSQL .= "CONTENTSITEM.PHOTO_DATE ASC, ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID DESC ";
		//LIMITは設けない。ただし、今後設ける可能性があるので残す。
		//$selectSQL .= "LIMIT ";
		//$selectSQL .= $limitCountArray[0].", ";
		//$selectSQL .= $limitCountArray[1]." ";
	
	}

//print	$selectSQL;

	$sqlResult = sqlSelectListExe($selectSQL);

	//関連項目の一覧を配列に代入する
	while($row = mysqli_fetch_array($sqlResult[0],MYSQLI_ASSOC)){
		$resultList[] = $row;
	}
	if(isset($resultList) == false){
		$resultList = "";
	}

	//コンテンツアイテムの件数を変数に代入する。
	while($row = mysqli_fetch_row($sqlResult[1])){
		$resultCount = $row[0];
	}
	if(isset($resultCount) == false){
		$resultCount = 0;
	}

	return array($resultList,$resultCount);
}

/*
 * コンテンツアイテム関連記事一覧取得
 * 
 * @param array $addressRelatedArray 関連記事に必要な情報の配列
 * @param array $limitCountArray FROM TOの配列
 * @retuen array コンテンツアイテム関連記事一覧取得結果
 * 
 *   
 * @date 2017/03/22
 * @since 2014/09/18
 * @version 1.4
 * 
*/
function selectContentsitemRelatedList($addressRelatedArray,$limitCountArray){

	//言語コード
	global $langCode;
	//言語ID
	global $langId;
	
	//キーワードの配列化
	$keywordArray = explode(",",$addressRelatedArray[1]);
	
	//言語が日本語の場合
	if($langCode == "ja"){

		$selectSQL  = "SELECT ";
	
		$selectSQL .= "SQL_CALC_FOUND_ROWS ";
	
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID, ";
		$selectSQL .= "CONTENTSCATEGORY_DIRECTORY, ";
		$selectSQL .= "CONTENTSCATEGORY_NAME, ";
		$selectSQL .= "CONTENTSITEM.TITLE, ";
		$selectSQL .= "DIRECTORY_NAME, ";
		$selectSQL .= "FILE_NAME, ";
		$selectSQL .= "IMAGE_ALT, ";
		$selectSQL .= "IMAGE_TITLE, ";
		$selectSQL .= "IMAGE_DIRECTORY_NAME, ";
		$selectSQL .= "IMAGE_FILE_NAME, ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "IMAGESIZE.IMAGESIZE_CODE, ";
		$selectSQL .= "ITEMCOMMENT, ";
		$selectSQL .= "KEYWORD, ";
		$selectSQL .= "PHOTO_DATE, ";
		$selectSQL .= "POSTED_DATE ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "CONTENTSITEM.INSERT_DATE, ";
		//$selectSQL .= "CONTENTSITEM.UPDATE_DATE ";
		$selectSQL .= "FROM ";
		$selectSQL .= "CONTENTSITEM ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORY ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.CONTENTSCATEGORY_ID = CONTENTSCATEGORY.CONTENTSCATEGORY_ID ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "LEFT OUTER JOIN ";
		//$selectSQL .= "IMAGESIZE ";
		//$selectSQL .= "ON ";
		//$selectSQL .= "CONTENTSITEM.IMAGESIZE_HEIGHT = IMAGESIZE.IMAGESIZE_HEIGHT ";
		//$selectSQL .= "AND ";
		//$selectSQL .= "CONTENTSITEM.IMAGESIZE_WIDTH = IMAGESIZE.IMAGESIZE_WIDTH ";
		$selectSQL .= "WHERE ";
		
		//キーワードの数だけ回す
		for ($i = 0; $i < count($keywordArray); $i++) {
			//最初のキーワードの処理
			if($i == 0){
				$selectSQL .= "( ";
			}
			$selectSQL .= "FIND_IN_SET('".htmlspecialchars($keywordArray[$i])."', KEYWORD) > 0 ";
			//最後のキーワードの処理
			if($i == count($keywordArray) - 1){
				$selectSQL .= ") ";
				$selectSQL .= "AND ";
			//そうではない場合はOR処理	
			}else{
				$selectSQL .= "OR ";
			}			
		}
	
		//自分自身のアイテムは検索させない
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID != '".$addressRelatedArray[0]."' ";
		$selectSQL .= "AND ";

		//ファイル名がindexは一覧ファイルそのものなので表示しない
		$selectSQL .= "FILE_NAME != 'index' ";
		$selectSQL .= "AND ";
	
		//同一撮影日の別記事が存在する場合
		if($addressRelatedArray[2] != ""){
			$selectSQL .= "( ";
			$selectSQL .= "PHOTO_DATE < DATE_FORMAT( '".$addressRelatedArray[2]."', '%Y-%m-%d 00:00:00') "; 
			$selectSQL .= "OR ";
			$selectSQL .= "PHOTO_DATE > DATE_FORMAT( '".$addressRelatedArray[2]."', '%Y-%m-%d 23:59:59') ";
			$selectSQL .= "OR ";
			$selectSQL .= "PHOTO_DATE IS NULL ";
			$selectSQL .= ") ";
			$selectSQL .= "AND ";
		}
	
		//投稿日が過去日付のものだけ取得する。
		$selectSQL .= "POSTED_DATE <= CURRENT_TIMESTAMP ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEM.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEM.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORY.DEL_FLG = 'N' ";
		$selectSQL .= "ORDER BY ";
		//キーワードの数だけ回す。キーワードの順番だけ優先して表示する
		for ($i = 0; $i < count($keywordArray); $i++) {
			$selectSQL .= "FIND_IN_SET('".htmlspecialchars($keywordArray[$i])."', KEYWORD) > 0 DESC, ";
		}
		$selectSQL .= "CONTENTSITEM.TITLE ASC, ";
		$selectSQL .= "PHOTO_DATE ASC, ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID DESC ";
		$selectSQL .= "LIMIT ";
		$selectSQL .= $limitCountArray[0].", ";
		$selectSQL .= $limitCountArray[1]." ";

	//言語が外国語の場合
	}else{

		$selectSQL  = "SELECT ";
	
		$selectSQL .= "SQL_CALC_FOUND_ROWS ";
	
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID, ";
		$selectSQL .= "CONTENTSCATEGORY.CONTENTSCATEGORY_DIRECTORY, ";
		$selectSQL .= "CONTENTSCATEGORYF.CONTENTSCATEGORY_NAME, ";
		$selectSQL .= "CONTENTSITEMF.TITLE, ";
		$selectSQL .= "CONTENTSITEM.DIRECTORY_NAME, ";
		$selectSQL .= "CONTENTSITEM.FILE_NAME, ";
		$selectSQL .= "CONTENTSITEMF.IMAGE_ALT, ";
		$selectSQL .= "CONTENTSITEMF.IMAGE_TITLE, ";
		$selectSQL .= "CONTENTSITEM.IMAGE_DIRECTORY_NAME, ";
		$selectSQL .= "CONTENTSITEM.IMAGE_FILE_NAME, ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "IMAGESIZE.IMAGESIZE_CODE, ";
		$selectSQL .= "CONTENTSITEMF.ITEMCOMMENT, ";
		$selectSQL .= "CONTENTSITEMF.KEYWORD, ";
		$selectSQL .= "CONTENTSITEM.PHOTO_DATE, ";
		$selectSQL .= "CONTENTSITEM.POSTED_DATE ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "CONTENTSITEMF.INSERT_DATE, ";
		//$selectSQL .= "CONTENTSITEMF.UPDATE_DATE ";

		$selectSQL .= "FROM ";
		$selectSQL .= "CONTENTSITEM ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORY ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.CONTENTSCATEGORY_ID = CONTENTSCATEGORY.CONTENTSCATEGORY_ID ";

		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSITEMF ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID = CONTENTSITEMF.CONTENTSITEM_ID ";

		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORYF ";
		$selectSQL .= "ON ";
		$selectSQL .= "( ";
		$selectSQL .= "CONTENTSCATEGORY.CONTENTSCATEGORY_ID = CONTENTSCATEGORYF.CONTENTSCATEGORY_ID ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMF.LANG_ID = CONTENTSCATEGORYF.LANG_ID ";
		$selectSQL .= ") ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "LEFT OUTER JOIN ";
		//$selectSQL .= "IMAGESIZE ";
		//$selectSQL .= "ON ";
		//$selectSQL .= "CONTENTSITEM.IMAGESIZE_HEIGHT = IMAGESIZE.IMAGESIZE_HEIGHT ";
		//$selectSQL .= "AND ";
		//$selectSQL .= "CONTENTSITEM.IMAGESIZE_WIDTH = IMAGESIZE.IMAGESIZE_WIDTH ";

		$selectSQL .= "WHERE ";
		
		//キーワードの数だけ回す
		for ($i = 0; $i < count($keywordArray); $i++) {
			//最初のキーワードの処理
			if($i == 0){
				$selectSQL .= "( ";
			}
			$selectSQL .= "FIND_IN_SET('".htmlspecialchars($keywordArray[$i])."', CONTENTSITEMF.KEYWORD) > 0 ";
			//最後のキーワードの処理
			if($i == count($keywordArray) - 1){
				$selectSQL .= ") ";
				$selectSQL .= "AND ";
			//そうではない場合はOR処理	
			}else{
				$selectSQL .= "OR ";
			}			
		}
	
		//自分自身のアイテムは検索させない
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID != '".$addressRelatedArray[0]."' ";
		$selectSQL .= "AND ";

		//ファイル名がindexは一覧ファイルそのものなので表示しない
		$selectSQL .= "CONTENTSITEM.FILE_NAME != 'index' ";
		$selectSQL .= "AND ";
	
		//同一撮影日の別記事が存在する場合
		if($addressRelatedArray[2] != ""){
			$selectSQL .= "( ";
			$selectSQL .= "CONTENTSITEM.PHOTO_DATE < DATE_FORMAT( '".$addressRelatedArray[2]."', '%Y-%m-%d 00:00:00') "; 
			$selectSQL .= "OR ";
			$selectSQL .= "CONTENTSITEM.PHOTO_DATE > DATE_FORMAT( '".$addressRelatedArray[2]."', '%Y-%m-%d 23:59:59') ";
			$selectSQL .= "OR ";
			$selectSQL .= "CONTENTSITEM.PHOTO_DATE IS NULL ";
			$selectSQL .= ") ";
			$selectSQL .= "AND ";
		}
	
		//投稿日が過去日付のものだけ取得する。
		$selectSQL .= "CONTENTSITEM.POSTED_DATE <= CURRENT_TIMESTAMP ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEMF.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYF.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEM.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORY.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMF.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYF.DEL_FLG = 'N' ";

		$selectSQL .= "ORDER BY ";
		//キーワードの数だけ回す。キーワードの順番だけ優先して表示する
		for ($i = 0; $i < count($keywordArray); $i++) {
			$selectSQL .= "FIND_IN_SET('".htmlspecialchars($keywordArray[$i])."', CONTENTSITEMF.KEYWORD) > 0 DESC, ";
		}
		$selectSQL .= "CONTENTSITEM.TITLE ASC, ";
		$selectSQL .= "CONTENTSITEM.PHOTO_DATE ASC, ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID DESC ";
		$selectSQL .= "LIMIT ";
		$selectSQL .= $limitCountArray[0].", ";
		$selectSQL .= $limitCountArray[1]." ";

	}
//print	$selectSQL;

	$sqlResult = sqlSelectListExe($selectSQL);

	//関連項目の一覧を配列に代入する
	while($row = mysqli_fetch_array($sqlResult[0],MYSQLI_ASSOC)){
		$resultList[] = $row;
	}
	if(isset($resultList) == false){
		$resultList = "";
	}

	//コンテンツアイテムの件数を変数に代入する。
	while($row = mysqli_fetch_row($sqlResult[1])){
		$resultCount = $row[0];
	}
	if(isset($resultCount) == false){
		$resultCount = 0;
	}

	return array($resultList,$resultCount);
}

/*
 * コンテンツアイテムのソート順取得
 * <br>
 * 多言語対応済み
 * 
 * @param array $contentsItemDataArray コンテンツアイテムの情報
 * @retuen array コンテンツアイテムのソート順とコンテンツアイテムの全件
 * 
 *   
 * @date 2017/03/22
 * @since 2015/09/27
 * @version 1.1
 * 
*/
function selectContentsItemSortCount($contentsItemDataArray){

	//言語コード
	global $langCode;
	//言語ID
	global $langId;
	
	//言語が日本語の場合
	if($langCode == "ja"){

		$selectSQL  = "SELECT ";
		$selectSQL .= "CONTENTSITEM_ID, ";
		$selectSQL .= "CONTENTSCATEGORY_ID, ";
		$selectSQL .= "SUM(CONTENTSITEM_ID_COUNT) AS CONTENTSITEM_ID_COUNT, ";
		$selectSQL .= "SUM(CONTENTSITEM_ID_COUNT_ALL) AS CONTENTSITEM_ID_COUNT_ALL ";

		$selectSQL .= "FROM "; 
		$selectSQL .= "( ";

		$selectSQL .= "( ";
		$selectSQL .= "SELECT ";
		$selectSQL .= "CONTENTSITEM1.CONTENTSITEM_ID,  ";
		$selectSQL .= "CONTENTSITEM1.CONTENTSCATEGORY_ID, "; 
		$selectSQL .= "COUNT(CONTENTSITEMCOUNT.CONTENTSITEM_ID) AS CONTENTSITEM_ID_COUNT, ";
		$selectSQL .= "0 AS CONTENTSITEM_ID_COUNT_ALL ";
		$selectSQL .= "FROM ";

		$selectSQL .= "CONTENTSITEM AS CONTENTSITEM1 ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORY AS CONTENTSCATEGORY1 ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM1.CONTENTSCATEGORY_ID = CONTENTSCATEGORY1.CONTENTSCATEGORY_ID ";
		$selectSQL .= ", ";
		
		$selectSQL .= "CONTENTSITEM AS CONTENTSITEMCOUNT ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORY AS CONTENTSCATEGORYCOUNT ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEMCOUNT.CONTENTSCATEGORY_ID = CONTENTSCATEGORYCOUNT.CONTENTSCATEGORY_ID ";

		$selectSQL .= "WHERE ";
		$selectSQL .= "CONTENTSITEM1.CONTENTSITEM_ID = '".$contentsItemDataArray["CONTENTSITEM_ID"]."' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEM1.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEM1.POSTED_DATE <= CURRENT_TIMESTAMP ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEM1.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORY1.DEL_FLG = 'N' ";

		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSCATEGORYCOUNT.CONTENTSCATEGORY_ID = '".$contentsItemDataArray["CONTENTSCATEGORY_ID"]."' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMCOUNT.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";
		//ファイル名がindexは一覧には表示されないので除外する
		$selectSQL .= "CONTENTSITEMCOUNT.FILE_NAME != 'index' ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEMCOUNT.POSTED_DATE <= ";
		$selectSQL .= "( ";
		$selectSQL .= "SELECT ";
		$selectSQL .= "CONTENTSITEMSTAMP.POSTED_DATE ";
		$selectSQL .= "FROM ";
		$selectSQL .= "CONTENTSITEM AS CONTENTSITEMSTAMP ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORY AS CONTENTSCATEGORYSTAMP ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEMSTAMP.CONTENTSCATEGORY_ID = CONTENTSCATEGORYSTAMP.CONTENTSCATEGORY_ID ";
		$selectSQL .= "WHERE ";
		$selectSQL .= "CONTENTSITEMSTAMP.CONTENTSITEM_ID = '".$contentsItemDataArray["CONTENTSITEM_ID"]."' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMSTAMP.LANG_ID = '".$langId."' "; 
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMSTAMP.POSTED_DATE <= CURRENT_TIMESTAMP ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMSTAMP.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYSTAMP.DEL_FLG = 'N' ";
		$selectSQL .= ") ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEMCOUNT.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYCOUNT.DEL_FLG = 'N' ";

		$selectSQL .= ") ";
		$selectSQL .= "UNION ALL "; 
		$selectSQL .= "( ";

		$selectSQL .= "SELECT ";
		$selectSQL .= "CONTENTSITEM2.CONTENTSITEM_ID,  ";
		$selectSQL .= "CONTENTSITEM2.CONTENTSCATEGORY_ID, "; 
		$selectSQL .= "0 AS CONTENTSITEM_ID_COUNT, ";
		$selectSQL .= "COUNT(CONTENTSITEMCOUNTALL.CONTENTSITEM_ID) AS CONTENTSITEM_ID_COUNT_ALL ";
		$selectSQL .= "FROM ";

		$selectSQL .= "CONTENTSITEM AS CONTENTSITEM2 ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORY AS CONTENTSCATEGORY2 ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM2.CONTENTSCATEGORY_ID = CONTENTSCATEGORY2.CONTENTSCATEGORY_ID ";
		$selectSQL .= ", ";
		
		$selectSQL .= "CONTENTSITEM AS CONTENTSITEMCOUNTALL ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORY AS CONTENTSCATEGORYCOUNTALL ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEMCOUNTALL.CONTENTSCATEGORY_ID = CONTENTSCATEGORYCOUNTALL.CONTENTSCATEGORY_ID ";

		$selectSQL .= "WHERE ";
		$selectSQL .= "CONTENTSITEM2.CONTENTSITEM_ID = '".$contentsItemDataArray["CONTENTSITEM_ID"]."' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEM2.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEM2.POSTED_DATE <= CURRENT_TIMESTAMP ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEM2.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORY2.DEL_FLG = 'N' ";

		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSCATEGORYCOUNTALL.CONTENTSCATEGORY_ID = '".$contentsItemDataArray["CONTENTSCATEGORY_ID"]."' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMCOUNTALL.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";
		//ファイル名がindexは一覧には表示されないので除外する
		$selectSQL .= "CONTENTSITEMCOUNTALL.FILE_NAME != 'index' ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEMCOUNTALL.POSTED_DATE <= CURRENT_TIMESTAMP ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMCOUNTALL.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYCOUNTALL.DEL_FLG = 'N' ";
		$selectSQL .= ") ";
		$selectSQL .= ") ";
		$selectSQL .= "AS CONTENTSITEM_SUM ";

	//言語が外国語の場合
	}else{

		$selectSQL  = "SELECT ";
		$selectSQL .= "CONTENTSITEM_ID, ";
		$selectSQL .= "CONTENTSCATEGORY_ID, ";
		$selectSQL .= "SUM(CONTENTSITEM_ID_COUNT) AS CONTENTSITEM_ID_COUNT, ";
		$selectSQL .= "SUM(CONTENTSITEM_ID_COUNT_ALL) AS CONTENTSITEM_ID_COUNT_ALL ";

		$selectSQL .= "FROM "; 
		$selectSQL .= "( ";

		$selectSQL .= "( ";
		$selectSQL .= "SELECT ";
		$selectSQL .= "CONTENTSITEMF1.CONTENTSITEM_ID,  ";
		$selectSQL .= "CONTENTSITEM1.CONTENTSCATEGORY_ID, "; 
		$selectSQL .= "COUNT(CONTENTSITEMFCOUNT.CONTENTSITEM_ID) AS CONTENTSITEM_ID_COUNT, ";
		$selectSQL .= "0 AS CONTENTSITEM_ID_COUNT_ALL ";
		$selectSQL .= "FROM ";

		$selectSQL .= "CONTENTSITEM AS CONTENTSITEM1 ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORY AS CONTENTSCATEGORY1 ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM1.CONTENTSCATEGORY_ID = CONTENTSCATEGORY1.CONTENTSCATEGORY_ID ";

		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSITEMF AS CONTENTSITEMF1 ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM1.CONTENTSITEM_ID = CONTENTSITEMF1.CONTENTSITEM_ID ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORYF AS CONTENTSCATEGORYF1 ";
		$selectSQL .= "ON ";
		$selectSQL .= "( ";
		$selectSQL .= "CONTENTSCATEGORY1.CONTENTSCATEGORY_ID = CONTENTSCATEGORYF1.CONTENTSCATEGORY_ID ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMF1.LANG_ID = CONTENTSCATEGORYF1.LANG_ID ";
		$selectSQL .= ") ";
		$selectSQL .= ", ";

		$selectSQL .= "CONTENTSITEM AS CONTENTSITEMCOUNT ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORY AS CONTENTSCATEGORYCOUNT ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEMCOUNT.CONTENTSCATEGORY_ID = CONTENTSCATEGORYCOUNT.CONTENTSCATEGORY_ID ";

		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSITEMF AS CONTENTSITEMFCOUNT ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEMCOUNT.CONTENTSITEM_ID = CONTENTSITEMFCOUNT.CONTENTSITEM_ID ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORYF AS CONTENTSCATEGORYFCOUNT ";
		$selectSQL .= "ON ";
		$selectSQL .= "( ";
		$selectSQL .= "CONTENTSCATEGORYCOUNT.CONTENTSCATEGORY_ID = CONTENTSCATEGORYFCOUNT.CONTENTSCATEGORY_ID ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMFCOUNT.LANG_ID = CONTENTSCATEGORYFCOUNT.LANG_ID ";
		$selectSQL .= ") ";

		$selectSQL .= "WHERE ";
		$selectSQL .= "CONTENTSITEMF1.CONTENTSITEM_ID = '".$contentsItemDataArray["CONTENTSITEM_ID"]."' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMF1.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEM1.POSTED_DATE <= CURRENT_TIMESTAMP ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEM1.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORY1.DEL_FLG = 'N' ";

		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMF1.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYF1.DEL_FLG = 'N' ";

		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSCATEGORYCOUNT.CONTENTSCATEGORY_ID = '".$contentsItemDataArray["CONTENTSCATEGORY_ID"]."' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMFCOUNT.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";
		//ファイル名がindexは一覧には表示されないので除外する
		$selectSQL .= "CONTENTSITEMCOUNT.FILE_NAME != 'index' ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEMCOUNT.POSTED_DATE <= ";
		$selectSQL .= "( ";
		$selectSQL .= "SELECT ";
		$selectSQL .= "CONTENTSITEMSTAMP.POSTED_DATE ";
		$selectSQL .= "FROM ";
		$selectSQL .= "CONTENTSITEM AS CONTENTSITEMSTAMP ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORY AS CONTENTSCATEGORYSTAMP ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEMSTAMP.CONTENTSCATEGORY_ID = CONTENTSCATEGORYSTAMP.CONTENTSCATEGORY_ID ";

		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSITEMF AS CONTENTSITEMFSTAMP ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEMSTAMP.CONTENTSITEM_ID = CONTENTSITEMFSTAMP.CONTENTSITEM_ID ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORYF AS CONTENTSCATEGORYFSTAMP ";
		$selectSQL .= "ON ";
		$selectSQL .= "( ";
		$selectSQL .= "CONTENTSCATEGORYSTAMP.CONTENTSCATEGORY_ID = CONTENTSCATEGORYFSTAMP.CONTENTSCATEGORY_ID ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMFSTAMP.LANG_ID = CONTENTSCATEGORYFSTAMP.LANG_ID ";
		$selectSQL .= ") ";

		$selectSQL .= "WHERE ";
		$selectSQL .= "CONTENTSITEMFSTAMP.CONTENTSITEM_ID = '".$contentsItemDataArray["CONTENTSITEM_ID"]."' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMFSTAMP.LANG_ID = '".$langId."' "; 
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMSTAMP.POSTED_DATE <= CURRENT_TIMESTAMP ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMSTAMP.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYSTAMP.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMFSTAMP.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYFSTAMP.DEL_FLG = 'N' ";
		$selectSQL .= ") ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEMCOUNT.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYCOUNT.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEMFCOUNT.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYFCOUNT.DEL_FLG = 'N' ";

		$selectSQL .= ") ";
		$selectSQL .= "UNION ALL "; 
		$selectSQL .= "( ";

		$selectSQL .= "SELECT ";
		$selectSQL .= "CONTENTSITEMF2.CONTENTSITEM_ID,  ";
		$selectSQL .= "CONTENTSITEM2.CONTENTSCATEGORY_ID, "; 
		$selectSQL .= "0 AS CONTENTSITEM_ID_COUNT, ";
		$selectSQL .= "COUNT(CONTENTSITEMFCOUNTALL.CONTENTSITEM_ID) AS CONTENTSITEM_ID_COUNT_ALL ";
		$selectSQL .= "FROM ";

		$selectSQL .= "CONTENTSITEM AS CONTENTSITEM2 ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORY AS CONTENTSCATEGORY2 ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM2.CONTENTSCATEGORY_ID = CONTENTSCATEGORY2.CONTENTSCATEGORY_ID ";

		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSITEMF AS CONTENTSITEMF2 ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM2.CONTENTSITEM_ID = CONTENTSITEMF2.CONTENTSITEM_ID ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORYF AS CONTENTSCATEGORYF2 ";
		$selectSQL .= "ON ";
		$selectSQL .= "( ";
		$selectSQL .= "CONTENTSCATEGORY2.CONTENTSCATEGORY_ID = CONTENTSCATEGORYF2.CONTENTSCATEGORY_ID ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMF2.LANG_ID = CONTENTSCATEGORYF2.LANG_ID ";
		$selectSQL .= ") ";
		$selectSQL .= ", ";

		$selectSQL .= "CONTENTSITEM AS CONTENTSITEMCOUNTALL ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORY AS CONTENTSCATEGORYCOUNTALL ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEMCOUNTALL.CONTENTSCATEGORY_ID = CONTENTSCATEGORYCOUNTALL.CONTENTSCATEGORY_ID ";

		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSITEMF AS CONTENTSITEMFCOUNTALL ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEMCOUNTALL.CONTENTSITEM_ID = CONTENTSITEMFCOUNTALL.CONTENTSITEM_ID ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORYF AS CONTENTSCATEGORYFCOUNTALL ";
		$selectSQL .= "ON ";
		$selectSQL .= "( ";
		$selectSQL .= "CONTENTSCATEGORYCOUNTALL.CONTENTSCATEGORY_ID = CONTENTSCATEGORYFCOUNTALL.CONTENTSCATEGORY_ID ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMFCOUNTALL.LANG_ID = CONTENTSCATEGORYFCOUNTALL.LANG_ID ";
		$selectSQL .= ") ";

		$selectSQL .= "WHERE ";
		$selectSQL .= "CONTENTSITEM2.CONTENTSITEM_ID = '".$contentsItemDataArray["CONTENTSITEM_ID"]."' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMF2.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEM2.POSTED_DATE <= CURRENT_TIMESTAMP ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEM2.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORY2.DEL_FLG = 'N' ";

		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMF2.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYF2.DEL_FLG = 'N' ";

		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSCATEGORYCOUNTALL.CONTENTSCATEGORY_ID = '".$contentsItemDataArray["CONTENTSCATEGORY_ID"]."' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMFCOUNTALL.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";
		//ファイル名がindexは一覧には表示されないので除外する
		$selectSQL .= "CONTENTSITEMCOUNTALL.FILE_NAME != 'index' ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEMCOUNTALL.POSTED_DATE <= CURRENT_TIMESTAMP ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMCOUNTALL.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYCOUNTALL.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEMFCOUNTALL.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYFCOUNTALL.DEL_FLG = 'N' ";

		$selectSQL .= ") ";
		$selectSQL .= ") ";
		$selectSQL .= "AS CONTENTSITEM_SUM ";

	}
//print "<br>";
//print	$selectSQL;

	$sqlResult = sqlSelectExe($selectSQL);
	while($row = mysqli_fetch_array($sqlResult,MYSQLI_ASSOC)){
		$selectContentsItemSortCount["CONTENTSITEM_ID"] = $row["CONTENTSITEM_ID"];
		$selectContentsItemSortCount["CONTENTSCATEGORY_ID"] = $row["CONTENTSCATEGORY_ID"];
		$selectContentsItemSortCount["CONTENTSITEM_ID_COUNT"] = $row["CONTENTSITEM_ID_COUNT"];
		$selectContentsItemSortCount["CONTENTSITEM_ID_COUNT_ALL"] = $row["CONTENTSITEM_ID_COUNT_ALL"];
	}
//print "<br>ソート順配列<br>";
//print_r($selectContentsItemSortCount);
//print "<br>";
	return $selectContentsItemSortCount;

}

/*
 * コンテンツアイテムの取得
 * <br>
 * 多言語対応済み
 * 
 * @param array $addressDataArray アドレス(ドメイン以下のファイル名)の配列
 * @retuen array コンテンツアイテムの取得結果
 * 
 * @date 2015/09/26
 * @since 2014/09/18
 * @version 1.4
 * 
*/
function selectcontentsItemtitle($addressDataArray){

	//コンテンツカテゴリID
	global $contentsCategoryId;
	//コンテンツアイテムID
	global $contentsItemId;
	//言語ID
	global $langId;

	//カテゴリトップ階層(外国語は1階層下げ)
	global $levelContentsCategory;

	//ディレクトリを取得する
	$directoryName = directoryName($addressDataArray);

	$selectSQL  = "( ";
	$selectSQL .= "SELECT ";
	$selectSQL .= "CONTENTSITEM.LANG_ID AS LANG_ID, "; 
	$selectSQL .= "CONTENTSITEM.TITLE AS TITLE, ";
	$selectSQL .= "CONTENTSITEM.FILE_NAME AS FILE_NAME ";
	$selectSQL .= "FROM CONTENTSITEM ";
	$selectSQL .= "LEFT OUTER JOIN ";
	$selectSQL .= "CONTENTSCATEGORY ";
	$selectSQL .= "ON ";
	$selectSQL .= "CONTENTSITEM.CONTENTSCATEGORY_ID = CONTENTSCATEGORY.CONTENTSCATEGORY_ID ";
	$selectSQL .= "WHERE ";

	//$selectSQL .= "CONTENTSCATEGORY_DIRECTORY = '".$addressDataArray[$levelContentsCategory]."' ";
	$selectSQL .= "CONTENTSCATEGORY.CONTENTSCATEGORY_ID = '".$contentsCategoryId."' ";
	
	$selectSQL .= "AND ";
	$selectSQL .= "DIRECTORY_NAME = '".$directoryName."' ";
	//ファイルがindexファイルでなければindexのタイトルを表示するためindexファイルのタイトルも取得するようにする処理
	if($addressDataArray[1] != "index"){
		$selectSQL .= "AND ";
		$selectSQL .= "( ";

		//$selectSQL .= "FILE_NAME = '".$addressDataArray[1]."' ";
		$selectSQL .= "CONTENTSITEM_ID = '".$contentsItemId."' ";

		$selectSQL .= "OR ";
		$selectSQL .= "FILE_NAME = 'index' ";
		$selectSQL .= ") ";
	}else{
		$selectSQL .= "AND ";

		//$selectSQL .= "FILE_NAME = '".$addressDataArray[1]."' ";
		$selectSQL .= "CONTENTSITEM_ID = '".$contentsItemId."' ";
	}

	//投稿日が過去日付のものだけ取得する。
	$selectSQL .= "AND ";
	$selectSQL .= "POSTED_DATE <= CURRENT_TIMESTAMP ";
	
	$selectSQL .= "AND ";
	$selectSQL .= "CONTENTSITEM.DEL_FLG = 'N' ";
	$selectSQL .= "AND ";
	$selectSQL .= "CONTENTSCATEGORY.DEL_FLG = 'N' ";
	//indexファイルのタイトルも取得するようにする処理だがindex.phpファイルは最後に持って行く
	if($addressDataArray[1] != "index"){
		$selectSQL .= "ORDER BY ";
		$selectSQL .= "CONTENTSITEM.FILE_NAME = 'index' ASC ";
	}

	$selectSQL .= ") ";
	$selectSQL .= "UNION ALL ";
	$selectSQL .= "( ";

	$selectSQL .= "SELECT ";
	$selectSQL .= "CONTENTSITEMF.LANG_ID AS LANG_ID, ";
	$selectSQL .= "CONTENTSITEMF.TITLE AS TITLE, ";
	$selectSQL .= "CONTENTSITEM.FILE_NAME AS FILE_NAME ";
	$selectSQL .= "FROM ";

	$selectSQL .= "CONTENTSITEM ";
	$selectSQL .= "LEFT OUTER JOIN ";
	$selectSQL .= "CONTENTSITEMF ";
	$selectSQL .= "ON ";
	$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID = CONTENTSITEMF.CONTENTSITEM_ID ";

	$selectSQL .= "LEFT OUTER JOIN ";
	$selectSQL .= "CONTENTSCATEGORY ";
	$selectSQL .= "ON ";
	$selectSQL .= "CONTENTSITEM.CONTENTSCATEGORY_ID = CONTENTSCATEGORY.CONTENTSCATEGORY_ID ";

	$selectSQL .= "LEFT OUTER JOIN ";
	$selectSQL .= "CONTENTSCATEGORYF ";
	$selectSQL .= "ON ";
	$selectSQL .= "( ";
	$selectSQL .= "CONTENTSCATEGORY.CONTENTSCATEGORY_ID = CONTENTSCATEGORYF.CONTENTSCATEGORY_ID ";
	$selectSQL .= "AND ";
	$selectSQL .= "CONTENTSITEMF.LANG_ID = CONTENTSCATEGORYF.LANG_ID ";
	$selectSQL .= ") ";
	$selectSQL .= "WHERE ";
	//$selectSQL .= "CONTENTSCATEGORY.CONTENTSCATEGORY_DIRECTORY = '".$addressDataArray[$levelContentsCategory]."' ";
	$selectSQL .= "CONTENTSCATEGORYF.CONTENTSCATEGORY_ID = '".$contentsCategoryId."' ";

	$selectSQL .= "AND ";
	$selectSQL .= "CONTENTSITEM.DIRECTORY_NAME = '".$directoryName."' ";
	//ファイルがindexファイルでなければindexのタイトルを表示するためindexファイルのタイトルも取得するようにする処理
	if($addressDataArray[1] != "index"){
		$selectSQL .= "AND ";
		$selectSQL .= "( ";

		//$selectSQL .= "CONTENTSITEM.FILE_NAME = '".$addressDataArray[1]."' ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID = '".$contentsItemId."' ";

		$selectSQL .= "OR ";
		$selectSQL .= "CONTENTSITEM.FILE_NAME = 'index' ";
		$selectSQL .= ") ";
	}else{
		$selectSQL .= "AND ";
		//$selectSQL .= "CONTENTSITEM.FILE_NAME = '".$addressDataArray[1]."' ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID = '".$contentsItemId."' ";
	}

	//投稿日が過去日付のものだけ取得する。
	$selectSQL .= "AND ";
	$selectSQL .= "CONTENTSITEM.POSTED_DATE <= CURRENT_TIMESTAMP ";
	
	$selectSQL .= "AND ";
	$selectSQL .= "CONTENTSITEM.DEL_FLG = 'N' ";
	$selectSQL .= "AND ";
	$selectSQL .= "CONTENTSCATEGORY.DEL_FLG = 'N' ";
	$selectSQL .= "AND ";
	$selectSQL .= "CONTENTSITEMF.DEL_FLG = 'N' ";
	$selectSQL .= "AND ";
	$selectSQL .= "CONTENTSCATEGORYF.DEL_FLG = 'N' ";
	//indexファイルのタイトルも取得するようにする処理だがindex.phpファイルは最後に持って行く
	if($addressDataArray[1] != "index"){
		$selectSQL .= "ORDER BY ";
		$selectSQL .= "CONTENTSITEM.FILE_NAME = 'index' ASC ";
	}

	$selectSQL .= ") ";
	$selectSQL .= "ORDER BY ";
	//indexファイルのタイトルも取得するようにする処理だがindex.phpファイルは最後に持って行く
	if($addressDataArray[1] != "index"){
		$selectSQL .= "LANG_ID ASC, ";
		$selectSQL .= "FILE_NAME = 'index' ASC ";
	}else{
		$selectSQL .= "LANG_ID ASC ";
	}

	//print	$selectSQL;
	$sqlResult = sqlSelectExe($selectSQL);

	while($row = mysqli_fetch_array($sqlResult,MYSQLI_ASSOC)){
		$selectcontentsItemtitle[$row["LANG_ID"]][] = $row["TITLE"];
	}

	if(isset($selectcontentsItemtitle) == false){
		$selectcontentsItemtitle[$langId][0] = $directoryName;
	}
	//print_r($selectcontentsItemtitle);
	return $selectcontentsItemtitle;

}

/*
 * コンテンツアイテム新着情報一覧取得
 * <br>
 * 多言語対応済み
 * 
 * @param array $contentsItemId コンテンツアイテムID。一覧画面はNULL
 * @param array $limitCountArray FROM TOの配列
 * @retuen array コンテンツアイテム新着情報一覧取得結果
 * 
 *   
 * @date 2017/03/22
 * @since 2014/11/07
 * @version 1.3
 * 
*/
function selectContentsitemWhatsNewList($contentsItemId,$limitCountArray){

	//言語コード
	global $langCode;
	//言語ID
	global $langId;

	//言語が日本語の場合
	if($langCode == "ja"){

		$selectSQL  = "SELECT ";
	
		$selectSQL .= "SQL_CALC_FOUND_ROWS ";
	
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID, ";
		$selectSQL .= "CONTENTSCATEGORY_DIRECTORY, ";
		$selectSQL .= "CONTENTSCATEGORY_NAME, ";
		$selectSQL .= "CONTENTSITEM.TITLE, ";
		$selectSQL .= "DIRECTORY_NAME, ";
		$selectSQL .= "FILE_NAME, ";
		$selectSQL .= "IMAGE_ALT, ";
		$selectSQL .= "IMAGE_TITLE, ";
		$selectSQL .= "IMAGE_DIRECTORY_NAME, ";
		$selectSQL .= "IMAGE_FILE_NAME, ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "IMAGESIZE.IMAGESIZE_CODE, ";
		$selectSQL .= "ITEMCOMMENT, ";
		$selectSQL .= "KEYWORD, ";
		$selectSQL .= "PHOTO_DATE, ";
		$selectSQL .= "POSTED_DATE ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "CONTENTSITEM.INSERT_DATE, ";
		//$selectSQL .= "CONTENTSITEM.UPDATE_DATE ";
		$selectSQL .= "FROM ";
		$selectSQL .= "CONTENTSITEM ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORY ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.CONTENTSCATEGORY_ID = CONTENTSCATEGORY.CONTENTSCATEGORY_ID ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "LEFT OUTER JOIN ";
		//$selectSQL .= "IMAGESIZE ";
		//$selectSQL .= "ON ";
		//$selectSQL .= "CONTENTSITEM.IMAGESIZE_HEIGHT = IMAGESIZE.IMAGESIZE_HEIGHT ";
		//$selectSQL .= "AND ";
		//$selectSQL .= "CONTENTSITEM.IMAGESIZE_WIDTH = IMAGESIZE.IMAGESIZE_WIDTH ";
		$selectSQL .= "WHERE ";
	
		if($contentsItemId != NULL){
			//自分自身のアイテムは検索させない
			$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID != '".$contentsItemId."' ";
			$selectSQL .= "AND ";
		}
	
		//ファイル名がindexは一覧ファイルそのものなので表示しない
		$selectSQL .= "FILE_NAME != 'index' ";
		$selectSQL .= "AND ";

		//投稿日が過去日付のものだけ取得する。
		$selectSQL .= "CONTENTSITEM.POSTED_DATE <= CURRENT_TIMESTAMP ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEM.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEM.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORY.DEL_FLG = 'N' ";
		$selectSQL .= "ORDER BY ";
		$selectSQL .= "POSTED_DATE DESC, ";
		$selectSQL .= "CONTENTSITEM.TITLE ASC, ";
		$selectSQL .= "PHOTO_DATE ASC, ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID DESC ";
		//LIMIT
		$selectSQL .= "LIMIT ";
		$selectSQL .= $limitCountArray[0].", ";
		$selectSQL .= $limitCountArray[1]." ";

	//言語が外国語の場合
	}else{
		$selectSQL  = "SELECT ";
	
		$selectSQL .= "SQL_CALC_FOUND_ROWS ";
	
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID, ";
		$selectSQL .= "CONTENTSCATEGORY.CONTENTSCATEGORY_DIRECTORY, ";
		$selectSQL .= "CONTENTSCATEGORYF.CONTENTSCATEGORY_NAME, ";
		$selectSQL .= "CONTENTSITEMF.TITLE, ";
		$selectSQL .= "CONTENTSITEM.DIRECTORY_NAME, ";
		$selectSQL .= "CONTENTSITEM.FILE_NAME, ";
		$selectSQL .= "CONTENTSITEMF.IMAGE_ALT, ";
		$selectSQL .= "CONTENTSITEMF.IMAGE_TITLE, ";
		$selectSQL .= "CONTENTSITEM.IMAGE_DIRECTORY_NAME, ";
		$selectSQL .= "CONTENTSITEM.IMAGE_FILE_NAME, ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "IMAGESIZE.IMAGESIZE_CODE, ";
		$selectSQL .= "CONTENTSITEMF.ITEMCOMMENT, ";
		$selectSQL .= "CONTENTSITEMF.KEYWORD, ";
		$selectSQL .= "CONTENTSITEM.PHOTO_DATE, ";
		$selectSQL .= "CONTENTSITEM.POSTED_DATE ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "CONTENTSITEMF.INSERT_DATE, ";
		//$selectSQL .= "CONTENTSITEMF.UPDATE_DATE ";

		$selectSQL .= "FROM ";
		$selectSQL .= "CONTENTSITEM ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORY ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.CONTENTSCATEGORY_ID = CONTENTSCATEGORY.CONTENTSCATEGORY_ID ";

		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSITEMF ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID = CONTENTSITEMF.CONTENTSITEM_ID ";

		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORYF ";
		$selectSQL .= "ON ";
		$selectSQL .= "( ";
		$selectSQL .= "CONTENTSCATEGORY.CONTENTSCATEGORY_ID = CONTENTSCATEGORYF.CONTENTSCATEGORY_ID ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMF.LANG_ID = CONTENTSCATEGORYF.LANG_ID ";
		$selectSQL .= ") ";
		//当面使わないのでコメントアウト
		//$selectSQL .= "LEFT OUTER JOIN ";
		//$selectSQL .= "IMAGESIZE ";
		//$selectSQL .= "ON ";
		//$selectSQL .= "CONTENTSITEM.IMAGESIZE_HEIGHT = IMAGESIZE.IMAGESIZE_HEIGHT ";
		//$selectSQL .= "AND ";
		//$selectSQL .= "CONTENTSITEM.IMAGESIZE_WIDTH = IMAGESIZE.IMAGESIZE_WIDTH ";

		$selectSQL .= "WHERE ";
	
		if($contentsItemId != NULL){
			//自分自身のアイテムは検索させない
			$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID != '".$contentsItemId."' ";
			$selectSQL .= "AND ";
		}

		//ファイル名がindexは一覧ファイルそのものなので表示しない
		$selectSQL .= "CONTENTSITEM.FILE_NAME != 'index' ";
		$selectSQL .= "AND ";
	
		//投稿日が過去日付のものだけ取得する。
		$selectSQL .= "CONTENTSITEM.POSTED_DATE <= CURRENT_TIMESTAMP ";
		$selectSQL .= "AND ";

		$selectSQL .= "CONTENTSITEMF.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYF.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";
	
		$selectSQL .= "CONTENTSITEM.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORY.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMF.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYF.DEL_FLG = 'N' ";

		$selectSQL .= "ORDER BY ";
		$selectSQL .= "CONTENTSITEM.POSTED_DATE DESC, ";
		$selectSQL .= "CONTENTSITEMF.TITLE ASC, ";
		$selectSQL .= "CONTENTSITEM.PHOTO_DATE ASC, ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID DESC ";
		//LIMIT
		$selectSQL .= "LIMIT ";
		$selectSQL .= $limitCountArray[0].", ";
		$selectSQL .= $limitCountArray[1]." ";
	
	}

//print	$selectSQL;

	$sqlResult = sqlSelectListExe($selectSQL);

	//関連項目の一覧を配列に代入する
	while($row = mysqli_fetch_array($sqlResult[0],MYSQLI_ASSOC)){
		$resultList[] = $row;
	}
	if(isset($resultList) == false){
		$resultList = "";
	}

	//コンテンツアイテムの件数を変数に代入する。
	while($row = mysqli_fetch_row($sqlResult[1])){
		$resultCount = $row[0];
	}
	if(isset($resultCount) == false){
		$resultCount = 0;
	}

	return array($resultList,$resultCount);
	
}

/*
 * コンテンツアイテムのデータ取得
 * <br>
 * 多言語対応済み
 * 
 * @param array $addressDataArray アドレス(ドメイン以下のファイル名)の配列
 * @retuen array コンテンツアイテムの/写真、投稿日、及びキーワードの取得結果
 * 
 *   
 * @date 2017/03/22
 * @since 2014/09/18
 * @version 1.4
 * 
*/
function selectPhotoPosted($addressDataArray){

	//カテゴリトップ階層(外国語は1階層下げ)
	global $levelContentsCategory;
	//言語コード
	global $langCode;
	//言語ID
	global $langId;
	//ディレクトリを取得する
	$directoryName = directoryName($addressDataArray);
	
	//言語が日本語の場合
	if($langCode == "ja"){
		$selectSQL  = "SELECT ";
		$selectSQL .= "CONTENTSITEM_ID, ";
		$selectSQL .= "CONTENTSITEM.CONTENTSCATEGORY_ID, ";
		$selectSQL .= "PHOTO_DATE, ";
		$selectSQL .= "POSTED_DATE, ";
		$selectSQL .= "ITEMCOMMENT, ";
		$selectSQL .= "KEYWORD ";
		$selectSQL .= "FROM ";
		$selectSQL .= "CONTENTSITEM ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORY ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.CONTENTSCATEGORY_ID = CONTENTSCATEGORY.CONTENTSCATEGORY_ID ";
		$selectSQL .= "WHERE ";
		$selectSQL .= "CONTENTSCATEGORY_DIRECTORY = '".$addressDataArray[$levelContentsCategory]."' ";
		$selectSQL .= "AND ";
		$selectSQL .= "DIRECTORY_NAME = '".$directoryName."' ";
		$selectSQL .= "AND ";
		$selectSQL .= "FILE_NAME = '".$addressDataArray[1]."' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEM.LANG_ID = '".$langId."' ";
	
		//投稿日が過去日付のものだけ取得する。
		$selectSQL .= "AND ";
		$selectSQL .= "POSTED_DATE <= CURRENT_TIMESTAMP ";
		
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEM.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORY.DEL_FLG = 'N' ";

	//言語が外国語の場合
	}else{
		$selectSQL  = "SELECT ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID, ";
		$selectSQL .= "CONTENTSITEM.CONTENTSCATEGORY_ID, ";
		$selectSQL .= "CONTENTSITEM.PHOTO_DATE, ";
		$selectSQL .= "CONTENTSITEM.POSTED_DATE, ";
		$selectSQL .= "CONTENTSITEMF.ITEMCOMMENT, ";
		$selectSQL .= "CONTENTSITEMF.KEYWORD ";
		$selectSQL .= "FROM "; 
		$selectSQL .= "CONTENTSITEM ";
		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORY ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.CONTENTSCATEGORY_ID = CONTENTSCATEGORY.CONTENTSCATEGORY_ID ";

		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSITEMF ";
		$selectSQL .= "ON ";
		$selectSQL .= "CONTENTSITEM.CONTENTSITEM_ID = CONTENTSITEMF.CONTENTSITEM_ID ";

		$selectSQL .= "LEFT OUTER JOIN ";
		$selectSQL .= "CONTENTSCATEGORYF ";
		$selectSQL .= "ON ";
		$selectSQL .= "( ";
		$selectSQL .= "CONTENTSCATEGORY.CONTENTSCATEGORY_ID = CONTENTSCATEGORYF.CONTENTSCATEGORY_ID ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMF.LANG_ID = CONTENTSCATEGORYF.LANG_ID ";
		$selectSQL .= ") ";
	
		$selectSQL .= "WHERE ";
		$selectSQL .= "CONTENTSCATEGORY.CONTENTSCATEGORY_DIRECTORY = '".$addressDataArray[$levelContentsCategory]."' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEM.DIRECTORY_NAME = '".$directoryName."' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEM.FILE_NAME = '".$addressDataArray[1]."' ";
	
		//投稿日が過去日付のものだけ取得する。
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEM.POSTED_DATE <= CURRENT_TIMESTAMP ";
		
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMF.LANG_ID = '".$langId."' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYF.LANG_ID = '".$langId."' ";

		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEM.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORY.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSITEMF.DEL_FLG = 'N' ";
		$selectSQL .= "AND ";
		$selectSQL .= "CONTENTSCATEGORYF.DEL_FLG = 'N' ";

	}

//print	$selectSQL;

	$sqlResult = sqlSelectExe($selectSQL);
	while($row = mysqli_fetch_array($sqlResult,MYSQLI_ASSOC)){
		$selectPhotoPosted["CONTENTSITEM_ID"] = $row["CONTENTSITEM_ID"];
		$selectPhotoPosted["CONTENTSCATEGORY_ID"] = $row["CONTENTSCATEGORY_ID"];
		$selectPhotoPosted["PHOTO_DATE"] = $row["PHOTO_DATE"];
		$selectPhotoPosted["POSTED_DATE"] = $row["POSTED_DATE"];
		$selectPhotoPosted["ITEMCOMMENT"] = $row["ITEMCOMMENT"];
		$selectPhotoPosted["KEYWORD"] = $row["KEYWORD"];
	}
	//撮影日がNULLの場合
	if(isset($selectPhotoPosted["PHOTO_DATE"]) == false){
		$selectPhotoPosted["PHOTO_DATE"] = "";
	}
	//投稿日がNULLの場合
	if(isset($selectPhotoPosted["POSTED_DATE"]) == false){
		$selectPhotoPosted["POSTED_DATE"] = "";
	}
	//アイテムコメントがNULLの場合
	if(isset($selectPhotoPosted["ITEMCOMMENT"]) == false){
		$selectPhotoPosted["ITEMCOMMENT"] = $row["ITEMCOMMENT"];
	}
	//キーワードがNULLの場合
	if(isset($selectPhotoPosted["KEYWORD"]) == false){
		$selectPhotoPosted["KEYWORD"] = "";
	}

	return $selectPhotoPosted;

}

/*
 * test用
 * 
 * @retuen array test取得結果
 * 
 *   
 * @since 2014/09/11
 * @version 1.0 
 * 
*/
function selectSecondDirectoryList(){
print "sqlselect通過確認";
print "<br>";

	$selectSQL  = "select "; 
	$selectSQL .= "* ";
	$selectSQL .= "from ";
	$selectSQL .= "TESTC ";
	$sqlResult = sqlSelectExe($selectSQL);
	while ($row = mysqli_fetch_array($sqlResult,MYSQLI_ASSOC)) {
//		print"test";
		$result[] = $row;
	}

return $result;

}

?>
