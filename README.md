## TELEGRAM_BOT

Приложение для получения и отправки сообщений в Telegram. Реализовано
на [Symfony/Messenger](https://symfony.com/doc/current/messenger.html) и транспортёре [RabbitMQ](https://www.rabbitmq.com/).

### Структура проекта:

**"TelegramMessage"** - класс, для хранения данных сообщения:

* $botApiKey - токен бота, который присваивается при регистрации.
* $botUsername - Username бота, который задается при регистации.
* $hookUrl - URL вебхука, куда должен прийти ответ с бота при нажатии на кнопку.
* $chatId - id чата.
* $text - текст сообщения.
* $keyboard - клавиатура с кнопками.**

**"Button"** - класс для хранения объектов данных кнопок.

* $title - название кнопки.
* $callbackData - url-suffix и id заказа (данные, которые вернутся через вебхук при нажатии кнопки).

**"ButtonLine"** - класс для хранения объектов линий с кнопками для клавиатуры.

* $buttons - массив из кнопок (объектов класса Button).

**"InlineKeyboard"** - класс для хранения объектов клавиатуры с кнопками.

* $buttonLines - массив из линий клавиатуры, в которых содержатся кнопки (объектов класса ButtonLine).
 
**"TelegramWebhookController"** - тестовый файл для отправки сообщений, подключён по Webhook с [Telegram](https://tlgrm.ru/docs/bots/api) 
(/telegram_webhook) и передаёт данные из сообщений в QEEP-Pro.

**"TelegramSendMessageHandler"** - обработчик сообщений, он передаёт данные в "TelegramSendMessageRequest" для формирования
запроса. Затем сформированный запрос передаёт в Longman\TelegramBot\Request для отправки сообщения.

### Тело запроса(для примера, с двумя кнопками):
```json
{
   "chat_id": 1111,
   "text": "Какой-то текст",
   "reply_markup": {
       "inline_keyboard": [
           {
               "text": "принять",
               "callback_data": {"0":"...\/accept","orderId":3}
           },
           {
               "text": "отменить",
               "callback_data": {"0":"...\/cancel","orderId":3}
           }
       ]
  }
}
```

#### Для сборки "Docker-compose":

* Установить [Docker](https://docs.docker.com/get-started/)

* Откройте терминал (bash) и введите следущие команды:

Собрать контейнеры:

```bash
docker-compose build
```

или

```bash
make build
```

Запустить контейнеры:

```bash
make start
```

Проверить запущенные контейнеры

```bash
docker-compose ps -a
```

или

```bash
make ps
```

#### Для установки необходимых библиотек и запуска Consumer:

Зайти в контейнер php-fpm

```bash
docker-compose exec php-fpm bash
```

или

```bash
make in
```

Убедитесь, что вы находитесь в директории /var/www, либо перейдите туда.

Установить необходимые библиотеки

```bash
composer install
```

Запустить consumer

```bash
php bin/console messenger:consume async -vvv
```

или

```bash
make work
```
#### Rabbitmq Management:

* user: guest

* password: guest
