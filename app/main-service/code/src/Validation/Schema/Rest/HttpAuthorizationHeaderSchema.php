<?php

declare(strict_types=1);

namespace App\Validation\Schema\Rest;

class HttpAuthorizationHeaderSchema
{
    public const SCHEMA = array (
  'type' => 'object',
  'properties' => 
  array (
    'Authorization' => 
    array (
      'type' => 'string',
      'minLength' => 1,
      'pattern' => '^[0-9]+$',
      'description' => 'Authorization id',
      'example' => 1,
    ),
  ),
  'required' => 
  array (
    0 => 'Authorization',
  ),
  'additionalProperties' => true,
);
}
