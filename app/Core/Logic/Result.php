<?php

namespace App\Core\Logic;

use Exception;

/**
 * A Result is a container for a value that may or may not be present.
 *
 * @see https://en.wikipedia.org/wiki/Monad_(functional_programming)#Maybe_monad
 *
 * @template T The type of the value.
 * @template E The type of the error.
 *
 * @psalm-immutable
 *
 * @psalm-type Result<T,E> = Result::success(T) | Result::fail(E)
 */
class Result
{
    /**
     * Create a new Result. @see Result::success() and Result::fail()
     * for creating a Result.
     *
     * @param T $value The value.
     * @param E $error The error.
     */
    private function __construct(

        private $isSuccess = false,
        /**
         * The value of this Result. If the value is an error, this will be null.
         *
         * @var T
         */
        private $value = null,
    ) {
    }

    /**
     * Create a new Result instance with the given value.
     *
     * @param T $value The value.
     *
     * @return Result<T,\Exception> The Result.
     */
    public static function success($value): Result
    {
        return new Result(true, $value);
    }

    /**
     * Create a new Result instance with the given error.
     *
     * @param E $value The error.
     *
     * @return Result<mixed,E> The Result.
     */
    public static function fail($value): Result
    {
        return new Result(false, $value);
    }

    /**
     * Check if the value is not null.
     *
     * @return bool True if the value is not null, false otherwise.
     */
    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    /**
     * Check if the error is not null.
     *
     * @return bool True if the error is not null, false otherwise.
     */
    public function isFailure(): bool
    {
        return !$this->isSuccess;
    }

    /**
     * Get the value of this Result.
     *
     * @throws LogicException If the value is null or if the error is not null.
     *
     * @return T The value.
     */
    public function getObject(): mixed
    {
        if ($this->isSuccess()) {
            return $this->value;
        }

        throw new \LogicException("Cannot retrieve value from failed result");
    }

    /**
     * Get the error of this Result.
     *
     * @throws LogicException If the error is null or if the value is not null.
     *
     * @return E The error.
     */
    public function getError(): mixed
    {
        if ($this->isFailure()) {
            return $this->value;
        }

        throw new \LogicException("Cannot retrieve error from successful result");
    }

    /**
     * This function takes a callable $f as a parameter and returns a new Result object.
     * If the current Result object is successful, the function $f is called with the
     * current Result object's value as an argument. The result of calling the function $f
     * is then encapsulated in a new Result object, which is returned by the function.
     * If the current Result object is a failure, the function returns a new Result
     * object with the same error.
     *
     * @param callable():(T|E) $f The callable to be called with the current Result object's value,
     * if it is successful.
     *
     * @return Result<T,E> The result of calling the given function, encapsulated in a
     * new Result object, or a new Result object with the same error if the current Result
     * object is a failure.
     *
     */
    public function map(callable $f): Result
    {
        if ($this->isSuccess()) {
            return Result::success($f($this->value));
        }

        return Result::fail($this->value);
    }

    /**
     * This function takes a callable $f as a parameter and returns the result of calling
     * the function $f with the current Result object's value, if it is successful.
     * If the current Result object is a failure, the function returns a new Result object
     * with the same error.
     *
     * @param callable():(T|E) $f The callable to be called with the current Result object's value,
     * if it is successful.
     *
     * @return T|Result<T|E> The result of calling the given function with the current Result
     * object's value, or a new Result object with the same error if the current Result
     * object is a failure.
     *
     */
    public function flatMap($f): Result
    {
        if ($this->isSuccess()) {
            return $f($this->value);
        }
        return $this;
    }


    /**
     * Convert this Result to a Maybe. If this Result is a success, the Maybe will be a Just with the value of this Result.
     *
     * @return Maybe<T> The Maybe with the value of this Result, or Nothing if this Result is a failure.
     */
    public function toMaybe(): Maybe
    {
        if ($this->isSuccess()) {
            return Maybe::just($this->value);
        }
        return Maybe::nothing();
    }
};
