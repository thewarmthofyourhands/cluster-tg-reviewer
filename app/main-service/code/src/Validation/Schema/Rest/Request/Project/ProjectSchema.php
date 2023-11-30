<?php

declare(strict_types=1);

namespace App\Validation\Schema\Rest\Request\Project;

class ProjectSchema
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
    'id' =>
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
    'projectStatus' =>
    array (
      'type' => 'string',
      'enum' =>
      array (
        0 => 'WITHOUT_CREDENTIAL',
        1 => 'INVALID_CREDENTIAL',
        2 => 'EXPIRED_CREDENTIAL',
        3 => 'WITHOUT_CHAT',
        4 => 'INVALID_CHAT',
        5 => 'WITHOUT_DEVELOPERS',
        6 => 'READY',
      ),
      'description' => '',
      'example' => 'WITHOUT_DEVELOPERS',
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
    1 => 'id',
    2 => 'name',
    3 => 'projectStatus',
    4 => 'gitRepositoryUrl',
    5 => 'gitType',
    6 => 'reviewType',
  ),
  'additionalProperties' => false,
);
}
