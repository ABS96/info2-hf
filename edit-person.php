<?php 
include 'jobs.php';
$db = getDb();

$successful_update = false;
if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($db, $_POST['id']);
    $vezeteknev = mysqli_real_escape_string($db, $_POST['vezeteknev']);
    $keresztnev = mysqli_real_escape_string($db, $_POST['keresztnev']);
    $titulus = mysqli_real_escape_string($db, $_POST['titulus']);
    if (!$vezeteknev or !$keresztnev) {
        die('A személynek rendelkeznie kell vezeték- és keresztnévvel!');
    } else {
        $query = sprintf("UPDATE szemely SET vezeteknev='%s', keresztnev='%s', titulus='%s' WHERE id=%s",
                $vezeteknev, $keresztnev, $titulus, $id);

        mysqli_query($db, $query) or die(mysqli_error($db));
        $successful_update = true;
    }

} else if (isset($_POST['delete']) || isset($_GET['delete'])) {
    $id = isset($_GET['delete']) ? $_GET['delete'] : $_POST['id'];
    $query1 = sprintf('DELETE FROM program WHERE author = %s', 
        mysqli_real_escape_string($db, $id));
    $query2 = sprintf('DELETE FROM szemely WHERE id = %s', 
        mysqli_real_escape_string($db, $id));
    $ret1 = mysqli_query($db, $query1) or die(mysqli_error($db));
    $ret2 = mysqli_query($db, $query2) or die(mysqli_error($db));
    $_SESSION['person_deleted'] = true;
    header("Location: personnel.php");
    return;
}

include 'common_head.html';
?>
  <title>Program nyilvántartás – Személy szerkesztése</title>
</head>
<body>
  <?php include 'menu.html'; ?>
  <div class="container main-content">
    <?php
      if (!isset($_GET['id'])) {
          header("Location: personnel.php");
          return;
        }
      $id = $_GET['id'];
      $query = sprintf('SELECT id, vezeteknev, keresztnev, titulus FROM szemely WHERE id = %s', mysqli_real_escape_string($db, $id)) or die(mysqli_error($db));
      $result = mysqli_query($db, $query);
      $row = mysqli_fetch_array($result);
      if (!$row) {
        header("Location: personnel.php");
        return;
      }
      ?>
      <h1>Személy szerkesztése</h1>
      <?php if ($successful_update): ?>
        <div class="alert alert-success">Személy adatai frissítve</div>
      <?php endif; ?>
      <form action="" method="post">
        <input type="hidden" name="id" id="id" value="<?=$id?>">
        <div class="form-group">
          <label for="vezeteknev">Vezetéknév</label>
          <input type="text" required class="form-control" name="vezeteknev" id="vezeteknev" value="<?=$row['vezeteknev']?>">
        </div>
        <div class="form-group">
          <label for="keresztnev">Keresztnév</label>
          <input type="text" required class="form-control" name="keresztnev" id="keresztnev" value="<?=$row['keresztnev']?>">
        </div>
        <div class="form-group">
          <label for="titulus">Titulus</label>
          <div class="radio">
            <input type="radio" name="titulus" value="hallgató"
              <?=($row['titulus']=='hallgató'?'checked':'')?>
            ><label class="ml-1">hallgató</label>
          </div>
          <div class="radio">
            <input type="radio" name="titulus" value="oktató"
              <?=($row['titulus']=='oktató'?'checked':'')?>
            ><label class="ml-1">oktató</label>
          </div>
        </div>
        <input class="btn btn-primary" name="update" type="submit" value="Mentés">
        <input class="btn btn-danger" name="delete" type="submit" value="Törlés">
      </form>

      <?php closeDb($db); ?>
  </div>
  <?php include 'common_footer.html'; ?>
