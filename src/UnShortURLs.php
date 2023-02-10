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
	 * @param array $domainList
	 */
	public function __construct(array $domainList)
	{
		$this->domainList = $domainList;
	}

	/**
	 * If the domain is whitelisted, unshort the url
	 * @param string $url
	 * @return string
	 */
	public function unShort(string $url): string
	{
		preg_match(self::$urlRegEx, $url, $match);

		// return if not whitelisted
		if(!in_array($match[1], $this->domainList))
			return $url;

		$request = curl_init($url);
		curl_setopt($request, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
		curl_exec($request);
		$statusCode = curl_getinfo($request, CURLINFO_HTTP_CODE);
		$redirectUrl = curl_getinfo($request, CURLINFO_REDIRECT_URL);
		curl_close($request);

		// recursion
		if($statusCode >= 300 && $statusCode < 400)
			return $this->unShort($redirectUrl);

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