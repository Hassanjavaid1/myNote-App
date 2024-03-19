<?php
$server = "localhost";
$username = "root";
$password = "";
$db = "NoteApp";
$connect = mysqli_connect($server, $username, $password, $db);
$update = false;
$create = false;

if (isset($_POST["title"], $_POST["desc"])) {
  $title = $_POST['title'];
  $description = $_POST['desc'];

  $insertData = "INSERT INTO `mynotes`(`title`,`description`) VALUES ('$title','$description')";
  $result = mysqli_query($connect, $insertData);
  if ($result) {
    $create = true;
  };
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['update'], $_POST['edit_title'], $_POST['edit_description'])) {
    $sno = $_POST['update'];
    $updateTitle = $_POST['edit_title'];
    $updateDescription = $_POST['edit_description'];
    $update = "UPDATE `mynotes` SET `title` = '$updateTitle' , `description` = '$updateDescription' WHERE `mynotes`.`sno` = $sno";
    $updateQuery = mysqli_query($connect, $update);

    if ($updateQuery) {
      $update = true;
    } else {
      echo "We could not update the record successfully";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>myNotes | Hassanjavaid</title>
</head>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<body>

  <nav class="flex items-center justify-around gap-[10rem] bg-[#040b14] py-4 text-white">
    <div class="flex items-center space-x-4">
      <h3 class="bg-[#7277AD] text-black px-4 font-bold py-1 rounded-[5rem]">PHP</h3>
      <li class="list-none"><a href="#">Home</a></li>
      <li class="list-none"><a href="#">About</a></li>
      <li class="list-none"><a href="#">Contact Us</a></li>
    </div>
    <div>
      <input type="search" name="search" class="bg-white px-[1rem] py-2 rounded-lg focus:outline-none text-black" placeholder="Search">
      <button type="button" class="bg-transparent border border-green-600 text-green-600 py-2 px-2 rounded-lg hover:bg-green-600 hover:text-white">Search</button>
    </div>
  </nav>
  <?php
  if ($update) {
    echo '<div class="bg-green-400 py-3 indent-5 rounded-[0.2rem]" id="success">Note has been Updated successfully!</div>';
  }
  if ($create) {

    echo '<div class="bg-green-400 py-3 indent-5 rounded-[0.2rem]" id="success">Note has been created successfully!</div>';
  } ;


  ?>

  <main class="flex flex-col mt-[2rem]">
    <div class="flex flex-col justify-center mx-auto w-[70%] gap-y-3">

      <h1 class="text-[1.6rem] font-bold">Add a Note to myNotes.</h1>
      <form action="index.php" method="post" class="flex flex-col justify-center gap-y-3">
        <label for="title" class="font-bold">Note Title</label>
        <input type="text" name="title" id="title" placeholder="Add a title" class="border border-[#6d706d] py-1 indent-2 rounded-md focus:outline-none">
        <label for="desc" class="font-bold">Note Description</label>
        <textarea name="desc" id="desc" cols="30" rows="3" class="border indent-2 border-[#6d706d] rounded-md focus:outline-none"></textarea>
        <button type="submit" class="text-left bg-green-600 border  hover:bg-transparent hover:border-green-600 hover:text-green-500 w-fit py-2 px-2 rounded-md cursor-pointer">Add Note</button>
      </form>
    </div>


    <div class="container text-center w-[70%] mt-5">
      <div class=" w-max mb-1">
        Search: <input type="search" name="searchEntries" id="searchEntries" class="border focus:outline-none hover:rounded-sm indent-2">

      </div>
      <table class="table  border-t-black border-[1px]">
        <thead>
          <tr>
            <th scope="col">S.No</th>
            <th scope="col">Title</th>
            <th scope="col">Description</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>

        <?php
        $select = "SELECT * FROM `mynotes`";
        $query = mysqli_query($connect, $select);
        if (isset($_POST["delete"])) {
          $id_to_delete = $_POST['delete'];
          $delete = "DELETE FROM `mynotes` WHERE `mynotes`.`sno` = $id_to_delete";
          $delete_query = mysqli_query($connect, $delete);
          if ($delete_query) {

            echo '<div class="bg-red-400 py-3 indent-5 rounded-[0.2rem]" id="success">Note has been deleted successfully!</div>';
          }
        }
        $num = 0;
        while ($row = mysqli_fetch_assoc($query)) {
          $num = $num + 1;

          echo " <tbody>
            <tr>
              <th scope='row'> " . $num . "</th>
              <td>" . $row['title'] . " </td>
              <td>" . $row['description'] . " </td> 
              <form action='index.php' method='POST'>
                <td><button class='border border-[grey] rounded-md bg-blue-500 text-white py-1 px-1' value='" . $row['sno'] . "' name='delete'>Delete</button>
                <button class='edit-btn bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600' value='" .  $row['sno'] . "' name='update'>Edit</button>
                
                </td>
                </form>
            </tr>
          </tbody>";
        }
        ?>
      </table>
    </div>
    <!-- Edit Modal -->
    <div id="modal-bg" class="fixed top-0 left-0 w-full h-full bg-gray-900 bg-opacity-50 hidden">
      <div class="flex justify-center items-center w-full h-full">
        <div id="modal" class="bg-white p-8 rounded shadow-md w-1/3">
          <h2 class="text-lg font-bold mb-4">Edit Note</h2>
          <form action="index.php" method="POST">
            <input type="hidden" id="update-sno" name="update">
            <div class="mb-4">
              <label for="edit-title" class="block text-sm font-semibold mb-2">Title:</label>
              <input type="text" id="edit-title" name="edit_title" class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
              <label for="edit-description" class="block text-sm font-semibold mb-2">Description:</label>
              <textarea id="edit-description" name="edit_description" class="w-full border rounded px-3 py-2"></textarea>
            </div>
            <div class="flex justify-end">
              <button type="button" id="cancel-edit" class="text-gray-500 mr-4">Cancel</button>
              <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
  setTimeout(() => {
    let success = document.getElementById('success');
    let fail = document.getElementById('fail')
    success.style.display = 'none';
    fail.style.display = 'none';
  }, 3000);

  // Handling Edit Modal
  const modalBg = document.getElementById('modal-bg');
  const modal = document.getElementById('modal');
  const editBtns = document.querySelectorAll('.edit-btn'); // Selecting all edit buttons
  const cancelBtn = document.getElementById('cancel-edit');
  let editTitleInput = document.getElementById('edit-title');
  let editDescriptionInput = document.getElementById('edit-description');

  editBtns.forEach(editBtn => {
    editBtn.addEventListener('click', function(event) {
      modalBg.classList.remove('hidden');
      const row = this.closest('tr'); // Get the parent row
      const title = row.cells[1].innerText; // Extract title data from the row
      const description = row.cells[2].innerText; // Extract description data from the row
      editTitleInput.value = title; // Fill title input in modal
      editDescriptionInput.value = description; // Fill description input in modal

      // Set sno value to a hidden field for submission
      document.getElementById('update-sno').value = this.value;

      event.preventDefault();
    });
  });

  cancelBtn.addEventListener('click', function() {
    modalBg.classList.add('hidden');
  });
</script>

</html>