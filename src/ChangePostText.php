<?php

namespace MatteoCiaroni\UnShortURLs;

use Flarum\Post\Event\Saving;
use Flarum\Settings\SettingsRepositoryInterface;

class ChangePostText
{
	protected SettingsRepositoryInterface $settings;

	public function __construct(SettingsRepositoryInterface $settings)
	{
		$this->settings = $settings;
	}

	public function handle(Saving $event): void
	{
		$oldText = $event->data["attributes"]["content"];

		if($oldText)
		{
			$domains = explode(",", $this->settings->get("matteociaroni-unshort-urls.domains"));
			$domains = array_map(function($domain) {return trim($domain);}, $domains); // trim all domains
			$newText = (new URLunshortener($domains))->parseText($oldText);
			$event->post->content = $newText;
		}
	}
}