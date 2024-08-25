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
After installation via composer a spark command is provided to finalize installation. It will use [codeigniter4/settings](https://github.com/codeigniter4/settings) to set up the configuration for: Auth.views, Auth.actions, and Halberd.issuer
```
php spark halberd:ini
```
The dependency [pragmarx/google2fa](https://github.com/antonioribeiro/google2fa?tab=readme-ov-file#server-time) requires that your server time be accurately synchronized (via NTP or some other means). CodeIgniter's [appTimezone](https://github.com/codeigniter4/CodeIgniter4/blob/655bd1de0c460b0e1353d2ead8ecff956ac08ccc/app/Config/App.php#L136) will not affect OTP generation.

Halberd input form(s) stylesheet should be located at
```
public/css/halberd.css
```
The QR Code will not be visible without some styling, for instance
```
svg
{
  width: 100%;
  height: 240px;
  fill-rule: evenodd;
}
```
## Supported Locales
* en (English)
* es (Español)
