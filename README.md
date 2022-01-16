# Atom VPN package for Laravel

[![Latest Stable Version](http://poser.pugx.org/cr4sec/atom-vpn/v)](https://packagist.org/packages/cr4sec/atom-vpn) [![Total Downloads](http://poser.pugx.org/cr4sec/atom-vpn/downloads)](https://packagist.org/packages/cr4sec/atom-vpn) [![Latest Unstable Version](http://poser.pugx.org/cr4sec/atom-vpn/v/unstable)](https://packagist.org/packages/cr4sec/atom-vpn) [![License](http://poser.pugx.org/cr4sec/atom-vpn/license)](https://packagist.org/packages/cr4sec/atom-vpn) [![PHP Version Require](http://poser.pugx.org/cr4sec/atom-vpn/require/php)](https://packagist.org/packages/cr4sec/atom-vpn)
## What It Does
This package allows you to manage [Atom VPN](https://secure.com/atom/) sessions.

Once installed you can do stuff like this:

```php
/** Search for the first free Atom VPN account */
Account::findFirstFreeAccount();

/** Create a new Atom VPN account */
$atomVpn->createAccount(['uuid' => 'my-uuid']);

/** Get expired user sessions */
$user->sessions()->expired();

/** Start a new session */
$session->start();
```

```bash
$ php artisan atom-vpn:clearing-unused-sessions

$ php artisan atom-vpn:create-vpn-account

$ php artisan atom-vpn:disable-vpn-account
```

## Installation

```bash
$ composer require cr4sec/atom-vpn
```

### Publishing config
```bash
$ php artisan vendor:publish --provider="Cr4sec\AtomVPN\AtomVPNServiceProvider"
```
