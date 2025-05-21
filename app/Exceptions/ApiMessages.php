<?php

    namespace App\Exceptions;

    class ApiMessages
    {
        private array $message = [];

        public function __construct(String $message, array $data = [])
        {
            $this->message['message'] = $message;
            $this->message['erros'] = $data;
        }

        public function getMessage(): array
        {
            return $this->message;
        }
    }