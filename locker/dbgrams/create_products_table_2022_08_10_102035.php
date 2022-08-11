<?php

use Bones\DataWing;
use Bones\Skeletons\DataWing\Skeleton;

return new class 
{

	protected $table = 'products';

	public function arise()
	{
		DataWing::create($this->table, function (Skeleton $table)
		{
			$table->id()->primaryKey();
			$table->string('name');
			$table->text('description');
			$table->text('coverImage');
			$table->text('coverImageOriginal');
			$table->string('platform');
			$table->date('releaseDate');
			$table->unsignedBigInteger('qty');
			$table->unsignedBigInteger('textQty');
			$table->string('price');
			$table->string('regionalLimitations');
			$table->unsignedBigInteger('regionId');
			$table->text('activationDetails');
			$table->unsignedBigInteger('kinguinId');
			$table->string('productId');
			$table->string('originalName');
			$table->unsignedBigInteger('offersCount');
			$table->unsignedBigInteger('totalQty');
			$table->string('ageRating');
			$table->string('steam');
			$table->text('cheapestOfferId');
			$table->text('languages');
			$table->text('tags');
			$table->text('merchantName');
			$table->text('developers');
			$table->text('publishers');
			$table->text('genres');
			$table->timestamps();
			return $table;
		});
	}

	public function fall()
	{
		DataWing::drop($this->table);
	}

};
