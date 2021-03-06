<?php
    namespace App\controllers;

    use App\models\Cart;
    use App\models\Item;

    class CartController extends \App\core\Controller {
        
        private $cart;
        private $item;

        function __construct() {
            $this->cart = new Cart();
            $this->item = new Item();
        }

        function format_cart_items(&$cart) {
            $items = explode(',', $cart['item_ids']);
            $item_amounts = explode(',', $cart['item_amounts']);

            $item_list = array();
            for ($i = 0; $i < count($items); ++$i) {
                $it = [];
                $user_item = $this->item->getByIndex($cart['user_id'], $items[$i]);
                $it['item_name'] = $user_item['item_name'];
                $it['price'] = floatval($user_item['price']);
                $it['amount'] = intval($item_amounts[$i]);
                $it['link'] = '/cart-shop/api/item/'.$items[$i];
                $item_list[] = $it;
            }

            $cart['items'] = $item_list;
            $cart['cart_status'] = $cart['status'];
            unset($cart['item_ids'], $cart['item_amounts'], $cart['status'], $cart['user_id']);
        }
        
        function get_all($user_id, $param = null) {
            $carts = $this->cart->getByUserID($user_id);

            for ($i = 0; $i < count($carts); ++$i ) {
                $carts[$i]['cart_id'] = $i;
                $this->format_cart_items($carts[$i]);
            }

            if (isset($param['client_id'])) {
                // filter out cart w/ none matching client_ids
            }

            $this->view('index', ['status' => http_response_code(), 'carts' => $carts]);
        }
        
        function get_one($user_id, $cart_index) {
            $carts = $this->cart->getByUserID($user_id);
            if (is_null($carts) || (count($carts) - 1) < $cart_index || $cart_index < 0) {
                $this->view('index', ['status' => http_response_code(), 'cart' => null]);
                return;
            } else {
                $cart = $carts[$cart_index];
                $cart['cart_id'] = $cart_index;
                $this->format_cart_items($cart);

                $this->view('index', ['status' => http_response_code(), 'cart' => $cart]);
            }
        }
        
        function insert($user_id, $item_id, $amount, $status, $client_id) {
            $item = $this->item->getByIndex($user_id, $item_id);
            if (is_null($item) || ($amount <= 0 || $amount > $item['stock']) ||
                !($status == "Preparing" || $status == "Completed" || $status == "Cancelled")) {
                $this->view('errors/400');
                return;
            }
            
            // If cart status is preparing or complete you reduce the stock from the original item
            // If the cart status is cancelled then return/don't take off the item amount to stock 
            if ($status == "Preparing" || $status == "Completed") {
                $this->item->update_stock($item['item_id'], $item['stock'] - $amount);
            }
            
            $this->cart->insert($user_id, $item_id, $amount, $status, $client_id);

            http_response_code(201);
            $this->view('index', ['status'=>http_response_code()]);
        }

        function update_status($user_id, $cart_index, $status) {
            $carts = $this->cart->getByUserID($user_id);
            if (is_null($carts) || (count($carts) - 1) < $cart_index || $cart_index < 0) {
                $this->view('errors/404');
            } else if (!($status == "Preparing" || $status == "Completed" || $status == "Cancelled")) {
                $this->view('errors/400');
            }

            $this->cart->update_status($carts[$cart_index]['cart_id'], $status);
            if ($carts[$cart_index]['status'] != "Cancelled" && $status == "Cancelled") {
                $cart = $carts[$cart_index];
                $cart_items = explode(',', $cart['item_ids']);
                $cart_amounts = explode(',', $cart['item_amounts']);
                for ($i = 0; $i < count($cart_items); ++$i) {
                    $item = $this->item->getByIndex($user_id, $cart_items[$i]);
                    $this->item->update_stock($item['item_id'], $item['stock'] + $cart_amounts[$i]);
                }                
            }
            
            $this->view('index', ['status'=>http_response_code(), 'message'=>'Cart Updated']);
        }

        function update_contents($user_id, $cart_index, $item_id, $amount) {
            $carts = $this->cart->getByUserID($user_id);
            $item = $this->item->getByIndex($user_id, $item_id);
            if (is_null($carts) || (count($carts) - 1) < $cart_index || $cart_index < 0) {
                $this->view('errors/404');
            } else if (is_null($item)) {
                $this->view('errors/404');
            }

            $cart = $carts[$cart_index];
            $cart_items = explode(',', $cart['item_ids']);
            $cart_amounts = explode(',', $cart['item_amounts']);
            if(in_array($item_id, $cart_items)) {
                //Update the amount
                for ($i = 0; $i < count($cart_items); ++$i) {
                    if ($cart_items[$i] == $item_id) {
                        if($amount <= 0) { // Remove from list     
                            $this->item->update_stock($item['item_id'], $item['stock'] + $cart_amounts[$i]);                       
                            unset($cart_items[$i], $cart_amounts[$i]);
                        } else {                            
                            if ($amount > $item['stock']) {
                                $this->view('errors/400');
                                return;
                            } else {
                                $this->item->update_stock($item['item_id'], $item['stock'] - $amount);
                                $cart_amounts[$i] = $amount;
                            }
                        }
                        break;
                    }
                }
            } else {
                // Add the item w/ amount
                if ($amount > $item['stock']) {
                    $this->view('errors/400');
                    return;
                } else {
                    $cart_items[] = $item_id;
                    $cart_amounts[] = $amount;
                }
            }

            $this->cart->update_contents($cart['cart_id'], implode(',', $cart_items), implode(',', $cart_amounts));
            
            $this->view('index', ['status'=>http_response_code(), 'message'=>'Cart Updated']);
        }

        function delete($user_id, $cart_index) {
            $carts = $this->cart->getByUserID($user_id);
            if (is_null($carts) || (count($carts) - 1) < $cart_index || $cart_index < 0) {
                $this->view('errors/404');
                return;
            }

            $this->cart->delete($carts[$cart_index]['cart_id']);
            $this->view('index', ['status'=>http_response_code(), 'message'=>'Cart Deleted']);
        }
    }        
?>