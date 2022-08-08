<?php

use Bones\DataWing;
use Bones\Skeletons\DataWing\Skeleton;

return new class 
{

	protected $table = 'product_publishers';

	public function arise()
	{
		DataWing::create($this->table, function (Skeleton $table)
		{
			$table->id()->primaryKey();
			$table->unsignedBigInteger('product_id');
			$table->string('name');
			$table->timestamps();
			return $table;
		});
	}

	public function fall()
	{
		DataWing::drop($this->table);
	}

};
