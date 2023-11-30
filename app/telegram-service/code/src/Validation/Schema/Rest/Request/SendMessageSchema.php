<?php

declare(strict_types=1);

namespace App\Validation\Schema\Rest\Request;

class SendMessageSchema
{
    public const SCHEMA = array (
  'type' => 'object',
  'properties' => 
  array (
    'chatId' => 
    array (
      'type' => 'integer',
      'minimal' => 1,
      'description' => '',
      'example' => 625235325,
    ),
    'message' => 
    array (
      'type' => 'string',
      'minLength' => 1,
      'description' => '',
      'example' => '',
    ),
  ),
  'required' => 
  array (
    0 => 'chatId',
    1 => 'message',
  ),
  'additionalProperties' => false,
);
}
