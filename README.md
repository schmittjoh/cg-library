# CG library provides a toolset for generating PHP code

## ThirdBridge forking note

Looks like the [original package](https://github.com/schmittjoh/cg-library) is abandoned, but unfortunately
intranet-v2 has a [hard dependency on `jms/aop-bundle`](https://github.com/third-bridge/intranet-v2/blob/master/composer.json#L78)
which in turn has a hard dependency on this package.

In its original form, it is incompatible with PHP 7.3 due to [deprecations on the reflection classes](https://www.php.net/manual/en/reflectiontype.tostring.php) 
it can encounter.

In order to override, add the following to your `composer.json`:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url":  "git@github.com:third-bridge/cg-library.git"
    }
  ],
  "require":      {
    "jms/cg": "dev-master"
  }
}
```

## Overview

This library provides some tools that you commonly need for generating PHP code.
One of it's strength lies in the enhancement of existing classes with behaviors.

