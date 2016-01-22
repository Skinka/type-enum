# TypeEnum

#Create enum

```php
class Published extends BaseEnum
{
    const PUBLISHED = 1;
    const UNPUBLISHED = 0;

    protected static $data = [
        self::PUBLISHED => [
            'text' => 'Published',
        ],
        self::UNPUBLISHED => [
            'text' => 'Un published',
        ]
    ];
}
```

#Example

```php
Published::getDataList();

[
    0 => 'Un published',
    1 => 'Published'
]

Published::getArray();

[0,1]

Peblished::PUBLISHED()->text();

Published

TitleType::getByValue(0)->text();

Un published

```