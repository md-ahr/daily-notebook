<?php
    // connect to database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "daily_notes";

    // create a connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Database connection error: " . mysqli_connect_error());
    } else {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $title = $_POST['title'];
            $description = $_POST['description'];
            if ($title == '' || $description == '') {
                echo '<div class="alert alert-danger text-center alert-dismissible fade show d-block w-50 mx-auto mt-5" role="alert">
                        <strong>Please filled all the required fields</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            } else {
                if (isset($_POST['id'])) {
                    $id = $_POST['id'];
                    $sql = "UPDATE `notes` SET `title` = '$title', `description` = '$description', `updated_date` = date('') WHERE `id` = '$id'";
                    $result = mysqli_query($conn, $sql);
                    if ($result) {
                        echo '<div class="alert alert-success text-center alert-dismissible fade show d-block w-50 mx-auto mt-5" role="alert">
                            <strong>Note updated successfully</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                    } else {
                        echo '<div class="alert alert-danger text-center alert-dismissible fade show d-block w-50 mx-auto mt-5" role="alert">
                            <strong>Note can not updated successfully</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                    }
                } else {
                    $sql = "INSERT INTO `notes` (`title`, `description`) VALUES ('$title', '$description')";
                    $result = mysqli_query($conn, $sql);
                    if ($result) {
                        echo '<div class="alert alert-success text-center alert-dismissible fade show d-block w-50 mx-auto mt-5" role="alert">
                            <strong>Note saved successfully</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                    } else {
                        echo '<div class="alert alert-danger text-center alert-dismissible fade show d-block w-50 mx-auto mt-5" role="alert">
                            <strong>Note can not saved successfully</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                    }
                }
            }
        }

        if (isset($_GET['delete'])) {
            $id = $_GET['delete'];
            $sql = "DELETE FROM `notes`  WHERE `id` = '$id'";
            global $conn;
            $result = mysqli_query($conn, $sql);
            if ($result) {
                echo '<div class="alert alert-success text-center alert-dismissible fade show d-block w-50 mx-auto mt-5" role="alert">
                            <strong>Note deleted successfully</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
            } else {
                echo '<div class="alert alert-danger text-center alert-dismissible fade show d-block w-50 mx-auto mt-5" role="alert">
                            <strong>Note can not deleted successfully</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
            }
        }

        $sqlGetNotes = "SELECT * FROM `notes`";
        $resultGetNotes = mysqli_query($conn, $sqlGetNotes);
        $notes = mysqli_num_rows($resultGetNotes);
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Daily Notes - PHP CRUD</title>
        <link rel="icon" type="image/x-icon" href="./public/favicon.jpg">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    </head>
    <body>

        <h1 class="text-center text-dark border-bottom w-50 pb-4 mt-5 mx-auto">Daily NoteBook</h1>

        <div class="w-75 mx-auto my-4">
            <div class="text-center mt-5 mb-4">
                <button type="button" class="btn btn-primary text-white fw-semibold" style="padding: 12px 30px;" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" class="bi bi-plus-lg" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z"/>
                    </svg>
                    Add Note
                </button>
            </div>
            <?php if (!empty($notes)) { ?>
                <table id="note-table" class="display text-center">
                    <thead>
                        <tr>
                            <th class="text-center">SL No.</th>
                            <th class="text-center">Title</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">Created Date</th>
                            <th class="text-center">Updated Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php while($notes = mysqli_fetch_assoc($resultGetNotes)) { ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td class="js--title-<?= $notes['id'] ?>"><?= $notes['title'] ?></td>
                                <td class="js--description-<?= $notes['id'] ?>"><?= $notes['description'] ?></td>
                                <td><?= date_format(new DateTimeImmutable($notes['created_date']), "dS F, Y (h:i:sa)") ?></td>
                                <td><?= date_format(new DateTimeImmutable($notes['updated_date']), "dS F, Y (h:i:sa)") ?></td>
                                <td>
                                    <button type="button" class="btn text-info p-0 js--btn-edit" data-bs-toggle="modal" data-bs-target="#editModal" data-id="<?= $notes['id'] ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                        </svg>
                                    </button>
                                    <button type="button" class="btn text-danger p-0 ms-3 js--btn-delete" data-id="<?= $notes['id'] ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p class="text-center lead fw-semibold text-secondary">No data available</p>
            <?php } ?>
        </div>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                        <div class="modal-header">
                            <h5 class="modal-title font-semibold">Add a note</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group w-100">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" class="form-control py-2" />
                            </div>
                            <div class="form-group my-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" rows="4" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-warning fw-semibold px-4 py-2" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary fw-semibold px-4 py-2 ms-3">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                        <input type="hidden" name="id" id="js--note-id" />
                        <div class="modal-header">
                            <h5 class="modal-title font-semibold">Update a note</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group w-100">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" class="js--note-title form-control py-2" />
                            </div>
                            <div class="form-group my-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" rows="4" class="js--note-description form-control"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-warning fw-semibold px-4 py-2" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary fw-semibold px-4 py-2 ms-3">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.1.slim.min.js" integrity="sha256-w8CvhFs7iHNVUtnSP0YKEg00p9Ih13rlL9zGqvLdePA=" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function () {
                $('#note-table').DataTable();
            });
            $('.js--btn-edit').on('click', function() {
                const id = $(this).data('id');
                $('#js--note-id').val(id);
                $('.js--note-title').val($('.js--title-'+id).text());
                $('.js--note-description').val($('.js--description-'+id).text());
            });
            $('.js--btn-delete').on('click', function() {
                const id = $(this).data('id');
                if (confirm('Press ok to delete this note')) {
                    window.location = `/daily-notes/index.php?delete=${id}`;
                }
            });
        </script>
    </body>
</html>