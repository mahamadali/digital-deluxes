<?php

use Bones\DataWing;
use Bones\Skeletons\DataWing\Skeleton;

return new class 
{

	protected $table = 'customer_billing_infos';

	public function arise()
	{
		DataWing::create($this->table, function (Skeleton $table)
		{
			$table->id()->primaryKey();
			$table->unsignedBigInteger('user_id');
			$table->string('order_reference');
			$table->string('first_name');
			$table->string('last_name');
			$table->string('email');
			$table->string('phone_number');
			$table->string('address');
			$table->string('country');
			$table->string('city');
			$table->string('additional_note');
			$table->timestamps();
			return $table;
		});
	}

	public function fall()
	{
		DataWing::drop($this->table);
	}

};
