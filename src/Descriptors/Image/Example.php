<?php

namespace App\Descriptors\Image;

class Example {

	public $types = [
		'normal' => [
			'size' => [1024,768,'crop'],
		],
		'thumbnail' => [
			'size' => [200,200,'crop'],
			'api' => [
				'limitColors' => [255, '#ff9900']
			],
		]
	];
}