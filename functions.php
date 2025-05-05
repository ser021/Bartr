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
                header("Location: login.php?error=notloggedin");
                exit();
            }
        } else {
            header("Location: login.php?error=notloggedin");
            exit();
        }
    }
	// products table every unique post id, then putting it into an array with the row of data corresponding to it 
	function getListings($int, $user_id){
        $mysqli = dbConnect();
        $result = $mysqli->query("SELECT * FROM listings WHERE owner_id != $user_id ORDER BY rand() LIMIT $int");
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
    
    function addPost($user_id, $title, $description, $images = null){
        $mysqli = dbConnect();
        $stmt = $mysqli->prepare("INSERT INTO listings (owner_id, title, description, images, created_at VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("isss", $user_id, $title, $description, $images);
        return $stmt ->execute();
    }

    function deletePost($post_id, $user_id){
        $mysqli = dbConnect();
        $stmt = $mysqli->prepare("DELETE FROM listings WHERE id = ? AND owner_id = ?");
        $stmt->bind_param("ii", $post_id, $user_id);
        return $stmt ->execute();
    }

    function searchListings($searchTerm = '', $user_id = null){
        $mysqli = dbConnect();
        $sql = "SELECT * FROM listings WHERE (title LIKE ? OR description LIKE ?)";
        if ($user_id !== null){
            $sql .= "AND owner_id = ?";
        }
        $stmt = $mysqli->prepare($sql);

        if ($user_id !== null) {
            $searchTerm = "%$searchTerm%";
            $stmt->bind_param("ssi", $searchTerm, $user_id);
        } else {
            $searchTerm = "%$searchTerm%";
            $stmt->bind_param("ss", $searchTerm, $searchTerm);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
    function getProfileById($id) {
        $mysqli = dbConnect();
        $stmt = $mysqli->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        return $data;
    }
    // Check if a username already exists
function getUserByUsername($username) {
    $mysqli = dbConnect();
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Update the user's profile information
function updateUserProfile($user_id, $username, $bio, $profile_pic_path) {
    $mysqli = dbConnect();
    $stmt = $mysqli->prepare("UPDATE users SET username = ?, bio = ?, profile_pic = ? WHERE id = ?");
    $stmt->bind_param("sssi", $username, $bio, $profile_pic_path, $user_id);
    return $stmt->execute();
}
