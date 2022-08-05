<?php

namespace Bones\Skeletons\DBFiller;

use Bones\Database;

return new class
{
	protected $table = 'roles';

	public function fill()
	{
		Database::__insertMulti([
			[
				'name' => 'admin',
			],
			[
				'name' => 'user',
			],
		], null, $this->table);
	}

};