# Jetifier
Jetifier is simple PHP library to send push notifications and messages via Firebase Cloud Messanging.

* Supports Notifications payload, Data payload and mixed,
* Supports different types of recipients
  * Device token
  * Topic
  * Condition (see: [Docs](https://firebase.google.com/docs/cloud-messaging/http-server-ref#table1))
* Currently only PHP 7.1 and above
* Supports sending messages via curl, file_get_contents and open to your implememtations (default curl)

# Usage
## send to device
```php
$client = new Client('API_KEY');
$message = new Message();
$recipient = new Device('TOKEN');
$notification = new Notification();

$notification->setTitle('title');

$message->setRecipient($recipient)
    ->setNotification($notification)

$client->send($message);
```
## send to topic
```php
$client = new Client('API_KEY');
$message = new Message();
$recipient = new Topic('topic_name');
$notification = new Notification();

$notification->setTitle('title');

$message->setRecipient($recipient)
    ->setNotification($notification)

$client->send($message);
```

## send to topic condition
```php
$client = new Client('API_KEY');
$message = new Message();

$recipient = new Condition(new Topic('topic_name'));
$recipient->addOrTopic(new Topic('second_topic');

$notification = new Notification();
$notification->setTitle('title');

$message->setRecipient($recipient)
    ->setNotification($notification)

$client->send($message);
```

## nesting conditions
```php
...

$recipient = new Condition(new Topic('topic_name'));
$subCondition = new Condition(new Topic('second_topic'));
$subCondition->addOrTopic(new Topic('third_topic');
$recipient->addAndCondition($subCondition);

...
```

## change send method
```php
$client = new Client('API_KEY');
$client->setSender(new \Jetifier\Sender\Post());

...
```