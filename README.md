# PHP FTP and SFTP Client Library

[![License](https://img.shields.io/github/license/yourusername/yourrepository)](https://github.com/yourusername/yourrepository/blob/main/LICENSE)
[![Latest Release](https://img.shields.io/github/v/release/yourusername/yourrepository)](https://github.com/yourusername/yourrepository/releases/latest)
[![Build Status](https://img.shields.io/github/workflow/status/yourusername/yourrepository/CI%20Build)](https://github.com/yourusername/yourrepository/actions)

Welcome to the PHP FTP and SFTP Client Library! This library simplifies FTP and SFTP protocol operations, making it easy to manage remote files and establish distant server connections. Whether you need basic file transfers or more advanced functionality, this library has you covered.

## Key Features

- **User-Friendly Assistance:** This library offers easy-to-use helper functions for handling remote files and establishing distant server connections.
  
- **Emphasis on Simplicity and Efficiency:** We've designed this package to be lightweight. It acts as a convenient layer on top of PHP's native FTP and SFTP protocol capabilities, enriched with helpful utilities.

- **Customization Options:** Advanced users can personalize functionality by inheriting from one of the two classes [SftpClientConnector](https://github.com/ajenguianis/ftp-sftp-connector/tree/develop/src/Connector/Sftp) 
 and [FtpClientConnector](https://github.com/ajenguianis/ftp-sftp-connector/tree/develop/src/Connector/Ftp) included in the package.

## Getting Started

### Installation

To get started with our library, you can install it via Composer:

```bash
composer require ajenguianis/ftp-sftp-connector
