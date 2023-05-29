<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();
header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $messages = array();
  if (!empty($_COOKIE['save'])) {
    setcookie('save', '', 100000);
    setcookie('login', '', 100000);
    setcookie('pass', '', 100000);
    $messages[] = 'Спасибо, результаты сохранены.';

    if (!empty($_COOKIE['pass'])) {
      $messages[] = sprintf(
        'Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
        и паролем <strong>%s</strong> для изменения данных.',
        strip_tags($_COOKIE['login']),
        strip_tags($_COOKIE['pass'])
      );
    }
  }
  $errors = array();
  $errors['fio'] = !empty($_COOKIE['fio_error']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['year'] = !empty($_COOKIE['year_error']);
  $errors['gender'] = !empty($_COOKIE['gender_error']);
  $errors['bodyparts'] = !empty($_COOKIE['bodyparts_error']);
  $errors['ability'] = !empty($_COOKIE['ability_error']);
  $errors['bio'] = !empty($_COOKIE['bio_error']);
  $errors['check'] = !empty($_COOKIE['check_error']);

  if ($errors['fio']) {
    setcookie('fio_error', '', 100000);
    $messages[] = '<div class="error">Заполните имя.</div>';
  }
  if ($errors['email']) {
    setcookie('email_error', '', 100000);
    $messages[] = '<div class="error">Заполните email.</div>';
  }
  if ($errors['year']) {
    setcookie('year_error', '', 100000);
    $messages[] = '<div class="error">Заполните год.</div>';
  }
  if ($errors['gender']) {
    setcookie('gender_error', '', 100000);
    $messages[] = '<div class="error">Заполните пол.</div>';
  }
  if ($errors['bodyparts']) {
    setcookie('bodyparts_error', '', 100000);
    $messages[] = '<div class="error">Заполните кол-во конечностей.</div>';
  }
  if ($errors['ability']) {
    setcookie('ability_error', '', 100000);
    $messages[] = '<div class="error">Заполните суперспособность.</div>';
  }
  if ($errors['bio']) {
    setcookie('bio_error', '', 100000);
    $messages[] = '<div class="error">Заполните биографию.</div>';
  }
  if ($errors['check']) {
    setcookie('check_error', '', 100000);
    $messages[] = '<div class="error">Ознакомьтесь с соглашением.</div>';
  }

  $values = array();
  $values['fio'] = empty($_COOKIE['fio_value']) ? '' : $_COOKIE['fio_value'];
  $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
  $values['year'] = empty($_COOKIE['year_value']) ? '' : $_COOKIE['year_value'];
  $values['gender'] = empty($_COOKIE['gender_value']) ? '' : $_COOKIE['gender_value'];
  $values['bodyparts'] = empty($_COOKIE['bodyparts_value']) ? '' : $_COOKIE['bodyparts_value'];
  $values['ability'] = empty($_COOKIE['ability_value']) ? array() : json_decode($_COOKIE['ability_value']);
  $values['bio'] = empty($_COOKIE['bio_value']) ? '' : $_COOKIE['bio_value'];
  $values['check'] = empty($_COOKIE['check_value']) ? '' : $_COOKIE['check_value'];

  if (
    empty($errors) && !empty($_COOKIE[session_name()]) &&
    !empty($_SESSION['login'])
  ) {
    $user = 'u52995';
    $pass = '1306430';
    $db = new PDO('mysql:host=localhost;dbname=u52995', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
    try {
      $get = $db->prepare("select * from application where id=?");
      $get->bindParam(1, $_SESSION['uid']);
      $get->execute();
      $inf = $get->fetchALL();
      $values['fio'] = $inf[0]['fio'];
      $values['email'] = $inf[0]['email'];
      $values['year'] = $inf[0]['year'];
      $values['gender'] = $inf[0]['gender'];
      $values['bodyparts'] = $inf[0]['bodyparts'];
      $values['bio'] = $inf[0]['bio'];

      $get2 = $db->prepare("select ability_id from ability_application where application_id=?");
      $get2->bindParam(1, $_SESSION['uid']);
      $get2->execute();
      $inf2 = $get2->fetchALL();
      for ($i = 0; $i < count($inf2); $i++) {
        if ($inf2[$i]['power_id'] == '1') {
          $values['1'] = 1;
        }
        if ($inf2[$i]['power_id'] == '2') {
          $values['2'] = 1;
        }
        if ($inf2[$i]['power_id'] == '3') {
          $values['3'] = 1;
        }
      }
    } catch (PDOException $e) {
      print('Error: ' . $e->getMessage());
      exit();
    }
    printf('Вход с логином %s, uid %d', $_SESSION['login'], $_SESSION['uid']);
  }

  include('form.php');
} else {
  if (!empty($_POST['logout'])) {
    session_destroy();
    header('Location: index.php');
  } else {
    $regex_name = '/[a-z,A-Z,а-я,А-Я,-]*$/';
    $regex_email = '/[a-z]+\w*@[a-z]+\.[a-z]{2,4}$/';

    $errors = FALSE;
    if (empty($_POST['fio']) or !preg_match($regex_name, $_POST['fio'])) {
      setcookie('fio_error', '1', time() + 24 * 60 * 60);
      setcookie('fio_value', '', 100000);
      $errors = TRUE;
    } else {
      setcookie('fio_value', $_POST['fio'], time() + 30 * 24 * 60 * 60);
      setcookie('fio_error', '', 100000);
    }

    if (empty($_POST['email']) || !preg_match($regex_email, $_POST['email'])) {
      setcookie('email_error', '1', time() + 24 * 60 * 60);
      setcookie('email_value', '', 100000);
      $errors = TRUE;
    } else {
      setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
      setcookie('email_error', '', 100000);
    }

    if (empty($_POST['year']) || !is_numeric($_POST['year']) || !preg_match('/^\d+$/', $_POST['year'])) {
      setcookie('year_error', '1', time() + 24 * 60 * 60);
      setcookie('year_value', '', 100000);
      $errors = TRUE;
    } else {
      setcookie('year_value', $_POST['year'], time() + 30 * 24 * 60 * 60);
      setcookie('year_error', '', 100000);
    }

    if (empty($_POST['gender']) || ($_POST['gender'] != 'm' && $_POST['gender'] != 'f')) {
      setcookie('gender_error', '1', time() + 24 * 60 * 60);
      setcookie('gender_value', '', 100000);
      $errors = TRUE;
    } else {
      setcookie('gender_value', $_POST['gender'], time() + 30 * 24 * 60 * 60);
      setcookie('gender_error', '', 100000);
    }
    if (empty($_POST['bodyparts']) || ($_POST['bodyparts'] != '2' && $_POST['bodyparts'] != '3' && $_POST['bodyparts'] != '4')) {
      setcookie('bodyparts_error', '1', time() + 24 * 60 * 60);
      setcookie('bodyparts_value', '', 100000);
      $errors = TRUE;
    } else {
      setcookie('bodyparts_value', $_POST['bodyparts'], time() + 30 * 24 * 60 * 60);
      setcookie('bodyparts_error', '', 100000);
    }

    foreach ($_POST['ability'] as $ability) {
      if (!is_numeric($ability) || !in_array($ability, [1, 2, 3, 4])) {
        setcookie('ability_error', '1', time() + 24 * 60 * 60);
        setcookie('ability_value', '', 100000);
        $errors = TRUE;
        break;
      }
    }
    if (!empty($_POST['ability'])) {
      setcookie('ability_value', json_encode($_POST['ability']), time() + 24 * 60 * 60);
      setcookie('ability_error', '', time() + 24 * 60 * 60);
    }

    if (empty($_POST['bio']) || !preg_match('/^[0-9A-Za-z0-9А-Яа-я,\.\s]+$/', $_POST['bio'])) {
      setcookie('bio_error', '1', time() + 24 * 60 * 60);
      setcookie('bio_value', '', time() + 30 * 24 * 60 * 60);
      $errors = TRUE;
    } else {
      setcookie('bio_value', $_POST['bio'], time() + 30 * 24 * 60 * 60);
      setcookie('bio_error', '', time() + 24 * 60 * 60);
    }

    if (!isset($_POST['check'])) {
      setcookie('check_error', '1', time() + 24 * 60 * 60);
      setcookie('check_value', '', time() + 30 * 24 * 60 * 60);
      $errors = TRUE;
    } else {
      setcookie('check_value', $_POST['check'], time() + 30 * 24 * 60 * 60);
      setcookie('check_error', '', time() + 24 * 60 * 60);
    }

    if ($errors) {
      setcookie('save', '', 100000);
      header('Location: login.php');
    } else {
      setcookie('fio_error', '', 100000);
      setcookie('email_error', '', 100000);
      setcookie('year_error', '', 100000);
      setcookie('gender_error', '', 100000);
      setcookie('bodyparts_error', '', 100000);
      setcookie('ability_error', '', 100000);
      setcookie('check_error', '', 100000);
    }

    $user = 'u52995';
    $pass = '1306430';
    $db = new PDO('mysql:host=localhost;dbname=u52995', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
    if (!empty($_COOKIE[session_name()]) && !empty($_SESSION['login']) and !$errors) {
      $app_id = $_SESSION['uid'];
      $upd = $db->prepare("update application set name=?,email=?,year=?,gender=?,limbs=?,biography=? where id=?");
      $upd->execute(array($_POST['fio'], $_POST['email'], $_POST['year'], $_POST['gender'], $_POST['bodyparts'], $_POST['bio'], $app_id));
      $del = $db->prepare("delete from ability_application where application_id=?");
      $del->execute(array($app_id));
      $upd1 = $db->prepare("insert into ability_application set ability_id=?,application_id=?");
      $stmt = $db->prepare("INSERT INTO ability_application SET application_id = ?, ability_id=?");
      foreach ($_POST['ability'] as $ability) {
        $stmt->execute([$app_id, $ability]);
      }
    } else {
      if (!$errors) {
        $login = 'N' . substr(uniqid(), -6);
        $password = substr(md5(uniqid()), 0, 15);
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        print($hashed);
        setcookie('login', $login);
        setcookie('pass', $password);
        try {
          $stmt = $db->prepare("INSERT INTO application SET name=?,email=?,year=?,gender=?,limbs=?,biography=?");
          $stmt->execute(array($_POST['fio'], $_POST['email'], $_POST['year'], $_POST['gender'], $_POST['bodyparts'], $_POST['bio']));
          $app_id = $db->lastInsertId();
          //$pwr=$db->prepare("INSERT INTO ability_application SET ability_id=?,application_id=?");
          //foreach($pwrs as $power){ 
          //  $pwr->execute(array($power,$id));
          //}
          $stmt = $db->prepare("INSERT INTO ability_application SET application_id = ?, ability_id=?");
          foreach ($_POST['ability'] as $ability) {
            $stmt->execute([$app_id, $ability]);
          }
          $usr = $db->prepare("insert into user set application_id=?,login=?,password_hash=?");
          $usr->execute(array($app_id, $login, $hashed));
        } catch (PDOException $e) {
          print('Error : ' . $e->getMessage());
          exit();
        }
      }
    }
    if (!$errors) {
      setcookie('save', '1');
    }
    header('Location: ./');
  }
}
