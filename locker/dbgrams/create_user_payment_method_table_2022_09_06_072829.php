<?php

use Bones\DataWing;
use Bones\Skeletons\DataWing\Skeleton;

return new class 
{

	protected $table = 'user_payment_method';

	public function arise()
	{
		DataWing::create($this->table, function (Skeleton $table)
		{
			$table->id()->primaryKey();
			$table->unsignedBigInteger('payment_method_id');
			$table->unsignedBigInteger('user_id');
			$table->unsignedBigInteger('status');
			return $table;
		});
	}

	public function fall()
	{
		DataWing::drop($this->table);
	}

};
