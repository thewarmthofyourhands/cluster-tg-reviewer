<?php

declare(strict_types=1);

namespace App\Validation\Schema\Rest\Request\Developer;

class ChangeDeveloperStatusSchema
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
    'id' => 
    array (
      'type' => 'integer',
      'minimal' => 1,
      'description' => '',
      'example' => 1,
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
    2 => 'id',
    3 => 'status',
  ),
  'additionalProperties' => false,
);
}
