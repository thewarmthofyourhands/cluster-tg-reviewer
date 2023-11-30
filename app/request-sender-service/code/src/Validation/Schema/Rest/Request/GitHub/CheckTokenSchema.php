<?php

declare(strict_types=1);

namespace App\Validation\Schema\Rest\Request\GitHub;

class CheckTokenSchema
{
    public const SCHEMA = array (
  'type' => 'object',
  'properties' => 
  array (
    'token' => 
    array (
      'type' => 'string',
      'description' => 'Token',
      'example' => 'asfasfa2112rf13',
    ),
    'repositoryFullName' => 
    array (
      'type' => 'string',
      'minLength' => 3,
      'pattern' => '^[a-zA-Z0-9_-]+/[a-zA-Z0-9_-]+$',
      'description' => 'Repository full name',
      'example' => 'organization/repository-name',
    ),
  ),
  'required' => 
  array (
    0 => 'token',
    1 => 'repositoryFullName',
  ),
  'additionalProperties' => false,
);
}
