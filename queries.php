<?php

    header("Access-Control-Allow-Origin: *");
    require_once(__DIR__ . "/ideasoft/api.config.php");

    if(!isset($_POST)){
        die();
    }

    class Database{

        protected $dbh;

        protected function connect() {

            try {
                
                $this->dbh = new PDO("mysql:host=" . HOST . "; dbname=" . DBNAME . "; charset=utf8;", USER, PASS);
                
            } catch (PDOException $ex) {

                $ex->getMessage();

            }

        }

        protected function disconnect() {

            $this->dbh = NULL;

        }

    }

    class Queries extends Database {

        public function __construct() {

            parent::connect();

        }
        
        public function __destruct() {

            parent::disconnect();

        }

        public function getvariantImage($product_id, $variant_title) {

            $smtp = $this->dbh->prepare('SELECT image FROM variants WHERE parent=? and name LIKE "%' . $variant_title . '%"');
            $smtp->execute(array(
                $product_id
            ));
            $result = $smtp->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($result);

        }

    }

    $queries = new Queries();
    $queries->getvariantImage($_POST["variant"]["productId"], $_POST["variant"]["title"]);

?>