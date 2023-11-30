<?php

declare(strict_types=1);

namespace App\Validation\Schema\Rest\Request\Fluentd;

class SendMessageSchema
{
    public const SCHEMA = array (
  'type' => 'object',
  'properties' =>
  array (
    'tag' =>
    array (
      'type' => 'string',
      'description' => 'fluentd tag',
      'example' => 'prod.reviewer.service.request.sender',
    ),
    'message' =>
    array (
      'type' => 'string',
      'maxLength' => 4000,
      'description' => 'Message',
      'example' => 'log data',
    ),
  ),
  'required' =>
  array (
    0 => 'tag',
    1 => 'message',
  ),
  'additionalProperties' => false,
);
}
