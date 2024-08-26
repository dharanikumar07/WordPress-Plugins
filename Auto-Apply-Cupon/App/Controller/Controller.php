<?php
namespace AAC\App\Controller;

class Controller {

    /**
     * Create a custom coupon and apply it to the cart
     *
     * @return void
     */
    public static function generateCouponCode() {
            $coupon_code = WC()->session->get('acc_coupon_code');
            // Check if the coupon code exists or needs to be regenerated
            if (!$coupon_code || !in_array($coupon_code, WC()->cart->get_applied_coupons())) {
                $length = 10;
                $characters = 'abcdefghijklmnopqrstuvwxyz1234567890';
                $coupon = substr(str_shuffle($characters), 0, $length);

                if (!in_array($coupon, WC()->cart->applied_coupons)) {
                    WC()->cart->applied_coupons[] = $coupon;
                    WC()->session->set("acc_coupon_code", $coupon);

                }
            }
    }

    /**
     * Get the coupon data based on the coupon code
     *
     * @param mixed $response The response from the applied coupon list
     * @param string $code The coupon code to match
     * @return array The coupon data or the original response if no match
     */
    public static function setCoupon($response, $code) {
        $coupon_code = WC()->session->get('acc_coupon_code');
        if ($code === $coupon_code) {
            return [
                'id' => 0,
                'amount' => 40,
                'discount_type' => 'percent',

            ];
        }

        return $response;
    }
}