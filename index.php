<?php

// remove comments.db file
unlink('comments.db');
// create a sqlite database
$db = new PDO('sqlite:comments.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$db->exec("CREATE TABLE IF NOT EXISTS columns(
          id    INTEGER PRIMARY KEY,
          name text NOT NULL
        ) ");




// // create the table if it doesn't exist
$db->exec("CREATE TABLE IF NOT EXISTS topic (
          id    INTEGER PRIMARY KEY,
          topic text DEFAULT 'TOPIC',
          column int(11) NOT NULL,
          created datetime NOT NULL,
          content text NOT NULL,
          author varchar(255) NOT NULL
        ) ");

// create comment table
$db->exec("CREATE TABLE IF NOT EXISTS comment (
          id    INTEGER PRIMARY KEY,
          comment text DEFAULT 'COMMENT',
          created datetime NOT NULL,
          content text NOT NULL,
          author varchar(255) NOT NULL,
          topic_id int(11) NOT NULL,
          parent_id int(11) NOT NULL
        ) ");


// columns array
$columns = array('todo', 'in progress', 'done', 'archive');

// create columns
for ($i=0; $i < 4; $i++) {
    $stmt = $db->prepare("INSERT INTO columns (name) VALUES (:name)");
    $stmt->bindParam(':name', $columns[$i]);
    $stmt->execute();
}

// author array
$authors = array('John', 'Paul', 'George', 'Ringo');
$content = array('Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl.', 'Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl.', 'Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl.', 'Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl.', 'Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl.', 'Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl.', 'Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Nullam auctor, nisl nec ');
// create ten topics
for ($i=0; $i < 10; $i++) {
    $stmt = $db->prepare("INSERT INTO topic (column, created, content, author) VALUES (:column, datetime('now'), :content, :author)");
    $stmt->bindParam(':column', $columns[rand(0, count($columns)-1)]);
    $stmt->bindParam(':content', $content[rand(0, count($content)-1)]);
    $stmt->bindParam(':author', $authors[rand(0, count($authors)-1)]);
    $stmt->execute();
}

// create 100 comments
for ($i=0; $i < 100; $i++) {
    $parent_id = rand(1, 10);
    $topic_id = rand(1, 10);
    $stmt = $db->prepare("INSERT INTO comment (created, content, author, topic_id, parent_id) VALUES (datetime('now'), :content, :author, :topic_id, :parent_id)");
    $stmt->bindParam(':content', $content[rand(0, count($content)-1)]);
    $stmt->bindParam(':author', $authors[rand(0, count($authors)-1)]);
    $stmt->bindParam(':topic_id', $topic_id);
    $stmt->bindParam(':parent_id', $parent_id);
    $stmt->execute();
}





// create a new topic
if (isset($_POST['topic'])) {
    $stmt = $db->prepare("INSERT INTO topic (column, created, content, author) VALUES (:column, datetime('now'), :content, :author)");
    $stmt->bindParam(':column', $_POST['column']);
    $stmt->bindParam(':content', $_POST['content']);
    $stmt->bindParam(':author', $_POST['author']);
    $stmt->execute();
}

// create a new comment
if (isset($_POST['comment'])) {
    $stmt = $db->prepare("INSERT INTO comment (created, content, author, topic_id, parent_id) VALUES (datetime('now'), :content, :author, :topic_id, :parent_id)");
    $stmt->bindParam(':content', $_POST['content']);
    $stmt->bindParam(':author', $_POST['author']);
    $stmt->bindParam(':topic_id', $_POST['topic_id']);
    $stmt->bindParam(':parent_id', $_POST['parent_id']);
    $stmt->execute();
}

if (isset($_GET['topic'])) {
    $topic_id = $_GET['topic'];

    // get topic
    $stmt = $db->prepare("SELECT * FROM topic WHERE id = :topic_id");
    $stmt->bindParam(':topic_id', $topic_id);
    $stmt->execute();
    $topic = $stmt->fetchAll(\PDO::FETCH_ASSOC);


    $stmt = $db->prepare("SELECT * FROM comment WHERE topic_id = :topic_id");
    $stmt->bindParam(':topic_id', $topic_id);
    $stmt->execute();
    $comments = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    // response($comments);

    response(array('topic' => $topic, 'comments' => $comments));
}



// get all topics
if (isset($_GET['topics'])) {
    $stmt = $db->prepare("SELECT * FROM topic");
    $stmt->execute();
    $topics = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    response($topics);
}


function response($data)
{
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kanban</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
  <link rel="shortcut icon"
    href="data:image/x-icon;base64,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" />
</head>

<body class="container">



  <header>
    <h1>Kanban</h1>
  </header>

  <form action="index.php" method="post">
    <input type="hidden" name="topic" value="1">
    <input type="text" name="author" placeholder="Author">
    <select name="column">
      <option value="todo">Todo</option>
      <option value="in progress">In Progress</option>
      <option value="done">Done</option>
      <option value="archive">Archive</option>
    </select>
    <textarea name="content" placeholder="Content"></textarea>
    <input type="submit" value="Create Topic">
  </form>

  <script>
  const pageURL = new URL(window.location.href);
  const origin = pageURL.origin;


  fetch(origin + '/?topics')
    .then(response => response.json())
    .then(data => console.log(data));
  </script>

</body>

</html>