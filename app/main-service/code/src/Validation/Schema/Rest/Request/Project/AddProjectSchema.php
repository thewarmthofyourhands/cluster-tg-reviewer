<?php

declare(strict_types=1);

namespace App\Validation\Schema\Rest\Request\Project;

class AddProjectSchema
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
    'name' => 
    array (
      'type' => 'string',
      'minLength' => 1,
      'description' => '',
      'example' => 'project 1',
    ),
    'gitRepositoryUrl' => 
    array (
      'type' => 'string',
      'minLength' => 1,
      'description' => '',
      'example' => 'organization/repository-1',
    ),
    'gitType' => 
    array (
      'type' => 'string',
      'enum' => 
      array (
        0 => 'GIT_HUB',
        1 => 'GIT_LAB',
        2 => 'GIT_BUCKET',
      ),
      'description' => '',
      'example' => 'GIT_HUB',
    ),
    'reviewType' => 
    array (
      'type' => 'string',
      'enum' => 
      array (
        0 => 'TEAM_LEAD_REVIEW',
        1 => 'CROSS_REVIEW',
        2 => 'CROSS_REVIEW_WITHOUT_TEAM_LEAD',
      ),
      'description' => '',
      'example' => 'TEAM_LEAD_REVIEW',
    ),
  ),
  'required' => 
  array (
    0 => 'adminId',
    1 => 'name',
    2 => 'gitRepositoryUrl',
    3 => 'gitType',
    4 => 'reviewType',
  ),
  'additionalProperties' => false,
);
}
