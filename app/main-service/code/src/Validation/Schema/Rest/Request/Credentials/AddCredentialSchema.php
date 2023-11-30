<?php

declare(strict_types=1);

namespace App\Validation\Schema\Rest\Request\Credentials;

class AddCredentialSchema
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
    'token' => 
    array (
      'type' => 'string',
      'minLength' => 1,
      'description' => '',
      'example' => 'monk_case',
    ),
    'dateExpired' => 
    array (
      'type' => 'string',
      'minLength' => 1,
      'pattern' => '^20[0-9][0-9]-[0-1][0-9]-[0-3][0-9]$',
      'description' => '',
      'example' => '2022-12-23 11:21:42',
    ),
  ),
  'required' => 
  array (
    0 => 'adminId',
    1 => 'projectId',
    2 => 'token',
    3 => 'dateExpired',
  ),
  'additionalProperties' => false,
);
}
