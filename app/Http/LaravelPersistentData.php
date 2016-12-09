<?php
namespace App\Http;

use Facebook\PersistentData\PersistentDataInterface;

class LaravelPersistentData implements PersistentDataInterface {
    /**
     * Get a value from a persistent data store.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key){
        // TODO: Implement get() method.
        return session($key);
    }

    /**
     * Set a value in the persistent data store.
     *
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value){
        // TODO: Implement set() method.
        session([$key => $value]);
    }
}