<?php

declare(strict_types=1);

namespace App\Validation\Schema\Rest;

class AuthHeaderSchema
{
    public const SCHEMA = array (
  'type' => 'object',
  'properties' => 
  array (
    'Content-Type' => 
    array (
      'type' => 'string',
      'enum' => 
      array (
        0 => 'application/json',
      ),
      'description' => 'Content-type',
      'example' => 'application/json',
    ),
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
    0 => 'Content-Type',
    1 => 'Authorization',
  ),
  'additionalProperties' => true,
);
}
