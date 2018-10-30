<?php
namespace Recras;

class Transient
{
    const BASE = 'recras_';

    /**
     * Delete a transient. Returns 0 for success, 1 for error for easy error counting
     *
     * @param string $name
     *
     * @return int
     */
    public static function delete($name)
    {
        return (delete_transient(self::BASE . $name) ? 0 : 1);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public static function get($name)
    {
        return get_transient(self::BASE . $name);
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return bool
     */
    public static function set($name, $value)
    {
        return set_transient(self::BASE . $name, $value, DAY_IN_SECONDS);
    }
}
