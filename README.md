# Halberd
A CodeIgniter Google Two-Factor Authentication Module for Shield
## Prerequisites
Project should have a stability level of dev
```
composer config minimum-stability dev
```
## Installation
```
composer require grimpirate/halberd:dev-develop
```
## Configuration
In the application's *.env* file *halberd.issuer* denotes the string that will appear on the Google Authenticator app as follows: ISSUER(username)
```
halberd.issuer = 'ISSUER'
```
Halberd uses [pragmarx/google2fa](https://github.com/antonioribeiro/google2fa?tab=readme-ov-file#server-time), which requires that your server time be accurately synchronized (via NTP or some other means). CodeIgniter's [appTimezone](https://github.com/codeigniter4/CodeIgniter4/blob/655bd1de0c460b0e1353d2ead8ecff956ac08ccc/app/Config/App.php#L136) will not affect OTP generation.