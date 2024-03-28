# Doctrine Encrypted Field  Bundle

The bundle permits to encrypt separate fields of database.

## Configuration (optional):

You may use bundle without an extra configuration. But the most common one is like this:

```yml
aeliot_doctrine_encrypted_field:
    encryption_availability_checker: App\Doctrine\Encryption\EncryptionAvailabilityChecker
    functions_provider: App\Doctrine\Encryption\FunctionsProvider
    secret_provider: App\Doctrine\Encryption\SecretProvider
```
