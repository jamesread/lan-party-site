<?php

class ListFinanceAccounts {
	public function getAll() {
		$sql = 'SELECT a.* FROM finance_accounts a';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->execute();

		$listAccounts = array();

		foreach ($stmt->fetchAll() as $itemAccount) {
			$accounts = new ItemFinanceAccount($itemAccount);
		}

		return $listAccounts;
	}
}
