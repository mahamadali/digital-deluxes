<?php

use Bones\DataWing;
use Bones\Skeletons\DataWing\Skeleton;

return new class 
{

	protected $table = 'orders';

	public function arise()
	{
		DataWing::modify($this->table, function (Skeleton $table)
		{
			$table->longText('payment_method')->nullable()->modify();
			return $table;
		});
	}

	public function fall()
	{
		DataWing::modify($this->table, function (Skeleton $table)
		{
			return $table;
		});
	}

};
