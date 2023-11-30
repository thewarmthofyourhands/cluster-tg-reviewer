<?php

declare(strict_types=1);

namespace App\Validation\Schema\Rest\Request\Project;

class GetProjectByNameSchema
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
  ),
  'required' => 
  array (
    0 => 'adminId',
    1 => 'name',
  ),
  'additionalProperties' => false,
);
}
