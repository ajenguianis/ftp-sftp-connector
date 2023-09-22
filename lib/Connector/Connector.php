<?php
/*
 * MIT License
 *
 * Copyright (c) 2023 Anis Ajengui
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 */

namespace AA\ServerConector\Connector;

use AA\ServerConector\Connector\Exception\InsufficientSetupException;
use AA\ServerConector\Connector\Exception\MissingProtocolException;
use AA\ServerConector\Connector\Ftp\FtpClientConnector;
use AA\ServerConector\Connector\Sftp\SftpClientConnector;
use FtpClient\FtpClient;
use phpseclib3\Net\SFTP;

/**
 * @method FtpClient|SFTP connect() connect and return connector
 * @method bool ping() Check if connected
 * @method void setRemotePath(string $remoteFile) Set remote path in distant server
 * @method bool|string downloadFile($localFile, $remoteFile = null, $mode = null) local file path in destination
 * @method array nlist($directory = '.', $recursive = false) list of files in path
 * @method int countItems($directory = '.', $recursive = true) count of files in path
 * @method array getByExtension($directory = '.', $recursive = false, $extension = 'csv') Downloads a file from the FTP|SFTP server and with specific extension
 * @method bool remove($path, $recursive = true) remove remote file
 * @method bool archive($fileFrom, $fileTo) archive remote file in path
 *
 * @author  Anis Ajengui <https://github.com/ajenguianis>
 */
class Connector
{
    /**
     * @var FtpClient|SFTP
     */
    private $connector;
    /**
     * @var string
     */
    private $protocol;

    /**
     * protocol name can be Ftp or Sftp
     * @param $protocolName
     * @return void
     */
    public function setProtocol($protocolName)
    {
        $this->protocol = $protocolName;
    }

    /**
     * Call an ftp or sftp method handled by the connector.
     *
     *
     * @param string $method
     * @param array $arguments
     * @return mixed
     *
     */
    public function __call($method, array $arguments)
    {
        if(!$this->connector){
            throw new InsufficientSetupException('You need to set up connector before calling methods');
        }
        return $this->connector->__call($method, $arguments);
    }

    /**
     * @param $host
     * @param $login
     * @param $password
     * @param $port
     * @param $timeout
     * @return void
     */
    public function setUp($host, $login, $password, $port, $timeout = 30): void
    {
        if (!$this->protocol) {
            throw new MissingProtocolException('Protocol name mast be set before set up');
        }

        if (strtolower($this->protocol) === 'ftp') {
            $this->connector = new FtpClientConnector();
        }
        if (strtolower($this->protocol) === 'sftp') {
            $this->connector = new SftpClientConnector();
        }
        $this->connector->setUp($host, $login, $password, $port, $timeout);
    }
}