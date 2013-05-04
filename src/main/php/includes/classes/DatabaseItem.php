<?php

use \libAllure\DatabaseFactory;

abstract class DatabaseItem implements ArrayAccess {
	private $id;
	private $table;
	protected $cache;

	public function __construct($id, $table) {
		$this->id = $id;
		$this->table = $table;

		if (is_numeric($this->id)) {
			$this->loadFromId($this->id);
		}
	}

	protected function loadFromId($id) {
		$sql = 'SELECT t.* FROM ' . $this->table . ' t WHERE t.id = :id';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':id', $id);
		$stmt->execute();

		$this->cache = $stmt->fetchRowNotNull();
	}

	protected function loadFromArray(array $fields) {}

	public function getField($field) {
		if (!isset($this->cache[$field])) {
			return null;
		} else {
			return $this->cache[$field];
		}
	}

	public function setField($field, $value) {
		$this->cache[$field] = $value;
	}

	public function reload() {
		$this->loadFromId($this->id);
	}

	public function getFields() {
		return $this->cache;
	}

	public function commit() {
		$sql = 'UPDATE ' . $this->table . ' SET ';

		$updates = array();
		foreach ($this->cache as $field => $value) {
			$updates[] = ' ' . $field . ' = :' . $field . 'Value ';
		}

		$sql .= implode($updates, ',');
		$sql .= ', id = id WHERE id = :id';

		$stmt = DatabaseFactory::getInstance()->prepare($sql);

		foreach ($this->cache as $field => $value) {
			$stmt->bindValue($field . 'Value', $value);
		}

		$stmt->bindValue(':id', $this->id);
		$stmt->execute();	
	}

	public function offsetExists($offset) {
		return array_key_exists($offset, $this->cache);
	}

	public function offsetGet($offset) {
		return $this->cache[$offset];
	}

	public function offsetSet($offset, $value) {
		$this->cache[$offset] = $value;
	}

	public function offsetUnset($offset) {
		unset($this->cache[$offset]);
	}

	public function getId() {
		return $this->id;
	}
}

?>
