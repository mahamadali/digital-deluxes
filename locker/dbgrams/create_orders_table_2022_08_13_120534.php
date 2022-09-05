<?php

use Bones\DataWing;
use Bones\Skeletons\DataWing\Skeleton;

return new class 
{

	protected $table = 'orders';

	public function arise()
	{
		DataWing::create($this->table, function (Skeleton $table)
		{
			$table->id()->primaryKey();
			$table->string('reference')->nullable();
			$table->string('transaction_id')->nullable();
			$table->string('payment_method_type')->nullable();
			$table->json('payment_method')->nullable();
			$table->string('status')->nullable();
			$table->string('status_message')->nullable();
			$table->string('currency')->nullable();
			$table->string('amount_in_cents');
			$table->unsignedBigInteger('user_id');
			$table->timestamps();
			return $table;
		});
	}

	public function fall()
	{
		DataWing::drop($this->table);
	}

};
