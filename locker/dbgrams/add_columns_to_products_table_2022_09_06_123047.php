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
			$table->enum('product_type', ['K', 'M'])->default('K')->after('productId');
			return $table;
		});
	}

	public function fall()
	{
		DataWing::modify($this->table, function (Skeleton $table)
		{
			$table->dropColumn('product_type');
			return $table;
		});
	}

};
