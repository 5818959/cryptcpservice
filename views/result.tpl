<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="pragma" content="no-cache">
    <title></title>
    <style>
      html, body {
        padding: 0;
        margin: 0;
      }
      h1, h3, pre {
        margin-left: 5px;
        margin-right: 5px;
      }
      form {
        margin: 10px 5px;
      }
      .banner {
        padding: 20px 10px;
        display: block;
      }
      .success {background-color: green; color: white;}
      .fail {background-color: red; color: white;}
    </style>
  </head>
  <body>
    <h1>Проверка подписи</h1>

    <?php if ($verifyResult): ?>
      <div class="banner success">
        Подпись проверена.
      </div>
    <?php else: ?>
      <div class="banner fail">
        Подпись не верна.
      </div>
    <?php endif ?>

    <h3>Результат работы утилиты</h3>
    <pre><?= $verifyDetails ?></pre>

    <form action="">
      <button action="submit">Проверить ещё</button>
    </form>

  </body>
</html>
