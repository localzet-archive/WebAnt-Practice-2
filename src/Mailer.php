<?php

declare(strict_types=1);

namespace Localzet\WebantPractice2;

use InvalidArgumentException;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Практическое задание от WebAnt №2
 * 
 * @package     WebAnt-Practice-2
 * @link        https://github.com/localzet/WebAnt-Practice-2
 * @author      Ivan Zorin <creator@localzet.com>
 */

class Mailer
{
    private PHPMailer $mail;
    private array $defaultConfig = [
        'host' => 'smtp.localzet.com',
        'username' => 'your_username',
        'password' => 'your_password',
        'secure' => 'ssl', // или 'tls'
        'port' => 465, // 587 при TLS

        'from' => 'from@localzet.com',
        'name' => 'Отправитель',
    ];

    /**
     * Создает новый объект Mailer и настраивает параметры SMTP.
     *
     * @param array $config Пользовательская конфигурация SMTP (необязательно).
     */
    function __construct(array $config = [])
    {
        // Объединение пользовательской конфигурации с конфигурацией по умолчанию
        $config = array_merge($config, $this->defaultConfig);

        // Создание объекта PHPMailer
        $mail = new PHPMailer();

        // Настройка SMTP
        $mail->isSMTP();
        $mail->Host = $config['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['username'];
        $mail->Password = $config['password'];
        $mail->SMTPSecure = $config['secure'];
        $mail->Port = $config['port'];

        $mail->setFrom($config['from'], $config['name']);

        $this->mail = $mail;
    }

    /**
     * Отправляет электронное письмо.
     *
     * @param string      $email   Email получателя.
     * @param string      $name    Имя получателя.
     * @param string      $text    Текст письма.
     * @param string|null $file    Путь к файлу вложения (необязательно).
     * @param string      $subject Тема письма (по умолчанию: 'Тема письма').
     * @return bool Возвращает true в случае успешной отправки письма, в противном случае false.
     * @throws InvalidArgumentException Если указан некорректный email.
     */
    function send(string $email, string $name, string $text, ?string $file = null, string $subject = 'Тема письма')
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Некорректный email :(', 400);
        }

        // email и имя получателя
        $this->mail->addAddress($email, $name);

        // Добавление тела письма
        $this->mail->isHTML(true);
        $this->mail->Subject = $subject;
        $this->mail->Body = $text;

        if ($file) {
            // Имя файла определится через PHPMailer::mb_pathinfo($path, PATHINFO_BASENAME)
            $this->mail->addAttachment($file);
        }

        // Отправка письма
        return $this->mail->send();
    }
}
