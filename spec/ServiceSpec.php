<?php

namespace spec\CryptCPService;

use CryptCPService\Service;
use CryptCPService\Request;
use CryptCPService\Exception\WrongUtilityPathException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ServiceSpec extends ObjectBehavior
{
    function let()
    {
        $cryptcpUtilPath = __DIR__ . '/../vendor/bin/fake-cryptcp';

        $this->beConstructedWith($cryptcpUtilPath);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('\CryptCPService\Service');
    }

    function it_should_throw_exception_if_type_is_invalid(Request $request)
    {
        $request->getType()->willReturn(-1);

        $this->shouldThrow('\CryptCPService\Exception\UnexpectedTypeException')
             ->duringVerify($request);
    }

    function it_should_throw_exception_if_cryptcp_utility_not_found()
    {
        $this->beConstructedWith(__DIR__ . '/wrong_utility_path');
        $exception = new WrongUtilityPathException(
            'Cryptcp utility not found.'
        );

        $this->shouldThrow($exception)->duringInstantiation();
    }

    function it_should_throw_exception_if_cryptcp_is_not_executable()
    {
        $this->beConstructedWith(__FILE__);
        $exception = new WrongUtilityPathException(
            'Cryptcp utility is not executable.'
        );

        $this->shouldThrow($exception)->duringInstantiation();
    }

    function it_can_verify_attached_signature(Request $request)
    {
        $request->getType()->willReturn(Service::TYPE_VERIFY_ATTACHED);
        $request->getData()->willReturn('test string');
        $request->getCertificate()->willReturn('<test_certificate>');
        $request->getSignature()->willReturn('<test_signature>');
        $request->getOptions()->willReturn(array(
            'nochain' => true,
            'norev' => true,
            'errchain' => false,
        ));

        $this->verify($request)->shouldBe(false);
    }

    function it_can_verify_detached_signature(Request $request)
    {
        $request->getType()->willReturn(Service::TYPE_VERIFY_DETACHED);
        $request->getData()->willReturn('test string');
        $request->getCertificate()->willReturn('<test_certificate>');
        $request->getSignature()->willReturn('<test_signature>');
        $request->getOptions()->willReturn(array(
            'nochain' => true,
            'norev' => true,
            'errchain' => false,
        ));

        $this->verify($request)->shouldBe(false);
    }

    function it_should_return_success_if_signature_is_valid(Request $request)
    {
        $cryptcpUtilPath = __DIR__ . '/../vendor/bin/fake-cryptcp-success';
        $this->beConstructedWith($cryptcpUtilPath);

        $request->getType()->willReturn(Service::TYPE_VERIFY_DETACHED);
        $request->getData()->willReturn('test string');
        $request->getCertificate()->willReturn('<test_certificate>');
        $request->getSignature()->willReturn('<test_signature>');
        $request->getOptions()->willReturn(array(
            'nochain' => false,
            'norev' => false,
            'errchain' => false,
        ));

        $this->verify($request)->shouldBe(true);
    }
}
