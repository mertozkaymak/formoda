<?php

    require_once(__DIR__ . "/api.connection.php");
    require_once(__DIR__ . "/api.config.php");

    class Variants extends Tokenizer{

        protected $variants = array();

        public function __construct() {

            $tokenizer = new Tokenizer();
            $status = $tokenizer->tokenController();

            if($status == 1){

                $page = 1;
                $total_pages = 0;
                $stop = 0;

                while ($stop == 0) {

                    $url = URL . "/api/products?limit=100&sort=id&page=" . $page;
                    $variables = array("Authorization: Bearer " . $tokenizer->acc, "Content-Type: application/json; charset=utf-8");

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HEADER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $variables);

                    $res = curl_exec($ch);
                    curl_close($ch);
                    
                    $response = explode("[{", $res, 2);

                    $header = $response[0];
                    $body = $response[1];

                    if($total_pages == 0){

                        $total_pages = floor(intval(trim(explode(":", explode("\n", $header)[6])[1])) / 100) + 1;
                        
                    }

                    if($page == $total_pages){

                        $stop = 1;

                    }
                    else{

                        $page++;
                        sleep(2);

                    }

                    $body = "[{" . $body;
                    $body = json_decode($body);
                    array_push($this->variants, $body);

                }

                $this->asyncVariants();
                
            }

        }

        public function asyncVariants() {

            $database = new Database();
            $database->connect();

            for ($page=0; $page < count($this->variants); $page++) {
             
                for ($variant=0; $variant < count($this->variants[$page]); $variant++) {

                    if(is_null($this->variants[$page][$variant]->parent)){

                        continue;

                    }
                    else{

                        if(count($this->variants[$page][$variant]->images) > 0){

                            $image = "https://formoda.com.tr/myassets/products/" . $this->variants[$page][$variant]->images[0]->directoryName . "/" . $this->variants[$page][$variant]->images[0]->filename . "." . $this->variants[$page][$variant]->images[0]->extension . "?revision=" . $this->variants[$page][$variant]->images[0]->revision;

                            $smtp = $database->dbh->prepare("SELECT * FROM variants WHERE iid=?");
                            $smtp->execute(array($this->variants[$page][$variant]->id));
                            $result = $smtp->fetchAll(PDO::FETCH_ASSOC);

                            if(count($result) > 0){

                                $smtp = $database->dbh->prepare("UPDATE variants SET name=?, image=? WHERE iid=?");
                                $smtp->execute(array(
                                    $this->variants[$page][$variant]->name,
                                    $image,
                                    $this->variants[$page][$variant]->id
                                ));
        
                            }
                            else{

                                $smtp = $database->dbh->prepare("INSERT INTO variants (iid, name, parent, image) VALUES (?, ?, ?, ?)");
                                $smtp->execute(array(
                                    $this->variants[$page][$variant]->id,
                                    $this->variants[$page][$variant]->name,
                                    $this->variants[$page][$variant]->parent->id,
                                    $image
                                ));
        
                            }

                        }

                    }

                }
            }

        }

    }

    new Variants();

?>