<?php


use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return (new Config())
    ->setRiskyAllowed(true) // Разрешить использование "рискованных" правил
    ->setIndent("    ") // Использовать 4 пробела для отступов
    ->setLineEnding("\n") // Переводы строк в стиле Unix
    ->setRules([
        '@PSR12' => true, // Поддержка стандарта PSR-12
        'strict_param' => true, // Строгая проверка типов параметров
        'array_syntax' => ['syntax' => 'short'], // Использовать короткий синтаксис массива
        'no_unused_imports' => true, // Убирать неиспользуемые импорты
        'no_trailing_whitespace' => true, // Убирать лишние пробелы
        'single_blank_line_at_eof' => true, // Оставлять одну пустую строку в конце файла
    ])
    ->setFinder(
        Finder::create()
            ->in(__DIR__ . '/src') // Директория с исходным кодом
            ->name('*.php') // Ищем только файлы PHP
            ->notName('*.blade.php') // Игнорируем шаблоны Laravel (если используешь)
            ->exclude(['vendor', 'node_modules', 'storage']) // Игнорируем сторонние директории
    );
