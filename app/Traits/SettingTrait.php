<?php

namespace App\Traits;

trait SettingTrait {
    public function getValue($collection, $key) {
        foreach ($collection as $key => $value) {
            if($value->key == $key) return $value->value;
        }
        return null;
    }

    public static function getDiscount($price, $discount) {
        $total_discount = floatval($price * ($discount / 100));
        return $price - $total_discount;
    }

    public function generateInvoice($prefix, $second = null, $third = null) {
        $str = $prefix;
        if($second) {
            $str .= $second;
        }
        if($third) {
            $str .= $third;
        }
        return $str;
    }
}
