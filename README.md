# console-error-logger
## By [Edmonds Commerce](https://www.edmondscommerce.co.uk)

Provides a class that can be used in Symfony 4 to log errors and non-zero exit statuses

## Install

In order to install this module add the following to your `composer.json`:

```text
{
  ...
  "require": {
      "edmondscommerce/console-error-logger": "dev-master"
  }, 
  ...
  "repositories": [
      {
          "type": "vcs",
          "url": "https://github.com/edmondscommerce/console-error-logger.git"
      }
  ],
  ...
}
```

## Usage

In order to use these listeners you simply need to add the following to your `config/services.yaml`:

```yaml
services:
kernel.listener.command_dispatch:
        class: EdmondsCommerce\ConsoleErrorLogger\ConsoleErrorListener
        tags:
            - { name: kernel.event_listener, event: console.error }
            - { name: kernel.event_listener, event: console.terminate }
```