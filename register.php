<?php

  session_start();

  // DATABASE CREDENTIAL
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'twitter';

  // CONNECT TO DATABASE
    $conn = new mysqli($host, $username, $password, $database);

  // CHECK CONNECTION
    if($conn -> connect_error) {
      echo 'Database connection failed.';
    }

  // CHECK IF THERE IS A POST REQUEST
    if($_SERVER['REQUEST_METHOD'] == "POST") {

      if($_POST['password'] == $_POST['confirmPassword']){
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        // WE SHOULD USE PASSWORD HASHING FOR PASSWORDS
        // password_hash(); accept 2 param, password and kind of encryption
          $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
        // ADD INFO TO THE DATABASE
          $sql = $conn -> prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
          $sql -> bind_param("ssss", $firstname, $lastname, $email, $password);
    
        // IF ALL INFO ARE CORRECT
          if($sql -> execute()) {
            header("Location: index.php");
          } else {
            $_SESSION['error'] = "Registration failed. Please try again.";
          }
      } else {
        $_SESSION['error'] = "Password did not match. Please try again.";
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
  <main class="login border rounded shadow-xl my-10 py-5 px-10 mx-auto max-w-[800px]
                flex flex-col">
    <!-- TITLE -->
    <div class="text-center mb-5">
      <p class="text-2xl">🐥</p>
      <h1 class="text-2xl font-bold">Twitter</h1>
      <small class="text-xs italic">Connect with your friends</small>
    </div>

    <hr>

    <!-- FORM -->
    <form class="mt-5 flex flex-col justify-center gap-3"
          action="register.php"
          method="POST">

      <p class="text-xl text-center font-bold">REGISTER</p>

      <!--  FIRTSNAME -->
      <div class="flex flex-col gap-2">
        <label  for="firstname"
                class="font-semibold">
          Firstname:
        </label>
        <input type="text"
                id="firstname"
                name="firstname" 
                placeholder="John"
                class="border rounded px-3 py-1 placeholder:italic outline-none"/>
      </div>

      <!-- LASTNAME -->
      <div class="flex flex-col gap-2">
        <label  for="lastname"
                class="font-semibold">
          Lastname:
        </label>
        <input type="text"
                id="lastname"
                name="lastname" 
                placeholder="Doe"
                class="border rounded px-3 py-1 placeholder:italic outline-none"/>
      </div>

      <!-- EMAIL -->
      <div class="flex flex-col gap-2">
        <label  for="email"
                class="font-semibold">
          Email:
        </label>
        <input type="text"
                id="email"
                name="email" 
                placeholder="johndoe@email.com"
                class="border rounded px-3 py-1 placeholder:italic outline-none"/>
      </div>

      <!-- PASSWORD -->
      <div class="flex flex-col gap-2">
        <label  for="password"
                class="font-semibold">
          Password:
        </label>
        <input type="password"
                id="password"
                name="password" 
                placeholder="••••••"
                class="border rounded px-3 py-1 placeholder:italic outline-none"/>
      </div>

      <!-- CONFIRM PASSWORD -->
      <div class="flex flex-col gap-2">
        <label  for="confirmPassword"
                class="font-semibold">
          Confirm Password:
        </label>
        <input type="password"
                id="confirmPassword"
                name="confirmPassword" 
                placeholder="••••••"
                class="border rounded px-3 py-1 placeholder:italic outline-none"/>
      </div>

      <div class="flex flex-col gap-2">
        <button class="bg-gray-950 rounded text-white font-semibold tracking-wide
                        py-1 mt-5 hover:translate-y-0.5 duration-300 hover:bg-gray-800"
                type="submit">
          Register
        </button>

          <?php
            if(isset($_SESSION['error'])) {
              echo '
                      <div class="bg-red-200 rounded text-center p-2 w-full text-xs mt-3 italic">
                        '.$_SESSION['error'].'
                      </div>
                    ';
              unset($_SESSION['error']);
            }
          ?>

        <a href="login.php"
          class="text-end hover:underline italic text-xs">
          Already have an account?
        </a>
      </div>
      
    </form>
  </main>

</body>
</html>