<?php

namespace App\Controller;

use App\Message\TelegramMessage;
use App\Service\Type\Button;
use App\Service\Type\ButtonLine;
use App\Service\Type\InlineKeyboard;
use Exception;
use Longman\TelegramBot\Request as TelegramRequest;
use Longman\TelegramBot\Telegram;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class TelegramWebhookController extends AbstractController
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @throws Exception
     */
    #[Route('/test-send-message', name: 'test_send_message', methods: ["GET"])]
    // Условный метод для отправки сообщения в Telegram от имени бота
    public function sendMessage(): Response
    {
        // Достаем данные из сервера
        $chatId = 1264424951;
        $text = 'Здесь текст заказа 5';
        $orderId = 3;
        // Собираем клавиатуру с кнопками, если это необходимо
        $keyboard = new InlineKeyboard();
        $buttonLine1 = new ButtonLine();
        $buttonLine1->addButton(
            new Button(
                'Принять', json_encode(['.../accept', 'orderId' => $orderId],
                    JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)
            )
        )->addButton(
            new Button(
                'Отложить', json_encode(['.../aside', 'orderId' => $orderId],
                    JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)
            )
        );
        $buttonLine2 = new ButtonLine();
        $buttonLine2->addButton(
            new Button(
                'Отменить',
                json_encode(['.../cancel', 'orderId' => $orderId],
                    JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)
            )
        );
        $keyboard->addLine($buttonLine1)->addLine($buttonLine2);
        //Создаем сообщение и отправляем его в очередь
        try {
           $this->messageBus->dispatch(
               new TelegramMessage(
                   $chatId,
                   $text,
                   $keyboard
               ));
           $result = 'Сообщение отправлено';
        } catch (Exception $exception) {
           $result = $exception->getMessage();
        }

        return new Response($result);
    }

    /**
     * Условный метод для удаления сообщения в Telegram, при нажатии на кнопку которого пришел ответ
     * @throws Exception
     */
    #[Route('/telegram-webhook', name: 'telegram_webhook', methods: ["POST"])]
    public function answer(Request $request, Telegram $telegram): Response
    {
        // Получаем данные после нажатия кнопки
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $chatId = $data['callback_query']['from']['id'];
        $messageId = $data['callback_query']['message']['message_id'];
        // Создаем отправляем запрос на удаление сообщения, от которого пришел ответ
        $body = [
            'chat_id' => $chatId,
            'message_id' => $messageId
        ];
        TelegramRequest::deleteMessage($body);
        //У нас остались полученные данные в переменной $data
        return new Response();
    }
}
