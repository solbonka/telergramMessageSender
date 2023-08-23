<?php

namespace App\MessageHandler;

use App\Message\TelegramMessage;
use App\Service\TelegramSendMessageCreator;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

class TelegramSendMessageHandler
{
    public function __construct(private Telegram $telegram)
    {
    }

    /**
     * @throws TelegramException
     */
    #[AsMessageHandler]
    public function __invoke(TelegramMessage $message): void
    {
        try {
            $modelRequest = new TelegramSendMessageCreator();
            $body = $modelRequest->createRequestBody($message->getChatId(), $message->getText(), $message->getKeyboard());
            Request::sendMessage($body);
        } catch (TelegramException $e) {
            throw new TelegramException($e->getMessage());
        }
    }
}
