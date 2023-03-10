<?php

namespace Semhoun\Mailer;

use Semhoun\Interfaces\MailableInterface;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Swift_SendmailTransport;
use Slim\Views\Twig;

/**
 * Class Mailer.
 *
 * @author Nathanaël Semhoun <nathanael@semhoun.net>
 * @author Andrew Dyer <andrewdyer@outlook.com>
 *
 *
 * @category Mailer
 *
 * @see https://github.com/semhoun/slim-mailer
 */
class Mailer
{
    /** @var string */
    protected $host = 'localhost';

    /** @var int */
    protected $port = 25;

    /** @var string */
    protected $username = '';

    /** @var string */
    protected $password = '';

    /** @var string */
    protected $from = [];

    /** @var Swift_Mailer */
    protected $swiftMailer;

    /** @var Twig */
    protected $twig;

    /** @var string */
    protected $protocol = null;

    /**
     * @param Twig  $twig
     * @param array|string $settings
     */
    public function __construct(Twig $twig, $settings)
    {
		if (is_string($settings)) {
			// sendmail config
			$transport = new Swift_SendmailTransport($settings);
			
			$this->swiftMailer = new Swift_Mailer($transport);
		}
		else {
			if (is_array($settings)) {
				// Parse the settings, update the mailer properties.
				foreach ($settings as $key => $value) {
					if (property_exists($this, $key)) {
						$this->{$key} = $value;
					}
				}
			}

			$transport = new Swift_SmtpTransport($this->host, $this->port, $this->protocol);
			$transport->setUsername($this->username);
			$transport->setPassword($this->password);

			$this->swiftMailer = new Swift_Mailer($transport);
		}
        $this->twig = $twig;
    }

    /**
     * @param $plugin
     */
    public function addPlugin($plugin) {
        $this->swiftMailer->registerPlugin($plugin);
    }

    /**
     * @param string $address
     * @param string $name    optional
     *
     * @return $this
     */
    public function setDefaultFrom(string $address, string $name = '')
    {
        $this->from = compact('address', 'name');

        return $this;
    }

    /**
     * @param mixed    $view
     * @param array    $data     optional
     * @param callable $callback optional
     *
     * @return int
     */
    public function sendMessage($view, array $data = [], callable $callback = null)
    {
        if ($view instanceof MailableInterface) {
            return $view->sendMessage($this);
        }

        $message = new MessageBuilder(new Swift_Message());
        $message->setFrom($this->from['address'], $this->from['name']);

        if ($callback) {
            call_user_func($callback, $message);
        }

        $message->setBody($this->twig->fetch($view, $data));

        return $this->swiftMailer->send($message->getSwiftMessage());
    }

    /**
     * @param string $address
     * @param string $name    optional
     *
     * @return PendingMailable
     */
    public function setTo(string $address, string $name = '')
    {
        return (new PendingMailable($this))->setTo($address, $name);
    }
	
	/**
     * The Transport used to send messages.
     *
     * @return Swift_Transport
     */
    public function getTransport()
    {
        return $this->swiftMailer->getTransport();
    }
}
