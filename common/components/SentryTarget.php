<?php

namespace common\components;

use yii\log\Logger;
use yii\log\Target;

/**
 * Sends error/warning log messages to Sentry (sentry/sentry ^4).
 * Replaces the abandoned mito/yii2-sentry target.
 */
class SentryTarget extends Target
{
    public $dsn;
    public $environment;

    public function init()
    {
        parent::init();
        \Sentry\init([
            'dsn' => $this->dsn,
            'environment' => $this->environment,
        ]);
    }

    public function export()
    {
        foreach ($this->messages as $message) {
            list($text, $level) = $message;

            if ($text instanceof \Throwable) {
                \Sentry\captureException($text);
                continue;
            }

            \Sentry\withScope(function (\Sentry\State\Scope $scope) use ($text, $level, $message) {
                $scope->setTag('category', $message[2]);
                \Sentry\captureMessage(
                    is_string($text) ? $text : print_r($text, true),
                    $level === Logger::LEVEL_ERROR ? \Sentry\Severity::error() : \Sentry\Severity::warning()
                );
            });
        }
    }
}
