<?php

declare(strict_types=1);

namespace App\Validation\Schema\Rest\Request\Project;

class GetProjectListSchema
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
  ),
  'required' => 
  array (
    0 => 'adminId',
  ),
  'additionalProperties' => false,
);
}
