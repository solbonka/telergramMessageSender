<?php

namespace App\Service;

use App\Service\Type\Button;
use App\Service\Type\InlineKeyboard;

class TelegramSendMessageCreator
{
    public function createRequestBody(string $chatId, string $text, InlineKeyboard $keyboard = null): array
    {
        if ($keyboard) {
            $body = [
                'chat_id' => $chatId,
                'text' => $text,
                'reply_markup' => [
                    'inline_keyboard' => $keyboard->getKeyboard()
                ]
            ];
        } else {
            $body = [
                'chat_id' => $chatId,
                'text' => $text
            ];
        }

        return $body;
    }
}
