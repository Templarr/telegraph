<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\Exceptions\TelegramWebhookException;
use DefStudio\Telegraph\Telegraph;

/**
 * @mixin Telegraph
 */
trait InteractsWithWebhooks
{
    public function registerWebhook(): Telegraph
    {
        $telegraph = clone $this;

        $url = route('telegraph.webhook', $telegraph->getBot());

        if (!str_starts_with($url, 'https://')) {
            throw TelegramWebhookException::invalidScheme();
        }

        $telegraph->endpoint = self::ENDPOINT_SET_WEBHOOK;
        $telegraph->data = [
            'url' => $url,
            'allowed_updates' => [
                'message',
                'edited_message',
                'channel_post',
                'edited_channel_post',
                'inline_query',
                'chosen_inline_result',
                'callback_query',
                'shipping_query',
                'pre_checkout_query',
                'poll',
                'poll_answer',
                'my_chat_member',
                'chat_member',
                'chat_join_request',
            ],
        ];

        return $telegraph;
    }

    public function unregisterWebhook(bool $dropPendingUpdates = false): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_UNSET_WEBHOOK;
        $telegraph->data = [
            'drop_pending_updates' => $dropPendingUpdates,
        ];

        return $telegraph;
    }

    public function getWebhookDebugInfo(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_GET_WEBHOOK_DEBUG_INFO;

        return $telegraph;
    }

    public function replyWebhook(int $callbackQueryId, string $message): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_ANSWER_WEBHOOK;
        $telegraph->data = [
            'callback_query_id' => $callbackQueryId,
            'text' => $message,
        ];

        return $telegraph;
    }
}
