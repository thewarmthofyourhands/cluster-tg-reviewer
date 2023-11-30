<?php

declare(strict_types=1);

namespace App\Validation\Schema\Rest\Request\PullRequest;

class GetOpenReviewPullRequestListSchema
{
    public const SCHEMA = array (
  'type' => 'object',
  'properties' => 
  array (
    'projectId' => 
    array (
      'type' => 'integer',
      'minimal' => 1,
      'description' => '',
      'example' => 1,
    ),
  ),
  'required' => 
  array (
    0 => 'projectId',
  ),
  'additionalProperties' => false,
);
}
