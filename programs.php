<?php 
include 'jobs.php';
$db = getDb();

$priorities = ['Magas', 'Közepes', 'Alacsony'];

$added = false;
if (isset($_POST['add'])) {
  $author = mysqli_real_escape_string($db, $_POST['author']);
  $priority = mysqli_real_escape_string($db, $_POST['priority']);

  $queryAddProgram = sprintf('INSERT INTO program(author, prioritas, felvetel_datum) VALUES ("%s", "%s", "%s")',
    $author,
    $priority,
    date('Y-m-d')
  );
  mysqli_query($db, $queryAddProgram) or die(mysqli_error($db));
  $added = true;
}

include 'common_head.html';
?>
  <title>Program nyilvántartás – Programok</title>
</head>
<body>
  <?php include 'menu.html'; ?>
  <div class="container main-content">
    <h1>Programok kezelése</h1>
    <?php if ($added): ?>
      <p>
        <span class="badge badge-success">Új program hozzáadva</span>
      </p>
    <?php endif; ?>
    <form class="form-inline" method="post">
      <div class="card mb-3">
        <div class="card-header">
          Szűrés
        </div>
        <div class="card-body">
          <div class="form-group mb-3">
            <label for="search_priority" class="mr-2">Prioritás</label>
            <select class="custom-select" name="search_priority">
              <option <?=!isset($_POST['search_priority']) || $_POST['search_priority'] === '–' ? 'selected' : ''?>>–</option>
              <?php
                for ($i = sizeof($priorities) - 1; $i >= 0; $i--) {
                  print('<option value="' . $i . '"' . (isset($_POST['search_priority']) && $_POST['search_priority'] == $i && $_POST['search_priority'] != '–' ? ' selected' : '') .'>' . $priorities[$i] . '</option>');
                }
              ?>
            </select>
          </div>
          <div class="form-group mb-3">
            <label for="search_name" class="">Felhasználó neve</label>
            <input class="form-control ml-2" type="search" name="search_name" value="<?=isset($_POST['search']) ? $_POST['search_name'] : ''?>">
          </div>
          <div class="form-group mb-3">
            <div class="custom-control custom-switch">
              <input type="checkbox" name="search_date" class="custom-control-input" id="search_date" <?= isset($_POST['search_date']) ? 'checked' : '' ?>>
              <label class="custom-control-label" for="search_date">Szűrés dátum szerint</label>
            </div>
          </div>
          <div class="form-group mb-3">
            <input class="form-control mr-2" type="date" name="search_date_from" value="<?= isset($_POST['search_date']) ? $_POST['search_date_from'] : date('Y-m-d') ?>"> – 
            <input class="form-control ml-2" type="date" name="search_date_to" value="<?= isset($_POST['search_date']) ? $_POST['search_date_to'] : date('Y-m-d') ?>">
          </div>
        </div>
        <div class="card-footer">
          <input type="submit" class="btn btn-secondary"   value="Keresés" name="search">
        </div>
      </div>
    </form>
    <?php
      $queryListPrograms = 'SELECT CONCAT(sz.vezeteknev , " ", sz.keresztnev) AS nev, program.id, prioritas, author, felvetel_datum FROM program INNER JOIN szemely sz on sz.id = author';
      if(isset($_POST['search'])) {
        $searchName = mysqli_real_escape_string($db, strtolower($_POST['search_name']));
        $searchPriority = mysqli_real_escape_string($db, $_POST['search_priority']);
        $searchDateFrom = mysqli_real_escape_string($db, $_POST['search_date_from']);
        $searchDateTo = mysqli_real_escape_string($db, $_POST['search_date_to']);
        $queryFilterName = sprintf('LOWER(CONCAT(sz.vezeteknev, " ", sz.keresztnev)) LIKE "%%%s%%"', $searchName);
        $queryFilterPriority = $_POST['search_priority'] != '–' ? ' AND prioritas = ' . $searchPriority : '';
        $queryFilterDate = isset($_POST['search_date']) ? ' AND felvetel_datum >= "' . $searchDateFrom . '" AND felvetel_datum <= "' . $searchDateTo . '"' : '';
        $queryFilter =  $queryFilterName . $queryFilterPriority . $queryFilterDate;
        $queryListPrograms = $queryListPrograms . ' WHERE ' . $queryFilter;
      }
      $result = mysqli_query($db, $queryListPrograms) or die(mysqli_error($db));
      $author = 0;
    ?>
    <table class="table table-sm table-bordered">
      <thead class="thead-dark">
          <tr>
            <td>Tulajdonos</td>
            <td>Azonosító</td>
            <td>Prioritás</td>
            <td>Felvétel dátuma</td>
            <td>Műveletek</td>
          </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_array($result)): ?>
        <tr>
          <?php
            $queryCount = 'SELECT COUNT(author) FROM program INNER JOIN szemely sz on sz.id = author WHERE author = ' . $row['author'] . (isset($queryFilter) ? (' AND ' . $queryFilter) : '');
            $author_count = mysqli_query($db, $queryCount) or die(mysqli_error($db));
            if ($author != $row['author']) {
              print('<td rowspan="' . mysqli_fetch_row($author_count)[0] . '">' . $row['nev'] . '</td>');
            }
            $author = $row['author'];
          ?>
          <td><?=$row['id']?></td>
          <td><?=$priorities[$row['prioritas']]?></td>
          <td><?=$row['felvetel_datum']?></td>
          <td class="text-right">
            <a class="btn btn-secondary btn-sm" href="edit-program.php?id=<?=$row['id']?>">
              <i class="fa fa-edit"></i>
            </a>
            <a class="btn btn-danger btn-sm" href="edit-program.php?delete=<?=$row['id']?>">
              <i class="fa fa-remove"></i>
            </a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <form method="post" action="">
      <div class="card">
        <div class="card-header">
          Új program felvétele
        </div>
        <div class="card-body">
          <div class="form-group">
            <label for="author" class="mr-2">Tulajdonos</label>
            <select class="custom-select" name="author">
              <?php
                $queryAuthors = 'SELECT id, CONCAT(vezeteknev, " ", keresztnev) AS nev FROM szemely ORDER BY vezeteknev';
                $resultAuthors = mysqli_query($db, $queryAuthors) or die(mysqli_error($db));
                while ($row = mysqli_fetch_array($resultAuthors)) {
                  print('<option value="' . $row['id'] . '" >' . $row['nev'] . '</option>');
                }
              ?>
            </select>
          </div>
          <div class="form-group mb-0">
            <label for="priority" class="mr-2">Prioritás</label>
            <select class="custom-select" name="priority">
              <?php
                for ($i = sizeof($priorities) - 1; $i >= 0; $i--) {
                  print('<option value="' . $i . '">' . $priorities[$i] . '</option>');
                }
              ?>
            </select>
          </div>
        </div>
        <div class="card-footer">
          <input type="submit" class="btn btn-primary" name="add" value="Hozzáadás">
        </div>
      </div>
    </form>
          
    <?php
        closeDb($db);
    ?>
  </div>
<?php include 'common_footer.html'; ?>