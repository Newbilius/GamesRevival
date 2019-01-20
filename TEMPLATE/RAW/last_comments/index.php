<?
$cacheFileName = "last_comments.html";
$maxCommentLength = 120;

if (filemtime("last_comments.html")!==FALSE && (time()-filemtime("last_comments.html"))<2){ //раз в 2 минуты обновляем свежие комменты
		echo file_get_contents($cacheFileName);
	}
else
	{
	$api_key = file_get_contents("disqus_api_key.txt");
	$forum = 'gamesrevival-ru';
	$limit = 5;

	$url_call = "http://disqus.com/api/3.0/forums/listPosts.json?api_key=" . $api_key
				. "&forum=" . $forum
				. "&limit=" . $limit
				. "&related=thread";

	$json = file_get_contents($url_call);
	$commentsData = json_decode ($json);
	
	//print_r($json);
	$html="";
	
	foreach ($commentsData -> response as $comment)
		{
			//echo "Welcome {$comment->createdAt}!";
			$time = strtotime($comment->createdAt);
			$dateFormatted = date('d.m.Y',$time);

			$text = $comment->raw_message;
			echo "!![".strlen($text)."]!!";
			echo "{".$text."}";
			
			if (strlen($text) > $maxCommentLength)
			{
				$str = explode( "\n", wordwrap( $text, $maxCommentLength));
				$text = $str[0]."...";
			}
			
			$html.="<p><a href='{$comment->url}'>{$comment->author->name}</a>
<br>({$dateFormatted})
<br>
{$text}
<br><a href='{$comment->thread->link}'>{$comment->thread->clean_title}</a>
</p>";
		}
	
	file_put_contents($cacheFileName, $html);
	echo $html;
}
?>