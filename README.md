# blister-php

A PHP implementation of the blister library accordingly to the [SPEC.md](https://github.com/lolPants/Blister/blob/master/SPEC.md)

## Limitation

- Unlike the implementation made by the author himself, I won't support the conversion from the legacy playlist model.
- No support for Zip/LevelId.
- Datetime precision are in seconds.

And of course, any PR to make changes is welcomed.

## Tests
The tests are available under the `tests/` directory.

```bash
# run the tests
composer test
```