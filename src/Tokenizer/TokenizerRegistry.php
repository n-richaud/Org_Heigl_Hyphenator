<?php
/**
 * Copyright (c) 2008-2011 Andreas Heigl<andreas@heigl.org>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @category   Hyphenation
 * @package    Org\Heigl\Hyphenator
 * @subpackage Tokenizer
 * @author     Andreas Heigl <andreas@heigl.org>
 * @copyright  2008-2011 Andreas Heigl<andreas@heigl.org>
 * @license    http://www.opensource.org/licenses/mit-license.php MIT-License
 * @version    2.0.1
 * @link       http://github.com/heiglandreas/Hyphenator
 * @since      04.11.2011
 */

namespace Org\Heigl\Hyphenator\Tokenizer;

use Countable;
use Iterator;

/**
 * This class provides a registry for storing multiple Tokenizers
 *
 * @category   Hyphenation
 * @package    Org\Heigl\Hyphenator
 * @subpackage Tokenizer
 * @author     Andreas Heigl <andreas@heigl.org>
 * @copyright  2008-2011 Andreas Heigl<andreas@heigl.org>
 * @license    http://www.opensource.org/licenses/mit-license.php MIT-License
 * @version    2.0.1
 * @link       http://github.com/heiglandreas/Hyphenator
 * @since      04.11.2011
 */
class TokenizerRegistry implements Iterator, Countable
{
    /**
     * Storage for the Tokenizers.
     *
     * @var Tokenizer[] $_registry
     */
    private $registry = array();

    /**
     * Add an item to the registry
     *
     * @param Tokenizer $tokenizer The tokeniter to be added
     *
     * @return TokenizerRegistry
     */
    public function add(Tokenizer $tokenizer)
    {
        if (! in_array($tokenizer, $this->registry)) {
            $this->registry[] = $tokenizer;
        }

        return $this;
    }

    /**
     * Get a dictionary entry by its key
     *
     * @param mixed $key The key to get the tokenizer for.
     *
     * @return Tokenizer|null
     */
    public function getTokenizerWithKey($key)
    {
        if (array_key_exists($key, $this->registry)) {
            return $this->registry[$key];
        }

        return null;
    }

    /**
     * Cleanup the registry
     *
     * @return TokenizerRegistry
     */
    public function cleanup()
    {
        $this->registry = array();

        return $this;
    }

    /**
     * Pass the given string through the given tokenizers
     *
     * @param string|TokenRegistry $string The String to be tokenized
     *
     * @return TokenRegistry
     */
    public function tokenize($string)
    {
        if (! $string instanceof TokenRegistry) {
            $wt = new WordToken($string);
            $string = new TokenRegistry();
            $string->add($wt);
        }
        foreach ($this as $tokenizer) {
            $string = $tokenizer->run($string);
        }

        return $string;
    }

    /**
     * Implementation of Iterator
     *
     * @see Iterator::rewind()
     *
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function rewind()
    {
        reset($this->registry);
    }

    /**
     * Get the current object
     *
     * @see Iterator::current()
     *
     * @return Tokenizer
     */
    #[\ReturnTypeWillChange]
    public function current()
    {
        return current($this->registry);
    }

    /**
     * Get the current key
     *
     * @see Iterator::key()
     *
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function key()
    {
        return key($this->registry);
    }

    /**
     * Get the number of items in the registry
     *
     * @see Countable::count()
     *
     * @return int
     */
    #[\ReturnTypeWillChange]
    public function count()
    {
        return count($this->registry);
    }

    /**
     * Push the internal pointer forward one step
     *
     * @see Iterator::next()
     *
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function next()
    {
        next($this->registry);
    }

    /**
     * Check whether the current pointer is in a valid place
     *
     * @see Iterator::valid()
     *
     * @return boolean
     */
    #[\ReturnTypeWillChange]
    public function valid()
    {
        if (false === current($this->registry)) {
            return false;
        }

        return true;
    }
}
