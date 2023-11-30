<?php

declare(strict_types=1);

namespace App\Validation\Schema\Rest\Request\Admin;

class AdminSchema
{
    public const SCHEMA = array (
  'type' => 'object',
  'properties' => 
  array (
    'id' => 
    array (
      'type' => 'integer',
      'minimal' => 1,
      'description' => 'Id',
      'example' => 1,
    ),
    'nickname' => 
    array (
      'type' => 'string',
      'minLength' => 3,
      'pattern' => '^[a-zA-Z0-9_-]+$',
      'description' => 'Messenger nickname',
      'example' => 'monk_case',
    ),
    'messengerId' => 
    array (
      'type' => 'integer',
      'minimal' => 1,
      'description' => 'Messenger id',
      'example' => 1,
    ),
    'messengerType' => 
    array (
      'type' => 'string',
      'enum' => 
      array (
        0 => 'TELEGRAM',
        1 => 'SLACK',
      ),
      'description' => 'Messenger type',
      'example' => 'TELEGRAM',
    ),
  ),
  'required' => 
  array (
    0 => 'id',
    1 => 'nickname',
    2 => 'messengerId',
    3 => 'messengerType',
  ),
  'additionalProperties' => false,
);
}
