<?php

declare(strict_types=1);

namespace App\Validation\Schema\Rest\Request\PullRequest;

class BindDeveloperToPullRequestSchema
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
    'developerId' => 
    array (
      'type' => 'integer',
      'minimal' => 1,
      'description' => '',
      'example' => 1,
    ),
    'id' => 
    array (
      'type' => 'integer',
      'minimal' => 1,
      'description' => '',
      'example' => 1,
    ),
  ),
  'required' => 
  array (
    0 => 'adminId',
    1 => 'projectId',
    2 => 'developerId',
    3 => 'id',
  ),
  'additionalProperties' => false,
);
}
