<?php
	require "config.php";
    /**
	 * The dbConnect function connects php with the MySQL server.
	 * @param  The function does not take any parameters.
	 * @return The function returns the mysqli object on success, or false on failure.
	 */	
	function dbConnect(){
        $mysqli = new mysqli(SERVER, USERNAME, PASSWORD, DATABASE);
        if($mysqli->connect_errno != 0){
           return FALSE;
        }else{
           return $mysqli;
        }
    }
    /**
	 * The function is fetching from the products table every unique post id, then putting it into an array with the row of data corresponding to it 
	 */	
	function getListings($int){
        $mysqli = dbConnect();
        $result = $mysqli->query("SELECT * FROM listings ORDER BY rand() LIMIT $int");
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
