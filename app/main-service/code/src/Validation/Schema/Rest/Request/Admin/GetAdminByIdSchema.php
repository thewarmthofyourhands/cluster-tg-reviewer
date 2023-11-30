<?php

declare(strict_types=1);

namespace App\Validation\Schema\Rest\Request\Admin;

class GetAdminByIdSchema
{
    public const SCHEMA = array (
  'type' => 'object',
  'properties' => 
  array (
    'id' => 
    array (
      'type' => 'integer',
      'minimal' => 1,
      'description' => 'Id',
      'example' => 1,
    ),
  ),
  'required' => 
  array (
    0 => 'id',
  ),
  'additionalProperties' => false,
);
}
