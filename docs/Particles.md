
While editing an object, never use ```$obj->setField($value)``` directly. Instead, use:

```php
$actor->edit($obj)->setField($value);
```

so that:

1. privileges are handled properly, 
2. editing will be recorded and timestamped.
