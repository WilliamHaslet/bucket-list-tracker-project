<?php
require("connect.php");

function userExists($username)
{
    global $db;
    $query = "SELECT * FROM users WHERE username = :username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $result = $statement->fetchColumn() > 0;
    $statement->closeCursor();
    return $result;
}

function usernameToID($username)
{
    global $db;
    $query = "SELECT user_ID FROM users WHERE username = :username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();
    return $result["user_ID"];
}

function tryLogin($username, $password)
{
    global $db;
    $query = "SELECT password FROM users WHERE username = :username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $storedHash = $statement->fetch()["password"];

    $result = crypt($password, $storedHash) == $storedHash;

    $statement->closeCursor();
    return $result;
}

function createUser($username, $password)
{
    global $db;
    
    try
    {
        $salt= uniqid(mt_rand(), true);
        $hashed = crypt($password, '$5$rounds=5000$' . $salt);

        $query = "INSERT INTO users (user_name, username, password) VALUES (\"\", :username, :hashed)";
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':hashed', $hashed);
        $statement->execute();
        $statement->closeCursor();
    }
    catch (PDOException $e)
    {
        echo $e->getMessage();
    }
    catch (Exception $e)
    {
        echo $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (ISSET($_POST['login-user']))
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $blank = ($username == "") || ($password == "");

        if ($blank) // Blank fields
        {
            echo '<script>alert("A field is blank")</script>';
        }
        else if (tryLogin($username, $password)) // Login success
        {
            session_start();
            $_SESSION['user_ID'] = usernameToID($username);
            header("Location: https://www.cs.virginia.edu/~wlh4dh/mainpage.php");
        }
        else // Login fails
        {
            echo '<script>alert("Incorrect username or password")</script>';
        }
    }

    if (ISSET($_POST['create-user']))
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $blank = ($username == "") || ($password == "");

        if ($blank) // Blank fields
        {
            echo '<script>alert("A field is blank")</script>';
        }
        else if (userExists($username)) // User already exists
        {
            echo '<script>alert("User already exists")</script>';
        }
        else // Create user and login
        {
            createUser($username, $password);
            tryLogin($username, $password);
            session_start();
            $_SESSION['user_ID'] = usernameToID($username);
            header("Location: https://www.cs.virginia.edu/~wlh4dh/mainpage.php");
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="include some description about your page">  

  <title>Bucket List Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="icon" type="image/png" href="https://www.cs.virginia.edu/~up3f/cs4750/images/db-icon.png" />
</head>

<body>
    <h1>Bucket Lists</h1>

    <form method="POST" action="loginpage.php">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" name="username">
        </div>
        <div class="form-group">
            <label for="pwd">Password:</label>
            <input type="password" class="form-control" name="password">
        </div>
        <button type="submit" name="login-user" class="btn btn-primary">Login</button>
        <button type="submit" name="create-user" class="btn btn-secondary">Create User</button>
    </form> 

    <script>
        if(window.history.replaceState) 
        {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>
