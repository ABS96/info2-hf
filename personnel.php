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
      <p>
        <span class="badge badge-success">Új személy hozzáadva</span>
      </p>
    <?php endif; ?>
    <form class="form-inline mb-3" method="post">
      <div class="card">
          <div class="card-body">
              <input class="form-control ml1 mw-50" type="search" name="search_name" placeholder="🔎" value="<?=isset($_POST['search']) ? $_POST['search_name'] : ''?>">
              <input type="submit" class="ml-1 btn btn-secondary" value="Keresés" name="search">
          </div>
      </div>
    </form>
    <?php
      $queryListPersonnel = 'SELECT id, CONCAT(vezeteknev, " ", keresztnev) AS nev, titulus FROM szemely';
      if (isset($_POST['search'])) {
        $queryListPersonnel = $queryListPersonnel . sprintf(' WHERE LOWER(CONCAT(vezeteknev, " ", keresztnev)) LIKE "%%%s%%"', mysqli_real_escape_string($db, strtolower($_POST['search_name'])));
      }
      $result = mysqli_query($db, $queryListPersonnel) or die(mysqli_error($db));
    ?>
    <table class="table table-striped table-sm table-bordered">
      <thead class="thead-dark">
        <tr>
          <th>Azonosító</th>
          <th>Név</th>
          <th>Titulus</th>
          <th>Programok száma</th>
          <th>Műveletek</th>
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
                print(mysqli_fetch_row($countResult)[0]);
              ?>
            </td>
            <td class="text-right">
              <a class="btn btn-secondary btn-sm" href="edit-person.php?id=<?=$row['id']?>">
                <i class="fa fa-edit"></i> Szerkesztés
              </a>
              <a class="btn btn-danger btn-sm" href="edit-person.php?delete=<?=$row['id']?>">
                <i class="fa fa-remove"></i> Törlés
              </a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    
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
              <input type="text" aria-label="Vezetéknév" name="vezeteknev" class="form-control" id="vezeteknev">
              <input type="text" aria-label="Keresztnév" name="keresztnev" class="form-control" id="keresztnev">
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