<?php

use \libAllure\Form;
use \libAllure\Session;
use \libAllure\ElementHidden;
use \libAllure\ElementSelect;

class FormUpdateGroupPrivileges extends Form {
	public function __construct($groupId) {
		parent::__construct('updateGroupPrivs', 'Update group privs');
		Session::requirePriv('SUPERUSER');

		$this->addElement(new ElementHidden('id', 'Group', $groupId));
		$this->addElement($this->getPermissionElement());
		$this->addDefaultButtons('Grant priv');	
	}

	private function getPermissionElement() {
		global $db;

		$el = new ElementSelect('privileges', 'Privileges', null, 'The permission to grant to the group. If the group already has the permission it will not appear in the list.');
	
		$sql = 'SELECT `key`, id, description FROM permissions WHERE id NOT IN (SELECT gp.permission FROM privileges_g gp WHERE `group` = :groupId)';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':groupId', $this->getElementValue('id'));
		$stmt->execute();

		foreach ($stmt->fetchAll() as $priv) {
			$el->addOption($priv['key'] . (empty($priv['description']) ? null : ' - ' . $priv['description']), $priv['id']);
		}

		return $el;
	}

	public function process() {
		global $db;

		$sql = 'INSERT INTO privileges_g (permission, `group`) VALUES (:permission, :group) ';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':permission', $this->getElementValue('privileges'));
		$stmt->bindValue(':group', $this->getElementValue('id'));
		$stmt->execute();

		redirect('group.php?action=view&amp;id=' . $this->getElementValue('id'), 'Updated.');
	}
}

?>
