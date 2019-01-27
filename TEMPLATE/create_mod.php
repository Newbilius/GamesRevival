<?
function prettyPrint($value){
	echo "<pre>";
	print_r($value);
	echo "</pre>";
}

function preparePosts($values){
	foreach ($values as $value)
		if (!isset($_POST[$value]))
			$_POST[$value] = "";
}

function splitTextareaToArray($inputString){
	$values = array_filter(preg_split ('/<br[^>]*>/i',nl2br($inputString)));
	foreach($values as $key => $value)
		if(strlen($value)<5)
			unset($values[$key]);
		else{
			$values[$key] = str_replace(array("\\n", "\\r"), '', $values[$key]); //двойные слеши не баг, а фича, специфика косяка где-то в движке генерации страницы
		}
	return array_values($values);
}

function splitArrayToLinksList($values){
	$gameLinkMergedArray = array();
	for ($i=0;$i<count($values);$i+=2){
		$linkName="";
		$linkValue="";
		
		if ($i < count($values))
			$linkName = $values[$i];
		
		if (($i+1) < count($values))
			$linkValue = $values[($i+1)];
		
		$linkUrlLength = strlen($linkValue);
		$linkNameLength = strlen($linkName);
		
		if (($linkUrlLength>5) && ($linkNameLength>5)){
			$gameLinkMergedArray[$linkName] = $linkValue;
		}
	}
	return $gameLinkMergedArray;
}

function getFileDataOrNull($fileVarName){
	if ($_FILES[$fileVarName]['error'] == UPLOAD_ERR_OK){
	if ($_FILES[$fileVarName]['size']<1024*1024*1.5){ //не больше 1.5 МБ
		$check = getimagesize($_FILES[$fileVarName]["tmp_name"]);
		if($check !== false) {
				$fileData = file_get_contents($_FILES[$fileVarName]["tmp_name"]);
				return $fileData;
			}
		}
	}
	return null;
}

function translit($text){
	$cyr = [
		'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п',
		'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',
		'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П',
		'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'
	];
	$lat = [
		'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p',
		'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya',
		'A','B','V','G','D','E','Io','Zh','Z','I','Y','K','L','M','N','O','P',
		'R','S','T','U','F','H','Ts','Ch','Sh','Sht','A','I','Y','e','Yu','Ya'
	];
	return str_replace($cyr, $lat, $text);
}

function getFolderNameFromString($text){
	return preg_replace('/[^A-Za-z0-9-]/', '_', translit($text));
}

//должен содержать две переменные - $username и $password
include("github_api_config.php");

class GitHubApiHelper {
	private $username;
	private $password;
	private $endpoint = "https://api.github.com/";
	
	function __construct($username, $password){
       $this -> username = $username;
	   $this -> password = $password;
	}
	
	public function get($url){
		$curl = $this->prepareCurl($url);
		curl_setopt($curl, CURLOPT_HTTPGET, true);
		
		$result = $this->makeRequest($curl);
		return $result;
	}
	
	public function put($url, $data){
		$curl = $this->prepareCurl($url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
		
		$result = $this->makeRequest($curl);
		return $result;
	}
	
	public function post($url, $data){
		$curl = $this->prepareCurl($url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
		
		$result = $this->makeRequest($curl);
		return $result;
	}
	
	private function prepareCurl($url){
		$process = curl_init($this->endpoint.$url);
		curl_setopt($process, CURLOPT_USERPWD, $this->username . ":" . $this->password);
		curl_setopt($process, CURLOPT_USERAGENT, "GamesRevival_Server");
		curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
	
		return $process;
	}
	
	function makeRequest($process){
		$result = curl_exec($process);
		curl_close($process);
	
		$json = json_decode ($result);
		return $json;
	}
}

class GitHubApi{
	private $API;
	private $RepositoryName;
	private $RepositoryUser;
	
	function __construct($username, $password, $repositoryUser, $repositoryName){   
	   $this->API = new GitHubApiHelper($username, $password);
	   
	   $this->RepositoryName = $repositoryName;
	   $this->RepositoryUser = $repositoryUser;
	}
	
	private function GetRepoUrl($url){
		return "repos/{$this->RepositoryUser}/{$this->RepositoryName}/{$url}";
	}
	
	public function CreateBranch($branchName){
		//get last commit hash
		$result = $this -> API -> get($this -> GetRepoUrl("commits/master"));
		$sha = $result -> sha;

		//create branch
		$createBranchData = array(
			'ref' => 'refs/heads/'.$branchName,
			'sha' => $sha
		);

		$result = $this -> API -> post($this -> GetRepoUrl("git/refs"), $createBranchData);
		return $result;
	}
	
	public function CreateFile($branchName, $file, $content) {
		$newFileData = array(
			'message' => "create file {$file}",
			'content' => base64_encode($content),
			'branch' => $branchName
		);

		$result = $this -> API -> put($this -> GetRepoUrl("contents/{$file}"), $newFileData);
		return $result;
	}
	
	public function CreatePullRequest($branchName, $title) {
		$pullRequestData = array(
			'title' => $title,
			'head' => $branchName,
			'base' => 'master'
		);
		$result = $this -> API ->post($this -> GetRepoUrl("pulls"), $pullRequestData);
		return $result;
	}
}

$api = new GitHubApi($username, $password, "Newbilius", "GamesRevival");

function prepareAllPosts(){
	preparePosts(array("gameTitle", "gameLinks",
		"modTitle", "modAbout", "modLinks", "modNewOS", "modNewTags", "modVideo"
	));
}

prepareAllPosts();
	
if (!isset($_POST['modOS']))
	$_POST['modOS']= array();
if (!isset($_POST['modTags']))
	$_POST['modTags']= array();

$errors = array();
$success = false;
	
if (!isset($_REQUEST['GameId']))
	$_REQUEST['GameId'] = "NULL";
	
if (!empty($_POST['formPosted'])){
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, [
		'secret' => "6LftAI0UAAAAADqQnO2gv0VA06wywDxchyjJ7FhL",
		'response' => $_POST['g-recaptcha-response']
	]);

	$resp = json_decode(curl_exec($ch));
	curl_close($ch);

	if (!$resp->success) {
		$errors[]="Подтвердите капчу!";
	}
	
	if ($_POST['gameSelector']==="NULL" && empty($_POST['gameTitle'])){
		$errors[]="Не указано название игры";
	}
	else{
		$_REQUEST['GameId'] = $_POST['gameSelector'];
	}
	
	if (empty($_POST['modTitle']))
		$errors[] = "Не указано название модификации";
	
	if (empty($_POST['modAbout']))
		$errors[] = "Отсутствует описание модификации";
	
	if (empty($_POST['modTags']) && empty($_POST['modNewTags']))
		$errors[] = "Нужно указать хотя бы один тег, например - с типом модификации (sourceport/mod/remaster и т.п.)";
	
	if (empty($errors)){
		$modLinkValues = splitArrayToLinksList(splitTextareaToArray($_POST['modLinks']));
		
		if (count($modLinkValues)<1)
		{
			$errors[] = "Обязательно нужно указать ссылки на сайт модификации";
		}
	}
	
	if (empty($errors)){
		$newTagsArray = array();
		$newOSArray = array();
		
		if (!empty($_POST['modNewTags']))
		{
			$newTagsTemp = explode(",",$_POST['modNewTags']);
			foreach ($newTagsTemp as $tagNumber => $tmpTag){
				$newTagsTemp[$tagNumber] = trim($tmpTag);
			}
			$newTagsArray = array_filter($newTagsTemp);
		}
		if (!empty($_POST['modNewOS']))
		{
			$newOSTemp = explode(",",$_POST['modNewOS']);
			foreach ($newOSTemp as $osNumber => $tmpOS){
				$tmpOS[$osNumber] = trim($tmpOS);
			}
			$newOSArray = array_filter($newOSTemp);
		}
		
		$os = implode(PHP_EOL, array_merge($newOSArray, $_POST['modOS']));
		$tags = implode(PHP_EOL, array_merge($newTagsArray, $_POST['modTags']));
		
		$gameLinkValues = splitArrayToLinksList(splitTextareaToArray($_POST['gameLinks']));
		
		//--------------------
		$br = "\\r\\n"; //двойные слеши не баг, а фича, специфика косяка где-то в движке генерации страницы
		$branchName = "new_".time();
		$api -> createBranch($branchName);
		
		$gameFolder = $_POST['gameSelector'];
		
		if ($_POST['gameSelector']==="NULL"){
			//создаём файл с названием игры
			$gameFolder = getFolderNameFromString($_POST['gameTitle']);
			$api -> CreateFile($branchName, "DATA/{$gameFolder}/title.txt", $_POST['gameTitle']);
			
			//создаём файл ссылок
			$links = "";
			foreach ($gameLinkValues as $linkName => $linkValue){
				$links = $links."[{$linkName}]({$linkValue})".$br.$br;
			}
			if (strlen($links)>15)
				$api -> CreateFile($branchName, "DATA/{$gameFolder}/links.md", $links);	
			
			//загружаем логотип
			$gameLogo = getFileDataOrNull("gameFileLogo");
			if ($gameLogo != null){
				$api -> CreateFile($branchName, "DATA/{$gameFolder}/logo.jpg", $gameLogo);
			}
		}
		
		$modFolder = getFolderNameFromString($_POST['modTitle']);
		$baseModUrl = "DATA/{$gameFolder}/{$modFolder}/";
		
		//название
		$api -> CreateFile($branchName, $baseModUrl."title.txt", $_POST['modTitle']);
		
		//описание
		$api -> CreateFile($branchName, $baseModUrl."about.md", $_POST['modAbout']);
		
		//ссылки
		if (count($modLinkValues) < 2){
			$api -> CreateFile($branchName, $baseModUrl."link.txt", reset($modLinkValues));
		}else{
			$modLinks = "";
			foreach ($modLinkValues as $linkName => $linkValue){
				$modLinks = $modLinks."[{$linkName}]({$linkValue})".$br.$br;
			}
			$api -> CreateFile($branchName, $baseModUrl."links.md", $modLinks);
		}
		
		$osText = "";
		
		//операционки
		if (strlen($os)>2)
			$api -> CreateFile($branchName, $baseModUrl."os.txt", $os);
		
		//теги
		if (strlen($tags)>2)
			$api -> CreateFile($branchName, $baseModUrl."tags.txt", $tags);
		
		//видео
		if (strlen($_POST['modVideo'])>20)
			$api -> CreateFile($branchName, $baseModUrl."video.txt", $_POST['modVideo']);
		
		//скриншоты
		$modImagesNames = array("modFilePics1", "modFilePics2", "modFilePics3", "modFilePics4");

		foreach ($modImagesNames as $modImageName){
			$imageData = getFileDataOrNull($modImageName);
			if ($imageData!=null){
				$api -> CreateFile($branchName, $baseModUrl.$_FILES[$modImageName]['name'], $imageData);
			}
		}
		
		$api -> CreatePullRequest($branchName, "Добавил мод {$_POST['modTitle']} для {$gameFolder}");
		
		//complete
		$success = true;
		$_POST = array();
		prepareAllPosts();
	}
}
?>

<? if (!empty($errors)){?>
<div class="alert alert-danger" role="alert">
  <?
	foreach ($errors as $error)
	{
		echo "{$error}<br>";
	}
  ?>
</div>
<?}?>

<? if ($success){?>
<div class="alert alert-success" role="alert">
   Огромное спасибо! Новые данные отправлены на премодерацию :)
</div>
<?}?>