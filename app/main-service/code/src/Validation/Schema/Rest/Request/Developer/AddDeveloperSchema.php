<?php

declare(strict_types=1);

namespace App\Validation\Schema\Rest\Request\Developer;

class AddDeveloperSchema
{
    public const SCHEMA = array (
  'type' => 'object',
  'properties' => 
  array (
    'adminId' => 
    array (
      'type' => 'integer',
      'minimal' => 1,
      'description' => '',
      'example' => 1,
    ),
    'projectId' => 
    array (
      'type' => 'integer',
      'minimal' => 1,
      'description' => '',
      'example' => 1,
    ),
    'nickname' => 
    array (
      'type' => 'string',
      'minLength' => 1,
      'description' => '',
      'example' => 'monk_case',
    ),
    'isAdmin' => 
    array (
      'type' => 'boolean',
      'description' => '',
      'default' => false,
      'example' => false,
    ),
    'status' => 
    array (
      'type' => 'string',
      'description' => '',
      'enum' => 
      array (
        0 => 'READY',
        1 => 'STOP',
      ),
      'example' => 'READY',
    ),
  ),
  'required' => 
  array (
    0 => 'adminId',
    1 => 'projectId',
    2 => 'nickname',
    3 => 'isAdmin',
    4 => 'status',
  ),
  'additionalProperties' => false,
);
}
