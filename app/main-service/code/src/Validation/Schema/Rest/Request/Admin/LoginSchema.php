<?php

declare(strict_types=1);

namespace App\Validation\Schema\Rest\Request\Admin;

class LoginSchema
{
    public const SCHEMA = array (
  'type' => 'object',
  'properties' => 
  array (
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
    0 => 'messengerId',
    1 => 'messengerType',
  ),
  'additionalProperties' => false,
);
}
