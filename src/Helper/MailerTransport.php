<?php


namespace App\Helper;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

class MailerTransport
{
    /**
     * @var EsmtpTransport
     */
    private $transport;

    /**
     * @var ParameterBagInterface
     */
    private $params;

    public function __construct( ParameterBagInterface $params)
    {
        $this->params=$params;
    }

    public function getTransport()
    {
        $this->transport = new EsmtpTransport(
            $this->params->get('mailer.smtp.host'),
            $this->params->get('mailer.smtp.port')
        );
        $this->transport->setUsername($this->params->get('mailer.smtp.username'));
        $this->transport->setPassword($this->params->get('mailer.smtp.password'));

        return $this->transport;
    }
}