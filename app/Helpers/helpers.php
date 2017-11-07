<?php

/**
 * Get items per page from config file paginator.
 *
 * @param string $index
 * @return int
 * @internal param string $text
 */
function getItemsPerPage(string $index): int
{
    return config("paginator.$index.perpage");
}

/**
 * Set items per page.
 *
 * @param string $index
 * @param int $number
 */
function setItemsPerPage(string $index, int $number): void
{
    config(["paginator.$index.perpage" => $number]);
}
