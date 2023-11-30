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
      'description' => 'Authorization token',
      'example' => 'ag423hg3h35h53h3h',
    ),
  ),
  'required' => 
  array (
    0 => 'Authorization',
  ),
  'additionalProperties' => true,
);
}
