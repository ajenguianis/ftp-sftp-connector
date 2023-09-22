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

namespace AA\ServerConector\Connector\Sftp;


use AA\ServerConector\Connector\AbstractConnector;
use AA\ServerConector\Connector\Exception\InsufficientSetupException;
use AA\ServerConector\Connector\Exception\MissingConfigurationException;
use phpseclib\Net\SFTP;

class SftpClientConnector extends AbstractConnector
{
    /**
     * @param $host
     * @param $login
     * @param $password
     * @param int $port
     * @param int $timeout
     */
    public function setUp($host, $login, $password, $port = 21, $timeout = 10): void
    {
        parent::setUp($host, $login, $password, $port, $timeout);
        $this->connector = new SFTP($this->host, $this->port, $this->timeout);
        $this->isSetup = true;
    }

    public function connect()
    {
        if (!$this->isSetup) {
            throw new InsufficientSetupException('Connector must be set up before any action');
        }
        if (!$this->connector->ping()) {
            $this->connector->login($this->login, $this->password);
        }

        return $this->connector;
    }

    /**
     * @return bool|void
     */
    public function ping()
    {
        return $this->connector->ping();
    }


    /**
     * @param $localFile
     * @param $remoteFile
     * @param $mode
     * @return bool|string
     */
    public function downloadFile($localFile, $remoteFile = null, $mode = FTP_BINARY): bool|string
    {
        if (!$remoteFile) {
            $remoteFile = $this->remotePath;
        }
        if (!$remoteFile) {
            throw new MissingConfigurationException('remote path is required to download file');
        }
        return $this->connector->get($remoteFile, $localFile);
    }


    /**
     * Get file list in directory
     *
     * @param $directory
     * @param $recursive
     * @return array
     */
    public function nlist($directory = '.', $recursive = false): array
    {
        $fileList = $this->connector->nlist($directory, $recursive);
        $list = [];
        if (!empty($fileList)) {
            foreach ($fileList as $value) {
                if ($value == '.' || $value == '..') {
                    continue;
                }
                $list[] = $value;
            }
        }
        return $list;
    }

    /**
     * Count the files in directory.
     *
     * @param string $directory The directory, by default is the current directory
     * @param bool $recursive true by default
     * @return int
     */
    public function countItems($directory = '.', $recursive = true)
    {
        return count($this->nlist($directory, $recursive));
    }

    /**
     * Get only file with matched extension
     *
     * @param $directory
     * @param $recursive
     * @param $extension
     * @return array
     *
     */
    public function getByExtension($directory = '.', $recursive = false, $extension = 'csv')
    {
        $items = $this->connector->nlist($directory, $recursive);
        if (empty($items)) {
            return [];
        }
        $list = [];
        foreach ($items as $item) {
            if (pathinfo($item, PATHINFO_EXTENSION) === $extension) {
                $list[] = $item;
            }
        }
        return $list;

    }

    public function remove($path, $recursive = true)
    {
        return $this->connector->delete($path, $recursive);
    }

    public function archive($fileFrom, $fileTo): void
    {
        $content = $this->connector->get($fileFrom);
        $this->connector->put($fileTo, $content);
        $this->connector->delete($fileFrom);
    }
}