<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="pragma" content="no-cache">
    <title></title>
  </head>
  <body>
    <h1>Проверка подписи</h1>

    <form action="" method="post">
      
      <div class="row">
        <label for="certificate">Сертификат</label><br>
        <textarea name="certificate" id="certificate" cols="80" rows="10"></textarea>
      </div>

      <div class="row">
        <label for="data">Подписанные данные</label><br>
        <textarea name="data" id="data" cols="80" rows="10"></textarea>
      </div>

      <div class="row"><br></div>

      <div class="row">
        <input type="radio" name="type" id="attached" value="0" checked="checked">
        <label for="attached"> присоединённая</label>

        <input type="radio" name="type" id="detached" value="1">
        <label for="detached"> отсоединённая</label>
      </div>

      <div class="row"><br></div>

      <div class="row">
        <input type="checkbox" name="nochain" id="nochain" value="1" checked="checked">
        <label for="nochain">не проверять цепочки найденных сертификатов (-nochain)</label><br>

        <input type="checkbox" name="norev" id="norev" value="1" checked="checked">
        <label for="norev">не проверять сертификаты в цепочке на предмет отозванности (-norev)</label><br>

        <input type="checkbox" name="errchain" id="errchain" value="1">
        <label for="errchain">завершать выполнение с ошибкой, если хотя бы один сертификат не прошел проверку (-errchain)</label>
      </div>

      <div class="row"><br></div>

      <div class="row">
        <input type="submit" value="Проверить">
      </div>

    </form>

  </body>
</html>
