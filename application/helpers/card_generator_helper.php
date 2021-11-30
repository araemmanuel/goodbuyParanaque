<?php


/**
* Generates a random credit card number from a prefix and length. This is useful
* when you need to test some specific brand numbers that you can't find out in
* the internet easily.
*/
class Card_Generator
{
    /**
     * Generates the random credit card number using the given prefix and
     * length. It uses default otherwise
     *
     * @param integer $prefix
     * @param integer $length
     *
     * @return string
     */
    public function single($prefix = null, $length = 16)
    {
        if ($length <= strlen($prefix)) {
            throw new \InvalidArgumentException(
                'The \'length\' parameter should be greater than \'prefix\' '.
                'string length'
            );
        }

        $number = $prefix . $this->getRand($length - strlen($prefix));

        return $number . (new LuhnCalculator())->verificationDigit($number);
    }

    /**
     * Generates the given amount of credit card numbers
     *
     * @param integer $amount
     * @param integer $prefix
     * @param integer $length
     *
     * @return integer[]
     */
    public function lot($amount, $prefix = null, $length = 16)
    {
        $numbers = [];
        for ($index = 1; $index <= $amount; $index++) {
            $numbers[] = $this->single($prefix, $length);
        }

        return $numbers;
    }

    /**
     * Retrieves a random number to put in the middle of card number
     *
     * Example:
     *     length = 5: Generates a number between 00000 and 99999
     *
     * @param integer $length
     *
     * @return integer
     */
    private function getRand($length)
    {
        $rand = '';
        for ($index = 1; $index < $length; $index++) {
            $rand .= rand(0, 9);
        }

        return $rand;
    }
}
class LuhnCalculator
{
    /**
     * Executes Luhn algorithm over the given number and return the sum. This
     * method does not include last digit of credit card number (verification
     * digit).
     *
     * @param string|integer $number
     *
     * @return integer
     */
    public function sum($number)
    {
        $numberArray = array_reverse(str_split($number));

        $sum = 0;
        for ($index = 0; $index < count($numberArray); $index++) {
            $digit = (int)$numberArray[$index];
            $sum += ($index % 2 == 0) ? $this->multiplyNumber($digit) : $digit;
        }

        return $sum;
    }

    /**
     * Retrives the corresponding verfication digit of the given credit card
     * number. If the verification digit is ten, returns zero
     *
     * @param string|integer $number
     *
     * @return integer
     */
    public function verificationDigit($number)
    {
        return 10 - ($this->sum($number) % 10 ?: 10);
    }

    /**
     * Multiplies number by two and decrease 9 if the number is greater than 10
     *
     * @param integer $number
     *
     * @return integer
     */
    private function multiplyNumber($number)
    {
        $result = $number * 2;

        return ($result >= 10) ? $result - 9 : $result;
    }
}
