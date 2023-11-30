<?php

declare(strict_types=1);

namespace App\Validation\Schema\Rest\Request\Chat;

class AddChatSchema
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
    'messengerId' => 
    array (
      'type' => 'integer',
      'minimal' => 1,
      'description' => '',
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
    0 => 'adminId',
    1 => 'projectId',
    2 => 'messengerId',
    3 => 'messengerType',
  ),
  'additionalProperties' => false,
);
}
