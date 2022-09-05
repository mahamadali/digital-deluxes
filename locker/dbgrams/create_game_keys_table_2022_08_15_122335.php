<?php

use Bones\DataWing;
use Bones\Skeletons\DataWing\Skeleton;

return new class 
{

	protected $table = 'game_keys';

	public function arise()
	{
		DataWing::create($this->table, function (Skeleton $table)
		{
			$table->id()->primaryKey();
			$table->unsignedBigInteger('order_id');
			$table->unsignedBigInteger('product_id');
			$table->string('serial');
			$table->string('type');
			$table->string('name');
			$table->string('kinguinId');
			$table->string('offerId');
			$table->timestamps();
			return $table;
		});
	}

	public function fall()
	{
		DataWing::drop($this->table);
	}

};
