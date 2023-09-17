<?php
require("connect.php");
require("bucket-list.php");

session_start();

$user_ID = $_SESSION['user_ID'];
// $user = getUserByID($user_ID);
$bucket_lists = displayUserBucketListsWithCategories($user_ID);
$current_list_id = null;
$bucket_list_items = null;
$categories = getCategories();

if ($user_ID == null) {
  http_response_code(404);
  include('404.php'); // provide your own HTML for the error page
  die();
}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if(ISSET($_POST['add-bucket-list']))
  {
    addBucketList($user_ID, $_POST['bucket_list_title']);
    $bucket_lists = displayUserBucketListsWithCategories($user_ID);
  }
  if(ISSET($_POST['update-bucket-list']))
  {
    editBucketList($_POST['bucket_list_ID'], $_POST['bucket_list_title'], $_POST['bucket_list_category_ID']);
    $bucket_lists = displayUserBucketListsWithCategories($user_ID);
  }
  if(ISSET($_POST['delete-bucket-list']))
  {
    deleteBucketList($_POST['bucket_list_ID']);
    $bucket_lists = displayUserBucketListsWithCategories($user_ID);
  }
  if(ISSET($_POST['bucket-list-title']))
  {
    $current_list_id = $_POST['bucket_list_ID'];
    $bucket_list_items = displayBucketListItems($_POST['bucket_list_ID']);
  }
  if(ISSET($_POST['sort-button']) && ISSET($_POST['current_list_id']))
  {
    $current_list_id = $_POST['current_list_id'];
    $bucket_list_items = displayBucketListItems($_POST['current_list_id'], $_POST['sort']);
  }
  if(ISSET($_POST['add-bucket-list-item']))
  {
    $current_list_id = $_POST['current_list_id'];
    addBucketListItem($_POST['current_list_id'], $_POST['bucket_list_item_name']);
    $bucket_list_items = displayBucketListItems($_POST['current_list_id']);
  }
  if(ISSET($_POST['update-bucket-list-item']))
  {
    $current_list_id = $_POST['current_list_id'];
    editBucketListItem($_POST['bucket_list_item_ID'], $_POST['bucket_list_item_name'], $_POST['description'], $_POST['cost'], $_POST['completed'], $_POST['location_name'], $_POST['street'], $_POST['city'], $_POST['state'], $_POST['zip_code'], $_POST['bucket_list_item_category_ID']);
    $bucket_list_items = displayBucketListItems($_POST['current_list_id']);
  }
  if(ISSET($_POST['delete-bucket-list-item']))
  {
    $current_list_id = $_POST['current_list_id'];
    deleteBucketListItem($_POST['bucket_list_item_ID']);
    $bucket_list_items = displayBucketListItems($_POST['current_list_id']);
  }
}
?>

<!-- 1. create HTML5 doctype -->
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">  
  
  <!-- 2. include meta tag to ensure proper rendering and touch zooming -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- 
  Bootstrap is designed to be responsive to mobile.
  Mobile-first styles are part of the core framework.
   
  width=device-width sets the width of the page to follow the screen-width
  initial-scale=1 sets the initial zoom level when the page is first loaded   
  -->
  
  <meta name="author" content="your name">
  <meta name="description" content="include some description about your page">  
    
  <title>Bucket Lists</title>
  
  <!-- 3. link bootstrap -->
  <!-- if you choose to use CDN for CSS bootstrap -->  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  
  <!-- you may also use W3's formats -->
  <!-- <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> -->
  
  <!-- 
  Use a link tag to link an external resource.
  A rel (relationship) specifies relationship between the current document and the linked resource. 
  -->
  
  <!-- If you choose to use a favicon, specify the destination of the resource in href -->
  <link rel="icon" type="image/png" href="https://www.cs.virginia.edu/~up3f/cs4750/images/db-icon.png" />
  
  <!-- if you choose to download bootstrap and host it locally -->
  <!-- <link rel="stylesheet" href="path-to-your-file/bootstrap.min.css" /> --> 
  
  <!-- include your CSS -->
  <!-- <link rel="stylesheet" href="custom.css" />  -->
       
</head>

<body>
  <div class="container">
    <div class="row mt-3 mx-3">
      <div class="col">
        <h1>Your Lists</h1> 
        <br>

        <!-- ADD bucket list trigger modal button -->
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#add-bucket-list-modal">+ New List</button>

        <!-- ADD bucket list modal -->
        <div class="modal fade" id="add-bucket-list-modal" tabindex="-1" aria-labelledby="add-bucket-list-modal-label" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">

              <div class="modal-header">
                <h5 class="modal-title" id="add-bucket-list-modal-label">Create New Bucket List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <form action="mainpage.php" method="POST">
                <div class="modal-body">   
                  <div class="row mb-3 mx-3">
                    Title:
                    <input type="text" class="form-control" name="bucket_list_title"/>        
                  </div> 
                </div> 

                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                  <button name="add-bucket-list" class="btn btn-primary" data-bs-dismiss="modal">Create List</button>
                </div>
              </form>
              
            </div>
          </div>
        </div>
        <!-- End ADD bucket list modal -->

        <!-- Display bucket lists and create UPDATE/DELETE trigger modal buttons -->
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Title</th>
              <th>Category</th>
              <th>Action</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach ($bucket_lists as $list): ?>
              <tr>
                <td>
                <form method="POST" action="mainpage.php">
                  <input type="hidden" name="bucket_list_ID" value="<?php echo $list['bucket_list_ID']?>" class="form-control"/>
                  <button type="submit" name="bucket-list-title" class="btn btn-outline-dark"><?php echo $list['bucket_list_title']; ?></button>
                </form>
                </td>

                <td><?php echo $list['category_name']; ?></td>
                <td><button class="btn btn-warning" data-bs-toggle="modal" type="button" data-bs-target="#update-bucket-list-modal<?php echo $list['bucket_list_ID']?>">Edit</button>
                <button class="btn btn-danger" data-bs-toggle="modal" type="button" data-bs-target="#delete-bucket-list-modal<?php echo $list['bucket_list_ID']?>">Delete</button></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <?php foreach ($bucket_lists as $list): ?>
          <!-- UPDATE bucket list modal -->
          <div class="modal fade" id="update-bucket-list-modal<?php echo $list['bucket_list_ID']?>" tabindex="-1" aria-labelledby="update-bucket-list-modal-label" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">

                <form method="POST" action="mainpage.php">
                  <div class="modal-header">
                    <h5 class="modal-title" id="update-bucket-list-modal-label">Update Bucket List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>

                  <div class="modal-body">
                    <div class="row mb-3 mx-3">
                      Title:
                      <input type="hidden" name="bucket_list_ID" value="<?php echo $list['bucket_list_ID']?>" class="form-control"/>
                      <input type="text" name="bucket_list_title" value="<?php echo $list['bucket_list_title']?>" class="form-control" required="required"/>
                    </div>
                    <div class="row mb-3 mx-3">
                      Select a Category (optional):
                      <select class="form-select" aria-label="Default select example" name="bucket_list_category_ID">
                        <option value=""></option>
                        <?php foreach ($categories as $cat): ?>
                          <option value="<?php echo $cat['category_ID']?>" <?php echo $list['category_ID'] == $cat['category_ID'] ? "selected" : ""; ?>><?php echo $cat['category_name']?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button name="update-bucket-list" class="btn btn-success">Update</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
          <!-- End UPDATE bucket list modal -->

          <!-- DELETE bucket list modal -->
          <div class="modal fade" id="delete-bucket-list-modal<?php echo $list['bucket_list_ID']?>" tabindex="-1" aria-labelledby="delete-bucket-list-modal-label" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">

                <form method="POST" action="mainpage.php">
                  <div class="modal-header">
                    <h5 class="modal-title">Delete Bucket List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>

                  <div class="modal-body">
                    <div class="row mb-3 mx-3">
                      Are you sure you want to delete this bucket list? This action cannot be undone.
                      <input type="hidden" name="bucket_list_ID" value="<?php echo $list['bucket_list_ID']?>" class="form-control"/>
                    </div>
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button name="delete-bucket-list" class="btn btn-danger">Delete</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
          <!-- End DELETE bucket list modal -->
        <?php endforeach; ?>
      </div>

      <div class="col">
        <h1>Bucket List</h1>
        <br>

        <!-- ADD bucket list item trigger modal button -->
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#add-bucket-list-item-modal" <?php echo ($current_list_id == null ? "disabled" : "enabled"); ?>>+ New Item</button>

        <!-- ADD bucket list item modal -->
        <div class="modal fade" id="add-bucket-list-item-modal" tabindex="-1" aria-labelledby="add-bucket-list-item-modal-label" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">

              <div class="modal-header">
                <h5 class="modal-title" id="add-bucket-list-item-modal-label">Create New Bucket List Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <form action="mainpage.php" method="POST">
                <div class="modal-body">   
                  <div class="row mb-3 mx-3">
                    Name:
                    <input type="text" class="form-control" name="bucket_list_item_name"/>        
                  </div> 
                </div> 

                <div class="modal-footer">
                  <input type="hidden" value="<?php echo $current_list_id; ?>" name="current_list_id" />
                  <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                  <button name="add-bucket-list-item" class="btn btn-primary" data-bs-dismiss="modal">Create Item</button>
                </div>
              </form>
              
            </div>
          </div>
        </div>
        <!-- End ADD bucket list item modal -->

        <!-- Sort button -->
        <form method="POST" action="mainpage.php">
          <select name="sort" id="sort">
            <option value="nameSortA">Name - ascending</option>
            <option value="nameSortD">Name - descending</option>
            <option value="completionSortA">Completion - incomplete first</option>
            <option value="completionSortD">Completion - complete first</option>
          </select>
          <input type="hidden" value="<?php echo $current_list_id; ?>" name="current_list_id" class="form-control"/>
          <button type="submit" name="sort-button" class="btn btn-outline-dark" <?php echo ($current_list_id == null ? "disabled" : "enabled"); ?>>Sort</button>
        </form>
        
        <!-- Display bucket lists items and create UPDATE/DELETE trigger modal buttons -->
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Name</th>
              <th>Description</th>
              <th>Cost</th>
              <th>Completed</th>
              <th>Location</th>
              <th>Category</th>
              <th>Action</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach ($bucket_list_items as $item): ?>
              <tr>
                <td><?php echo $item['bucket_list_item_name']; ?></td>
                <td><?php echo $item['description']; ?></td>
                <td><?php echo $item['cost']; ?></td>
                <td><?php echo $item['completed'] == "1" ? "Yes" : "No"; ?></td>
                <td><?php echo $item['location_name']; ?></td>
                <td><?php echo $item['category_name']; ?></td>
                <td><button class="btn btn-warning" data-bs-toggle="modal" type="button" data-bs-target="#update-bucket-list-item-modal<?php echo $item['bucket_list_item_ID']?>">Details</button>
                <button class="btn btn-danger" data-bs-toggle="modal" type="button" data-bs-target="#delete-bucket-list-item-modal<?php echo $item['bucket_list_item_ID']?>">Delete</button></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <!-- uses stored procedure (advanced sql), displays number of bucket list items in currently selected bucket list -->
        <?php echo ($current_list_id == null ? "" : "Item Count: " . displayUserBucketListItemCount($current_list_id)); ?>

        <?php foreach ($bucket_list_items as $item): ?>
          <!-- UPDATE bucket list item modal -->
          <div class="modal fade" id="update-bucket-list-item-modal<?php echo $item['bucket_list_item_ID']?>" tabindex="-1" aria-labelledby="update-bucket-list-item-modal-label" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">

                <form method="POST" action="mainpage.php">
                  <div class="modal-header">
                    <h5 class="modal-title" id="update-bucket-list-item-modal-label">Update Bucket List Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>

                  <div class="modal-body">
                    <div class="row mb-3 mx-3">
                      Name:
                      <input type="text" name="bucket_list_item_name" value="<?php echo $item['bucket_list_item_name']?>" class="form-control" required="required"/>
                      <br>
                      Description:
                      <input type="text" name="description" value="<?php echo $item['description']?>" class="form-control" />
                      <br>
                      Cost:
                      <input type="number" name="cost" value="<?php echo $item['cost']?>" class="form-control" />
                      <br>
                      Completed:
                      <select class="form-select" id="completed" name="completed">
                        <option value="completed">Completed</option>
                        <option value="incomplete" <?php echo $item['completed'] == "1" ? "" : "selected"; ?>>Incomplete</option>                    
                      </select>
                      <br>
                      Location name:
                      <input type="text" name="location_name" value="<?php echo $item['location_name']?>" class="form-control" />
                      Street:
                      <input type="text" name="street" value="<?php echo $item['street']?>" class="form-control" />
                      City:
                      <input type="text" name="city" value="<?php echo $item['city']?>" class="form-control" />
                      State:
                      <input type="text" name="state" value="<?php echo $item['state']?>" class="form-control" />
                      Zip Code:
                      <input type="number" name="zip_code" value="<?php echo $item['zip_code']?>" class="form-control" />
                      Select a Category (optional):
                      <select class="form-select" aria-label="Default select example" name="bucket_list_item_category_ID">
                        <option value=""></option>
                        <?php foreach ($categories as $cat): ?>
                          <option value="<?php echo $cat['category_ID']?>" <?php echo $item['category_ID'] == $cat['category_ID'] ? "selected" : ""; ?>><?php echo $cat['category_name']?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>

                  <div class="modal-footer">
                    <input type="hidden" name="bucket_list_item_ID" value="<?php echo $item['bucket_list_item_ID']?>" class="form-control"/>
                    <input type="hidden" value="<?php echo $current_list_id; ?>" name="current_list_id" />
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button name="update-bucket-list-item" class="btn btn-success">Update</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
          <!-- End UPDATE bucket list item modal -->

          <!-- DELETE bucket list item modal -->
          <div class="modal fade" id="delete-bucket-list-item-modal<?php echo $item['bucket_list_item_ID']?>" tabindex="-1" aria-labelledby="delete-bucket-list-item-modal-label" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">

                <form method="POST" action="mainpage.php">
                  <div class="modal-header">
                    <h5 class="modal-title">Delete Bucket List Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>

                  <div class="modal-body">
                    <div class="row mb-3 mx-3">
                      Are you sure you want to delete this bucket list item? This action cannot be undone.
                      <input type="hidden" name="bucket_list_item_ID" value="<?php echo $item['bucket_list_item_ID']?>" class="form-control"/>
                    </div>
                  </div>

                  <div class="modal-footer">
                    <input type="hidden" value="<?php echo $current_list_id; ?>" name="current_list_id" />
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button name="delete-bucket-list-item" class="btn btn-danger">Delete</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
          <!-- End DELETE bucket list item modal -->
        <?php endforeach; ?>
      </div>

      <!-- Can uncomment if we want the page to have 3 columns like in the proposal.
      Might be easier to have Details open in a modal when a bucket list item is clicked -->
      <!-- <div class="col">
        <h1>Details</h1>
      </div> -->
      <!-- <?php var_dump($bucket_list_items); ?> -->

    </div>
  </div>



  <!-- CDN for JS bootstrap -->
  <!-- you may also use JS bootstrap to make the page dynamic -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  
  <!-- for local -->
  <!-- <script src="your-js-file.js"></script> -->  
  
  <!-- the script below ensures that the form isn't resubmitted on page refresh -->
  <script>
  if(window.history.replaceState) 
  {
    window.history.replaceState(null, null, window.location.href);
  }
  </script>
 
</body>
</html>