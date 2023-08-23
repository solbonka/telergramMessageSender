<?php

namespace App\Service\Type;

class ButtonLine
{
    private array $buttons; //массив из кнопок (объектов класса Button).

    public function addButton(Button $button): self
    {
        $this->buttons[] = $button;

        return $this;
    }

    public function toArray(): array
    {
        $buttonLine = [];
        foreach ($this->buttons as $button) {
            $buttonLine[] = $button->toArray();
        }

        return $buttonLine;
    }
}
