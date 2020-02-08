<?php

namespace BotMan\Tinker\Drivers;

use BotMan\BotMan\Interfaces\DriverInterface;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Users\User;
use Clue\React\Stdio\Stdio;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class ConsoleDriver implements DriverInterface
{
    /** @var string */
    protected $message;

    /** @var Stdio */
    protected $client;

    /** @var string */
    protected $bot_id;

    /** @var bool */
    protected $hasQuestion = false;

    /** @var array */
    protected $lastQuestions;

    const DRIVER_NAME = 'SlackRTM';

    const BOT_NAME = 'BotMan';

    /**
     * Driver constructor.
     * @param array $config
     * @param Stdio $client
     */
    public function __construct(array $config, Stdio $client)
    {
        $this->event = Collection::make();
        $this->config = Collection::make($config);
        $this->client = $client;

        $this->client->on('data', function ($line) {
            $this->message = rtrim($line, "\r\n");
        });
    }

    /**
     * Return the driver name.
     *
     * @return string
     */
    public function getName()
    {
        return self::DRIVER_NAME;
    }

    /**
     * Determine if the request is for this driver.
     *
     * @return bool
     */
    public function matchesRequest()
    {
        return false;
    }

    /**
     * @param  IncomingMessage $message
     * @return Answer
     */
    public function getConversationAnswer(IncomingMessage $message)
    {
        $index = (int) $message->getText() - 1;

        if ($this->hasQuestion && isset($this->lastQuestions[$index])) {
            $question = $this->lastQuestions[$index];

            return Answer::create($question['name'])
                ->setInteractiveReply(true)
                ->setValue($question['value'])
                ->setMessage($message);
        }

        return Answer::create($this->message)->setMessage($message);
    }

    /**
     * Retrieve the chat message.
     *
     * @return array
     */
    public function getMessages()
    {
        return [new IncomingMessage($this->message, 999, '#channel', $this->message)];
    }

    /**
     * @return bool
     */
    public function isBot()
    {
        return strpos($this->message, 'BotMan: ') === 0;
    }

    /**
     * Send a typing indicator.
     * @param IncomingMessage $matchingMessage
     * @return mixed
     */
    public function types(IncomingMessage $matchingMessage)
    {
        $this->client->write(self::BOT_NAME.': ...'.PHP_EOL);
    }

    /**
     * Retrieve User information.
     * @param IncomingMessage $matchingMessage
     * @return User
     */
    public function getUser(IncomingMessage $matchingMessage)
    {
        return new User($matchingMessage->getSender());
    }

    /**
     * @return bool
     */
    public function isConfigured()
    {
        return false;
    }

    /**
     * @param string|\BotMan\BotMan\Messages\Outgoing\Question $message
     * @param IncomingMessage $matchingMessage
     * @param array $additionalParameters
     * @return $this
     */
    public function buildServicePayload($message, $matchingMessage, $additionalParameters = [])
    {
        $questionData = null;
        if ($message instanceof OutgoingMessage) {
            $text = $message->getText();
        } elseif ($message instanceof Question) {
            $text = $message->getText();
            $questionData = $message->toArray();
        } else {
            $text = $message;
        }

        return compact('text', 'questionData');
    }

    /**
     * @param mixed $payload
     * @return Response
     */
    public function sendPayload($payload)
    {
        $questionData = $payload['questionData'];
        $this->client->write(self::BOT_NAME.': '.$payload['text'].PHP_EOL);

        if (! is_null($questionData)) {
            foreach ($questionData['actions'] as $key => $action) {
                $this->client->write(($key + 1).') '.$action['text'].PHP_EOL);
            }
            $this->hasQuestion = true;
            $this->lastQuestions = $questionData['actions'];
        }
    }

    /**
     * Does the driver match to an incoming messaging service event.
     *
     * @return bool|mixed
     */
    public function hasMatchingEvent()
    {
        return false;
    }

    /**
     * Tells if the stored conversation callbacks are serialized.
     *
     * @return bool
     */
    public function serializesCallbacks()
    {
        return false;
    }
}
