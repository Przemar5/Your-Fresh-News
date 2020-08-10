<?php

namespace App\Handlers;

class PhraseSearcher
{
	protected string $phrase;
	protected \App\Article $article;
	protected bool $contains;
	protected int $maxLength;


	public function __construct(?string $phrase, ?int $maxLength = 300)
	{
		$phrase = addslashes($phrase);
    	// $phrase = htmlspecialchars($phrase);
    	$phrase = strip_tags($phrase);
    	// $phrase = filter_var($phrase, FILTER_SANITIZE_STRING);
    	$phrase = trim($phrase);

    	$this->maxLength = $maxLength;
		$this->phrase = $phrase;
	}

	public function setArticle(\App\Article $article)
	{
		$this->article = $article;
		$this->article->title = strip_tags($article->title);
		$this->article->body = strip_tags($article->body);
	}

	public function contains()
	{
		return $this->contains = 
				(preg_match('/' . $this->phrase . '/i', $this->article->title) ||
				preg_match('/' . $this->phrase . '/i', $this->article->body));
	}

	public function highlight(?string $string)
	{
		return preg_replace(
			'/'.$this->phrase.'/i', 
			'<span style="background-color: #f5ff64;">' . $this->phrase . '</span>', 
			$string);
	}

	public function prepareResult()
	{
		if (preg_match('/'.$this->phrase.'/i', $this->article->title)) {
			
			$this->article->title = $this->highlight($this->article->title);
		}

		if (preg_match('/'.$this->phrase.'/i', $this->article->body)) {
			
			$firstIndex = stripos($this->article->body, $this->phrase);
			$bodyLength = strlen($this->article->body);

			if ($firstIndex >= 20) {

				$difference = 0;
				$maxDifference = ceil(($this->maxLength + strlen($this->phrase)) / 2);

				while ($firstIndex > 20 && $difference < $maxDifference &&
					!(substr($this->article->body, $firstIndex - 20, 1) == ' ' ||
					 substr($this->article->body, $firstIndex - 20, 1) == '\n')) {

					$firstIndex--;
					$difference++;
				}
			}

			if ($bodyLength <= $this->maxLength) {
				// Do nothing, presented article body will be as it is
			
			} elseif ($firstIndex < 20) {

				$this->article->body = substr($this->article->body, 
					0, $this->maxLength);
				$this->article->body = trim($this->article->body);
				
				if (!strlen($this->article->body) < $this->maxLength) {

					$this->article->body .= '...';
				}
			
			} elseif ($firstIndex > $bodyLength + $this->maxLength) {

				$this->article->body = '...' . trim(substr($this->article->body, 
					$bodyLength - $this->maxLength));

			} else {

				$this->article->body = substr($this->article->body, 
					$firstIndex - 20, $this->maxLength);
				$this->article->body = '...' . trim($this->article->body) . '...';
			}

			$this->article->body = $this->highlight($this->article->body);
		
		} elseif (strlen($this->article->body) > $this->maxLength) {

			$this->article->body = substr($this->article->body, 0, $this->maxLength);
		}
	}

	public function getArticle()
	{
		return $this->article;
	}
}