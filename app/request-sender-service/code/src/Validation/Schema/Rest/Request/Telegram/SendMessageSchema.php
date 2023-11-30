<?php

declare(strict_types=1);

namespace App\Validation\Schema\Rest\Request\Telegram;

class SendMessageSchema
{
    public const SCHEMA = array (
  'type' => 'object',
  'properties' => 
  array (
    'token' => 
    array (
      'type' => 'string',
      'description' => 'Token',
      'example' => '124dqw214r1t1rhhagd2',
    ),
    'chatId' => 
    array (
      'type' => 'integer',
      'minimal' => 1,
      'description' => 'Chat id telegram',
      'example' => 1,
    ),
    'text' => 
    array (
      'type' => 'string',
      'minLength' => 1,
      'description' => 'Telegram message text',
      'default' => '',
      'example' => 'Hi user',
    ),
    'keyboard' => 
    array (
      'type' => 
      array (
        0 => 'null',
        1 => 'object',
      ),
      'properties' => 
      array (
        'buttonList' => 
        array (
          'type' => 'array',
          'default' => 
          array (
          ),
          'items' => 
          array (
            'type' => 'object',
            'description' => 'Button',
            'example' => 
            array (
              'text' => 'Button 1',
            ),
            'properties' => 
            array (
              'text' => 
              array (
                'type' => 'string',
                'description' => 'Button text',
              ),
              'request_chat' => 
              array (
                'type' => 'object',
                'properties' => 
                array (
                  'request_id' => 
                  array (
                    'type' => 'integer',
                  ),
                  'additionalProperties' => true,
                ),
              ),
              'required' => 
              array (
                0 => 'text',
              ),
              'additionalProperties' => true,
            ),
          ),
        ),
        'oneTimeKeyboard' => 
        array (
          'type' => 'boolean',
          'default' => true,
          'description' => 'is Keyboard one time',
          'example' => false,
        ),
        'isPersistent' => 
        array (
          'type' => 'boolean',
          'default' => false,
          'description' => 'is Keyboard persistent',
          'example' => false,
        ),
        'removeKeyboard' => 
        array (
          'type' => 'boolean',
          'default' => false,
          'description' => 'remove Keyboard',
          'example' => true,
        ),
      ),
      'required' => 
      array (
        0 => 'buttonList',
      ),
      'description' => 'Telegram keyboard',
      'default' => NULL,
      'example' => '',
    ),
    'inlineKeyboard' => 
    array (
      'type' => 
      array (
        0 => 'null',
        1 => 'array',
      ),
      'items' => 
      array (
        'type' => 'object',
        'properties' => 
        array (
          'text' => 
          array (
            'type' => 'string',
            'minLength' => 1,
            'description' => '',
            'example' => 'Inline text button',
          ),
          'callbackData' => 
          array (
            'type' => 'string',
            'minLength' => 1,
            'description' => '',
            'default' => '{}',
            'example' => '{}',
          ),
        ),
        'required' => 
        array (
          0 => 'text',
          1 => 'callbackData',
        ),
      ),
      'description' => 'Telegram inline keyboard',
      'default' => NULL,
      'example' => '',
    ),
  ),
  'required' => 
  array (
    0 => 'token',
    1 => 'chatId',
    2 => 'text',
    3 => 'keyboard',
    4 => 'inlineKeyboard',
  ),
  'additionalProperties' => false,
);
}
