<?php

declare(strict_types=1);

namespace App\Validation\Schema\Rest\Request\Admin;

class GetAdminByMessengerIdSchema
{
    public const SCHEMA = array (
  'type' => 'object',
  'properties' => 
  array (
    'messengerId' => 
    array (
      'type' => 'integer',
      'minimal' => 1,
      'description' => 'messenger id',
      'example' => 1,
    ),
    'messengerType' => 
    array (
      'type' => 'string',
      'enum' => 
      array (
        0 => 'TELEGRAM',
        1 => 'SLACK',
      ),
      'description' => 'messenger type',
      'example' => 'TELEGRAM',
    ),
  ),
  'required' => 
  array (
    0 => 'messengerId',
    1 => 'messengerType',
  ),
  'additionalProperties' => false,
);
}
