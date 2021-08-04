# Atom VPN package for Laravel

[![Latest Stable Version](http://poser.pugx.org/cr4sec/atom-vpn/v)](https://packagist.org/packages/cr4sec/atom-vpn) [![Total Downloads](http://poser.pugx.org/cr4sec/atom-vpn/downloads)](https://packagist.org/packages/cr4sec/atom-vpn) [![Latest Unstable Version](http://poser.pugx.org/cr4sec/atom-vpn/v/unstable)](https://packagist.org/packages/cr4sec/atom-vpn) [![License](http://poser.pugx.org/cr4sec/atom-vpn/license)](https://packagist.org/packages/cr4sec/atom-vpn)

## What It Does
This package allows you to manage user permissions and roles in a database.

Once installed you can do stuff like this:

```php
$atomVpn->createAccount(['uuid' => 'my-uuid']);

$user->sessions()->expired();

$session->start();
```

## Installation

```bash
$ composer require cr4sec/atom-vpn
```

### Publishing config
```bash
$ php artisan vendor:publish --provider="Cr4sec\AtomVPN\AtomVPNServiceProvider"
```
