<?php

namespace MatteoCiaroni\UnShortURLs;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\UriResolver;
use GuzzleHttp\Psr7\Utils;

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
	 * @param int $iterationsCount
	 * @return string
	 */
	public function unShort(string $url, int $iterationsCount = 0): string
	{
		preg_match(self::$urlRegEx, $url, $match);

		// return if not whitelisted or if max iteration is reached
		if(!in_array($match[1], $this->domainList) || $iterationsCount >= $this->maxIterations)
			return $url;

		try
		{
			$response = (new Client(["timeout" => $this->timeout, "allow_redirects" => false]))->get($url);

			// recursion
			if($response->getStatusCode() >= 300 && $response->getStatusCode() < 400)
			{
				// get the absolute URL from location header
				$newUrl =  self::resolveURL($url, $response->getHeader("Location")[0]);

				return $this->unShort($newUrl, $iterationsCount + 1);
			}
		}
		catch (GuzzleException) {}

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

	/**
	 * Get the absolute URL from a base URL and another URL, relative to the previous one
	 * @param string $base the base URL
	 * @param string $relative the URL relative to the base URL
	 * @return string
	 */
	protected static function resolveURL(string $base, string $relative): string
	{
		return (string) UriResolver::resolve(Utils::uriFor($base), Utils::uriFor($relative));
	}
}