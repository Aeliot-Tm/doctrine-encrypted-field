# Doctrine Encrypted Field  Bundle

The bundle permits to encrypt separate fields of database.

## Installation

Call command line script to install:
```shell
composer require aeliot/doctrine-encrypted-field:dev-main
```

NOTE: this is alpha-version, so you have to install main branch.

## Configuration (optional):

You may use bundle without an extra configuration. But the most common one is like this:

```yml
aeliot_doctrine_encrypted_field:
    encryption_availability_checker: App\Doctrine\Encryption\EncryptionAvailabilityChecker
    functions_provider: App\Doctrine\Encryption\FunctionsProvider
    secret_provider: App\Doctrine\Encryption\SecretProvider
```
