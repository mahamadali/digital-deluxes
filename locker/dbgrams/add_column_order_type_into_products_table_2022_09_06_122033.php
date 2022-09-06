<?php

use Bones\DataWing;
use Bones\Skeletons\DataWing\Skeleton;

return new class 
{

	protected $table = 'products';

	public function arise()
	{
		DataWing::modify($this->table, function (Skeleton $table)
		{
			$table->enum('order_type', ['K', 'M'])->default('K')->after('productId');
			return $table;
		});
	}

	public function fall()
	{
		DataWing::modify($this->table, function (Skeleton $table)
		{
			$table->dropColumn('order_type');
			return $table;
		});
	}

};
