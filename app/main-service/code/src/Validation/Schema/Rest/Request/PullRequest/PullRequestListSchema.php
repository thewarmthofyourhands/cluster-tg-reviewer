<?php

declare(strict_types=1);

namespace App\Validation\Schema\Rest\Request\PullRequest;

class PullRequestListSchema
{
    public const SCHEMA = array (
  'type' => 'array',
  'items' => 
  array (
    'type' => 'object',
    'properties' => 
    array (
      'id' => 
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
      'pullRequestNumber' => 
      array (
        'type' => 'integer',
        'minimal' => 1,
        'description' => '',
        'example' => 1,
      ),
      'title' => 
      array (
        'type' => 'string',
        'minLength' => 1,
        'description' => '',
        'example' => 'project 1',
      ),
      'branch' => 
      array (
        'type' => 'string',
        'minLength' => 1,
        'description' => '',
        'example' => 'topic-1',
      ),
      'status' => 
      array (
        'type' => 'string',
        'enum' => 
        array (
          0 => 'OPEN',
          1 => 'PENDING',
          2 => 'REVIEWING',
          3 => 'APPROVED',
          4 => 'CLOSED',
        ),
        'description' => '',
        'example' => 'OPEN',
      ),
      'lastPendingDate' => 
      array (
        'type' => 
        array (
          0 => 'null',
          1 => 'string',
        ),
        'description' => '',
        'example' => '2022-12-03 12:21:11',
      ),
    ),
    'required' => 
    array (
      0 => 'id',
      1 => 'projectId',
      2 => 'developerId',
      3 => 'pullRequestNumber',
      4 => 'title',
      5 => 'branch',
      6 => 'status',
      7 => 'lastPendingDate',
    ),
    'additionalProperties' => false,
  ),
);
}
