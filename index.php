<?php include 'common_head.html'; ?>
  <title>Program nyilvántartás</title>
</head>
<body>
  <?php include 'menu.html'; ?>
  <div class="container main-content">
    <div class="punchwrap">
      <div class="jumbotron text-light">
        <h1>Lyukkártyás programok nyilvántartása</h1>
        <div class="punch"></div>
      </div>
    </div>
  </div>

  <script>
    const generate = () => {
      let punch = document.getElementsByClassName('punch')[0];
      punch.innerHTML = "";

      for (let j = 0; j < 10; j++) {

        let line = document.createElement('div');
        line.classList.add('line');

        for (let i = 0; i < 50; i++) {
            let number = document.createElement('div');
            number.innerText = j;
            number.classList.add(Math.random() < 0.8 ? 'number' : 'hole');
            line.append(number)
        }

        punch.append(line);
      }
    }

    generate();
    setInterval(() => {
      generate();
    }, 2*1000);
  </script>

<?php include 'common_footer.html'; ?>