<?php

use Bones\DataWing;
use Bones\Skeletons\DataWing\Skeleton;

return new class 
{

	protected $table = 'currency_rates';

	public function arise()
	{
		DataWing::create($this->table, function (Skeleton $table)
		{
			$table->id()->primaryKey();
			$table->string('from');
			$table->string('to');
			$table->decimal('rate', 10, 5);
			return $table;
		});
	}

	public function fall()
	{
		DataWing::drop($this->table);
	}

};
