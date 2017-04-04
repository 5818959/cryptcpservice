<?php

namespace spec\CryptCPService;

use CryptCPService\Request;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RequestSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Request::class);
    }

    function it_can_handle_attached_signature_verify_request($request)
    {
        $exampleRequest = array(
            'data' => 'test phrase',
            'certificate' => 'test certificate',
            'signature' => 'test signature',
            'type' => 0,
            'options' => array(
                'nochain' => false,
                'norev' => false,
                'errchain' => false,
            ),
        );

        $this->handle($exampleRequest);

        $this->getData()->shouldBe('test phrase');
        $this->getCertificate()->shouldBe('test certificate');
        $this->getSignature()->shouldBe('test signature');
        $this->getType()->shouldBe(0);
        $this->getOptions()->shouldBeArray();
    }

    function it_can_handle_detached_signature_verify_request($request)
    {
        $exampleRequest = array(
            'data' => 'test phrase',
            'certificate' => 'test certificate',
            'signature' => 'test hash',
            'type' => 1,
            'options' => array(
                'nochain' => false,
                'norev' => false,
                'errchain' => false,
            ),
        );

        $this->handle($exampleRequest);

        $this->getData()->shouldBe('test phrase');
        $this->getCertificate()->shouldBe('test certificate');
        $this->getSignature()->shouldBe('test hash');
        $this->getType()->shouldBe(1);
        $this->getOptions()->shouldBeArray();
    }

    function it_should_validate_request()
    {
        $exampleRequest = array(
            'data' => 'test phrase',
            'certificate' => 'test certificate',
            'signature' => 'test signature',
            'type' => 0,
            'options' => array(),
        );

        $this->handle($exampleRequest);

        $this->validate()->shouldReturn(true);
    }

    function it_should_fail_while_validate_bad_request()
    {
        $badRequest = array(
            'data' => '',
            'type' => -1,
        );

        $this->handle($badRequest);

        $this->validate()->shouldReturn(false);
    }

    function it_should_return_last_validation_errors()
    {
        $badRequest = array(
            'data' => '',
        );

        $this->handle($badRequest);
        $this->validate()->shouldReturn(false);

        $this->getLastErrors()->shouldBeArray();
        $this->getLastErrors()->shouldHaveValue(Request::REQUEST_EMPTY_DATA_MESSAGE);
        $this->getLastErrors()->shouldHaveValue(Request::REQUEST_EMPTY_CERTIFICATE_MESSAGE);
        $this->getLastErrors()->shouldHaveValue(Request::REQUEST_EMPTY_SIGNATURE_MESSAGE);
        $this->getLastErrors()->shouldHaveValue(Request::REQUEST_WRONG_TYPE_MESSAGE);
    }


    function getMatchers()
    {
        return [
            'haveValue' => function($subject, $value) {
                return in_array($value, $subject);
            },
        ];
    }
}
