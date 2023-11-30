<?php

declare(strict_types=1);

namespace App\Validation\Schema\Rest\Request\Chat;

class ChatSchema
{
    public const SCHEMA = array (
  'type' => 
  array (
    0 => 'object',
    1 => 'null',
  ),
  'properties' => 
  array (
    'id' => 
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
    'status' => 
    array (
      'type' => 'string',
      'enum' => 
      array (
        0 => 'READY',
        1 => 'NOT_EXIST',
      ),
      'description' => 'Chat status',
      'example' => 'NOT_EXIST',
    ),
  ),
  'required' => 
  array (
    0 => 'id',
    1 => 'projectId',
    2 => 'messengerId',
    3 => 'messengerType',
    4 => 'status',
  ),
  'additionalProperties' => false,
);
}
