# Blister-php

A PHP implementation of the blister library accordingly to the [SPEC.md](https://github.com/lolPants/Blister/blob/master/SPEC.md)

# Dependencies

It require `mongodb` from pecl, here are [the installation instruction from the official php doc](https://www.php.net/manual/en/mongodb.installation.pecl.php).
The reason of this dependency is that the blister format use [BSON](http://bsonspec.org/).

## Limitation

- Unlike the implementation made by the author himself, I won't support the conversion from the legacy playlist model.
- Datetime precision is in seconds.

And of course, any PR to make changes is welcomed.

## Tests
The tests are available under the `tests/` directory.

```bash
# run the tests
composer test
```