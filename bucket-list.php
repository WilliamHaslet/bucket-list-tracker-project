<?php
// currently unused, will probably be used once signup/login are implemented
function getUserByID($user_ID)
{
    global $db;
    $query = "SELECT * FROM users WHERE user_ID = :user_ID";
    $statement = $db->prepare($query);
    $statement->bindValue(':user_ID', $user_ID);
    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();
    return $result;
}

function getCategories()
{
    global $db;
    $query = "SELECT * FROM categories";
    $statement = $db->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll(); //fetchAll() gets all rows, fetch() gets one row
    $statement->closeCursor();
    return $result;
}

// deprecated, using displayUserBucketListsWithCategories($user_ID) instead, but keeping here just in case
function displayUserBucketLists($user_ID)
{
    global $db;
    $query = "SELECT * FROM bucket_lists NATURAL JOIN user_has NATURAL JOIN users WHERE user_ID = :user_ID";
    $statement = $db->prepare($query);
    $statement->bindValue(':user_ID', $user_ID);
    $statement->execute();
    $result = $statement->fetchAll(); //fetchAll() gets all rows, fetch() gets one row
    $statement->closeCursor();
    return $result;
}

function displayUserBucketListsWithCategories($user_ID)
{
    global $db;
    $query = "SELECT * FROM users NATURAL JOIN user_has NATURAL JOIN bucket_lists LEFT JOIN is_categorized_as USING (bucket_list_ID) LEFT JOIN categories USING (category_ID) WHERE user_ID = :user_ID";
    $statement = $db->prepare($query);
    $statement->bindValue(':user_ID', $user_ID);
    $statement->execute();
    $result = $statement->fetchAll(); //fetchAll() gets all rows, fetch() gets one row
    $statement->closeCursor();
    return $result;
}

function addBucketList($user_ID, $bucket_list_title)
{
    global $db;
    $query1 = "INSERT INTO bucket_lists (bucket_list_title) VALUES (:bucket_list_title)";
    $query2 = "INSERT INTO user_has VALUES (:user_ID, LAST_INSERT_ID())";
    try{
        $statement1 = $db->prepare($query1);
        $statement1->bindValue(':bucket_list_title', $bucket_list_title);
        $statement1->execute();
        $statement1->closeCursor();
        
        $statement2 = $db->prepare($query2);
        $statement2->bindValue(':user_ID', $user_ID);
        $statement2->execute();
        $statement2->closeCursor();
    }
    catch (PDOException $e){
        //echo $e->getMessage();
        if ($statement->rowCount() == 0)
        {
            echo "Failed to add this bucket list </br>";
        }
    }
    catch (Exception $e){
        echo $e->getMessage();
    }
}

function editBucketList($bucket_list_ID, $bucket_list_title, $category_ID)
{
    global $db;
    // query1 updates the fields that are in bucket_lists
    $query1 = "UPDATE bucket_lists SET bucket_list_title=:bucket_list_title WHERE bucket_list_ID=:bucket_list_ID";
    $statement1 = $db->prepare($query1);
    $statement1->bindValue(":bucket_list_title", $bucket_list_title);
    $statement1->bindValue(":bucket_list_ID", $bucket_list_ID);
    $statement1->execute();
    $statement1->closeCursor();

    // query2 checks if the current item has already been categorized (it exists in the is_categorized_as table)
    $query2 = "SELECT COUNT(*) FROM is_categorized_as WHERE bucket_list_ID = :bucket_list_ID";
    $statement2 = $db->prepare($query2);
    $statement2->bindValue(':bucket_list_ID', $bucket_list_ID);
    $statement2->execute();
    $is_categorized = $statement2->fetch();
    $statement2->closeCursor();
    
    if($is_categorized[0] == 0){ // item has not been categorized, insert it into the is_categorized_as table
        $query3 = "INSERT INTO is_categorized_as (bucket_list_ID, category_ID) VALUES (:bucket_list_ID, :category_ID)";
        $statement3 = $db->prepare($query3);
        $statement3->bindValue(":bucket_list_ID", $bucket_list_ID);
        $statement3->bindValue(":category_ID", $category_ID);
        $statement3->execute();
        $statement3->closeCursor();
    }

    else // item has been categorized, so update its category
    {
        if($category_ID == null) // user selected to categorize item as null, delete it from the is_categorized_as table
        {
            $query4 = "DELETE FROM is_categorized_as WHERE bucket_list_ID=:bucket_list_ID";
            $statement4 = $db->prepare($query4);
            $statement4->bindValue(':bucket_list_ID', $bucket_list_ID);
            $statement4->execute();
            $statement4->closeCursor();
        }

        else // user selected to put item in a new category, update the is_categorized_as table
        {
            $query5 = "UPDATE is_categorized_as SET category_ID=:category_ID WHERE bucket_list_ID=:bucket_list_ID";
            $statement5 = $db->prepare($query5);
            $statement5->bindValue(":bucket_list_ID", $bucket_list_ID);
            $statement5->bindValue(":category_ID", $category_ID);
            $statement5->execute();
            $statement5->closeCursor();
        }
    }
}

function deleteBucketList($bucket_list_ID)
{
    global $db;
    $query1 = "DELETE FROM item_in WHERE bucket_list_ID=:bucket_list_ID";
    $query2 = "DELETE FROM is_categorized_as WHERE bucket_list_ID=:bucket_list_ID";
    $query3 = "DELETE FROM bucket_lists WHERE bucket_list_ID=:bucket_list_ID";

    $statement1 = $db->prepare($query1);
    $statement1->bindValue(':bucket_list_ID', $bucket_list_ID);
    $statement1->execute();
    $statement1->closeCursor();

    $statement2 = $db->prepare($query2);
    $statement2->bindValue(':bucket_list_ID', $bucket_list_ID);
    $statement2->execute();
    $statement2->closeCursor();

    $statement3 = $db->prepare($query3);
    $statement3->bindValue(':bucket_list_ID', $bucket_list_ID);
    $statement3->execute();
    $statement3->closeCursor();
}

function displayBucketListItems($bucket_list_ID, $sort="none")
{
    global $db;
    $query = "SELECT * FROM bucket_list_items NATURAL JOIN item_in NATURAL JOIN bucket_lists LEFT JOIN is_type USING(bucket_list_item_ID) LEFT JOIN categories USING(category_ID) LEFT JOIN is_located_in USING(bucket_list_item_ID) LEFT JOIN locations USING(location_ID) WHERE bucket_list_ID = :bucket_list_ID";
    if ($sort == "nameSortA") {
        $query .= " ORDER BY bucket_list_item_name ASC";
    }
    elseif ($sort == "nameSortD") {
        $query .= " ORDER BY bucket_list_item_name DESC";
    }
    elseif ($sort == "completionSortA") {
        $query .= " ORDER BY completed ASC";
    }
    elseif ($sort == "completionSortD") {
        $query .= " ORDER BY completed DESC";
    }

    $statement = $db->prepare($query);
    $statement->bindValue(':bucket_list_ID', $bucket_list_ID);
    $statement->execute();
    $result = $statement->fetchAll(); //fetchAll() gets all rows, fetch() gets one row
    $statement->closeCursor();
    return $result;
}

function addBucketListItem($bucket_list_ID, $bucket_list_item_name)
{
    global $db;
    $query1 = "INSERT INTO bucket_list_items (bucket_list_item_name, completed) VALUES (:bucket_list_item_name, false)";
    $query2 = "INSERT INTO item_in VALUES (:bucket_list_ID, LAST_INSERT_ID())";
    try{
        $statement1 = $db->prepare($query1);
        $statement1->bindValue(':bucket_list_item_name', $bucket_list_item_name);
        $statement1->execute();
        $statement1->closeCursor();
        
        $statement2 = $db->prepare($query2);
        $statement2->bindValue(':bucket_list_ID', $bucket_list_ID);
        $statement2->execute();
        $statement2->closeCursor();
    }
    catch (PDOException $e){
        //echo $e->getMessage();
        if ($statement->rowCount() == 0)
        {
            echo "Failed to add this bucket list item </br>";
        }
    }
    catch (Exception $e){
        echo $e->getMessage();
    }
}

function editBucketListItem($bucket_list_item_ID, $bucket_list_item_name, $description, $cost, $completed, $location_name, $street, $city, $state, $zip_code, $category_ID)
{
    global $db;
    // query1 updates the fields that are in bucket_list_items
    $query1 = "UPDATE bucket_list_items SET bucket_list_item_name=:bucket_list_item_name, description=:description, cost=:cost, completed=:completed WHERE bucket_list_item_ID=:bucket_list_item_ID";
    $statement1 = $db->prepare($query1);
    $statement1->bindValue(":bucket_list_item_name", $bucket_list_item_name);
    $statement1->bindValue(":description", $description);
    $statement1->bindValue(":cost", intval($cost));
    $statement1->bindValue(":completed", $completed == "completed" ? 1 : 0);
    $statement1->bindValue(":bucket_list_item_ID", $bucket_list_item_ID);
    $statement1->execute();
    $statement1->closeCursor();

    // LOCATION

    // check if location info cleared
    $clear_location = $location_name == null AND
                      $street == null AND
                      $city == null AND
                      $state == null AND
                      $zip_code == null;

    if ($clear_location) {
        $query2 = "DELETE FROM locations WHERE location_ID = (SELECT location_ID FROM locations NATURAL JOIN is_located_in WHERE bucket_list_item_ID=:bucket_list_item_ID)";
        $statement2 = $db->prepare($query2);
        $statement2->bindValue(':bucket_list_item_ID', $bucket_list_item_ID);
        $statement2->execute();
        $statement2->closeCursor();
    }
    elseif ($location_name != null) { // update location only if location name is present
        $query2 = "SELECT COUNT(*) FROM is_located_in WHERE bucket_list_item_ID = :bucket_list_item_ID";
        $statement2 = $db->prepare($query2);
        $statement2->bindValue(':bucket_list_item_ID', $bucket_list_item_ID);
        $statement2->execute();
        $has_location = $statement2->fetch();
        $statement2->closeCursor();
        if ($has_location[0] == 0) { // has no existing location info
            $query3 = '';
            $statement3 = null;
            if ($zip_code == null) {
                $query3 = "INSERT INTO locations (location_name, street, city, state) VALUES (:location_name, :street, :city, :state)";
                $statement3 = $db->prepare($query3);
            }
            else {
                $query3 = "INSERT INTO locations (location_name, street, city, state, zip_code) VALUES (:location_name, :street, :city, :state, :zip_code)";
                $statement3 = $db->prepare($query3);
                $statement3->bindValue(":zip_code", intval($zip_code));
            }
            $statement3->bindValue(":location_name", $location_name);
            $statement3->bindValue(":street", $street);
            $statement3->bindValue(":city", $city);
            $statement3->bindValue(":state", $state);
            $statement3->execute();
            $statement3->closeCursor();

            $query4 = "INSERT INTO is_located_in VALUES (:bucket_list_item_ID, LAST_INSERT_ID())";
            $statement4 = $db->prepare($query4);
            $statement4->bindValue(":bucket_list_item_ID", $bucket_list_item_ID);
            $statement4->execute();
            $statement4->closeCursor();
        }
        else {
            $query3 = "UPDATE locations NATURAL JOIN is_located_in SET location_name=:location_name, street=:street, city=:city, state=:state, zip_code=:zip_code WHERE bucket_list_item_ID=:bucket_list_item_ID";
            $statement3 = $db->prepare($query3);
            if ($zip_code == null) {
                $statement3->bindValue(":zip_code", null, PDO::PARAM_NULL);
            }
            else {
                $statement3->bindValue(":zip_code", intval($zip_code));
            }
            $statement3->bindValue(":bucket_list_item_ID", $bucket_list_item_ID);
            $statement3->bindValue(":location_name", $location_name);
            $statement3->bindValue(":street", $street);
            $statement3->bindValue(":city", $city);
            $statement3->bindValue(":state", $state);
            $statement3->execute();
            $statement3->closeCursor();
        }

    }

    // CATEGORY

    if ($category_ID != '') {
        // query2 checks if the current item has already been categorized (it exists in the is_type table)
        $query2 = "SELECT COUNT(*) FROM is_type WHERE bucket_list_item_ID = :bucket_list_item_ID";
        $statement2 = $db->prepare($query2);
        $statement2->bindValue(':bucket_list_item_ID', $bucket_list_item_ID);
        $statement2->execute();
        $is_categorized = $statement2->fetch();
        $statement2->closeCursor();
        
        if($is_categorized[0] == 0){ // item has not been categorized, insert it into the is_type table
            $query3 = "INSERT INTO is_type (bucket_list_item_ID, category_ID) VALUES (:bucket_list_item_ID, :category_ID)";
            $statement3 = $db->prepare($query3);
            $statement3->bindValue(":bucket_list_item_ID", $bucket_list_item_ID);
            $statement3->bindValue(":category_ID", $category_ID);
            $statement3->execute();
            $statement3->closeCursor();
        }
        else // item has been categorized, so update its category
        {
            if($category_ID == null) // user selected to categorize item as null, delete it from the is_type table
            {
                $query4 = "DELETE FROM is_type WHERE bucket_list_item_ID=:bucket_list_item_ID";
                $statement4 = $db->prepare($query4);
                $statement4->bindValue(':bucket_list_item_ID', $bucket_list_item_ID);
                $statement4->execute();
                $statement4->closeCursor();
            }

            else // user selected to put item in a new category, update the is_type table
            {
                $query5 = "UPDATE is_type SET category_ID=:category_ID WHERE bucket_list_item_ID=:bucket_list_item_ID";
                $statement5 = $db->prepare($query5);
                $statement5->bindValue(":bucket_list_item_ID", $bucket_list_item_ID);
                $statement5->bindValue(":category_ID", $category_ID);
                $statement5->execute();
                $statement5->closeCursor();
            }
        }
    }
}

function deleteBucketListItem($bucket_list_item_ID)
{
    global $db;
    $query1 = "DELETE FROM item_in WHERE bucket_list_item_ID=:bucket_list_item_ID";
    $query2 = "DELETE FROM bucket_list_items WHERE bucket_list_item_ID=:bucket_list_item_ID";

    $statement1 = $db->prepare($query1);
    $statement1->bindValue(':bucket_list_item_ID', $bucket_list_item_ID);
    $statement1->execute();
    $statement1->closeCursor();

    $statement2 = $db->prepare($query2);
    $statement2->bindValue(':bucket_list_item_ID', $bucket_list_item_ID);
    $statement2->execute();
    $statement2->closeCursor();
}

// currently unused, uses stored procedure (advanced sql), wrote this before writing displayUserBucketListItemCount()
// can display it somewhere if we want
function displayTotalUsersCount()
{
    global $db;
    $query = "CALL get_users_count()";
    $statement = $db->prepare($query);
    $statement->execute();
    $result = $statement->fetch()[0];
    $statement->closeCursor();
    return $result;
}

// uses stored procedure (advanced sql)
function displayUserBucketListItemCount($bucket_list_ID)
{
    global $db;
    $query = "CALL get_bucket_list_items_count(?)";
    $statement = $db->prepare($query);
    $statement->bindValue(1, $bucket_list_ID, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetch()[0];
    $statement->closeCursor();
    return $result;
}

// currently unused, might not need
function getBucketListByTitle($bucket_list_title)
{
    global $db;
    $query = "SELECT * FROM bucket_lists WHERE bucket_list_title = :bucket_list_title";
    $statement = $db->prepare($query);
    $statement->bindValue(':bucket_list_title', $bucket_list_title);
    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();
    return $result;
}

?>