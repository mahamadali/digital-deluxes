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
			$table->unsignedBigInteger('coupon_id')->nullable()->after('order_type');
			return $table;
		});
	}

	public function fall()
	{
		DataWing::modify($this->table, function (Skeleton $table)
		{
			$table->dropColumn('coupon_id');
			return $table;
		});
	}

};
