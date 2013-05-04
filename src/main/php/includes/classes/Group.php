<?php

class Group {
	private $groupRecord;

	public function __construct($gid) {
		$this->updateFromDatabase($gid);
	}

	private function updateFromDatabase($id) {
		global $db;

		$sql = 'SELECT g.*, COUNT(mem.id) memberCount FROM groups g LEFT JOIN group_memberships mem ON mem.`group` = g.id WHERE g.id = :id GROUP BY g.id LIMIT 1';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':id', $id);
		$stmt->execute();

		if ($stmt->numRows() == 0) {
			throw new Exception('Group not found.');
		}

		$this->groupRecord = $stmt->fetchRow();
	}

	public function getTitle() {
		return $this->getAttribute('title');
	}

	public function getId() {
		return $this->getAttribute('id');
	}

	public function getAttribute($name) {
		return $this->groupRecord[$name];
	}

	public function getMemberCount() {
		return $this->groupRecord['memberCount'];
	}

	public function getArray() {
		return array(
			'title' => $this->getTitle(),
			'id' => $this->getId(),
			'membershipCount' => $this->getMemberCount(),
		);
	}

	public function getMembers() {
		global $db;

		$sql = 'SELECT DISTINCT u.id, u.username, "suplimentary" as type, g.css AS groupCss FROM group_memberships gm INNER JOIN groups g ON gm.`group` = g.id INNER JOIN users u ON gm.user = u.id WHERE gm.`group` = :groupId UNION SELECT u.id, u.username, "primary" AS type, g.css AS groupCss FROM users u JOIN groups g ON u.group = :groupId2 AND u.group = g.id ';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':groupId', $this->groupRecord['id']);
		$stmt->bindValue(':groupId2', $this->groupRecord['id']);
		$stmt->execute();

		return $stmt->fetchAll();
	}

	public function getPrivs() {
		global $db;

		$sql = 'SELECT p.id, p.key, p.description FROM privileges_g gp INNER JOIN permissions p ON gp.permission = p.id WHERE gp.group = :groupId';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':groupId', $this->getId());
		$stmt->execute();

		return $stmt->fetchAll();
	}
}

?>
