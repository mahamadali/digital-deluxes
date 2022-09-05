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
				'value' => 'landaettabrandon@yahoo.com',
			],
			[
				'key' => 'block_email_domains',
				'value' => '',
			],
		], null, $this->table);
	}

};