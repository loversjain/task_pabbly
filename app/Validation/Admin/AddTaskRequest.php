<?php

namespace App\Validation\Admin;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\URI;
use CodeIgniter\Validation\Exceptions\ValidationException;

class AddTaskRequest implements RequestInterface
{
    public static function rules(): array
    {
        return [
            'Title' => 'required',
            'Description' => 'required',
            'Due_date' => 'required',
        ];
    }

    public static function messages(): array
    {
        return [
            'Title' => [
                'required' => 'Email address is required.',
            ],
            'Description' => [
                'required' => 'Description is required.',
            ],
            'Due_date' => [
                'required' => 'Due date is required.',
            ],
            // Add more custom error messages as needed
        ];
    }

    public static function placeholders(): array
    {
        return [
            'Title' => 'Your email address',
            'Description' => 'Your password',
            'Due_date' => 'Your password',
            // Add more placeholders as needed
        ];
    }

    public static function labels(): array
    {
        return [
            'Title' => 'Email Address',
            'Description' => 'Password',
            'Due_date' => 'Password',
            // Add more labels as needed
        ];
    }

    public function getProtocolVersion(): string
    {
        // TODO: Implement getProtocolVersion() method.
    }

    public function setBody($data)
    {
        // TODO: Implement setBody() method.
    }

    public function getBody()
    {
        // TODO: Implement getBody() method.
    }

    public function appendBody($data)
    {
        // TODO: Implement appendBody() method.
    }

    public function populateHeaders(): void
    {
        // TODO: Implement populateHeaders() method.
    }

    public function headers(): array
    {
        // TODO: Implement headers() method.
    }

    public function hasHeader(string $name): bool
    {
        // TODO: Implement hasHeader() method.
    }

    public function header($name)
    {
        // TODO: Implement header() method.
    }

    public function getHeaderLine(string $name): string
    {
        // TODO: Implement getHeaderLine() method.
    }

    public function setHeader(string $name, $value)
    {
        // TODO: Implement setHeader() method.
    }

    public function removeHeader(string $name)
    {
        // TODO: Implement removeHeader() method.
    }

    public function appendHeader(string $name, ?string $value)
    {
        // TODO: Implement appendHeader() method.
    }

    public function prependHeader(string $name, string $value)
    {
        // TODO: Implement prependHeader() method.
    }

    public function setProtocolVersion(string $version)
    {
        // TODO: Implement setProtocolVersion() method.
    }

    public function getMethod(bool $upper = false): string
    {
        // TODO: Implement getMethod() method.
    }

    public function withMethod($method)
    {
        // TODO: Implement withMethod() method.
    }

    public function getUri()
    {
        // TODO: Implement getUri() method.
    }

    public function withUri(URI $uri, $preserveHost = false)
    {
        // TODO: Implement withUri() method.
    }

    public function getIPAddress(): string
    {
        // TODO: Implement getIPAddress() method.
    }

    public function isValidIP(string $ip, ?string $which = null): bool
    {
        // TODO: Implement isValidIP() method.
    }

    public function getServer($index = null, $filter = null)
    {
        // TODO: Implement getServer() method.
    }

    public function createTask(array $data)
    {
    }
}
