<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>White Box Tool</title>
    <link rel="icon" type="image/x-icon" href="/img/favicon.ico" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="css/jquery-linedtextarea.css" type="text/css" rel="stylesheet" />
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery-linedtextarea.js"></script>
    <script src="js/run_prettify.js"></script>
    <script src="js/app.js"></script>
  </head>

  <body>
    <nav class="navbar navbar-toggleable-md fixed-top navbar-light bg-faded">
      <a class="navbar-brand" href="/"></a>
      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="/">Home</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="/list.php">List upload</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="/setting.php">Setting</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="/draw.php">Draw  CFD</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="/run-php.php">Run PHP</a>
          </li>
        <li class="nav-item active dropdown">
            <a class="nav-link dropdown-toggle" href="http://example.com" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Other Tool</a>
            <div class="dropdown-menu" aria-labelledby="dropdown01">
              <a class="nav-link" href="/prettify-php.php">Prettify Code</a>
              <a class="nav-link" href="/prettify-js.php">Prettify Code (with JS)</a>
              <a class="nav-link" href="/fix.php">Fix  Node</a>
            </div>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="/guide.php">User Guide</a>
          </li>
          <?php if(isset($_COOKIE['node'])) : ?>
          <li class="nav-item active">
            <a class="nav-link" href="/cfd.php" style="font-style: italic;">Cookie</a>
          </li>
          <?php endif; ?>
        </ul>
      </div>
    </nav>

