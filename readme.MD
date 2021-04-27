# Бандл для Symfony, обеспечивающий поддержку шаблонизатора Blade в связке Битрикс + Symfony (или Wordpress + Symfony).

На базе [https://github.com/arrilot/bitrix-blade](пакета)

## Установка

`composer require proklung/symfony-blade-bundle`

## Использование

Заменяем шаблон компонента с template.php на template.blade и можно писать на Blade

Директива @include('path.to.view') модифицирована следующим образом:

Сначала view ищется относительно директории текущего шаблона компонента (там где лежит template.blade).

Если не view там не нашёлся, то он ищется относительно базовой директории (по умолчанию local/views, но может быть указана другая при вызове BladeProvider::register()).

#### Дополнительно

В контейнере появляются два сервиса рендеринга из шаблонов Blade:

- `symfony_blade.blade` - рендерер для Битрикса.
- `symfony_blade.blade_instance` - фреймворконезависимый рендерер (работает, например, в Wordpress).

##### Методы:

Вернет сгенерированный html (там внутри магический метод `__toString`, вызывающий рендерер) для указанного шаблона с параметрами.

```php
    /**
     * Получить View шаблона.
     *
     * @param string $file      Blade шаблон.
     * @param array  $data      Данные.
     * @param array  $mergeData Данные.
     *
     * @return View
     */
    public function file(string $file, array $data = [], array $mergeData = []) : View;
```

Можно динамически задать пути, где шаблонизатор будет искать шаблоны.

```php
    /**
     * Добавить базовый путь.
     *
     * @param string $path Базовый путь.
     *
     * @return void
     */
    public function addBasePath(string $path): void;
```

## Пользовательские директивы (custom directives)

При установке пакета за вас уже автоматически зарегистрировано некоторое количество полезных директив:

`@bxComponent` - аналог $APPLICATION->IncludeComponent()
`@block('key')` и `@endblock` - всё что заключено между ними будет выведено в месте, где вызван метод $APPLICATION->ShowViewContent('key')
`@lang('key')` - равносильно {!! Bitrix\Main\Localization\Loc::getMessage('key') !!}
`@auth` и `@endauth` - сокращенная запись <?php if($USER->IsAuthorized()) ?> ... <?php endif ?>
`@guest` и `@endguest` - аналогично, но проверка на неавторизованного юзера.
`@admin` и `@endadmin` - аналогично, но $USER->IsAdmin()
`@csrf` - сокращенная форма для <input type="hidden" name="sessid" value="{!! bitrix_sessid() !!}" />

Фильтры:

`lcfirst` - первый символ в нижний регистр
`ucfirst` - первый символ в верхний регистр
`date` - строку в дату, согласно заданному формату
`trim` - trim строки

Директивы:

`istrue` / `endistrue`
`isfalse` / `endisfalse`
`isnull` / `endisnull`
`isnotnull` / `endisnotnull`

Дампы

`dump` 
`dd` 



### Добавление своих фильтров и директив Blade

Чтобы подцепить кастомный фильтр - нужно пометить сервис тэгом `blade.custom.filter`. 
Директиву - тэгом `blade.custom.directive` (класс должен реализовывать интерфейс `BladeDirectiveInterface`). 

## Конфигурация

При необходимости пути можно поменять в конфигурации бандла в `local/configs/packages/symfony_blade.yaml`:

```yaml
symfony_blade:
  baseViewPath: '%kernel.project_dir%/local/views'
  cachePath: '%kernel.project_dir%/bitrix/cache/blade'
  readonly: false
```
    
#### Очистка кэша

Для обеспечения высокой скорости работы Blade кэширует скомпилированные шаблоны в php файлы. В большинстве случаев чистить этот кэш самостоятельно потребности нет, потому что блейд сверяет время модификации файлов шаблонов и кэша и самостоятеьно инвалидирует этот кэш. Однако в некоторых случаях (например при добавлении новой пользовательской директивы), этот кэш всё-же надо сбросить. Делается это методом BladeProvider::clearCache()

Для Битрикса существует сервис `symfony_blade.clear_cache`, очищающий кэш в случае присутствия GET параметра clear_cache.

#### Некоторые моменты

Битрикс позволяет использовать сторонние шаблонизаторы только в шаблонах компонентов. Шаблоны сайтов только на php.

По понятным причинам наследованием шаблонов в полную силу воспользоваться не получится.

Традиционное расширение .blade.php использовать нельзя. Битрикс видя .php включает php движок.

Вместо $this в шаблоне следует использовать $template - например $template->setFrameMode(true);

Проверку `<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true) die();?>` прописывать в blade-шаблоне не нужно, она добавляется в скомпилированные view автоматически. Также вместе с этим выполняется и extract($arResult, EXTR_SKIP);

Чтобы языковой файл из шаблона подключился, его (этот языковой файл) надо назвать как обычно - template.php

#### Дополнительно

##### PhpStorm

Чтобы включить подсветку синтаксиса в PhpStorm для .blade файлов нужно добавить это расширение в Settings->Editor->File Types->Blade

Чтобы PhpStorm понимал и подсвечивалл должным образом пользовательские директивы из этого пакета их можно добавить в него. Делается это в Settings->Language & Frameworks->PHP->Blade->Directives
