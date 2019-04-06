<?php
/*
NOTICE OF LICENSE

This source file is subject to the NekloEULA that is bundled with this package in the file ICENSE.txt.

It is also available through the world-wide-web at this URL: http://store.neklo.com/LICENSE.txt

Copyright (c)  Neklo (http://store.neklo.com/)
*/


class Neklo_ProductPosition_Helper_String extends Mage_Core_Helper_String
{
    /**
     * Parse query string to array
     *
     * @param string $str
     * @return array
     */
    public function parseQueryStr($str)
    {
        $argSeparator = '&';
        $result = array();
        $partsQueryStr = explode($argSeparator, $str);

        foreach ($partsQueryStr as $partQueryStr) {
            if ($this->_validateQueryStr($partQueryStr)) {
                $param = $this->_explodeAndDecodeParam($partQueryStr);
                $param = $this->_handleRecursiveParamForQueryStr($param);
                $result = $this->_appendParam($result, $param);
            }
        }
        return $result;
    }

    /**
     * Validate query pair string
     *
     * @param string $str
     * @return bool
     */
    protected function _validateQueryStr($str)
    {
        if (!$str || (strpos($str, '=') === false)) {
            return false;
        }
        return true;
    }

    /**
     * Prepare param
     *
     * @param string $str
     * @return array
     */
    protected function _explodeAndDecodeParam($str)
    {
        $preparedParam = array();
        $param = explode('=', $str);
        $preparedParam['key'] = urldecode(array_shift($param));
        $preparedParam['value'] = urldecode(array_shift($param));

        return $preparedParam;
    }

    /**
     * Append param to general result
     *
     * @param array $result
     * @param array $param
     * @return array
     */
    protected function _appendParam(array $result, array $param)
    {
        $key   = $param['key'];
        $value = $param['value'];

        if ($key) {
            if (is_array($value) && array_key_exists($key, $result)) {
                $helper = $this->getArrayHelper();
                $result[$key] = $helper->mergeRecursiveWithoutOverwriteNumKeys($result[$key], $value);
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * Handle param recursively
     *
     * @param array $param
     * @return array
     */
    protected function _handleRecursiveParamForQueryStr(array $param)
    {
        $value = $param['value'];
        $key = $param['key'];

        $subKeyBrackets = $this->_getLastSubkey($key);
        $subKey = $this->_getLastSubkey($key, false);
        if ($subKeyBrackets) {
            if ($subKey) {
                $param['value'] = array($subKey => $value);
            } else {
                $param['value'] = array($value);
            }
            $param['key'] = $this->_removeSubkeyPartFromKey($key, $subKeyBrackets);
            $param = $this->_handleRecursiveParamForQueryStr($param);
        }

        return $param;
    }

    /**
     * Get last part key from query array
     *
     * @param string $key
     * @param bool $withBrackets
     * @return string
     */
    protected function _getLastSubkey($key, $withBrackets = true)
    {
        $subKey = '';
        $leftBracketSymbol  = '[';
        $rightBracketSymbol = ']';

        $firstPos = strrpos($key, $leftBracketSymbol);
        $lastPos  = strrpos($key, $rightBracketSymbol);

        if (($firstPos !== false || $lastPos !== false)
            && $firstPos < $lastPos
        ) {
            $keyLenght = $lastPos - $firstPos + 1;
            $subKey = substr($key, $firstPos, $keyLenght);
            if (!$withBrackets) {
                $subKey = ltrim($subKey, $leftBracketSymbol);
                $subKey = rtrim($subKey, $rightBracketSymbol);
            }
        }
        return $subKey;
    }
}