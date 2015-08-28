# Laasti/flysystem-provider

## Installation

```
composer require laasti/flysystem-provider
```

## Usage

```php

$container = new League\Container\Container;
$container->addServiceProvider('Laasti\FlysystemProvider\FlysystemProvider');
//The first defined adapter is used as the default for League\Flysystem\FilesystemInterface
$container->add('config.flysystem', [
    //the first item in array is the adapter class, the second is the adapter's constructor parameters
    'upload' => ['League\Flysystem\Adapter\Local', ['your-uploads-directory']],
    'temp' => ['League\Flysystem\Adapter\Local', ['your-temp-directory']],
    //see League/Flysystem's documentation for more adapters
]);

$manager = $container->get('League\Flysystem\MountManager');
$manager->read('upload://path-to-file.txt);
//or get the default filesystem
$filesystem = $container->get('League\Flysystem\FilesystemInterface');
//or get a filesystem by its name from the container
$tempFiles = $container->get('flysystem.filesystem.temp');
//or an adapter
$tempAdapter = $container->get('flysystem.adapter.temp');

```

## Contributing

1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D

## History

See CHANGELOG.md for more information.

## Credits

Author: Sonia Marquette (@nebulousGirl)

## License

Released under the MIT License. See LICENSE.txt file.