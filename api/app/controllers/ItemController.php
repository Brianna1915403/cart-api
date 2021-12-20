<?php
    namespace App\controllers;

    use App\core\Token;
    use App\models\Item;
    use Aws\S3\S3Client; 
    use Dotenv\Dotenv;
    use Exception;

    class ItemController extends \App\core\Controller {
        
        private $item;
        private $aws;
        private $dotenv;

        function __construct() { 
            $this->item = new Item(); 
            // To access the .env file
            $this->dotenv = Dotenv::createImmutable(getcwd());
            $this->dotenv->load();
            $this->aws = new S3Client([
                'region'  => 'us-east-1',
                'version' => 'latest',
                'credentials' => [
                    'key'    => $_ENV['S3_KEY'],
                    'secret' => $_ENV['SECRET_KEY'],
                ]
            ]);
        }

        function put_in_cdn($picture) {
            // Sanitizing the picture name as to reduce the risk of AWS errors
            $sanitized_url = Token::get_uniqid(5)."_".preg_replace('/[^a-z0-9\.]/', '', basename(strtolower($picture)));
            try {
                // Insert image into CDN
                $result = $this->aws->putObject([
                    'Bucket' => $_ENV['S3_BUCKET'],
                    'Key'    => $sanitized_url,
                    'SourceFile' => $picture	
                ]);
                return $sanitized_url;
            } catch (Exception $e) {
                http_response_code(500);
                $this->view('index', ['status'=>http_response_code(), 'message'=>'Internal Server Error: Operation Not Completed']);
                return null;
            }
        }

        function get_from_cdn(&$item) {
            try {
                // Retrieve image from CDN
                $command = $this->aws->getCommand('GetObject', [
                    'Bucket' => $_ENV['S3_BUCKET'],
                    'Key' => $item['picture']
                ]);
            
                $request = $this->aws->createPresignedRequest($command, '+5 minutes');
                $url = (string)$request->getUri();
                $item['picture'] = $url;
            } catch (Exception $e) {
                http_response_code(500);
                $this->view('index', ['status'=>http_response_code(), 'message'=>'Internal Server Error: Could Not Retrieve Item']);
                return false;
            }
        }

        function delete_from_cdn($item) {
            try {
                // Delete an image from CDN
                $result = $this->aws->deleteObject([
                    'Bucket' => $_ENV['S3_BUCKET'],
                    'Key'    => $item['picture']
                ]);

            } catch (Exception $e) {
                http_response_code(500);
                $this->view('index', ['status'=>http_response_code(), 'message'=>'Internal Server Error: Operation Not Completed']);
                return false;
            }
        }

        function format_items(&$items) {
            if (is_null($items)) {
                return;
            }
            // Reassiging the item_id to match what the user enters in the url so it makes more sense contextually. 
            // Unseting the user_id so the user does not have access to critical database information.
            for ($i = 0; $i < count($items); ++$i) {
                $items[$i]['item_id'] = $i;
                $items[$i]['price'] = floatval($items[$i]['price']);
                $items[$i]['stock'] = intval($items[$i]['stock']);
                unset($items[$i]['user_id']); // This is dumb... I could have just changed the query...
                if (!is_null($items[$i]['picture'])) {
                    $this->get_from_cdn($items[$i]);   
                }
            }
        }
        
        function format_item(&$item) {
            if (is_null($item)) {
                return;
            }

            $item['item_id'] = $item['item_index'];
            $item['price'] = floatval($item['price']);
            $item['stock'] = intval($item['stock']);
            unset($item['user_id']); // This is dumb... I could have just changed the query...
            if (!is_null($item['picture'])) {
                $this->get_from_cdn($item);   
            }
        }
        
        function get_all($user_id) {
            $items = $this->item->getByUserID($user_id);
            
            $this->format_items($items);            

            $this->view('index', [
                'status' => http_response_code(),
                'items' => (!$items ? 'None Found' : $items)
            ]);
        }

        function get_one($user_id, $item_index) {
            $item = $this->item->getByIndex($user_id, $item_index);
            
            $this->format_item($item);

            $this->view('index', [
                'status' => http_response_code(),
                'item' => ($item)
            ]);
        }

        function insert($user_id, $name, $desc, $price, $picture, $tag, $stock) {

            $S3_pic_name = null;            
            if (!is_null($picture) && !empty($picture)) {                
                $S3_pic_name = $this->put_in_cdn($picture);
            }

            $items = $this->item->getByUserID($user_id);
            $item_index = $items[count($items) - 1]['item_index'] + 1;

            $this->item->insert(
                $user_id, 
                $item_index,
                $name, 
                $desc, 
                is_numeric($price)? floatval($price) : 0.00, 
                $S3_pic_name, 
                $tag, 
                is_numeric($stock)? intval($stock) : 0
            );
            
            http_response_code(201);
            $this->view('index', ['status'=>http_response_code()]);
        }

        function update($user_id, $item_index, $name = null, $desc = null, $price = null, $picture = null, $tag = null, $stock = null) {
            $item = $this->item->getByIndex($user_id, $item_index);
            
            if (is_null($item)) {
                $this->view('errors/404');
                return;
            }

            $item_id = $item['item_id'];

            if (!is_null($name) && !empty($name)) {
                $this->item->update_item_name($item_id, $name);
            }

            if (!is_null($desc)) {
                $this->item->update_description($item_id, $desc);
            }

            if (!is_null($price)) {
                $this->item->update_price($item_id, $price);
            }

            if (!is_null($picture)) {
                if (!is_null($item['picture'])) {
                    if (!$this->delete_from_cdn($item)) {
                        return;
                    }
                }
                $pic = null;
                if (!empty($picture)) {
                    $pic = $this->put_in_cdn($picture);
                }
                $this->item->update_picture($item_id, $pic);
            }

            if (!is_null($tag)) {
                $this->item->update_tag($item_id, $tag);
            }

            if (!is_null($stock)) {
                $this->item->update_stock($item_id, $stock);
            }

            $this->view('index', ['status'=>http_response_code(), 'message'=>'Item Updated']);
        }

        function delete($user_id, $item_index) {
            $item = $this->item->getByIndex($user_id, $item_index);
            if (!$item) {
                $this->view('errors/404');
                return;
            } else {                
                if (!is_null($item['picture'])) {
                    if (!$this->delete_from_cdn($item)) {
                        return;
                    }
                }

                $this->item->delete($item['item_id']);
                $this->view('index', ['status'=>http_response_code(), 'message'=>'Item Deleted']);
            }
        }
    }        
?>