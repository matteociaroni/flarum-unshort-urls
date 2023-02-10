<?php

namespace MatteoCiaroni\UnShortURLs;

class UnShortURLs
{
	/**
	 * Domains to unshort
	 * @var string[]
	 */
	protected array $domainList;

	/**
	 * The regular expression to find urls
	 * @var string
	 */
	protected static string $urlRegEx = "/https?:\/\/([^\/\s]+)\/?([^\s\[\]\(\)\*\_]*)/";

	/**
	 * The timeout for HTTP connections
	 * @var int
	 */
	protected int $timeout;

	/**
	 * The maximum number of iterations to unshort a URL
	 * @var int
	 */
	protected int $maxIterations;

	/**
	 * @param array $domainList
	 * @param int $timeout
	 * @param int $maxIterations
	 */
	public function __construct(array $domainList, int $timeout, int $maxIterations)
	{
		$this->domainList = $domainList;
		$this->timeout = $timeout;
		$this->maxIterations = $maxIterations;
	}

	/**
	 * If the domain is whitelisted, unshort the url
	 * @param string $url
	 * @return string
	 */
	public function unShort(string $url, int $iterationsCount = 0): string
	{
		if($iterationsCount >= $this->maxIterations)
			return $url;

		preg_match(self::$urlRegEx, $url, $match);

		// return if not whitelisted
		if(!in_array($match[1], $this->domainList))
			return $url;

		$request = curl_init($url);
		curl_setopt($request, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($request, CURLOPT_TIMEOUT, $this->timeout);
		curl_exec($request);
		$statusCode = curl_getinfo($request, CURLINFO_HTTP_CODE);
		$redirectUrl = curl_getinfo($request, CURLINFO_REDIRECT_URL);
		curl_close($request);

		// recursion
		if($statusCode >= 300 && $statusCode < 400)
			return $this->unShort($redirectUrl, $iterationsCount + 1);

		return $url;
	}

	/**
	 * Replaces all urls with unshorted ones
	 * @param string $text
	 * @return string
	 */
	public function replaceURLs(string $text): string
	{
		preg_match_all(self::$urlRegEx, $text, $urls);

		foreach ($urls[0] as $oldUrl)
			$text = str_replace($oldUrl, $this->unShort($oldUrl), $text);

		return $text;
	}
}