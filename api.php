<?php

// remove comments.db file
unlink('comments.db');
// create a sqlite database
$db = new PDO('sqlite:comments.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$db->exec("CREATE TABLE IF NOT EXISTS columns(
          id    INTEGER PRIMARY KEY,
          position int(11) NOT NULL,
          column_name text NOT NULL
        ) ");


$db->exec("CREATE TABLE IF NOT EXISTS topic (
          id    INTEGER PRIMARY KEY,
          topic text DEFAULT 'TOPIC',
          position int(11) NOT NULL,
          column int(11) NOT NULL,
          created datetime NOT NULL,
          title text NOT NULL,
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
    $stmt = $db->prepare("INSERT INTO columns (column_name, position) VALUES (:column_name, $i+1)");
    $stmt->bindParam(':column_name', $columns[$i]);
    $stmt->execute();
}

// author array
$authors = array('John', 'Paul', 'George', 'Ringo');
// array with animal names
$title = array('dog', 'cat', 'bird', 'fish', 'snake', 'lizard', 'frog', 'turtle', 'hamster', 'rabbit', 'mouse', 'cow', 'horse', 'pig', 'sheep', 'goat', 'chicken', 'duck', 'goose', 'turkey', 'deer', 'bear', 'wolf', 'fox', 'lion', 'tiger', 'elephant', 'giraffe', 'zebra', 'monkey', 'gorilla', 'panda', 'koala', 'hippo', 'rhino', 'dolphin', 'whale', 'shark', 'seal', 'walrus', 'penguin', 'owl', 'eagle', 'hawk', 'sparrow', 'ant', 'bee', 'butterfly', 'dragonfly', 'mosquito', 'fly', 'spider', 'scorpion', 'crab', 'lobster', 'shrimp', 'snail', 'slug', 'octopus', 'squid', 'starfish', 'seahorse', 'jellyfish', 'clam', 'mushroom', 'tree', 'flower', 'grass', 'leaf', 'bush', 'cactus', 'rock', 'mountain', 'hill', 'valley', 'ocean', 'lake', 'river', 'pond', 'island', 'desert', 'forest', 'cave', 'cloud', 'rain', 'snow', 'sun', 'moon', 'star', 'planet', 'galaxy', 'universe', 'wind', 'rainbow', 'lightning', 'thunder', 'fire', 'water', 'ice', 'earth', 'air', 'time', 'space', 'music', 'dance', 'poetry', 'art', 'painting', 'drawing', 'sculpture', 'photography', 'film', 'television', 'computer', 'phone', 'book', 'magazine', 'newspaper', 'pen', 'pencil', 'eraser', 'notebook', 'paper', 'glass', 'cup', 'plate', 'bowl', 'fork', 'knife', 'spoon', 'chair', 'table', 'bed', 'lamp', 'door', 'window', 'car', 'truck', 'bus', 'train', 'plane', 'boat', 'ship');
$content = array('Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl.', 'Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl.', 'Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl.', 'Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl.', 'Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl.', 'Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl.', 'Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Nullam auctor, nisl nec ');
// create ten topics
for ($i=0; $i < 50; $i++) {
    $stmt = $db->prepare("INSERT INTO topic (position, column, created, content, title, author) VALUES (:position, :column, datetime('now'), :content, :title, :author)");
    $column_id= rand(0, count($columns));
    $position = $i+1;
    $stmt->bindParam(':position', $position);
    $stmt->bindParam(':column', $column_id);
    $stmt->bindParam(':title', $title[rand(0, count($title)-1)]);
    $stmt->bindParam(':content', $content[rand(0, count($content)-1)]);
    $stmt->bindParam(':author', $authors[rand(0, count($authors)-1)]);
    $stmt->execute();
}

// create 100 comments
// for ($i=0; $i < 100; $i++) {
//     $parent_id = rand(1, 10);
//     $topic_id = rand(1, 10);
//     $stmt = $db->prepare("INSERT INTO comment (created, content, author, topic_id, parent_id) VALUES (datetime('now'), :content, :author, :topic_id, :parent_id)");
//     $stmt->bindParam(':content', $content[rand(0, count($content)-1)]);
//     $stmt->bindParam(':author', $authors[rand(0, count($authors)-1)]);
//     $stmt->bindParam(':topic_id', $topic_id);
//     $stmt->bindParam(':parent_id', $parent_id);
//     $stmt->execute();
// }





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
    // get all topics and join with column name
    $stmt = $db->prepare("SELECT topic.id, topic.created, topic.title,topic.content, topic.author,topic.column,topic.position, columns.column_name FROM topic JOIN columns ON topic.column = columns.id");


    // $stmt = $db->prepare("SELECT * FROM topic");
    $stmt->execute();
    $topics = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    $sort_topics_by_column=[];
    foreach ($topics as $topic) {
        $sort_topics_by_column[$topic['column']][] = $topic;
    }
    response($sort_topics_by_column);
    // response($topics);
}


// get all columns
if (isset($_GET['columns'])) {
    $stmt = $db->prepare("SELECT * FROM columns");
    $stmt->execute();
    $columns = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    response($columns);
}






function response($data)
{
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}