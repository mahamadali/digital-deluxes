<?php

namespace Bones\Skeletons\DBFiller;

use Bones\Database;

return new class
{
	protected $table = 'price_profits';

	public function fill()
	{
		Database::__insertMulti([
			[
				'min_price' => 0.01,
				'max_price' => 2.99,
				'profit_perc' => 15,
			],
			[
				'min_price' => 3.00,
				'max_price' => 5.99,
				'profit_perc' => 12,
			],
			[
				'min_price' => 6.00,
				'max_price' => 9.99,
				'profit_perc' => 10,
			],
			[
				'min_price' => 10.00,
				'max_price' => 19.99,
				'profit_perc' => 8,
			],
			[
				'min_price' => 20.00,
				'max_price' => 39.99,
				'profit_perc' => 6,
			],
			[
				'min_price' => 40.00,
				'max_price' => 99.99,
				'profit_perc' => 4,
			],
			[
				'min_price' => 100.00,
				'max_price' => NULL,
				'profit_perc' => 2,
			],
		], null, $this->table);
	}

};