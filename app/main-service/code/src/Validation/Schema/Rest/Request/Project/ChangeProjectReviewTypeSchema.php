<?php

declare(strict_types=1);

namespace App\Validation\Schema\Rest\Request\Project;

class ChangeProjectReviewTypeSchema
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
    2 => 'reviewType',
  ),
  'additionalProperties' => false,
);
}
