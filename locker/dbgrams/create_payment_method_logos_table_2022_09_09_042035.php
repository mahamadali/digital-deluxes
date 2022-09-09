<?php

use Bones\DataWing;
use Bones\Skeletons\DataWing\Skeleton;

return new class 
{

	protected $table = 'payment_method_logos';

	public function arise()
	{
		DataWing::create($this->table, function (Skeleton $table)
		{
			$table->id()->primaryKey();
			$table->unsignedBigInteger('payment_id');
			$table->string('logo');
			return $table;
		});
	}

	public function fall()
	{
		DataWing::drop($this->table);
	}

};
