<?php

use Bones\DataWing;
use Bones\Skeletons\DataWing\Skeleton;

return new class 
{

	protected $table = 'price_profits';

	public function arise()
	{
		DataWing::create($this->table, function (Skeleton $table)
		{
			$table->id()->primaryKey();
			$table->decimal('min_price', 10, 2);
			$table->decimal('max_price', 10, 2);
			$table->decimal('profit_perc', 10, 2);
			return $table;
		});
	}

	public function fall()
	{
		DataWing::drop($this->table);
	}

};
