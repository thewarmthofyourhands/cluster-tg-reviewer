<?php

declare(strict_types=1);

namespace App\Validation\Schema\Rest\Request\Project;

class GetProjectByGitRepositoryUrlSchema
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
    'gitRepositoryUrl' => 
    array (
      'type' => 'string',
      'minLength' => 1,
      'description' => '',
      'example' => 'organization/repository-1',
    ),
  ),
  'required' => 
  array (
    0 => 'adminId',
    1 => 'gitRepositoryUrl',
  ),
  'additionalProperties' => false,
);
}
