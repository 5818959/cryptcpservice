<?php

namespace CryptCPService;

use CryptCPService\Exception\UnexpectedTypeException;
use CryptCPService\Exception\WrongUtilityPathException;
use TempFileService\Service as TempFileService;

/**
 * Service.
 */
class Service
{
    const TYPE_VERIFY_ATTACHED = 0;
    const TYPE_VERIFY_DETACHED = 1;

    /**
     * cryptcp.exe full path.
     *
     * @var string
     **/
    private $cryptcpUtilPath;
    /**
     * Last command execution output.
     *
     * @var string
     **/
    private $lastOutput;

    /**
     * Constructor.
     *
     * @param string $cryptcpUtilPath Full path to cryptcp.exe
     *
     * @throws WrongUtilityPathException Wrong cryptcp.exe path
     */
    public function __construct($cryptcpUtilPath)
    {
        if (@file_exists($cryptcpUtilPath) === false) {
            throw new WrongUtilityPathException('Cryptcp utility not found.');
        }
        if (@is_executable($cryptcpUtilPath) === false) {
            throw new WrongUtilityPathException(
                'Cryptcp utility is not executable.'
            );
        }

        $this->cryptcpUtilPath = $cryptcpUtilPath;
        $this->lastOutput = '';
    }

    /**
     * Verify signature.
     *
     * @param Request $request Verify request
     *
     * @return boolean TRUE if signature valid, otherwise FALSE
     *
     * @throws UnexpectedTypeException Wrong verify type
     **/
    public function verify(Request $request)
    {
        $verifyType = $request->getType();

        if ($verifyType == self::TYPE_VERIFY_ATTACHED) {
            return $this->verifyAttached(
                $request->getCertificate(),
                $request->getSignature(),
                $request->getOptions()
            );
        } elseif ($verifyType == self::TYPE_VERIFY_DETACHED) {
            return $this->verifyDetached(
                $request->getData(),
                $request->getCertificate(),
                $request->getSignature(),
                $request->getOptions()
            );
        }

        throw new UnexpectedTypeException('Wrong verify type.');
    }

    /**
     * Return last command execution output.
     *
     * @return string Last output
     **/
    public function getLastOutput()
    {
        return $this->lastOutput;
    }

    /**
     * Verify attached signature.
     * Used command: <cryptcp> -verify -f <file_with_cert> <file_with_signature> [<file_to_save_signed_data>] [<flags>].
     *
     * @param string $certificate Certificate
     * @param string $signature   Attached signature
     * @param string $options     Verify options
     *
     * @return boolean TRUE if signature valid, otherwise FALSE
     */
    private function verifyAttached($certificate, $signature, $options)
    {
        $certificateFile = TempFileService::create(['content' => $certificate]);
        $signatureFile = TempFileService::create(['content' => $signature]);
        $flags = $this->makeOptions($options);

        $command = '-verify -f ' . $certificateFile->getPath()
                 . ' ' . $signatureFile->getPath() . ' ' . $flags;

        $verifyResult = $this->runCommand($command);

        unset($certificateFile, $signatureFile);

        return $verifyResult;
    }

    /**
     * Verify detached signature.
     * Used command: <cryptcp> -dir <util_dir_name> -vsignf -f <sign.txt.sgn> <sign.txt> [<flags>].
     *
     * @param string $data        Signed data string
     * @param string $certificate Certificate
     * @param string $hash        Detached signature
     * @param string $options     Verify options
     *
     * @return boolean TRUE if signature valid, otherwise FALSE
     */
    private function verifyDetached($data, $certificate, $hash, $options)
    {
        $certificateFile = TempFileService::create(['content' => $certificate]);
        $dataFile = TempFileService::create([
            'content' => $data,
            'postfix' => '.dat',
        ]);
        $hashFile = TempFileService::create([
            'content' => $hash,
            'dir' => $dataFile->getDir(),
            'name' => $dataFile->getName() . '.sgn',
        ]);
        $flags = $this->makeOptions($options);

        $command = '-dir ' . $dataFile->getDir() . ' -vsignf -f '
                 . $certificateFile->getPath() . ' ' . $dataFile->getPath()
                 . ' ' . $flags;

        $verifyResult = $this->runCommand($command);

        unset($certificateFile, $dataFile, $hashFile);

        return $verifyResult;
    }

    /**
     * Make command options string.
     *
     * @param array $options Command options
     *
     * @return string Command options string
     */
    private function makeOptions(array $options)
    {
        $string = '';
        foreach ($options as $option => $isEnable) {
            if ($isEnable === true) {
                $string .= ' -' . $option;
            }
        }

        return $string;
    }

    /**
     * Run cryptcp command.
     *
     * @param string $command Command
     *
     * @return string Command execution result
     */
    private function runCommand($command)
    {
        $output = array();
        $result = @exec($this->cryptcpUtilPath . ' ' . $command, $output);

        $this->lastOutput = array();
        foreach ($output as $outputLine) {
            $this->lastOutput[] = mb_convert_encoding($outputLine, 'UTF-8', 'CP866');
        }

        if ($result == '[ReturnCode: 0]') {
            return true;
        }

        return false;
    }
}
