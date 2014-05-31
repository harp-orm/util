Util
====

[![Build Status](https://travis-ci.org/harp-orm/util.png?branch=master)](https://travis-ci.org/harp-orm/util)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/harp-orm/util/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/harp-orm/util/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/harp-orm/util/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/harp-orm/util/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/harp-orm/util/v/stable.png)](https://packagist.org/packages/harp-orm/util)

General purpose helper methods

Array Helpers
-------------

- __Arr::toAssoc__ - convert a random array to a strict structure - every entry has a string key
- __Arr::invoke__ - if the array holds only objects, aggregate the results of a method call on all the objects
- __Arr::pluckProperty__ - aggregate the value of a property of each object in the array
- __Arr::pluckUniqueProperty__ - aggregate the value of a property of each object in the array, only unique values
- __Arr::pluck__ - aggregate all the values of entries with a given key
- __Arr::flatten__ - flatten all the values of nested arrays
- __Arr::groupBy__ - group by result of callback

SplObjectStorage Helpers
------------------------
- __Objects::invoke__ - aggregate the results of a method call on all the objects
- __Objects::filter__ - similar to array_filter, but for SplObjectStorage
- __Objects::toArray__ - convert SplObjectStorage into an array
- __Objects::groupBy__ - change SplObjectStorage where the "key" object is the result of a callback, and all the other objects are grouped by them

## License

Copyright (c) 2014, Clippings Ltd. Developed by Ivan Kerin

Under BSD-3-Clause license, read LICENSE file.
