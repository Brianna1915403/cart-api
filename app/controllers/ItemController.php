<?php
    namespace App\controllers;

    use App\models\Item;

    class ItemController extends \App\core\Controller {
        
        private $item;

        function __construct() { $this->item = new Item(); }

        function index() {
            echo "ItemController";
            $this->view('index');
        }

        function get_all($user_id) {
            $items = $this->item->getByUserID($user_id);
            foreach ($items as &$item) {
                unset($item['item_id']);
                unset($item['user_id']);
            }
            
            $this->view('index', [
                'status' => http_response_code(),
                'items' => (!$items ? 'None Found' : $items)
            ]);
        }

        function get_one($user_id, $item_index) {
            $items = $this->item->getByUserID($user_id);
            foreach ($items as &$item) {
                unset($item['item_id']);
                unset($item['user_id']);
            }           
            
            $this->view('index', [
                'status' => http_response_code(),
                'item' => ((!$items || (count($items) - 1) < $item_index || $item_index < 0) ? null : $items[$item_index])
            ]);
        }

        function insert($user_id, $name, $desc, $price, $picture, $tag, $stock) {
            $this->item->insert($user_id, $name, $desc, $price, $picture, $tag, $stock);
            
            http_response_code(201);
            $this->view('index', ['status'=>http_response_code()]);
        }
    }        
?>