<?php

use Bones\DataWing;
use Bones\Skeletons\DataWing\Skeleton;

return new class 
{

	protected $table = 'payment_methods';

	public function arise()
	{
		DataWing::create($this->table, function (Skeleton $table)
		{
			$table->id()->primaryKey();
			$table->string('title');
			$table->enum('status', ['ACTIVE', 'INACTIVE']);
			return $table;
		});
	}

	public function fall()
	{
		DataWing::drop($this->table);
	}

};
