<?php 
include 'jobs.php';
$db = getDb();

$added = false;
if (isset($_POST['add'])) {
  $vezeteknev = mysqli_real_escape_string($db, $_POST['vezeteknev']);
  $keresztnev = mysqli_real_escape_string($db, $_POST['keresztnev']);
  if (!$vezeteknev or !$keresztnev) {
    die('A személynek rendelkeznie kell vezeték- és keresztnévvel!');
  }
  $titulus = mysqli_real_escape_string($db, $_POST['titulus']);

  $queryAddPerson = sprintf('INSERT INTO szemely(vezeteknev, keresztnev, titulus) VALUES ("%s", "%s", "%s")',
    $vezeteknev,
    $keresztnev,
    $titulus
  );
  mysqli_query($db, $queryAddPerson) or die(mysqli_error($db));
  $added = true;
}

include 'common_head.html';
?>
  <title>Program nyilvántartás – Személyek</title>
</head>
<body>
  <?php include 'menu.html'; ?>

  <div class="container main-content">
    <h1>Személyek kezelése</h1>
    <?php if ($added): ?>
      <div class="alert alert-success">Személy hozzáadva</div>
    <?php
      endif;
      if (isset($_SESSION['person_deleted'])): 
    ?>
      <div class="alert alert-danger">Személy törölve</div>
    <?php
      unset($_SESSION['person_deleted']);
      endif;
    ?>

    <form class="form-inline mb-3" method="post">
      <div class="card">
          <div class="card-body">
            <div class="input-group">
              <input type="search" class="form-control" placeholder="név" name="search_name" value="<?=isset($_POST['search']) ? $_POST['search_name'] : ''?>">
              <div class="input-group-append">
                <button class="btn btn-secondary" type="submit" value="Keresés" name="search">Keresés</button>
              </div>
            </div>
          </div>
      </div>
    </form>

    <?php
      $queryListPersonnel = 'SELECT id, CONCAT(vezeteknev, " ", keresztnev) AS nev, titulus FROM szemely';
      if (isset($_POST['search'])) {
        $queryListPersonnel = $queryListPersonnel
        . sprintf(' WHERE LOWER(CONCAT(vezeteknev, " ", keresztnev)) LIKE "%%%s%%"', mysqli_real_escape_string($db, strtolower($_POST['search_name'])));
      }
      $result = mysqli_query($db, $queryListPersonnel) or die(mysqli_error($db));
    ?>
    <div class="table-responsive">
      <table class="table table-striped table-sm table-bordered">
        <thead class="thead-dark">
          <tr>
            <th>Azonosító</th>
            <th>Név</th>
            <th>Titulus</th>
            <th>Programok száma</th>
            <th class="">Műveletek</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_array($result)): ?>
            <tr>
              <td><?=$row['id']?></td>
              <td><?=$row['nev']?></td>
              <td><?=$row['titulus']?></td>
              <td>
                <?php
                  $queryCountPrograms = 'SELECT COUNT(id) FROM program WHERE author = ' . $row['id'];
                  $countResult = mysqli_query($db, $queryCountPrograms) or die(mysqli_error($db));
                  $progCount = mysqli_fetch_row($countResult)[0];
                  print($progCount);
                  for ($i=0; $i < $progCount; $i++) { 
                    print('<i class="program-dot"></i>');
                  }
                ?>
              </td>
              <td class="text-right">
                <a class="btn btn-secondary btn-sm" href="programs.php?author=<?=$row['id']?>">
                  <i class="fa fa-eye"></i><span> Programok</span>
                </a>
                <a class="btn btn-secondary btn-sm" href="edit-person.php?id=<?=$row['id']?>">
                  <i class="fa fa-edit"></i><span> Szerkesztés</span>
                </a>
                <a class="btn btn-danger btn-sm" href="edit-person.php?delete=<?=$row['id']?>">
                  <i class="fa fa-remove"></i><span> Törlés</span>
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    
    <?php
      closeDb($db);
    ?>

    <form method="post" action="">
      <div class="card">
        <div class="card-header">
          Új személy felvétele
        </div>
        <div class="card-body">
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">Vezetéknév és keresztnév</span>
              </div>
              <input type="text" required aria-label="Vezetéknév" name="vezeteknev" class="form-control" id="vezeteknev">
              <input type="text" required aria-label="Keresztnév" name="keresztnev" class="form-control" id="keresztnev">
            </div>
          </div>
          <div class="form-group">
            <label for="titulus">Titulus</label>
            <div class="form-group">
              <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="hallgató" name="titulus" class="custom-control-input" value="hallgató" checked>
                <label class="custom-control-label" for="hallgató">hallgató</label>
              </div>
              <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="oktató" name="titulus" class="custom-control-input" value="oktató">
                <label class="custom-control-label" for="oktató">oktató</label>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <input type="submit" class="btn btn-primary" name="add" value="Hozzáadás">
        </div>
      </div>
    </form>
    
  </div>
<?php include 'common_footer.html'; ?>