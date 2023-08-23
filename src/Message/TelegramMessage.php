<?php

namespace App\Message;

use App\Service\Type\InlineKeyboard;

class TelegramMessage
{
    private int $chatId; // Id чата.
    private string $text; // Текст сообщения.
    private InlineKeyboard $keyboard; // клавиатура с кнопками.

    public function __construct(int $chatId, string $text, InlineKeyboard $keyboard = null)
    {
        $this->chatId = $chatId;
        $this->text = $text;
        $this->keyboard = $keyboard;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getChatId(): int
    {
        return $this->chatId;
    }

    public function getKeyboard(): InlineKeyboard
    {
        return $this->keyboard;
    }
}
