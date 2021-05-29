# Служебный бандл, позволяющий получить экземпляр контейнера за пределами Symfony в кастомных (Wordpress, Bitrix) вариантах использования

***INTERNAL***

## Установка

composer.json:

```json
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/proklung/container.locator.bundle"
        }
    ]
```

```bash

composer require proklung/container-locator-bundle

```

## Зачем?

Эксперимент. Сейчас хэлпер `container` в [моих](https://github.com/ProklUng/bitrix.core.symfony) пакетах обращается
к основному классу `ServiceProvider`, что нормально. Способ, реализуемый в этом бандле - альтернативный, представялется,
что выглядит малость "чище".

Основан на знании, что в каждый инициированый бандл по методу `boot` загоняется экземпляр контейнера. 

Все, что делается в этом бандле - декорируется метод `setContainer` бандла, сохраняя контейнер в статическое свойство.
Плюс отвязывается от конкретной реализации контейнера.

## Как достать контейнер

Класс `ContainerLocator`:

- статический метод `instanceFromBundles()` - из файла конфигураций бандлов. Стоимость - загрузка файла и перебор классов,
пока не будет найден бандл, имеющий метод `getContainer`.
- статический метод `instance()` - из класса этого бандла.

В результате хэлпер `container` может выглядеть как-то так:

```php
    function container($classContainer = ContainerLocatorBundle::class)
    {
        $container = $classContainer::instance();
        if ($container === null) {
            throw new RuntimeException(
              'Service container '. is_object($classContainer) ? get_class($classContainer) : $classContainer.
                      ' not initialized.'
            );
        }

        return $container;
    }
```

## Синтаксический сахарок

Для использования в неконтейнеродружелюбных местах (внутри компонентов Битрикс и т.п)

Класс `ContainerHelper`:

- `public static function kernel()` - Kernel
- `public static function kernelParameters()` - Параметры Kernel
- `public static function parameter(string $param)` - Конкретный параметр Kernel.
- `public static function twig()` - Twig.
- `public static function session()` - Session.
- `public static function logger()` - Session.