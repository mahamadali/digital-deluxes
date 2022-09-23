<?php

use Bones\DataWing;
use Bones\Skeletons\DataWing\Skeleton;

return new class 
{

	protected $table = 'coupons';

	public function arise()
	{
		DataWing::create($this->table, function (Skeleton $table)
		{
			$table->id()->primaryKey();
			$table->string('code');
			$table->decimal('percentage');
			$table->string('condition');
			$table->decimal('price_limit');
			$table->string('activation_count');
			$table->enum('status', ['ACTIVE', 'INACTIVE'])->default('ACTIVE');
			$table->timestamps();
			return $table;
		});
	}

	public function fall()
	{
		DataWing::drop($this->table);
	}

};
