<?php

namespace Bones\Skeletons\DBFiller;

use Bones\Database;

return new class
{
	protected $table = 'cms_pages';

	public function fill()
	{
		Database::__insertMulti([
			[
				'title' => 'Terms & Conditions',
				'slug' => 'terms-conditions',
				'description' => '',
			],
		], null, $this->table);
	}

};