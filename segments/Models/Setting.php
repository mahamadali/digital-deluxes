<?php

namespace Models;

use Models\Base\Model;

class Setting extends Model
{
	protected $table = 'settings';

	public static function getMeta($key) {
		return self::where('`key`', $key)->first()->value;
	}

}