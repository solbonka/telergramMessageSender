<?php

namespace App\Service\Type;

class InlineKeyboard
{
    private array $buttonLines; //массив из линий клавиатуры, в которых содержатся кнопки (объектов класса ButtonLine).

    public function addLine(ButtonLine $buttonLine): self
    {
        $this->buttonLines[] = $buttonLine;

        return $this;
    }

    public function getKeyboard(): array
    {
        $keyboard = [];
        foreach ($this->buttonLines as $buttonLine) {
            $keyboard[] = $buttonLine->toArray();
        }

        return $keyboard;
    }
}
