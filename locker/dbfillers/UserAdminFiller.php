<?php

namespace Bones\Skeletons\DBFiller;

use Bones\Database;

return new class
{
	protected $table = 'users';

	public function fill()
	{
		Database::__insertMulti([
			[
				'first_name' => 'Super',
				'last_name' => 'Admin',
				'email' => 'super@admin.com',
				'password' => md5('secret'),
				'role_id' => 1,
				'status' => 'Active'
			],
		], null, $this->table);
	}

};