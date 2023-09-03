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


use AA\ServerConector\Connector\Exception\MethodNotFoundException;
use FtpClient\FtpClient;
use phpseclib\Net\SFTP;

abstract class AbstractConnector implements ConnectorInterface
{
    public SFTP|FtpClient $connector;
    protected $host;
    protected $login;
    protected $password;
    protected $port;
    protected $timeout;
    protected $remotePath;
    protected $isSetup;

    public function __construct()
    {
        $this->isSetup = false;
    }
    /**
     * Forward the method call to FTP|SFTP functions
     *
     * @param  string       $function
     * @param  array        $arguments
     * @return mixed
     * @throws \Exception When the function is not valid
     */
    public function __call($function, array $arguments)
    {

        if (method_exists($this, $function)) {
            /** @var callable $callable */
            $callable = [$this, $function];
            return call_user_func_array($callable, $arguments);
        }

        throw new MethodNotFoundException("{$function} is not a valid FTP|SFTP Connector function");
    }
    /**
     * @param $host
     * @param $login
     * @param $password
     * @param $port
     * @param $timeout
     * @return void
     */
    public function setUp($host, $login, $password, $port = 21, $timeout = 10): void
    {
        $this->host = $host;
        $this->login = $login;
        $this->password = $password;
        $this->port = $port;
        $this->timeout = $timeout;
    }

    public function setRemotePath($remoteFile): void
    {
        $this->remotePath = $remoteFile;
    }



}
