<?php

namespace App\Service\Type;

class Button
{
    private string $title;
    private string $callbackData;

    public function __construct(string $title, string $callbackData)
    {
        $this->title = $title;
        $this->callbackData = $callbackData;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCallbackData(): string
    {
        return $this->callbackData;
    }

    public function toArray(): array
    {
        return [
            'text' => $this->title,
            'callback_data' => $this->callbackData
        ];
    }
}
