<?php

declare(strict_types=1);

namespace App\Validation\Schema\Rest\Request\Admin;

class AddAdminSchema
{
    public const SCHEMA = array (
  'type' => 'object',
  'properties' => 
  array (
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
    0 => 'nickname',
    1 => 'messengerId',
    2 => 'messengerType',
  ),
  'additionalProperties' => false,
);
}
