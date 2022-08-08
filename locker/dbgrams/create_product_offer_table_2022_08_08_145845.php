<?php

use Bones\DataWing;
use Bones\Skeletons\DataWing\Skeleton;

return new class 
{

	protected $table = 'product_offer';

	public function arise()
	{
		DataWing::create($this->table, function (Skeleton $table)
		{
			$table->id()->primaryKey();
			$table->unsignedBigInteger('product_id');
			$table->string('name');
			$table->string('offerId');
			$table->string('price');
			$table->unsignedBigInteger('qty');
			$table->string('textQty');
			$table->string('merchantName');
			$table->string('isPreorder');
			$table->date('releaseDate');
			$table->timestamps();
			return $table;
		});
	}

	public function fall()
	{
		DataWing::drop($this->table);
	}

};
