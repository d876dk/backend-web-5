<!DOCTYPE html>
<html lang="en">

<head>
  <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
  <title>Task 5 </title>
  <link rel="stylesheet" href="style1.css">
  <style>
    .error {
      background-color: red;
    }
  </style>
</head>

<body>

  <?php
  if (!empty($messages)) {
    print('<div id="messages">');
    foreach ($messages as $message) {
      print($message);
    }
    print('</div>');
  }
  ?>

  <div id="form">

    <form action="index.php" method="POST">

      <label>
        ФИО:
        <input name="fio" <?php print($errors['fio'] ? 'class="error"' : ''); ?> value="<?php print $values['fio']; ?>" />
      </label><br />

      <label>
        Почта:
        <input name="email" type="email" <?php print($errors['email'] ? 'class="error"' : ''); ?> value="<?php print $values['email']; ?>" />
      </label><br />

      <label>
        Дата рождения
        <select name="year">
          <?php
          for ($i = 1900; $i <= 2022; $i++) {
            $selected = ($i == $values['year']) ? 'selected="selected"' : '';
            printf('<option value="%d" %s>%d год</option>', $i, $selected, $i);
          }
          ?>
        </select><br />

        Пол
        <label><input type="radio" checked="checked" name="gender" value="m" <?php print($errors['gender'] ? 'class="error"' : ''); ?> <?php if ($values['gender'] == 'm') print 'checked'; ?> />
          Мужской</label>
        <label><input type="radio" name="gender" value="f" <?php print($errors['gender'] ? 'class="error"' : ''); ?> <?php if ($values['gender'] == 'f') print 'checked'; ?> />
          Женский</label><br />
        Конечностей
        <label><input type="radio" checked="checked" name="bodyparts" value="2" <?php print($errors['bodyparts'] ? 'class="error"' : ''); ?> <?php if ($values['bodyparts'] == '2') print 'checked'; ?> />
          2</label>
        <label><input type="radio" name="bodyparts" value="3" <?php print($errors['bodyparts'] ? 'class="error"' : ''); ?> <?php if ($values['bodyparts'] == '3') print 'checked'; ?> />
          4</label>
        <label><input type="radio" name="bodyparts" value="4" <?php print($errors['bodyparts'] ? 'class="error"' : ''); ?> <?php if ($values['bodyparts'] == '4') print 'checked'; ?> />
          10</label><br />
        <label>
          СуперСила
          <br />
          <select name="ability[]" multiple="multiple" <?php print($errors['ability'] ? 'class="error"' : ''); ?>>
            <option value="1" selected="selected">Нет</option>
            <option value="2" <?php print(in_array('2', $values['ability']) ? 'selected ="selected"' : ''); ?>>Полет</option>
            <option value="3" <?php print(in_array('3', $values['ability']) ? 'selected ="selected"' : ''); ?>>Невидимость</option>
            <option value="4" <?php print(in_array('4', $values['ability']) ? 'selected ="selected"' : ''); ?>>Телепортация</option>
          </select>
        </label><br />
        <label>
          Биография<br />
          <textarea name="bio" <?php print($errors['bio'] ? 'class="error"' : ''); ?>><?php print $values['bio']; ?></textarea>
        </label><br />

        <label><input type="checkbox" name="check" />
          Ознакомился</label>

        <input type="submit" value="Send" />
    </form>
    <?php
    if (empty($_SESSION['login'])) {
      echo '
   <div class="login">
    <p>Если у вас есть аккаунт, вы можете <a href="login.php">войти</a></p>
   </div>';
    } else {
      echo '
    <div class="logout">
      <form action="index.php" method="post">
        <input name="logout" type="submit" value="Выйти">
      </form>
    </div>';
    } ?>
  </div>
</body>