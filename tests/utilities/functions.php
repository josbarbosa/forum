<?php

/**
 * @param $class
 * @param int $quantity
 * @param array $attributes
 * @return mixed
 */
function create($class, int $quantity = 1, array $attributes=[])
{
    return $quantity <= 1 ?
        factory($class)->create($attributes) :
        factory($class, $quantity)->create($attributes);

}

/**
 * @param $class
 * @param int $quantity
 * @param array $attributes
 * @return mixed
 */
function make($class, int $quantity = 1, array $attributes=[])
{
    return $quantity <= 1 ?
        factory($class)->make($attributes) :
        factory($class, $quantity)->make($attributes);
}
