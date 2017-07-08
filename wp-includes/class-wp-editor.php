tImage($postID,$size = self::THUMB_FULL){
			
			$post_thumbnail_id = get_post_thumbnail_id( $postID );
			if(empty($post_thumbnail_id))
				return("");
			
			$arrImage = wp_get_attachment_image_src($post_thumbnail_id,$size);
			if(empty($arrImage))
				return("");
			
			$urlImage = $arrImage[0];
			return($urlImage);
		}
		
		/**
		 * 
		 * get post thumb id from post id
		 */
		public static function getPostThumbID($postID){
			$thumbID = get_post_thumbnail_id( $postID );
			return($thumbID);
		}
		
		
		/**
		 * 
		 * get attachment image array by id and size
		 */
		public static function getAttachmentImage($thumbID,$size = self::THUMB_FULL){
			
			$arrImage = wp_get_attachment_image_src($thumbID,$size);
			if(empty($arrImage))
				return(false);
			
			$output = array();
			$output["url"] = UniteFunctionsRev::getVal($arrImage, 0);
			$output["width"] = UniteFunctionsRev::getVal($arrImage, 1);
			$output["height"] = UniteFunctionsRev::getVal($arrImage, 2);
			
			return($output);
		}
		
		
		/**
		 * 
		 * get attachment image url
		 */
		public static function getUrlAttachmentImage($thumbID,$size = self::THUMB_FULL){
			$arrImage = wp_get_attachment_image_src($thumbID,$size);
			if(empty($arrImage))
				return(false);
			$url = UniteFunctionsRev::getVal($arrImage, 0);
			return($url);
		}
		
		
		/**
		 * 
		 * get link of edit slides by category id
		 */
		public static function getUrlSlidesEditByCatID($catID){
			
			$url = self::$urlAdmin;
			$url .= "edit.php?s&post_status=all&post_type=post&action=-1&m=0&cat=".$catID."&paged=1&mode=list&action2=-1";
			
			return($url);
		}
		
		/**
		 * 
		 * get edit post url
		 */
		public static function getUrlEditPost($postID){
			$url = self::$urlAdmin;
			$url .= "post.php?post=".$postID."&action=edit";
			
			return($url);
		}
		
		
		/**
		 * 
		 * get new post url
		 */
		public static function getUrlNewPost(){
			$url = self::$urlAdmin;
			$url .= "post-new.php";
			return($url);
		}
		
		
		/**
		 * 
		 * delete post
		 */
		public static function deletePost($postID){
			$success = wp_delete_post($postID,false);
			if($success == false)
				UniteFunctionsRev::throwError("Could not delete post: $postID");
		}
		
		/**
		 * 
		 * update post thumbnail
		 */
		public static function updatePostThumbnail($postID,$thumbID){
			set_post_thumbnail($postID, $thumbID);
		}
		
		
		/**
		 * 
		 * get intro from content
		 */
		public static function getIntroFromContent($text){
			$intro = "";
			if(!empty($text)){
				$arrExtended = get_extended($text);
				$intro = UniteFunctionsRev::getVal($arrExtended, "main");
				
				/*
				if(strlen($text) != strlen($intro))
					$intro .= "...";
				*/
			}
			
			return($intro);
		}

		
		/**
		 * 
		 * get excerpt from post id
		 */
		public static function getExcerptById($postID, $limit=55){
			
			 $post = get_post($postID);	
			 
			 $excerpt = $post->post_excerpt;
			 $excerpt = trim($excerpt);
			 
			 $excerpt = trim($excerpt);
			 if(empty($excerpt))
				$excerpt = $post->post_content;			 
			 
			 $excerpt = strip_tags($excerpt,"<b><br><br/><i><strong><small>");
			 
			 $excerpt = UniteFunctionsRev::getTextIntro($excerpt, $limit);
			 
			 return $excerpt;
		}		
		
		
		/**
		 * 
		 * get user display name from user id
		 */
		public static function getUserDisplayName($userID){
			
			$displayName =  get_the_author_meta('display_name', $userID);
			
			return($displayName);
		}
		
		
		/**
		 * 
		 * get categories by id's
		 */
		public static function getCategoriesByIDs($arrIDs,$strTax = null){			
			
			if(empty($arrIDs))
				return(array());
				
			if(is_string($arrIDs))
				$strIDs = $arrIDs;
			else
				$strIDs = implode(",", $arrIDs);
			
			$args = array();
			$args["include"] = $strIDs;
							
			if(!empty($strTax)){
				if(is_string($strTax))
					$strTax = explode(",",$strTax);
				
				$args["taxonomy"] = $strTax;
			}
						
			$arrCats = get_categories( $args );
			
			if(!empty($arrCats))
				$arrCats = UniteFunctionsRev::convertStdClassToArray($arrCats);			
			
			return($arrCats);
		}
		
		
		/**
		 * 
		 * get categories short 
		 */
		public static function getCategoriesByIDsShort($arrIDs,$strTax = null){
			$arrCats = self::getCategoriesByIDs($arrIDs,$strTax);
			$arrNew = array();
			foreach($arrCats as $cat){
				$catID = $cat["term_id"];
				$catName = $cat["name"];
				$arrNew[$catID] =  $catName;
			}
			
			return($arrNew);
		}
		
		
		/**
		 * get categories list, copy the code from default wp functions
		 */
		public static function getCategoriesHtmlList($catIDs,$strTax = null){
			global $wp_rewrite;
			
			//$catList = get_the_category_list( ",", "", $postID );
			
			$categories = self::getCategoriesByIDs($catIDs,$strTax);
			
			$arrErrors = UniteFunctionsRev::getVal($categories, "errors");
			
			if(!empty($arrErrors)){
				foreach($arrErrors as $key=>$arr){
					$strErrors = implode($arr,",");				
				}
				
				UniteFunctionsRev::throwError("getCategoriesHtmlList error: ".$strErrors);
			}
			
			$rel = ( is_object( $wp_rewrite ) && $wp_rewrite->using_permalinks() ) ? 'rel="category tag"' : 'rel="category"';
			
			$separator = ',';
			
			$thelist = '';
						
			$i = 0;
			foreach ( $categories as $category ) {

				if(is_object($category))
					$category = (array)$category;
				
				if ( 0 < $i )
					$thelist .= $separator;
					
				$catID = $category["term_id"];
				$link = get_category_link($catID);
				$catName = $category["name"];
				
				if(!empty($link))
					$thelist .= '<a href="' . esc_url( $link ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s", REVSLIDER_TEXTDOMAIN), $category["name"] ) ) . '" ' . $rel . '>' . $catName.'</a>';
				else
					$thelist .= $catName;
				
				++$i;
			}
			
			
			return $thelist;
		}
		
		
		/**
		 * 
		 * get post tags html list
		 */
		public static function getTagsHtmlList($postID){
			$tagList = get_the_tag_list("",",","",$postID);
			return($tagList);
		}
		
		/**
		 * 
		 * convert date to the date format that the user chose.
		 */
		public static function convertPostDate($date){
			if(empty($date))
				return($date);
			$date = date_i18n(get_option('date_format'), strtotime($date));
			return($date);
		}
		
		/**
		 * 
		 * get assoc list of the taxonomies
		 */
		public static function getTaxonomiesAssoc(){
			$arr = get_taxonomies();
			unset($arr["post_tag"]);
			unset($arr["nav_menu"]);
			unset($arr["link_category"]);
			unset($arr["post_format"]);
			
			return($arr);
		}
		
		
		/**
		 * 
		 * get post types array with taxomonies
		 */
		public static function getPostTypesWithTaxomonies(){
			$arrPostTypes = self::getPostTypesAssoc();
			
			foreach($arrPostTypes as $postType=>$title){
				$arrTaxomonies = self::getPostTypeTaxomonies($postType);
				$arrPostTypes[$postType] = $arrTaxomonies;
			}
			
			return($arrPostTypes);
		}
		
		
		/**
		 * 
		 * get array of post types with categories (the taxonomies is between).
		 * get only those taxomonies that have some categories in it.
		 */
		public static function getPostTypesWithCats(){
			$arrPostTypes = self::getPostTypesWithTaxomonies();
			
			$arrPostTypesOutput = array();
			foreach($arrPostTypes as $name=>$arrTax){

				$arrTaxOutput = array();
				foreach($arrTax as $taxName=>$taxTitle){
					$cats = self::getCategoriesAssoc($taxName);
					if(!empty($cats))
						$arrTaxOutput[] = array(
								 "name"=>$taxName,
								 "title"=>$taxTitle,
								 "cats"=>$cats);
				}
								
				$arrPostTypesOutput[$name] = $arrTaxOutput;
				
			}
			
			return($arrPostTypesOutput);
		}
		
		
		/**
		 * 
		 * get array of all taxonomies with categories.
		 */
		public static function getTaxonomiesWithCats(){
						
			$arrTax = self::getTaxonomiesAssoc();
			$arrTaxNew = array();
			foreach($arrTax as $key=>$value){
				$arrItem = array();
				$arrItem["name"] = $key;
				$arrItem["title"] = $value;
				$arrItem["cats"] = self::getCategoriesAssoc($key);
				$arrTaxNew[$key] = $arrItem;
			}
			
			return($arrTaxNew);
		}

		
		/**
		 * 
		 * get content url
		 */
		public static function getUrlContent(){
		
			if(self::isMultisite() == false){	//without multisite
				$baseUrl = content_url()."/";
			}
			else{	//for multisite
				$arrUploadData = wp_upload_dir();
				$baseUrl = $arrUploadData["baseurl"]."/";
			}
			
			if(is_ssl()){
				$baseUrl = str_replace("http://", "https://", $baseUrl);
			}
			
			return($baseUrl);
		}

		/**
		 * 
		 * get wp-content path
		 */
		public static function getPathContent(){		
			if(self::isMultisite()){
				if(!defined("BLOGUPLOADDIR")){
					$pathBase = self::getPathBase();
					$pathContent = $pathBase."wp-content/";
				}else
				  $pathContent = BLOGUPLOADDIR;
			}else{
				$pathContent = WP_CONTENT_DIR;
				if(!empty($pathContent)){
					$pathContent .= "/";
				}
				else{
					$pathBase = self::getPathBase();
					$pathContent = $pathBase."wp-content/";
				}
			}
			
			return($pathContent);
		}

		/**
		 * 
		 * get cats and taxanomies data from the category id's
		 */
		public static function getCatAndTaxData($catIDs){
			
			if(is_string($catIDs)){
				$catIDs = trim($catIDs);
				if(empty($catIDs))
					return(array("tax"=>"","cats"=>""));
				
				$catIDs = explode(",", $catIDs);
			}
			
			$strCats = "";
			$arrTax = array();
			foreach($catIDs as $cat){
				if(strpos($cat,"option_disabled") === 0)
					continue;
				
				$pos = strrpos($cat,"_");
				if($pos === false)
					UniteFunctionsRev::throwError("The category is in wrong format");
				
				$taxName = substr($cat,0,$pos);
				$catID = substr($cat,$pos+1,strlen($cat)-$pos-1);
				
				$arrTax[$taxName] = $taxName;
				if(!empty($strCats))
					$strCats .= ",";
					
				$strCats .= $catID;				
			}
			
			$strTax = "";
			foreach($arrTax as $taxName){
				if(!empty($strTax))
					$strTax .= ",";
					
				$strTax .= $taxName;
			} 
			
			$output = array("tax"=>$strTax,"cats"=>$strCats);
			
			return($output);
		}
		
		
		/**
		 * 
		 * get current language code
		 */
		public static function getCurrentLangCode(){
			$langTag = ICL_LANGUAGE_CODE;

			return($langTag);
		}
		
		/**
		 * 
		 * write settings language file for wp automatic scanning
		 */
		public static function writeSettingLanguageFile($filepath){
			$info = pathinfo($filepath);
			$path = UniteFunctionsRev::getVal($info, "dirname")."/";
			$filename = UniteFunctionsRev::getVal($info, "filename");
			$ext =  UniteFunctionsRev::getVal($info, "extension");
			$filenameOutput = "{$filename}_{$ext}_lang.php";
			$filepathOutput = $path.$filenameOutput;
			
			//load settings
			$settings = new UniteSettingsAdvancedRev();	
			$settings->loadXMLFile($filepath);
			$arrText = $settings->getArrTextFromAllSettings();
			
			$str = "";
			$str .= "<?php \n";
			foreach($arrText as $text){
				$text = str_replace('"', '\\"', $text);
				$str .= "_e(\"$text\",\"".REVSLIDER_TEXTDOMAIN."\"); \n";				
			}
			$str .= "?>";
			
			UniteFunctionsRev::writeFile($str, $filepathOutput);
		}

		
		/**
		 * 
		 * check the current post for the existence of a short code
		 */  
		public static function hasShortcode($shortcode = '') {  
		
			if(!is_singular())
				return false;
				
		    $post = get_post(get_the_ID());  
		      
		    if (empty($shortcode))   
		        return $found;
				
		    $found = false; 
		        
		    if (stripos($post->post_content, '[' . $shortcode) !== false )    
		        $found = true;  
		       
		    return $found;  
		}  		
		
		
	}	//end of the class
						
	//init the static vars
	UniteFunctionsWPRev::initStaticVars();
	
?>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 <?php

	class UniteFunctionsRev{
		
		public static function throwError($message,$code=null){
			if(!empty($code))
				throw new Exception($message,$code);
			else
				throw new Exception($message);
		}
		
		
		/**
		 * 
		 * set output for download
		 */
		public static function downloadFile($str,$filename="output.txt"){
			
			//output for download
			header('Content-Description: File Transfer');
			header('Content-Type: text/html; charset=UTF-8');
    		header("Content-Disposition: attachment; filename=".$filename.";");
    		header("Content-Transfer-Encoding: binary");
    		header("Content-Length: ".strlen($str));			
			echo $str;			
			exit();
		}
		
		
		
		/**
		 * 
		 * turn boolean to string
		 */
		public static function boolToStr($bool){
			if(gettype($bool) == "string")
				return($bool);
			if($bool == true)
				return("true");
			else 
				return("false");
		}
		
		/**
		 * 
		 * convert string to boolean
		 */
		public static function strToBool($str){
			if(is_bool($str))
				return($str);
				
			if(empty($str))
				return(false);
				
			if(is_numeric($str))
				return($str != 0);
				
			$str = strtolower($str);
			if($str == "true")
				return(true);
				
			return(false);
		}
		
		
		//------------------------------------------------------------
		// get black value from rgb value
		public static function yiq($r,$g,$b){
			return (($r*0.299)+($g*0.587)+($b*0.114));
		}
		
		/**
		 * 
		 * convert colors to rgb
		 */
		public static function html2rgb($color){
			
			if(empty($color))
				$color = "#000000";
			
			if ($color[0] == '#')
				$color = substr($color, 1);
			if (strlen($color) == 6)
				list($r, $g, $b) = array($color[0].$color[1],
										 $color[2].$color[3],
										 $color[4].$color[5]);
			elseif (strlen($color) == 3)
				list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
			else
				return false;
			$r = hexdec($r); $g = hexdec($g); $b = hexdec($b);
			return array($r, $g, $b);
		}
			
		
		//---------------------------------------------------------------------------------------------------
		// convert timestamp to time string
		public static function timestamp2Time($stamp){
			$strTime = date("H:i",$stamp);
			return($strTime);
		}
		
		//---------------------------------------------------------------------------------------------------
		// convert timestamp to date and time string
		public static function timestamp2DateTime($stamp){
			$strDateTime = date("d M Y, H:i",$stamp);
			return($strDateTime);		
		}
		
		//---------------------------------------------------------------------------------------------------
		// convert timestamp to date string
		public static function timestamp2Date($stamp){
			$strDate = date("d M Y",$stamp);	//27 Jun 2009
			return($strDate);
		}
		
		/**
		 * get value from array. if not - return alternative
		 */
		public static function getVal($arr,$key,$altVal=""){
			if(isset($arr[$key])) return($arr[$key]);
			return($altVal);
		}
		
		/**
		 * 
		 * convert object to string.
		 */
		public static function toString($obj){
			return(trim((string)$obj));
		}
		
		/**
		 * remove utf8 bom sign
		 */
		public static function remove_utf8_bom($content){
			$content = str_replace(chr(239),"",$content);
			$content = str_replace(chr(187),"",$content);
			$content = str_replace(chr(191),"",$content);		
			$content = trim($content);
			return($content);
		}
		
		//------------------------------------------------------------
		// get variable from post or from get. get wins.
		public static function getPostGetVariable($name,$initVar = ""){
			$var = $initVar;
			if(isset($_POST[$name])) $var = $_POST[$name];
			else if(isset($_GET[$name])) $var = $_GET[$name];
			return($var);
		}
		
		
		//------------------------------------------------------------
		
		public static function getPostVariable($name,$initVar = ""){
			$var = $initVar;
			if(isset($_POST[$name])) $var = $_POST[$name];
			return($var);
		}
		
		//------------------------------------------------------------
	
		
		public static function getGetVar($name,$initVar = ""){
			$var = $initVar;
			if(isset($_GET[$name])) $var = $_GET[$name];
			return($var);
		}
		
		/**
		 * 
		 * validate that some file exists, if not - throw error
		 */
		public static function validateFilepath($filepath,$errorPrefix=null){
			if(file_exists($filepath) == true)
				return(false);
			if($errorPrefix == null)
				$errorPrefix = "File";
			$message = $errorPrefix." $filepath not exists!";
			self::throwError($message);
		}
		
		/**
		 * 
		 * validate that some value is numeric
		 */
		public static function validateNumeric($val,$fieldName=""){
			self::validateNotEmpty($val,$fieldName);
			
			if(empty($fieldName))
				$fieldName = "Field";
			
			if(!is_numeric($val))
				self::throwError("$fieldName should be numeric ");
		}
		
		/**
		 * 
		 * validate that some variable not empty
		 */
		public static function validateNotEmpty($val,$fieldName=""){
			
			if(empty($fieldName))
				$fieldName = "Field";
				
			if(empty($val) && is_numeric($val) == false)
				self::throwError("Field <b>$fieldName</b> should not be empty");
		}
		
		
		/**
		 * 
		 * if directory not exists - create it
		 * @param $dir
		 */
		public static function checkCreateDir($dir){
			if(!is_dir($dir))
				mkdir($dir);
			
			if(!is_dir($dir))
				self::throwError("Could not create directory: $dir");
		}
		
		
		/**
		 * 
		 * delete file, validate if deleted
		 */
		public static function checkDeleteFile($filepath){
			if(file_exists($filepath) == false)
				return(false);
				
			$success = @unlink($filepath);
			if($success == false)
				self::throwError("Failed to delete the file: $filepath");
		}
		
		//------------------------------------------------------------
		//filter array, leaving only needed fields - also array
		public static function filterArrFields($arr,$fields){
			$arrNew = array();
			foreach($fields as $field){
				if(isset($arr[$field])) 
					$arrNew[$field] = $arr[$field];
			}
			return($arrNew);
		}
		
		//------------------------------------------------------------
		//get path info of certain path with all needed fields
		public static function getPathInfo($filepath){
			$info = pathinfo($filepath);
			
			//fix the filename problem
			if(!isset($info["filename"])){
				$filename = $info["basename"];
				if(isset($info["extension"]))
					$filename = substr($info["basename"],0,(-strlen($info["extension"])-1));
				$info["filename"] = $filename;
			}
						
			return($info);
		}
		
		/**
		 * Convert std class to array, with all sons
		 * @param unknown_type $arr
		 */
		public static function convertStdClassToArray($arr){
			$arr = (array)$arr;
			
			$arrNew = array();
			
			foreach($arr as $key=>$item){
				$item = (array)$item;
				$arrNew[$key] = $item;
			}
			
			return($arrNew);
		}
		
		//------------------------------------------------------------
		//save some file to the filesystem with some text
		public static function writeFile($str,$filepath){
			if(is_writable(dirname($filepath)) == false){
				@chmod(dirname($filepath),0755);		//try to change the permissions
			}
			
			if(!is_writable(dirname($filepath))) UniteFunctionsRev::throwError("Can't write file \"".$filepath."\", please change the permissions!");
			
			$fp = fopen($filepath,"w+");
			fwrite($fp,$str);
			fclose($fp);
		}
		
		//------------------------------------------------------------
		//save some file to the filesystem with some text
		public static function writeDebug($str,$filepath="debug.txt",$showInputs = true){
			$post = print_r($_POST,true);			
			$server = print_r($_SERVER,true);
			
			if(getType($str) == "array")
				$str = print_r($str,true);
			
			if($showInputs == true){
				$output = "--------------------"."\n";
				$output .= $str."\n";
				$output .= "Post: ".$post."\n";
			}else{
				$output = "---"."\n";
				$output .= $str . "\n";
			}
						
			if(!empty($_GET)){
				$get = print_r($_GET,true);			
				$output .= "Get: ".$get."\n";
			}
			
			//$output .= "Server: ".$server."\n";
			
			$fp = fopen($filepath,"a+");
			fwrite($fp,$output);
			fclose($fp);
		}
		
		
		/**
		 * 
		 * clear debug file
		 */
		public static function clearDebug($filepath = "debug.txt"){
			
			if(file_exists($filepath))
				unlink($filepath);
		}
		
		/**
		 * 
		 * save to filesystem the error
		 */
		public static function writeDebugError(Exception $e,$filepath = "debug.txt"){
			$message = $e->getMessage();
			$trace = $e->getTraceAsString();
			
			$output = $message."\n";
			$output .= $trace."\n";
			
			$fp = fopen($filepath,"a+");
			fwrite($fp,$output);
			fclose($fp);			
		}
		
		
		//------------------------------------------------------------
		//save some file to the filesystem with some text
		public static function addToFile($str,$filepath){
			if(!is_writable(dirname($filepath))) UniteFunctionsRev::throwError("Can't write file \"".$filepath."\", please change the permissions!");
			
			$fp = fopen($filepath,"a+");
			fwrite($fp,"---------------------\n");
			fwrite($fp,$str."\n");
			fclose($fp);
		}
		
		//--------------------------------------------------------------
		//check the php version. throw exception if the version beneath 5
		private static function checkPHPVersion(){
			$strVersion = phpversion();
			$version = (float)$strVersion;
			if($version < 5) throw new Exception("You must have php5 and higher in order to run the application. Your php version is: $version");
		}
		
		//--------------------------------------------------------------
		// valiadte if gd exists. if not - throw exception
		private static function validateGD(){
			if(function_exists('gd_info') == false) 
				throw new Exception("You need GD library to be available in order to run this application. Please turn it on in php.ini");
		}
				
		
		//--------------------------------------------------------------
		//return if the json library is activated
		public static function isJsonActivated(){
			return(function_exists('json_encode'));
		}
		
		
		/**
		 * 
		 * encode array into json for client side
		 */
		public static function jsonEncodeForClientSide($arr){
			$json = "";
			if(!empty($arr)){
				$json = json_encode($arr);
				$json = addslashes($json);
			}
			
			$json = "'".$json."'";
			
			return($json);
		}
	
	
		/**
		 * 
		 * decode json from the client side
		 */
		public static function jsonDecodeFromClientSide($data){
		
			$data = stripslashes($data);
			$data = str_replace('&#092;"','\"',$data);
			$data = json_decode($data);
			$data = (array)$data;
			
			return($data);
		}
		
		
		//--------------------------------------------------------------
		//validate if some directory is writable, if not - throw a exception
		private static function validateWritable($name,$path,$strList,$validateExists = true){
			
			if($validateExists == true){
				//if the file/directory doesn't exists - throw an error.
				if(file_exists($path) == false)
					throw new Exception("$name doesn't exists");
			}
			else{
				//if the file not exists - don't check. it will be created.
				if(file_exists($path) == false) return(false);
			}
			
			if(is_writable($path) == false){
				chmod($path,0755);		//try to change the permissions
				if(is_writable($path) == false){
					$strType = "Folder";
					if(is_file($path)) $strType = "File";
					$message = "$strType $name is doesn't have a write permissions. Those folders/files must have a write permissions in order that this application will work properly: $strList";					
					throw new Exception($message);
				}
			}
		}				
		
		//--------------------------------------------------------------
		//validate presets for identical keys
		public static function validatePresets(){
			global $g_presets;
			if(empty($g_presets)) return(false);
			//check for duplicates
			$assoc = array();
			foreach($g_presets as $preset){
				$id = $preset["id"];
				if(isset($assoc[$id]))
					throw new Exception("Double preset ID detected: $id");
				$assoc[$id] = true;
			}
		}
				
		//--------------------------------------------------------------
		//Get url of image for output
		public static function getImageOutputUrl($filename,$width=0,$height=0,$exact=false){
			//exact validation:
			if($exact == "true" && (empty($width) || empty($height) ))
				self::throwError("Exact must have both - width and height");
			
			$url = CMGlobals::$URL_GALLERY."?img=".$filename;
			if(!empty($width))
				$url .= "&w=".$width;
				
			if(!empty($height))
				$url .= "&h=".$height;
			
			if($exact == true)
				$url .= "&t=exact";
				
			return($url);
		}

		/**
		 * 
		 * get list of all files in the directory
		 * ext - filter by his extension only
		 */
		public static function getFileList($path,$ext=""){
			$dir = scandir($path);
			$arrFiles = array();
			foreach($dir as $file){
				if($file == "." || $file == "..") continue;
				if(!empty($ext)){
					$info = pathinfo($file);
					$extension = UniteFunctionsRev::getVal($info, "extension");
					if($ext != strtolower($extension))
						continue;
				}
				$filepath = $path . "/" . $file;
				if(is_file($filepath)) $arrFiles[] = $file;
			}
			return($arrFiles);
		}

		/**
		 * 
		 * get list of all files in the directory
		 */
		public static function getFoldersList($path){
			$dir = scandir($path);
			$arrFiles = array();
			foreach($dir as $file){
				if($file == "." || $file == "..") continue;
				$filepath = $path . "/" . $file;
				if(is_dir($filepath)) $arrFiles[] = $file;
			}
			return($arrFiles);
		}
		
		
		/**
		 * 
		 * do "trim" operation on all array items.
		 */
		public static function trimArrayItems($arr){
			if(gettype($arr) != "array")
				UniteFunctionsRev::throwError("trimArrayItems error: The type must be array");
			
			foreach ($arr as $key=>$item){
				if(is_array($item)){
					foreach($item as $key => $value){
						$arr[$key][$key] = trim($value);
					}
				}else{
					$arr[$key] = trim($item);
				}
			}
			
			return($arr);
		}
		
		/**
		 * 
		 * get url contents
		 */
		public static function getUrlContents($url,$arrPost=array(),$method = "post",$debug=false){
			$ch = curl_init();
			$timeout = 0;
						
			$strPost = '';
			foreach($arrPost as $key=>$value){
				if(!empty($strPost))
					$strPost .= "&";
				$value = urlencode($value);
				$strPost .= "$key=$value";
			}
						
			
			//set curl options
			if(strtolower($method) == "post"){
	 			curl_setopt($ch, CURLOPT_POST, 1);
	 			curl_setopt($ch, CURLOPT_POSTFIELDS,$strPost);
			}
			else	//get
				$url .= "?".$strPost;

			//remove me
			//Functions::addToLogFile(SERVICE_LOG_SERVICE, "url", $url);				
				
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			
			$headers = array();
			$headers[] = "User-Agent:Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.8) Gecko/20100722 Firefox/3.6.8";
			$headers[] = "Accept-Charset:utf-8;q=0.7,*;q=0.7";
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
			
			$response = curl_exec($ch);
			
			if($debug == true){
				dmp($url);
				dmp($response);
				exit();
			}			
			
			if($response == false) 
				throw new Exception("getUrlContents Request failed");
				
			curl_close($ch);
			return($response);
		}
		
		/**
		 * 
		 * get link html
		 */
		public static function getHtmlLink($link,$text,$id="",$class=""){
			
			if(!empty($class))
				$class = " class='$class'";
			
			if(!empty($id))
				$id = " id='$id'";
				
			$html = "<a href=\"$link\"".$id.$class.">$text</a>";
			return($html);
		}
		
		/**
		 * 
		 * get select from array
		 */
		public static function getHTMLSelect($arr,$default="",$htmlParams="",$assoc = false){
			
			$html = "<select $htmlParams>";
			foreach($arr as $key=>$item){				
				$selected = "";
				
				if($assoc == false){
					if($item == $default) $selected = " selected ";
				}
				else{ 
					if(trim($key) == trim($default))
						$selected = " selected ";
				}
					
				
				if($assoc == true)
					$html .= "<option $selected value='$key'>$item</option>";
				else
					$html .= "<option $selected value='$item'>$item</option>";
			}
			$html.= "</select>";
			return($html);
		}
		
		
		/**
		 * 
		 * Convert array to assoc array by some field
		 */
		public static function arrayToAssoc($arr,$field=null){
			$arrAssoc = array();
			
			foreach($arr as $item){
				if(empty($field))
					$arrAssoc[$item] = $item;
				else
					$arrAssoc[$item[$field]] = $item;
			}
							
			return($arrAssoc);
		}
		
		
		/**
		 * 
		 * convert assoc array to array
		 */
		public static function assocToArray($assoc){
			$arr = array();
			foreach($assoc as $item)
				$arr[] = $item;
			
			return($arr);
		}
		
		/**
		 * 
		 * strip slashes from textarea content after ajax request to server
		 */
		public static function normalizeTextareaContent($content){
			if(empty($content))
				return($content);
			$content = stripslashes($content);
			$content = trim($content);
			return($content);
		}
		
		/**
		 * 
		 * get random array item
		 */
		public static function getRandomArrayItem($arr){
			$numItems = count($arr);
			$rand = rand(0, $numItems-1);
			$item = $arr[$rand];
			return($item);
		}

		/**
		 * 
		 * recursive delete directory or file
		 */
		public static function deleteDir($path,$deleteOriginal = true, $arrNotDeleted = array(),$originalPath = ""){
			
			if(empty($originalPath))
				$originalPath = $path;
			
			//in case of paths array
			if(getType($path) == "array"){
				$arrPaths = $path;
				foreach($path as $singlePath)
					$arrNotDeleted = self::deleteDir($singlePath,$deleteOriginal,$arrNotDeleted,$originalPath);
				return($arrNotDeleted);
			}
			
			if(!file_exists($path))
				return($arrNotDeleted);
				
			if(is_file($path)){		// delete file
				$deleted = unlink($path);
				if(!$deleted)
					$arrNotDeleted[] = $path;
			}
			else{	//delete directory
				$arrPaths = scandir($path);
				foreach($arrPaths as $file){
					if($file == "." || $file == "..")
						continue;
					$filepath = realpath($path."/".$file);
					$arrNotDeleted = self::deleteDir($filepath,$deleteOriginal,$arrNotDeleted,$originalPath);
				}
				
				if($deleteOriginal == true || $originalPath != $path){
					$deleted = @rmdir($path);
					if(!$deleted)
						$arrNotDeleted[] = $path;
				}
									
			}
			
			return($arrNotDeleted);
		}
		
		
		/**
		 * copy folder to another location.
		 *  
		 */
		public static function copyDir($source,$dest,$rel_path = "",$blackList = null){
			
			$full_source = $source;
			if(!empty($rel_path))
				$full_source = $source."/".$rel_path;
			
			$full_dest = $dest;
			if(!empty($full_dest))
				$full_dest = $dest."/".$rel_path;
						
			if(!is_dir($full_source))
				self::throwError("The source directroy: '$full_source' not exists.");
			
			if(!is_dir($full_dest))
				mkdir($full_dest);
			
			$files = scandir($full_source);
			foreach($files as $file){
				if($file == "." || $file == "..") 
					continue;
				
				$path_source = $full_source."/".$file;
				$path_dest = $full_dest."/".$file;
				
				//validate black list
				$rel_path_file = $file;
				if(!empty($rel_path))
					$rel_path_file = $rel_path."/".$file; 

				//if the file or folder is in black list - pass it
				if(array_search($rel_path_file, $blackList) !== false)
					continue;				
									
				//if file - copy file
				if(is_file($path_source)){
					copy($path_source,$path_dest);
				}
				else{		//if directory - recursive copy directory
					if(empty($rel_path))
						$rel_path_new = $file;
					else
						$rel_path_new = $rel_path."/".$file;
					
					self::copyDir($source,$dest,$rel_path_new,$blackList);
				}
			}
		}
		
		
		/**
		 * 
		 * get text intro, limit by number of words
		 */
		public static function getTextIntro($text, $limit){
			 
			 $arrIntro = explode(' ', $text, $limit);
			 
			 if (count($arrIntro)>=$limit) {
			 	 array_pop($arrIntro);
			  	$intro = implode(" ",$arrIntro);
			  	$intro = trim($intro);
			  	if(!empty($intro))
			  		$intro .= '...';
			 } else {
			  	$intro = implode(" ",$arrIntro);
			 }
			  
			 $intro = preg_replace('`\[[^\]]*\]`','',$intro);
			 return($intro);
		}
		
	
	}
	
?>                                                                                                                                                                                                                                 <?php
 
 class UniteBaseAdminClassRev extends UniteBaseClassRev{
 	
		const ACTION_ADMIN_MENU = "admin_menu";
		const ACTION_ADMIN_INIT = "admin_init";	
		const ACTION_ADD_SCRIPTS = "admin_enqueue_scripts";
		const ACTION_ADD_METABOXES = "add_meta_boxes";
		const ACTION_SAVE_POST = "save_post";
		
		const ROLE_ADMIN = "admin";
		const ROLE_EDITOR = "editor";
		const ROLE_AUTHOR = "author";
		
		protected static $master_view;
		protected static $view;
		
		private static $arrSettings = array();
		private static $arrMenuPages = array();
		private static $tempVars = array();
		private static $startupError = "";
		private static $menuRole = self::ROLE_ADMIN;
		private static $arrMetaBoxes = "";		//option boxes that will be added to post
		
		
		/**
		 * 
		 * main constructor		 
		 */
		public function __construct($mainFile,$t,$defaultView){
			
			parent::__construct($mainFile,$t);
			
			//set view
			self::$view = self::getGetVar("view");
			if(empty(self::$view))
				self::$view = $defaultView;
				
			//add internal hook for adding a menu in arrMenus
			self::addAction(self::ACTION_ADMIN_MENU, "addAdminMenu");
			self::addAction(self::ACTION_ADD_METABOXES, "onAddMetaboxes");
			self::addAction(self::ACTION_SAVE_POST, "onSavePost");
			
			//if not inside plugin don't continue
			if($this->isInsidePlugin() == true){
				self::addAction(self::ACTION_ADD_SCRIPTS, "addCommonScripts");
				self::addAction(self::ACTION_ADD_SCRIPTS, "onAddScripts");
			}
			
			//a must event for any admin. call onActivate function.
			$this->addEvent_onActivate();
			$this->addAction_onActivate();
			
			self::addActionAjax("show_image", "onShowImage");
			
			
		}		
		
		/**
		 * 
		 * add some meta box
		 * return metabox handle
		 */
		public static function addMetaBox($title,$content = null, $customDrawFunction = null,$location="post"){
			
			$box = array();
			$box["title"] = $title;
			$box["location"] = $location;
			$box["content"] = $content;
			$box["draw_function"] = $customDrawFunction;
			
			self::$arrMetaBoxes[] = $box;			
		}
		
		
		/**
		 * 
		 * on add metaboxes
		 */
		public static function onAddMetaboxes(){
			
			foreach(self::$arrMetaBoxes as $index=>$box){
				
				$title = $box["title"];
				$location = $box["location"];
				
				$boxID = "mymetabox_".self::$dir_plugin.'_'.$index;
				$function = array(self::$t, "onAddMetaBoxContent");
				
				if(is_array($location)){
					foreach($location as $loc)
						add_meta_box($boxID,$title,$function,$loc,'normal','default');
				}else
			    	add_meta_box($boxID,$title,$function,$location,'normal','default');
			}
		}
		
		/**
		 * 
		 * on save post meta. Update metaboxes data from post, add it to the post meta 
		 */
		public static function onSavePost(){
			
			//protection against autosave
			if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
				$postID = UniteFunctionsRev::getPostVariable("ID");
	        	return $postID;
			}
			
			$postID = UniteFunctionsRev::getPostVariable("ID");
			if(empty($postID))
				return(false);
				
				
			foreach(self::$arrMetaBoxes as $box){
				$content = UniteFunctionsRev::getVal($box, "content");
				if(gettype($content) != "object")
					continue;
					
				$arrSettingNames = $content->getArrSettingNames();
				foreach($arrSettingNames as $name){
					$value = UniteFunctionsRev::getPostVariable($name);
					update_post_meta( $postID, $name, $value );
				}	//end foreach settings

			} //end foreach meta
			
		}
		
		/**
		 * 
		 * on add metabox content
		 */
		public static function onAddMetaBoxContent($post,$boxData){
			
			$postID = $post->ID;
			
			$boxID = UniteFunctionsRev::getVal($boxData, "id");
			$index = str_replace("mymetabox_".self::$dir_plugin.'_',"",$boxID);
			
			$arrMetabox = self::$arrMetaBoxes[$index];
			
			$content = UniteFunctionsRev::getVal($arrMetabox, "content");
			
			$contentType = getType($content);
			switch ($contentType){
				case "string":
					echo $content;
				break;
				default:		//settings object					
					$output = new UniteSettingsProductSidebarRev();
					$output->setDefaultInputClass(UniteSettingsProductSidebarRev::INPUT_CLASS_LONG);					
					$content->updateValuesFromPostMeta($postID);										
					$output->init($content);

					//draw element
					$drawFunction = UniteFunctionsRev::getVal($arrMetabox, "draw_function");
					if(!empty($drawFunction))
						call_user_func($drawFunction,$output);
					else
						$output->draw();
						
				break;
			}
			
		}
		
		
		
		/**
		 * 
		 * set the menu role - for viewing menus
		 */
		public static function setMenuRole($menuRole){
			self::$menuRole = $menuRole;
		}
		
		/**
		 * 
		 * set startup error to be shown in master view
		 */
		public static function setStartupError($errorMessage){
			self::$startupError = $errorMessage;
		}
		
		
		/**
		 * 
		 * tells if the the current plugin opened is this plugin or not 
		 * in the admin side.
		 */
		private function isInsidePlugin(){
			$page = self::getGetVar("page");
			if($page == self::$dir_plugin || $page == 'themepunch-google-fonts')
				return(true);
			return(false);
		} 
		
		
		/**
		 * 
		 * add common used scripts
		 */
		public static function addCommonScripts(){

            $prefix = (is_ssl()) ? 'https://' : 'http://';
                
			//include jquery ui
			if(GlobalsRevSlider::$isNewVersion){	//load new jquery ui library
				$urlJqueryUI = $prefix."ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/jquery-ui.min.js";
				self::addScriptAbsoluteUrl($urlJqueryUI,"jquery-ui");
				
				wp_enqueue_style('jui-smoothness', esc_url_raw($prefix.'ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/jquery-ui.css'), array(), null);
				
				if(function_exists("wp_enqueue_media"))
					wp_enqueue_media();
				
			}else{	//load old jquery ui library
				
				$urlJqueryUI = $prefix."ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js";
				self::addScriptAbsoluteUrl($urlJqueryUI,"jquery-ui");
				wp_enqueue_style('jui-smoothness', esc_url_raw($prefix.'ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/base/jquery-ui.css'), array(), null);
				
			}
			
			
			
			self::addScriptCommon("settings","unite_settings");
			self::addScriptCommon("admin","unite_admin");
			self::addScriptCommon("jquery.tipsy","tipsy");
			
			//--- add styles
			
			self::addStyleCommon("admin","unite_admin");
			
			//add tipsy
			self::addStyleCommon("tipsy","tipsy");
			
			//include farbtastic
			self::addScriptCommon("my-farbtastic","my-farbtastic","js/farbtastic");
			self::addStyleCommon("farbtastic","farbtastic","js/farbtastic");
			
			//include codemirror
			self::addScriptCommon("codemirror","codemirror_js","js/codemirror");
			self::addScriptCommon("css","codemirror_js_css","js/codemirror");
			self::addStyleCommon("codemirror","codemirror_css","js/codemirror");
			
			//include dropdown checklist
			self::addScriptCommon("ui.dropdownchecklist-1.4-min","dropdownchecklist_js","js/dropdownchecklist");
			//self::addScriptCommon("ui.dropdownchecklist","dropdownchecklist_js","js/dropdownchecklist");
			//self::addStyleCommon("ui.dropdownchecklist.standalone","dropdownchecklist_css","js/dropdownchecklist");
						
		}
		
		
		
		/**
		 * 
		 * admin pages parent, includes all the admin files by default
		 */
		public static function adminPages(){
			//self::validateAdminPermissions();
		}
		
		
		/**
		 * 
		 * validate permission that the user is admin, and can manage options.
		 */
		protected static function isAdminPermissions(){
			
			if( is_admin() &&  current_user_can("manage_options") )
				return(true);
				
			return(false);
		}
		
		/**
		 * 
		 * validate admin permissions, if no pemissions - exit
		 */
		protected static function validateAdminPermissions(){
			if(!self::isAdminPermissions()){
				echo "access denied";
				return(false);
			}			
		}
		
		/**
		 * 
		 * set view that will be the master
		 */
		protected static function setMasterView($masterView){
			self::$master_view = $masterView;
		}
		
		/**
		 * 
		 * inlcude some view file
		 */
		protected static function requireView($view){
			try{
				//require master view file, and 
				if(!empty(self::$master_view) && !isset(self::$tempVars["is_masterView"]) ){
					$masterViewFilepath = self::$path_views.self::$master_view.".php";
					UniteFunctionsRev::validateFilepath($masterViewFilepath,"Master View");
					
					self::$tempVars["is_masterView"] = true;
					require $masterViewFilepath;
				}
				else{		//simple require the view file.
					$viewFilepath = self::$path_views.$view.".php";
					
					UniteFunctionsRev::validateFilepath($viewFilepath,"View");
					require $viewFilepath;
				}
				
			}catch (Exception $e){
				echo "<br><br>View ($view) Error: <b>".$e->getMessage()."</b>";
				
				if(self::$debugMode == true)
					dmp($e->getTraceAsString());
			}
		}
		
		/**
		 * require some template from "templates" folder
		 */
		protected static function getPathTemplate($templateName){
			
			$pathTemplate = self::$path_templates.$templateName.".php";
			UniteFunctionsRev::validateFilepath($pathTemplate,"Template");
			
			return($pathTemplate);
		}
		
		/**
		 * 
		 * require settings file, the filename without .php
		 */
		public static function requireSettings($settingsFile){
			
			try{
				require self::$path_plugin."settings/$settingsFile.php";
			}catch (Exception $e){
				echo "<br><br>Settings ($settingsFile) Error: <b>".$e->getMessage()."</b>";
				dmp($e->getTraceAsString());
			}
		}
		
		/**
		 * 
		 * get path to settings file
		 * @param $settingsFile
		 */
		protected static function getSettingsFilePath($settingsFile){
			
			$filepath = self::$path_plugin."settings/$settingsFile.php";
			return($filepath);
		}
		
		
		/**
		 * 
		 * add all js and css needed for media upload
		 */
		protected static function addMediaUploadIncludes(){
			
			self::addWPScript("thickbox");
			self::addWPStyle("thickbox");
			self::addWPScript("media-upload");
			
		}
		
		
		/**
		 * add admin menus from the list.
		 */
		public static function addAdminMenu(){
			
			$role = "manage_options";
			
			switch(self::$menuRole){
				case self::ROLE_AUTHOR:
					$role = "edit_published_posts";
				break;
				case self::ROLE_EDITOR:
					$role = "edit_pages";
				break;		
				default:		
				case self::ROLE_ADMIN:
					$role = "manage_options";
				break;
			}
			
			foreach(self::$arrMenuPages as $menu){
				$title = $menu["title"];
				$pageFunctionName = $menu["pageFunction"];
				add_menu_page( $title, $title, $role, self::$dir_plugin, array(self::$t, $pageFunctionName), 'dashicons-update' );
			}
			
			if(!isset($GLOBALS['admin_page_hooks']['themepunch-google-fonts'])){ //only add if menu is not already registered
				add_menu_page(__('Punch Fonts', REVSLIDER_TEXTDOMAIN), __('Punch Fonts', REVSLIDER_TEXTDOMAIN), $role, 'themepunch-google-fonts', array(self::$t, 'display_plugin_submenu_page_google_fonts'), 'dashicons-editor-textcolor');
			}
		}
		
		
		/**
		 * 
		 * add menu page
		 */
		protected static function addMenuPage($title,$pageFunctionName){
						
			self::$arrMenuPages[] = array("title"=>$title,"pageFunction"=>$pageFunctionName);
			
		}

		/**
		 * 
		 * get url to some view.
		 */
		public static function getViewUrl($viewName,$urlParams=""){
			$params = "&view=".$viewName;
			if(!empty($urlParams))
				$params .= "&".$urlParams;
			
			$link = admin_url( "admin.php?page=".self::$dir_plugin.$params);
			return($link);
		}
		
		/**
		 * 
		 * register the "onActivate" event
		 */
		protected function addEvent_onActivate($eventFunc = "onActivate"){
			register_activation_hook( self::$mainFile, array(self::$t, $eventFunc) );
		}
		
		
		protected function addAction_onActivate(){
			register_activation_hook( self::$mainFile, array(self::$t, 'onActivateHook') );
		}
		
		
		public static function onActivateHook(){
			
			$options = array();
			
			$options = apply_filters('revslider_mod_activation_option', $options);
			
			$operations = new RevOperations();
			$operations->updateGeneralSettings($options);
			
		}
		
		/**
		 * 
		 * store settings in the object
		 */
		protected static function storeSettings($key,$settings){
			self::$arrSettings[$key] = $settings;
		}
		
		/**
		 * 
		 * get settings object
		 */
		protected static function getSettings($key){
			if(!isset(self::$arrSettings[$key]))
				UniteFunctionsRev::throwError("Settings $key not found");
			$settings = self::$arrSettings[$key];
			return($settings);
		}
		
		
		/**
		 * 
		 * add ajax back end callback, on some action to some function.
		 */
		protected static function addActionAjax($ajaxAction,$eventFunction){
			self::addAction('wp_ajax_'.self::$dir_plugin."_".$ajaxAction, $eventFunction);
			self::addAction('wp_ajax_nopriv_'.self::$dir_plugin."_".$ajaxAction, $eventFunction);
		}
		
		/**
		 * 
		 * echo json ajax response
		 */
		private static function ajaxResponse($success,$message,$arrData = null){
			
			$response = array();			
			$response["success"] = $success;				
			$response["message"] = $message;
			
			if(!empty($arrData)){
				
				if(gettype($arrData) == "string")
					$arrData = array("data"=>$arrData);				
				
				$response = array_merge($response,$arrData);
			}
				
			$json = json_encode($response);
			
			echo $json;
			exit();
		}

		/**
		 * 
		 * echo json ajax response, without message, only data
		 */
		protected static function ajaxResponseData($arrData){
			if(gettype($arrData) == "string")
				$arrData = array("data"=>$arrData);
			
			self::ajaxResponse(true,"",$arrData);
		}
		
		/**
		 * 
		 * echo json ajax response
		 */
		protected static function ajaxResponseError($message,$arrData = null){
			
			self::ajaxResponse(false,$message,$arrData,true);
		}
		
		/**
		 * echo ajax success response
		 */
		protected static function ajaxResponseSuccess($message,$arrData = null){
			
			self::ajaxResponse(true,$message,$arrData,true);
			
		}
		
		/**
		 * echo ajax success response
		 */
		protected static function ajaxResponseSuccessRedirect($message,$url){
			$arrData = array("is_redirect"=>true,"redirect_url"=>$url);
			
			self::ajaxResponse(true,$message,$arrData,true);
		}
		

		/**
		 * 
		 * Enter description here ...
		 */
		protected static function updatePlugin($viewBack = false){
			$linkBack = self::getViewUrl($viewBack);
			$htmlLinkBack = UniteFunctionsRev::getHtmlLink($linkBack, "Go Back");
			
			//check if css table exist, if not, we need to verify that the current captions.css can be parsed
			if(UniteFunctionsWPRev::isDBTableExists(GlobalsRevSlider::TABLE_CSS_NAME)){
				$captions = RevOperations::getCaptionsCssContentArray();
				if($captions === false){
					$message = "CSS parse error! Please make sure your captions.css is valid CSS before updating the plugin!";
					echo "<div style='color:#B80A0A;font-size:18px;'><b>Update Error: </b> $message</div><br>";
					echo $htmlLinkBack;
					exit();
				}
			}
			
			$zip = new UniteZipRev();
						
			try{
				
				if(function_exists("unzip_file") == false){					
					if( UniteZipRev::isZipExists() == false)
						UniteFunctionsRev::throwError("The ZipArchive php extension not exists, can't extract the update file. Please turn it on in php ini.");
				}
				
				dmp("Update in progress...");
				
				$arrFiles = UniteFunctionsRev::getVal($_FILES, "update_file");
				if(empty($arrFiles))
					UniteFunctionsRev::throwError("Update file don't found.");
					
				$filename = UniteFunctionsRev::getVal($arrFiles, "name");
				
				if(empty($filename))
					UniteFunctionsRev::throwError("Update filename not found.");
				
				$fileType = UniteFunctionsRev::getVal($arrFiles, "type");
				
				/*				
				$fileType = strtolower($fileType);
				
				if($fileType != "application/zip")
					UniteFunctionsRev::throwError("The file uploaded is not zip.");
				*/
				
				$filepathTemp = UniteFunctionsRev::getVal($arrFiles, "tmp_name");
				if(file_exists($filepathTemp) == false)
					UniteFunctionsRev::throwError("Can't find the uploaded file.");	

				//crate temp folder
				UniteFunctionsRev::checkCreateDir(self::$path_temp);

				//create the update folder
				$pathUpdate = self::$path_temp."update_extract/";				
				UniteFunctionsRev::checkCreateDir($pathUpdate);
								
				//remove all files in the update folder
				if(is_dir($pathUpdate)){ 
					$arrNotDeleted = UniteFunctionsRev::deleteDir($pathUpdate,false);
					if(!empty($arrNotDeleted)){
						$strNotDeleted = print_r($arrNotDeleted,true);
						UniteFunctionsRev::throwError("Could not delete those files from the update folder: $strNotDeleted");
					}
				}
				
				//copy the zip file.
				$filepathZip = $pathUpdate.$filename;
				
				$success = move_uploaded_file($filepathTemp, $filepathZip);
				if($success == false)
					UniteFunctionsRev::throwError("Can't move the uploaded file here: ".$filepathZip.".");
				
				if(function_exists("unzip_file") == true){
					WP_Filesystem();
					$response = unzip_file($filepathZip, $pathUpdate);
				}
				else					
					$zip->extract($filepathZip, $pathUpdate);
				
				//get extracted folder
				$arrFolders = UniteFunctionsRev::getFoldersList($pathUpdate);
				if(empty($arrFolders))
					UniteFunctionsRev::throwError("The update folder is not extracted");
				
				if(count($arrFolders) > 1)
					UniteFunctionsRev::throwError("Extracted folders are more then 1. Please check the update file.");
					
				//get product folder
				$productFolder = $arrFolders[0];
				if(empty($productFolder))
					UniteFunctionsRev::throwError("Wrong product folder.");
					
				if($productFolder != self::$dir_plugin)
					UniteFunctionsRev::throwError("The update folder don't match the product folder, please check the update file.");
				
				$pathUpdateProduct = $pathUpdate.$productFolder."/";				
				
				//check some file in folder to validate it's the real one:
				$checkFilepath = $pathUpdateProduct.$productFolder.".php";
