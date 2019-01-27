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

$api = new GitHubApi($username, $password, "Newbilius", "GamesRevival");

$errors = array();
$success = false;	

if (!isset($_REQUEST["ModName"]) || 
	!isset($_REQUEST["GameId"]) || 
	!isset($_REQUEST["ModeId"]))
{
	$errors[] = "Что-то пошло фатально не так, лучше вернуться на ту страницу с который вы начали и попробовать ещё раз";
}
	
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
		$errors[]="Подтвердите капчу";
	}
	
	//prettyPrint($_FILES);
	
	if (empty($errors)){
		$branchName = "new_".time();
		$api -> createBranch($branchName);
		
		$gameFolder = $_REQUEST['GameId'];
		$modFolder = $_REQUEST['ModeId'];
		
		$baseModUrl = "DATA/{$gameFolder}/{$modFolder}/";
		
		$files = $_FILES['modFiles'];
		$filesCount = count($files['name']);
		$savedFiles = 0;
		
		for( $i=0 ; $i < $filesCount ; $i++ ) {
			if ($files['error'][$i] != UPLOAD_ERR_OK)
				$errors[] = "Ошибка загрузки файла ".$files['name'][$i];
			else if ($files['size'][$i]>1024*1024*1.5)
				$errors[] = "Файл ".$files['name'][$i]." больше 1.5 МБ";
			else {
				$check = getimagesize($files["tmp_name"][$i]);
				if($check !== false) {
					$savedFiles++;
					$imageData = file_get_contents($files["tmp_name"][$i]);
					$api -> CreateFile($branchName, $baseModUrl.$files["name"][$i], $imageData);
				}
				else
					$errors[] = "Файл ".$files['name'][$i]." не является картинкой";
			}
		}
		if ($savedFiles > 0){
			$success = true;
			$api -> CreatePullRequest($branchName, "Добавлены скриншоты для ".$_REQUEST['ModName']);
		}
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
   Огромное спасибо! Скриншоты отправлены на премодерацию и в течение 1-2 дней появятся на сайте :)
</div>
<?}?>