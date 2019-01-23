<?
function prettyPrint($value){
	echo "<pre>";
	print_r($value);
	echo "</pre>";
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
		curl_setopt($process, CURLOPT_USERAGENT,"GamesRevival_Server");
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
	private $Username;
	
	function __construct($username, $password, $repositoryName){
	   $this->Username = $username;
	   
       $this->API = new GitHubApiHelper($username, $password);
	   $this->RepositoryName = $repositoryName;
	}
	
	private function GetRepoUrl($url){
		return "repos/{$this->Username}/{$this->RepositoryName}/{$url}";
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

$api = new GitHubApi($username, $password, "ExperimentRepo");

$result = array();

$result['createBranch'] = $api -> CreateBranch($branchName);
$result['file1'] = $api -> CreateFile($branchName, "Duke3D/Folder/file1.md", "**just text string** - просто тестовая строка :)))))");
$result['file2'] = $api -> CreateFile($branchName, "Duke3D/Folder/file2.md", "*курсивчик*");
$result['pullRequest'] = $api -> CreatePullRequest($branchName, "создал два файла");

prettyPrint($result);
?>