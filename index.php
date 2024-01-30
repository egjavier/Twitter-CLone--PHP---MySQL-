<?php

  session_start();

  // CHECK OF THERE IS SESSION OR IF USER IS LOGGED-IN
    if (!isset($_SESSION['user_id'])) {
      header('Location: login.php');
    }

  // DATABASE CREDENTIAL
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'twitter';

  // CONNECT TO DATABASE
    $conn = new mysqli($host, $username, $password, $database); 
      
  // FUNCTION TO DISPLAY TWEETS
    function displaytwets() {

      global $conn;

      $sql = "SELECT * FROM tweets ORDER BY date_posted DESC";
      $result = $conn -> query($sql);

      // YOU CAN JOIN TABLES TO GET THE USERS
        // $sql = "SELECT * FROM tweets 
        //         JOIN users
        //         ON tweets.user_id - users.id
        //         ORDER BY date_posted DESC";

      if($result -> num_rows > 0) {

        while($row = $result -> fetch_assoc()) {

          // PERFORM ANOTHER QUERY TO GET USER NAME 
            $sql2 = $conn -> prepare("SELECT firstname, lastname FROM users WHERE id =  ?" );
            $sql2 -> bind_param('s', $row['user_id']);
            $sql2 -> execute();

            $result2 = $sql2->get_result();
            $result2 = $result2 -> fetch_object();


          echo '<div class="border p-5 rounded mb-3">';
            echo  '<p class="text-gray-600 font-bold">'.$result2 -> firstname." ".$result2 -> lastname.'</p>';
            echo '<small class="text-xs text-gray-600">'.date_format(date_create($row['date_posted']), "g:i A F j, Y").'</small>';
            echo '<p class="font-semibold">'.$row['body'].'</p>';
          echo  '</div>';
        }
      } else {
        echo '<p class="italic text-gray-500 text-sm text-center mt-20">No tweets Available.</p>';
      }


    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- TAILWIND -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            clifford: '#da373d',
          }
        }
      }
    }
  </script>

  <title>Twitter Clone</title>
</head>
<body class="mx-2">
  <main class="login border rounded shadow-xl my-10 p-5 mx-auto max-w-[1000px] min-h-[90vh]
                flex flex-col">
    <!-- TITLE -->
    <div class="text-center mb-5">
      <p class="text-3xl">🐥</p>
      <h1 class="text-3xl font-bold">Twitter</h1>
      <small class="text-xs italic">Connect with your friends</small>
    </div>

    <!-- FORM -->
    <div class="border my-5 p-5 rounded">
      <div class="flex justify-between items-center">
        <p class="text-lg text-start font-bold">Welcome, 
            <?php echo $_SESSION['firstname']." ".$_SESSION['lastname'] ?>
          ! 👋
        </p>
        <a href="logout.php" class="hover:underline text-sm font-semibold cursor-pointer">LOGOUT</a>
      </div>

      <form action="newtweet.php" 
            method="POST" 
            class="mt-3 w-full flex flex-col gap-3">
        <input type="text"
                name="tweet"
                placeholder="What's on your mind? 💭"
                class="p-3 placeholder:italic border outline-none rounded"/>
        <div class="text-end">
          <button class="bg-gray-950 text-white px-5 py-1 rounded w-fit font-semibold tracking-wide
                        hover:translate-y-0.5 duration-200 hover:bg-gray-800 text-sm">
            Tweet
          </button>
        </div>
      </form>
    </div>

    <!-- TWEETS -->  
      <?php
        displaytwets();
      ?>

  </main>

</body>
</html>