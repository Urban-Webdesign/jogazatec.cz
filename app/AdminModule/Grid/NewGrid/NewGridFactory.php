<?php declare(strict_types = 1);

namespace App\AdminModule\Grid\NewGrid;

interface NewGridFactory
{

	public function create(): NewGrid;

}
