<?php

function create($class, int $quantity = 1, array $attributes=[])
{
    return $quantity <= 1 ?
        factory($class)->create($attributes) :
        factory($class, $quantity)->create($attributes);

}

function make($class, int $quantity = 1, array $attributes=[])
{
    return $quantity <= 1 ?
        factory($class)->make($attributes) :
        factory($class, $quantity)->make($attributes);
}
