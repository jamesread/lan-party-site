<?php

use \libAllure\Form;
use \libAllure\Session;
use \libAllure\ElementHidden;
use \libAllure\ElementSelect;
use \libAllure\User;

class FormAddUserToGroup extends Form {
	public function __construct($userId) {
		parent::__construct('addUserToGroup', 'Add user to group');

		Session::requirePriv('GROUP_EDIT');

		$this->addElement(new ElementHidden('id', 'User', $userId));
		$elGroup = $this->getGroupSelection();
		$this->addElement($elGroup);

		$this->addDefaultButtons();
		$this->getElement('submit')->setCaption('Add user to group');
	}

	private function getGroupSelection() {
		$el = new ElementSelect('selectedGroup', 'Group');
	
		foreach ($this->getAvailableGroups() as $group) {
			$el->addOption($group['title'], $group['id']);
		}

		return $el;		
	}

	private function getAvailableGroups() {
		global $db;

		$user = User::getUserById($this->getElementValue('id'));

		$sql = 'SELECT g.id, g.title FROM groups g WHERE id NOT IN (SELECT gm.id FROM group_memberships gm WHERE gm.user = :userId) AND g.id != :userPrimaryGroup';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':userId', $user->getId());
		$stmt->bindValue(':userPrimaryGroup', $user->getData('group'));
		$stmt->execute();

		return $stmt->fetchAll();
	}

	public function process() {
		global $db;

		$sql = 'INSERT INTO group_memberships (user, `group`) VALUES (:user, :group) ';
		$stmt = $db->prepare($sql);

		$stmt->bindValue(':user', $this->getElementValue('id'));
		$stmt->bindValue(':group', $this->getElementValue('selectedGroup'));
		$stmt->execute();

		redirect('profile.php?id=' . $this->getElementValue('id'), 'User added to group');
	}
}

?>
