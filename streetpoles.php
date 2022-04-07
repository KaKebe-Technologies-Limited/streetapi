<?php
// headers

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once('config/Database.php');

// model section 
class Post{
    // db stuffs
    private $conn;
  

    // event properties
    
    public $street;
    public $lat;
    public $lon;
    public $ads_description;
    public $date_created;
    public $date_updated;
    public $ad_owner;
    public $expiry_date;

    // constructor with DB
    public function __construct($db){
        $this->conn = $db;
    }
    // get post

    public function read(){
        $querry = 'SELECT 
        street,
        lat,
        lon,
        ads_description,
        date_created,
        date_updated,
        ad_owner,
        expiry_date

        FROM poles';

        // prepare statement
        $stmt = $this->conn->prepare($querry);

        // execute querry
        $stmt->execute();

        return $stmt;
        

    }
 
    
}

// end of model section 

// post section 


//instantiate db

$database = new Database();
$db = $database->connect();


// instantiate the blog post
$post = new Post($db);


// blog post querry
$result = $post->read();
// get row count
$num = $result->rowCount();

// check if any post
if($num >0){
    // post array
    $post_arr = array();
    $posts_arr['data'] = array();
    
    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $post_item = array(
            'street' => $street,
            'lat' => $lat,
            'lon' => $lon,
            'ads_description' => $ads_description,
            'date_created' => $date_created,
            'date_updated' => $date_updated, 
            'ad_owner' => $ad_owner,
            'expiry_date' => $expiry_date

        );

        // push to data

        array_push($posts_arr['data'], $post_item); 
    }

    // turn the data to json 
    echo json_encode($posts_arr);

}else{
    // no posts
    echo json_encode(
        array('message' => 'No Posts Found')
    );
}

