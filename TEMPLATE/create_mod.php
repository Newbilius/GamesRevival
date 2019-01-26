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

$branchName = "new_".time();
$api = new GitHubApi($username, $password, "Newbilius", "ExperimentRepo3");

/*
$result = array();

$result['createBranch'] = $api -> CreateBranch($branchName);
$result['file1'] = $api -> CreateFile($branchName, "Duke3D/Folder/file1.md", "**just text string** - просто тестовая строка :)))))");
$result['file2'] = $api -> CreateFile($branchName, "Duke3D/Folder/file2.md", "*курсивчик*");
$result['pullRequest'] = $api -> CreatePullRequest($branchName, "создал два файла");

prettyPrint($result);*/

preparePosts(array("gameTitle", "gameLinks",
	"modTitle", "modAbout", "modLinks", "modNewOS", "modNewTags", "modVideo"
	));
	
if (!isset($_POST['modOS']))
	$_POST['modOS']= array();
if (!isset($_POST['modTags']))
	$_POST['modTags']= array();

$errors = array();
$success = false;
	
if (!isset($_REQUEST['GameId']))
	$_REQUEST['GameId'] = "NULL";
	
if (!empty($_POST['formPosted'])){
	if ($_POST['gameSelector']==="NULL" && empty($_POST['gameTitle'])){
		$errors[]="Не указано название игры";
	}
	else{
		$_REQUEST['GameId'] = $_POST['gameSelector'];
	}
	
	if (empty($_POST['modTitle']))
		$errors[]="Не указано название модификации";
	
	if (empty($_POST['modAbout']))
		$errors[]="Отсутствует описание модификации";
	
	if (empty($_POST['modTags']) && empty($_POST['modNewTags']))
		$errors[]="Нужно указать хотя бы один тег с типом модификации (sourceport/mod/remaster и т.п.)";
	
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
		
		
		$os = implode(PHP_EOL, array_merge($newOSArray,$_POST['modOS']));
		$tags = implode(PHP_EOL, array_merge($newTagsArray,$_POST['modTags']));
		prettyPrint($os);
		prettyPrint($tags);
		
		$modLinks = array_filter(explode(PHP_EOL,$_POST['modLinks']));
		prettyPrint($modLinks);
		
		//$success = true;
	}
}
	
prettyPrint($_POST);
echo "<HR>";
prettyPrint($_FILES);
echo "<HR>";

/*
gameFileLogo

modFilePics1
modFilePics2
modFilePics3
modFilePics4
*/
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