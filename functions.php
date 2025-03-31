<?php
	require "config.php";
	// connects php with the MySQL server
	function dbConnect(){
        $mysqli = new mysqli(SERVER, USERNAME, PASSWORD, DATABASE);
        if($mysqli->connect_errno != 0){
           return FALSE;
        }else{
           return $mysqli;
        }
    }
    // checks if user is logged in, then returns the data associated with their profile
    function checkLogin(){
        $mysqli = dbConnect();
        if(isset($_SESSION['user_id'])){
            $id = $_SESSION['user_id'];
            $result = $mysqli->query("SELECT * FROM users WHERE id = '$id' LIMIT 1");
            if($result && mysqli_num_rows($result) > 0){
                $user_data = mysqli_fetch_assoc($result);
                return $user_data;
            } else {
                echo "User not found.";
                return null; // Ensure this returns null if no user is found
            }
        } else {
            echo "Session not set.";
            return null; // Return null if session is not set
        }
    }
	// products table every unique post id, then putting it into an array with the row of data corresponding to it 
	function getListings($int){
        $mysqli = dbConnect();
        $result = $mysqli->query("SELECT * FROM listings ORDER BY rand() LIMIT $int");
        while ($row = $result->fetch_assoc()){
           $data[] = $row;
        }
        return $data;
    }
    function joinUsersListings(){
        $mysqli = dbConnect();
        $sql = "SELECT listings.id AS listing_id, listings.title, listings.description, listings.images, listings.created_at, 
            users.id AS user_id, users.username, users.email, users.profile_pic, users.bio FROM listings
            JOIN users ON listings.owner_id = users.id";
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
            // Fetch the data as an associative array
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            // Return the result
            return $data;
        } else {
            return []; // Return an empty array if no data found
        }
    }
    
    function getListingsByProfile($user_id){
        $mysqli = dbConnect();
        $result = $mysqli->query("SELECT listings.title, listings.description, listings.images FROM listings WHERE listings.owner_id=$user_id");
        $data = [];
        while ($row = $result->fetch_assoc()){
            $data[] = $row;
        }
        return $data;
    }

    function getListingInformation($int){
        $mysqli = dbConnect();
        $result = $mysqli->query("SELECT listings.title, listings.description, listings.images, users.username, users.profile_pic, users.bio FROM listings, users WHERE users.id=$int");
        while ($row = $result->fetch_assoc()){
           $data[] = $row;
        }
        return $data;
    }

    function getListingByTitle($title){
        $mysqli = dbConnect();
	    $stmt = $mysqli->prepare("SELECT * FROM listings WHERE title = ?");
	    $stmt->bind_param("s", $title);
	    $stmt->execute();
	    $result = $stmt->get_result();
	    $data = $result->fetch_all(MYSQLI_ASSOC);
	    if(count($data) == 0){
	        header("Location: home.php");
	        exit();
	    }else{
	        return $data;
	    }
    }

    function getOwnerProfileByListing($owner_id){
        $mysqli = dbConnect();
        $sql = "SELECT users.username, users.profile_pic, users.bio FROM users WHERE $owner_id = users.id";
        $result = $mysqli->query($sql);
        while ($row = $result->fetch_assoc()){
           $data[] = $row;
        }
        return $data;
    }
