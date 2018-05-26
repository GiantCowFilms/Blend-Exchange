<?php declare(strict_types=1);

namespace BlendExchange\Exception\Handler;
use Whoops\Handler\Handler;
use Omnimail\Email;
use Omnimail\MailerInterface;

final class EmailHandler extends Handler
{
    private $mailer;
    private $to;
    private $cache;
    public function __construct(MailerInterface $mailer,\Stash\Pool $cache,string $to) {
        $this->mailer = $mailer;
        $this->to = $to;
        $this->cache = $cache;
    }

    /**
     * Get the exception trace as plain text.
     * @return string
     */
    private function getTraceOutput()
    {
        $inspector = $this->getInspector();
        $frames = $inspector->getFrames();
        $response = "\nStack trace:";
        $line = 1;
        foreach ($frames as $frame) {
            /** @var Frame $frame */
            $class = $frame->getClass();
            $template = "\n%3d. %s->%s() %s:%d%s";
            if (! $class) {
                // Remove method arrow (->) from output.
                $template = "\n%3d. %s%s() %s:%d%s";
            }
            $response .= sprintf(
                $template,
                $line,
                $class,
                $frame->getFunction(),
                $frame->getFile(),
                $frame->getLine(),
                ''
            );
            $line++;
        }
        return $response;
    }
    /**
     * Create plain text response and return it as a string
     * @return string
     */
    public function generateResponse()
    {
        $exception = $this->getException();
        return sprintf(
            "%s: %s in file %s on line %d%s\n",
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $this->getTraceOutput()
        );
    }

    /**
     * Quick and dirty rate limit to ensure this doesn't generate a *huge* sending bill.
     * Settings have been hardcoded, although they should actually be part of application config
     *
     * @return bool
     */
    public function rateLimit() : bool
    {
        $rate = 2;
        $per = 86400;

        $item = $this->cache->getItem('blendexchange/error/emailhandler');
        if($item->isMiss()) {
            $rateLimit = [
                'lastCheck' => time(),
                'allowance' => $rate
            ];
        } else {
            $rateLimit = $item->get();
        }

        $current = time();
        $deltaTime = $current - $rateLimit['lastCheck'];
        $rateLimit['lastCheck'] = $current;
        $rateLimit['allowance'] += $deltaTime * ($rate/$per);
        $pass = false;
        $rateLimit['allowance'] = min($rate,$rateLimit['allowance']);

        if ($rateLimit['allowance'] < 1) {
            $pass = false;
        } else {
            $rateLimit['allowance'] -= 1;
            $pass = true;
        }
        $item->set($rateLimit);
        $this->cache->save($item);
        return $pass;
    }

    public function handle() 
    {
        if (!$this->rateLimit()) {
            return Handler::DONE;
        }
        $email = (new Email())
            ->addTo($this->to)
            ->setFrom('error.bot@blend-exchange.giantcowfilms.com')
            ->setSubject('A fatal error occurred')
            ->setTextBody(
                $this->generateResponse()
            );
        $this->mailer->send($email);

        return Handler::DONE;
    }
}