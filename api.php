<?php


/**
 * preflight options request.
 */
if ('OPTIONS' === $_SERVER['REQUEST_METHOD']) {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: *');
    header('Access-Control-Allow-Headers: *');
    header('Access-Control-Max-Age: 1728000');

    exit;
}



// show all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$columns = array('todo', 'in progress', 'done', 'archive');

$response = array();

if (isset($_GET['init'])) {
    // remove db file
    unlink('comments.db');
    $response['db'][] = 'old file removed';
}

// create a sqlite database
$db = new PDO('sqlite:comments.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['init'])) {
    global $db, $columns;

    $db->exec("CREATE TABLE IF NOT EXISTS columns(
          id    INTEGER PRIMARY KEY,
          position int(11) NOT NULL,
          column_name text NOT NULL
        ) ");


    $db->exec("CREATE TABLE IF NOT EXISTS topic (
          id    INTEGER PRIMARY KEY,
          topic text DEFAULT 'TOPIC',
          position int(11) DEFAULT 0,
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
    $response['db'][] = 'new file created';


    // create columns
    for ($i=0; $i < 4; $i++) {
        $stmt = $db->prepare("INSERT INTO columns (column_name, position) VALUES (:column_name, $i)");
        $stmt->bindParam(':column_name', $columns[$i]);
        $stmt->execute();
    }
    // get columns
    $stmt = $db->prepare("SELECT column_name FROM columns");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // strigify columns
    $columns = array_map(function ($column) {
        return $column['column_name'];
    }, $columns);
    $response['columns'] = $columns;
}

if (isset($_GET['dummy'])) {
    global $db, $columns;
    $topic_count = $_GET['dummy'] ? $_GET['dummy'] : 10;
    // author array
    $authors = array('John', 'Paul', 'George', 'Ringo');
    // array with animal names
    $title = array('dog', 'cat', 'bird', 'fish', 'snake', 'lizard', 'frog', 'turtle', 'hamster', 'rabbit', 'mouse', 'cow', 'horse', 'pig', 'sheep', 'goat', 'chicken', 'duck', 'goose', 'turkey', 'deer', 'bear', 'wolf', 'fox', 'lion', 'tiger', 'elephant', 'giraffe', 'zebra', 'monkey', 'gorilla', 'panda', 'koala', 'hippo', 'rhino', 'dolphin', 'whale', 'shark', 'seal', 'walrus', 'penguin', 'owl', 'eagle', 'hawk', 'sparrow', 'ant', 'bee', 'butterfly', 'dragonfly', 'mosquito', 'fly', 'spider', 'scorpion', 'crab', 'lobster', 'shrimp', 'snail', 'slug', 'octopus', 'squid', 'starfish', 'seahorse', 'jellyfish', 'clam', 'mushroom', 'tree', 'flower', 'grass', 'leaf', 'bush', 'cactus', 'rock', 'mountain', 'hill', 'valley', 'ocean', 'lake', 'river', 'pond', 'island', 'desert', 'forest', 'cave', 'cloud', 'rain', 'snow', 'sun', 'moon', 'star', 'planet', 'galaxy', 'universe', 'wind', 'rainbow', 'lightning', 'thunder', 'fire', 'water', 'ice', 'earth', 'air', 'time', 'space', 'music', 'dance', 'poetry', 'art', 'painting', 'drawing', 'sculpture', 'photography', 'film', 'television', 'computer', 'phone', 'book', 'magazine', 'newspaper', 'pen', 'pencil', 'eraser', 'notebook', 'paper', 'glass', 'cup', 'plate', 'bowl', 'fork', 'knife', 'spoon', 'chair', 'table', 'bed', 'lamp', 'door', 'window', 'car', 'truck', 'bus', 'train', 'plane', 'boat', 'ship');
    $content = array('Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl.', 'Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl.', 'Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl.', 'Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl.', 'Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl.', 'Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl.', 'Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Nullam auctor, nisl nec ');
    // create ten topics
    for ($i=0; $i < $topic_count; $i++) {
        $stmt = $db->prepare("INSERT INTO topic (position, column, created, content, title, author) VALUES (:position, :column, datetime('now'), :content, :title, :author)");
        $column_id= rand(0, count($columns));
        $position = $i;
        $stmt->bindParam(':position', $position);
        $stmt->bindParam(':column', $column_id);
        $stmt->bindParam(':title', $title[rand(0, count($title)-1)]);
        $stmt->bindParam(':content', $content[rand(0, count($content)-1)]);
        $stmt->bindParam(':author', $authors[rand(0, count($authors)-1)]);
        $stmt->execute();
    }

    $response['dummy'] = "created ".$topic_count." topics";

    // // create 100 comments
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
}




// create a new topic
if (isset($_GET['addTopic'])) {
    $data = json_decode(file_get_contents('php://input'), true);
    // response($data);
    if (empty($data['title']) || empty($data['content']) || empty($data['author']) || empty($data['column'])) {
        response(array('message' => 'Missing data', 'data' => $data));
    }
    $stmt = $db->prepare("INSERT INTO topic (column, created, title, content, author) VALUES (:column, :date, :title, :content, :author)");
    $stmt->bindParam(':date', $data['created']);
    $stmt->bindParam(':title', $data['title']);
    $stmt->bindParam(':column', $data['column']);
    $stmt->bindParam(':content', $data['content']);
    $stmt->bindParam(':author', $data['author']);
    $stmt->execute();

    $topic_id = $db->lastInsertId();

    response(array('message' => 'success', 'topic_id' => $topic_id));
}


// create a new topic
if (isset($_GET['moveTopic'])) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data['topicId']) || empty($data['columnId'])) {
        response(array('message' => 'Missing data', 'data' => $data));
    }
    $stmt = $db->prepare("UPDATE topic SET column = :column WHERE id = :topic_id");
    $stmt->bindParam(':column', $data['columnId']);
    $stmt->bindParam(':topic_id', $data['topicId']);
    $stmt->execute();

    response(array('message' => 'success'));
}




// create a new comment
if (isset($_POST['comment'])) {
    $stmt = $db->prepare("INSERT INTO comment (created, content, author, topic_id, parent_id) VALUES (:date, :content, :author, :topic_id, :parent_id)");
    $stmt->bindParam(':date', $data['created']);
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


if (isset($_GET['start_alt'])) {
    $stmt = $db->prepare("SELECT topic.id, topic.created, topic.title,topic.content, topic.author,topic.column,topic.position, columns.column_name FROM topic JOIN columns ON topic.column = columns.id");


    // $stmt = $db->prepare("SELECT * FROM topic");
    $stmt->execute();
    $topics = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    $sort_topics_by_column=[];
    foreach ($topics as $topic) {
        $sort_topics_by_column[$topic['column']][] = $topic;
    }

    $stmt = $db->prepare("SELECT * FROM columns");
    $stmt->execute();
    $columns = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    response(array('columns' => $columns, 'topics' => $sort_topics_by_column));
}

if (isset($_GET['start'])) {
    // get all tiopics
    $stmt = $db->prepare("SELECT * FROM topic ORDER BY position ASC");
    $stmt->execute();
    $topics = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    // get all columns
    $stmt = $db->prepare("SELECT * FROM columns ORDER BY position ASC");
    $stmt->execute();
    $columns = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    // loop over columns and add topics
    foreach ($columns as $key => $column) {
        $columns[$key]['topics'] = [];
        foreach ($topics as $topic) {
            if ($topic['column'] == $column['id']) {
                $topic['column_name'] = $column['column_name'];
                $columns[$key]['topics'][] = $topic;
            }
        }
    }

    response($columns);
}



if (isset($_GET['updatecolumnpositions'])) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data)) {
        response(array('message' => 'Missing data', 'data' => $data));
    }
    $columnId = [];
    foreach ($data as $row) {
        $stmt = $db->prepare("UPDATE columns SET position = :position WHERE id = :column_id");
        $stmt->bindParam(':position', $row['position']);
        $stmt->bindParam(':column_id', $row['id']);
        $stmt->execute();
        $columnId[] = $db->lastInsertId();
    }
    response(array('message' => 'success', 'columnId' => $columnId));
}




response($response);

function response($data)
{
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json; charset=UTF-8');
    header('Access-Control-Allow-Methods: GET');
    header('Access-Control-Max-Age: 3600');
    header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
    echo json_encode($data);
    exit;
}
