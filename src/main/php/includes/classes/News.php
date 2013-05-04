<?php

class News {
	private $count;

	/**
	 * A cache of news items fetched from the database.
	 */
	private $cache;

	public function post($title, $content, $author) {
		global $db;

		$title = $db->escape($title);
		$content = $db->escape($content);
		$author = intval($author);

		$sql = sprintf('INSERT INTO news (title, content, author, date) VALUES ("%s", "%s", "%s", now())', $title, $content, $author);
		$db->query($sql);
	}

	public function deleteAll() {
		global $db;

		$sql = 'DELETE FROM news';
		$db->query($sql);
	}

	public function setCount($count) {
		$this->count = intval($count);
	}

	private function getFromDatabase() {
		global $db;

		$sql = 'SELECT n.id, n.title, n.content, n.date, u.username, n.author FROM news n, users u WHERE n.author = u.id ORDER BY date DESC LIMIT ' . $this->count;
		$result = $db->query($sql);

		$this->cache = $result->fetchAll();
		reset($this->cache);
	}

	public function getNext() {
		if ($this->cache == null) {
			$this->getFromDatabase();
		} else {
			next($this->cache);
		}

		return current($this->cache);
	}
}

?>
