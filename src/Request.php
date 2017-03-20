<?php

namespace CryptCPService;

/**
 * Request.
 */
class Request
{
    const REQUEST_EMPTY_DATA_MESSAGE = 'Empty data message';
    const REQUEST_EMPTY_CERTIFICATE_MESSAGE = 'Empty certificate';
    const REQUEST_EMPTY_SIGNATURE_MESSAGE = 'Empty signature or hash';
    const REQUEST_WRONG_TYPE_MESSAGE = 'Wrong signature type';

    /**
     * Data.
     *
     * @var string
     */
    private $data;
    /**
     * Certificate.
     *
     * @var string
     */
    private $certificate;
    /**
     * Signature.
     *
     * @var string
     */
    private $signature;
    /**
     * Type.
     *
     * @var numeric
     */
    private $type;
    /**
     * Options.
     *
     * @var array
     */
    private $options;
    /**
     * Last validation errors.
     *
     * @var array
     **/
    private $lastErrors;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->data = '';
        $this->certificate = '';
        $this->signature = '';
        $this->type = -1;
        $this->options = array();
        $this->lastErrors = array();
    }

    /**
     * Handle request data.
     *
     * @param array $request Request data
     *
     * @return self
     */
    public function handle(array $request)
    {
        $this->data = isset($request['data']) ? (string) $request['data'] : '';
        $this->certificate = isset($request['certificate'])
                             ? (string) $request['certificate']
                             : '';
        $this->signature = isset($request['signature'])
                           ? (string) $request['signature']
                           : '';
        $this->type = isset($request['type']) ? (int) $request['type'] : -1;
        $this->options['nochain'] = isset($request['nochain']) ? true : false;
        $this->options['norev'] = isset($request['norev']) ? true : false;
        $this->options['errchain'] = isset($request['errchain']) ? true : false;

        return $this;
    }

    /**
     * Validate request.
     *
     * @return boolean True if request valid, otherwise false
     */
    public function validate()
    {
        $this->cleanLastErrors();

        if (empty($this->data)) {
            $this->addError(self::REQUEST_EMPTY_DATA_MESSAGE);
        }
        if (empty($this->certificate)) {
            $this->addError(self::REQUEST_EMPTY_CERTIFICATE_MESSAGE);
        }
        if (empty($this->signature)) {
            $this->addError(self::REQUEST_EMPTY_SIGNATURE_MESSAGE);
        }
        if ($this->type < 0) {
            $this->addError(self::REQUEST_WRONG_TYPE_MESSAGE);
        }

        if (count($this->getLastErrors())) {
            return false;
        }

        return true;
    }

    /**
     * Return data.
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Return certificate.
     *
     * @return string
     */
    public function getCertificate()
    {
        return $this->certificate;
    }

    /**
     * Return signature.
     *
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Return type.
     *
     * @return numeric
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Return options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Return last validation errors.
     *
     * @return array
     */
    public function getLastErrors()
    {
        return $this->lastErrors;
    }

    /**
     * Adds message to last validation errors.
     *
     * @param string $errorMessage Error message
     *
     * @return self
     */
    private function addError($errorMessage)
    {
        $this->lastErrors[] = $errorMessage;

        return $this;
    }

    /**
     * Clean last validation errors.
     *
     * @return self
     */
    private function cleanLastErrors()
    {
        $this->lastErrors = array();

        return $this;
    }
}
