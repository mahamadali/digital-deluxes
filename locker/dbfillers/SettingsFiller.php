<?php

namespace Bones\Skeletons\DBFiller;

use Bones\Database;

return new class
{
	protected $table = 'settings';

	public function fill()
	{
		Database::__insertMulti([
			[
				'key' => 'receive_email_alerts_at',
				'value' => 'hasanmoriya429@gmail.com',
			],
		], null, $this->table);
	}

};