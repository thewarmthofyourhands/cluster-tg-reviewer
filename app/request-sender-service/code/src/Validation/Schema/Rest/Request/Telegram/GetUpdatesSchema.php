<?php

declare(strict_types=1);

namespace App\Validation\Schema\Rest\Request\Telegram;

class GetUpdatesSchema
{
    public const SCHEMA = array (
  'type' => 'object',
  'properties' => 
  array (
    'token' => 
    array (
      'type' => 'string',
      'description' => 'Token',
      'example' => '124dqw214r1t1rhhagd2',
    ),
    'ts' => 
    array (
      'type' => 'integer',
      'minimal' => 1,
      'description' => 'Ts telegram',
      'example' => 1,
    ),
  ),
  'required' => 
  array (
    0 => 'token',
  ),
  'additionalProperties' => false,
);
}
