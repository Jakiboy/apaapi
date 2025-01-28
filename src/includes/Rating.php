<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Product Advertising API Library (v5)
 * @version   : 1.3.x
 * @copyright : (c) 2019 - 2025 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

declare(strict_types=1);

namespace Apaapi\includes;

/**
 * @deprecated Use Scrapper Class instead
 */
final class Rating extends Scrapper
{
    /**
     * Get item rating data.
     *
     * @access public
     * @return array
     */
    public function get() : array
    {
        return $this->getRating();
    }
}
