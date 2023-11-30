<?php

declare(strict_types=1);

namespace App\Validation\Schema\Rest;

class AcceptableContentType
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
  ),
  'required' => 
  array (
    0 => 'Content-Type',
  ),
  'additionalProperties' => true,
);
}
