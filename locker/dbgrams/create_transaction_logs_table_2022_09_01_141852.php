<?php

use Bones\DataWing;
use Bones\Skeletons\DataWing\Skeleton;

return new class 
{

	protected $table = 'transaction_logs';

	public function arise()
	{
		DataWing::create($this->table, function (Skeleton $table)
		{
			$table->id()->primaryKey();
			$table->unsignedBigInteger('user_id');
			$table->string('tx_id');
			$table->string('currency');
			$table->enum('type', ['wallet', 'order'])->default('order');
			$table->string('amount');
			$table->enum('status', ['PENDING', 'COMPLETED'])->default('PENDING');
			$table->string('payment_method');
			$table->unsignedBigInteger('payment_method_id');
			$table->timestamps();
			return $table;
		});
	}

	public function fall()
	{
		DataWing::drop($this->table);
	}

};
