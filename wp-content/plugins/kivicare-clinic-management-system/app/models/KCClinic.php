<?php


namespace App\models;

use App\baseClasses\KCModel;

class KCClinic extends KCModel {

	public function __construct()
	{
		parent::__construct('clinics');
	}


}