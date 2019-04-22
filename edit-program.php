<?php 
include 'jobs.php';
$db = getDb();

$priorities = ['Magas', 'Közepes', 'Alacsony'];

$successful_update = false;
if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($db, $_POST['id']);
    $priority = mysqli_real_escape_string($db, $_POST['priority']);
    $author = mysqli_real_escape_string($db, $_POST['author']);
    $date = mysqli_real_escape_string($db, $_POST['date']);

    $query = sprintf("UPDATE program SET prioritas='%s', author='%s', felvetel_datum='%s' WHERE id=%s",
            $priority, $author, $date, $id);

    mysqli_query($db, $query) or die(mysqli_error($db));
    $successful_update = true;
} else if (isset($_POST['delete']) || isset($_GET['delete'])) {
    $id = isset($_GET['delete']) ? $_GET['delete'] : $_POST['id'];
    $query = sprintf('DELETE FROM program WHERE id = %s', 
        mysqli_real_escape_string($db, $id));
    $ret = mysqli_query($db, $query) or die(mysqli_error($db));
    header("Location: programs.php");
    return;
}

include 'common_head.html';
?>
  <title>Program nyilvántartás – Program szerkesztése</title>
</head>
<body>
  <?php include 'menu.html'; ?>
  <div class="container main-content">
    <?php
      if (!isset($_GET['id'])) {
        header("Location: programs.php");
        return;
      }
      $id = mysqli_real_escape_string($db, $_GET['id']) or die(mysqli_error($db));
      $query = sprintf('SELECT id, prioritas, author, felvetel_datum FROM program WHERE id = %s', $id);
      $result = mysqli_query($db, $query);
      $row = mysqli_fetch_array($result);
      if (!$row) {
        header("Location: programs.php");
        return;
      }
      ?>
      <h1>Program szerkesztése: <span class="program-id"><?=$id?></span></h1>
      <?php if ($successful_update): ?>
        <p>
          <span class="badge badge-success">Program frissítése sikeres</span>
        </p>
      <?php endif; ?>
      <form action="" method="post">
        <input type="hidden" name="id" id="id" value="<?=$id?>">
        <div class="form-group">
          <label for="author" class="mr-2">Tulajdonos</label>
          <select class="custom-select" name="author">
            <?php
              $queryAuthors = 'SELECT id, CONCAT(vezeteknev, " ", keresztnev) AS nev FROM szemely ORDER BY vezeteknev';
              $resultAuthors = mysqli_query($db, $queryAuthors) or die(mysqli_error($db));
              while ($row2 = mysqli_fetch_array($resultAuthors)) {
                print('<option value="' . $row2['id'] . '" ' . ($row2['id'] == $row['author'] ? 'selected' : '') . '>' . $row2['nev'] . '</option>');
              }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label for="priority" class="mr-2">Prioritás</label>
          <select class="custom-select" name="priority">
            <?php
              for ($i = sizeof($priorities) - 1; $i >= 0; $i--) {
                print('<option value="' . $i . '" ' . ($row['prioritas'] == $i ? 'selected' : '') . '>' . $priorities[$i] . '</option>');
              }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label for="date" class="mr-2">Felvétel dátuma</label>
          <input class="form-control" type="date" name="date" value="<?=$row['felvetel_datum']?>">
        </div>
        <input class="btn btn-primary" name="update" type="submit" value="Mentés">
        <input class="btn btn-danger" name="delete" type="submit" value="Törlés">
      </form>

      <?php closeDb($db); ?>
  </div>
  <?php include 'common_footer.html'; ?>
