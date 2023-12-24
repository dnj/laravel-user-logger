<?php
return [
	/*
     * If set to true, the subject returns soft deleted models.
     */
	'subject_returns_soft_deleted_models' => false,

	'user_model' => null,

	'routes' => [
		'enable' => true,
		'prefix' => 'api/user-logger',
	],

	'migrations' => [
		'enable' => true,
	],
];