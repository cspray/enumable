# Enumable

A PHP7 library to generate type-declarable enums.

## Installation

Composer is the recommended way of installing Enumable.

`composer require cspray/enumable`

## Quick Start

> Enumable is currently under development and only supports printing generated code to the terminal. Check out 
> the ROADMAP for when more useful functionality will be available.

Here's an [3v4l](http://3v4l.org/GRO2M) of a generated enum that lists a few of my favorite video games. This code was 
taken from the results of this guide.

To generate the example code execute the following command from the directory you installed Enumable.

`php app.php enumable:build Cspray\\FavoriteGames assassins_creed skyrim little_big_planet`

This command will result in the following code being generated:

```
<?php

namespace Cspray;

interface FavoriteGames {

    public function getValue() : string;

    public function isAssassinsCreed() : bool;

    public function isSkyrim() : bool;

    public function isLittleBigPlanet() : bool;

}
```

```
<?php

namespace Cspray;

use Cspray\FavoriteGames;

abstract class FavoriteGamesEnum {

    private static $enums;

    static public function ASSASSINS_CREED() : FavoriteGames {
        if (!isset(self::$enums['assassins_creed'])) {
            self::$enums['assassins_creed'] = self::buildEnum('assassins_creed');
        }
        return self::$enums['assassins_creed'];
    }

    static public function SKYRIM() : FavoriteGames {
        if (!isset(self::$enums['skyrim'])) {
            self::$enums['skyrim'] = self::buildEnum('skyrim');
        }
        return self::$enums['skyrim'];
    }

    static public function LITTLE_BIG_PLANET() : FavoriteGames {
        if (!isset(self::$enums['little_big_planet'])) {
            self::$enums['little_big_planet'] = self::buildEnum('little_big_planet');
        }
        return self::$enums['little_big_planet'];
    }

    static private function buildEnum(string $val) : FavoriteGames {
        return new class($val) implements FavoriteGames {

            private $val;

            public function __construct(string $val) {
                $this->val = $val;
            }

            public function getValue() : string {
                return $this->val;
            }

            public function isAssassinsCreed() : bool {
                return $this->val === 'assassins_creed';
            }

            public function isSkyrim() : bool {
                return $this->val === 'skyrim';
            }

            public function isLittleBigPlanet() : bool {
                return $this->val === 'little_big_planet';
            }

            public function __toString() : string {
                return $this->val;
            }

        };
    }

}

```

## Dependencies

- [cspray/code-anvil](https://github.com/cspray/code-anvil) API to generate PHP7 code.
- [symfony/console](https://github.com/symfony/Console) CLI application framework powering command line enum builder.
- [danielstjules/stringy](danielstjules/stringy) A library that makes advanced string manipulations easy.
- [morrisonlevi/ardent](https://github.com/morrisonlevi/Ardent) Library for various data structures.
