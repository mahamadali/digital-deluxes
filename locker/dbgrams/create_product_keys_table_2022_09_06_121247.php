<?php

use Bones\DataWing;
use Bones\Skeletons\DataWing\Skeleton;

return new class 
{

	protected $table = 'product_keys';

	public function arise()
	{
		DataWing::create($this->table, function (Skeleton $table)
		{
			$table->id()->primaryKey();
			$table->unsignedBigInteger('product_id');
			$table->text('key');
			$table->unsignedBigInteger('is_used');
			return $table;
		});
	}

	public function fall()
	{
		DataWing::drop($this->table);
	}

};
