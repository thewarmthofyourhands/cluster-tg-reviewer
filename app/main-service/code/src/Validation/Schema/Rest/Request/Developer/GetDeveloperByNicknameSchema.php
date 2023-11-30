<?php

declare(strict_types=1);

namespace App\Validation\Schema\Rest\Request\Developer;

class GetDeveloperByNicknameSchema
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
    'nickname' => 
    array (
      'type' => 'string',
      'minLength' => 1,
      'description' => '',
      'example' => 'monk_case',
    ),
  ),
  'required' => 
  array (
    0 => 'adminId',
    1 => 'projectId',
    2 => 'nickname',
  ),
  'additionalProperties' => false,
);
}
