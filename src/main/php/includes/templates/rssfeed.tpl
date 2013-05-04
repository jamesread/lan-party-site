<?xml version = "1.0" encoding = "UTF-8" ?>

<rss version = "2.0" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<title>{$title}</title>

		<link>{$baseUrl}</link>
		<atom:link href = "{$rssUrl}" rel = "self" />

		<description>{$description|default:"No description"}</description>
		<lastBuildDate>{$lastBuildDate}</lastBuildDate>
		<pubDate>{$lastBuildDate}</pubDate>
		<ttl>1800</ttl>

		{foreach from = "$listArticles" item = "article"}

		<item>
			<title>{$article.title}</title>
			<link>{$article.link}</link>
			<guid isPermaLink = "false">{$article.link}?id={$article.id}</guid>
			<pubDate>{$article.date}</pubDate>
		</item>
		{/foreach}
	</channel>
</rss>
