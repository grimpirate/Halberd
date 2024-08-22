# Halberd
A CodeIgniter Google Two-Factor Authentication Module for Shield
## Installation
Project should have a stability level of dev
```
composer config minimum-stability dev
composer config prefer-stable true
composer require grimpirate/halberd:dev-develop
```
## Configuration
In the application's configuration ([*.env*](https://codeigniter.com/user_guide/general/configuration.html#dotenv-file)), *issuer* denotes the string that will appear on the Google Authenticator app as follows: ISSUER: username/email
```
GrimPirate\Halberd\Config\Halberd.issuer = 'ISSUER'
OR
halberd.issuer = 'ISSUER'
OR
halberd_issuer = 'ISSUER'
```
Also supports the use of [codeigniter4/settings](https://github.com/codeigniter4/settings) to maintain *issuer* in a database
```
service('settings')->set('Halberd.issuer', 'ISSUER');
```
The dependency [pragmarx/google2fa](https://github.com/antonioribeiro/google2fa?tab=readme-ov-file#server-time) requires that your server time be accurately synchronized (via NTP or some other means). CodeIgniter's [appTimezone](https://github.com/codeigniter4/CodeIgniter4/blob/655bd1de0c460b0e1353d2ead8ecff956ac08ccc/app/Config/App.php#L136) will not affect OTP generation.
## Supported Locales
* en (English)
* es (Español)
