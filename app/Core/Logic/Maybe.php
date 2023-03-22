<?php

namespace App\Core\Logic;

/**
 * A Maybe is a container for a value that may or may not be present.
 *
 * @see https://en.wikipedia.org/wiki/Monad_(functional_programming)#Maybe_monad
 *
 * @template T The type of the value.
 *
 */
class Maybe
{
    const Nothing = null;

    /**
     * Create a new Maybe. @see Maybe::just() and Maybe::nothing()
     * for creating a Maybe.
     *
     * @param T|null $value The value.
     */
    private function __construct(
        /**
         * True if the value is not null, false otherwise.
         */
        private bool $isJust = false,
        /**
         * The value of this Maybe. If the value is nothing, this will be null.
         *
         * @var T|null
         */
        private $value = null
    ) {
    }

    /**
     * Create a new Maybe with the given value.
     *
     * @param T $value The value.
     *
     * @return Maybe<T> The Maybe.
     */
    public static function just($value): Maybe
    {
        return new Maybe(true, $value);
    }

    /**
     * Create a new Maybe with no value.
     *
     * @return Maybe<null> The Maybe.
     */
    public static function nothing(): Maybe
    {
        return new Maybe(false, null);
    }

    /**
     * Create a new Maybe with the given value.
     *
     * @return bool True if the value is not null, false otherwise.
     */
    public function isJust(): bool
    {
        return $this->isJust;
    }

    /**
     * Create a new Maybe with no value.
     *
     * @return bool True if the value is null, false otherwise.
     */
    public function isNothing(): bool
    {
        return !$this->isJust;
    }

    /**
     * Get the value from this Maybe.
     *
     * @throws \Exception if the value is nothing.
     *
     * @return T The value.
     */
    public function getValue()
    {
        if ($this->isJust()) {
            return $this->value;
        }

        throw new \Exception("Cannot retrieve value from nothing");
    }

    /**
     * Get the value from this Maybe or the default value.
     *
     * Same as $maybe->isJust() ? $maybe->value : $default.
     *
     * Use this method only when @param $default is a value that does
     * not aggregate roots. for more information @see Maybe::getOrElse
     *
     * @param mixed $default.
     *
     * @return mixed The value or the default value.
     */
    public function getOrElse($default)
    {
        return $this->isJust() ? $this->value : $default;
    }

    /**
     * Get the value from this Maybe or execute the function.
     *
     * This function is util for get result that aggregate roots. For example:
     *
     * $id = Maybe::nothing();
     *
     * $maybe->getOrElse(function() {
     *     return IncrementalId::next();
     * });
     *
     * In this case, if $id is nothing, the function will be executed and the result will be returned.
     * Othwerwise, the method will not be executed and the value will be returned.
     *
     * @param callable $f
     *
     * @return mixed The value or the result of the function
     */
    public function getOrCall(callable $f)
    {
        return $this->isJust() ? $this->value : $f();
    }

    /**
     * Get the value from this Maybe or throw the exception.
     *
     * @param \Exception $e The exception to throw.
     *
     * @throws \Exception if the value is nothing.
     *
     * @return mixed The value.
     */
    public function getOrThrow(\Exception $e)
    {
        if ($this->isJust()) {
            return $this->value;
        }

        throw $e;
    }

    /**
     * If is just a value, map a function over the value of this Maybe and returns a Maybe value.
     *
     * @param callable $f The function to map over the value.
     *
     * @return Maybe The result of the function or nothing.
     */
    public function map($f): Maybe
    {
        if ($this->isJust()) {
            return Maybe::just($f($this->value));
        }
        return Maybe::nothing();
    }

    /**
     * If is just a value, map a function over the value of this Maybe and returns a result of @param $f
     *
     * @param callable $f The function to map over the value.
     *
     * @return mixed The result of the function or nothing.
     */
    public function flatMap($f)
    {
        if ($this->isJust()) {
            return $f($this->value);
        }
        return Maybe::nothing();
    }
}
