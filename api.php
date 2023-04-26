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

$colors = array('#e5c07b','#e06c75','#5c6370','#f44747','#56b6c2','#98c379','#7f848e','#abb2bf','#61afef','#c678dd','#d19a66','#be5046');

$response = array();


if (isset($_GET['db'])) {
    $db_name = $_GET['db'];
    $response['meta']['db']['name'] = $db_name;
    if (!file_exists($db_name)) {
        $response['meta']['new file'] = 'new file created';
        $_GET['init'] = 'true';
    }
    $response['meta']['GET'] = $_GET;

// response($response);
} else {
    $response['error'] = 'no db name';
    response($response);
}



if (isset($_GET['init'])) {
    // remove db file
    @unlink($db_name);
    $response['meta']['db']['old file removed'] = true;
}

// create a sqlite database
$db = new PDO('sqlite:'.$db_name);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['init'])) {
    global $db, $columns;

    $db->exec("CREATE TABLE IF NOT EXISTS columns(
          id    INTEGER PRIMARY KEY,
          position int(11) NOT NULL,
          column_name text NOT NULL,
          color text DEFAULT '#fff'
        ) ");

    // topic text DEFAULT 'TOPIC',

    $db->exec("CREATE TABLE IF NOT EXISTS topic (
          id    INTEGER PRIMARY KEY,
          position int(11) DEFAULT 0,
          column int(11) NOT NULL,
          created datetime NOT NULL,
          deadline datetime DEFAULT NULL,
          author varchar(255) NOT NULL,
          title text NOT NULL,
          content text NOT NULL,
          color text DEFAULT '#fff'
        ) ");

    // create comment table
    // comment text DEFAULT 'COMMENT',
    $db->exec("CREATE TABLE IF NOT EXISTS comment (
          id    INTEGER PRIMARY KEY,
          created datetime NOT NULL,
          content text NOT NULL,
          author varchar(255) NOT NULL,
          topic_id int(11) NOT NULL,
          parent_id int(11) NOT NULL
        ) ");
    $response['meta']['db']['new file created'] = true;


    // create columns
    for ($i=0; $i < 4; $i++) {
        $stmt = $db->prepare("INSERT INTO columns (column_name, position, color) VALUES (:column_name, $i,:color)");
        $stmt->bindParam(':column_name', $columns[$i]);
        $stmt->bindParam(':color', $colors[rand(0, count($colors)-1)]);
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
    $response['meta']['columns'] = $columns;
}


if (isset($_GET['test'])) {
    echo "<pre>";
    $md = createMarkdownContent();
    echo $md['title'];
    print_r($md);
}


function createMarkdownContent()
{
    $titles = array('dog', 'cat', 'bird', 'fish', 'snake', 'lizard', 'frog', 'turtle', 'hamster', 'rabbit', 'mouse', 'cow', 'horse', 'pig', 'sheep', 'goat', 'chicken', 'duck', 'goose', 'turkey', 'deer', 'bear', 'wolf', 'fox', 'lion', 'tiger', 'elephant', 'giraffe', 'zebra', 'monkey', 'gorilla', 'panda', 'koala', 'hippo', 'rhino', 'dolphin', 'whale', 'shark', 'seal', 'walrus', 'penguin', 'owl', 'eagle', 'hawk', 'sparrow', 'ant', 'bee', 'butterfly', 'dragonfly', 'mosquito', 'fly', 'spider', 'scorpion', 'crab', 'lobster', 'shrimp', 'snail', 'slug', 'octopus', 'squid', 'starfish', 'seahorse', 'jellyfish', 'clam', 'mushroom', 'tree', 'flower', 'grass', 'leaf', 'bush', 'cactus', 'rock', 'mountain', 'hill', 'valley', 'ocean', 'lake', 'river', 'pond', 'island', 'desert', 'forest', 'cave', 'cloud', 'rain', 'snow', 'sun', 'moon', 'star', 'planet', 'galaxy', 'universe', 'wind', 'rainbow', 'lightning', 'thunder', 'fire', 'water', 'ice', 'earth', 'air', 'time', 'space', 'music', 'dance', 'poetry', 'art', 'painting', 'drawing', 'sculpture', 'photography', 'film', 'television', 'computer', 'phone', 'book', 'magazine', 'newspaper', 'pen', 'pencil', 'eraser', 'notebook', 'paper', 'glass', 'cup', 'plate', 'bowl', 'fork', 'knife', 'spoon', 'chair', 'table', 'bed', 'lamp', 'door', 'window', 'car', 'truck', 'bus', 'train', 'plane', 'boat', 'ship');
    $content = array('Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Nullam auctor,  Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl.', 'Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl.', 'Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl.', 'Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl.', 'Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl.', 'Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl. Nullam auctor, nisl nec luctus aliquam, nunc nisl aliquet nunc, vel aliquet nisl nunc vel nisl.', 'Lorem ipsum dolor sit amet, consec tetur adipiscing elit. Nullam auctor, nisl nec ');
    $list = array('Lorem', 'ipsum', 'dolor', 'sit', 'amet', 'consec', 'tetur', 'adipiscing', 'elit', 'Nullam', 'auctor', 'nisl', 'nec', 'luctus', 'aliquam', 'nunc', 'aliquet', 'vel', 'nunc', 'vel', 'nisl', 'Nullam', 'auctor', 'nisl', 'nec', 'luctus', 'aliquam', 'nunc', 'aliquet', 'vel', 'nunc', 'vel', 'nisl', 'Nullam', 'auctor', 'nisl', 'nec', 'luctus', 'aliquam', 'nunc', 'aliquet', 'vel', 'nunc', 'vel', 'nisl', 'Nullam', 'auctor', 'nisl', 'nec', 'luctus', 'aliquam', 'nunc', 'aliquet', 'vel', 'nunc', 'vel', 'nisl', 'Nullam', 'auctor', 'nisl', 'nec', 'luctus', 'aliquam', 'nunc', 'aliquet', 'vel', 'nunc', 'vel', 'nisl', 'Nullam', 'auctor', 'nisl', 'nec', 'luctus', 'aliquam', 'nunc', 'aliquet', 'vel', 'nunc', 'vel', 'nisl', 'Nullam', 'auctor', 'nisl', 'nec', 'luctus', 'aliquam', 'nunc', 'aliquet', 'vel', 'nunc', 'vel', 'nisl');
    $markdown = array();
    $title =  $titles[rand(0, count($titles)-1)];

    $markdown['title'] = $title;
    $markdown['content'] = '# ' . $title. "\n";
    $markdown['content'] .= $content[rand(0, count($content)-1)] . "\n";
    for ($j=0; $j < rand(2, 7); $j++) {
        $markdown['content'] .= '- ' . $list[rand(0, count($list)-1)] . "\n";
    }
    $markdown['content'] .= '## ' . $titles[rand(0, count($titles)-1)] . "\n";
    $markdown['content'] .= $content[rand(0, count($content)-1)] . "\n";
    return $markdown;
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
        $stmt = $db->prepare("INSERT INTO topic (position, column, created, content, title, author, color) VALUES (:position, :column, datetime('now'), :content, :title, :author,:color)");
        $column_id= rand(0, count($columns));
        $position = $i;
        $md = createMarkdownContent();
        $stmt->bindParam(':position', $position);
        $stmt->bindParam(':column', $column_id);
        $stmt->bindParam(':title', $md['title']);
        $stmt->bindParam(':content', $md['content']);
        $stmt->bindParam(':author', $authors[rand(0, count($authors)-1)]);
        $stmt->bindParam(':color', $colors[rand(0, count($colors)-1)]);
        $stmt->execute();
    }

    $response['meta']['dummy'] = "created ".$topic_count." topics";

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
    $stmt = $db->prepare("INSERT INTO topic (column, created, title, content, author,color,deadline) VALUES (:column, :date, :title, :content, :author,:color,:deadline)");
    $stmt->bindParam(':date', $data['created']);
    $stmt->bindParam(':color', $data['color']);
    $stmt->bindParam(':title', $data['title']);
    $stmt->bindParam(':column', $data['column']);
    $stmt->bindParam(':content', $data['content']);
    $stmt->bindParam(':author', $data['author']);
    $stmt->bindParam(':deadline', $data['deadline']);
    $stmt->execute();

    $topic_id = $db->lastInsertId();

    response(array('message' => 'success', 'topic_id' => $topic_id));
}
if (isset($_GET['editTopic'])) {
    $data = json_decode(file_get_contents('php://input'), true);
    // response($data);
    if (empty($data['title']) || empty($data['content']) || empty($data['author']) || empty($data['column'])) {
        response(array('message' => 'Missing data', 'data' => $data));
    }
    $stmt = $db->prepare("UPDATE topic SET column = :column, title = :title, content = :content, author = :author, color = :color, deadline = :deadline WHERE id = :topic_id");
    $stmt->bindParam(':column', $data['column']);
    $stmt->bindParam(':title', $data['title']);
    $stmt->bindParam(':content', $data['content']);
    $stmt->bindParam(':author', $data['author']);
    $stmt->bindParam(':color', $data['color']);
    $stmt->bindParam(':deadline', $data['deadline']);
    $stmt->bindParam(':topic_id', $data['id']);
    $stmt->execute();

    response(array('message' => 'success'));
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
    $response['meta']['topicCount'] =  count($topics);

    // get all columns
    $stmt = $db->prepare("SELECT * FROM columns ORDER BY position ASC");
    $stmt->execute();
    $columns = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $response['meta']['columnCount'] =  count($columns);

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
    $response['columns'] = $columns;

    response($response);
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

if (isset($_GET['updatetopicpositions'])) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data)) {
        response(array('message' => 'Missing data', 'data' => $data));
    }
    $topicId = [];
    $sqlArray = [];
    foreach ($data as $row) {
        // string to int
        $row['position'] = (string)$row['position'];
        $row['id'] = (string)$row['id'];
        $row['column_id'] = (string)$row['column_id'];


        // update topic position and column
        // $sql = "UPDATE topic SET position = ".$row['position'].", column = ".$row['column_id']." WHERE id = ".$row['id'];
        // $sqlArray[] = $sql;
        // $db->exec($sql);

        $stmt = $db->prepare("UPDATE topic SET position = :position, column = :column WHERE id = :topic_id");
        $stmt->bindParam(':position', $row['position']);
        $stmt->bindParam(':topic_id', $row['id']);
        $stmt->bindParam(':column', $row['column_id']);
        $stmt->execute();
        $topicId[] = $db->lastInsertId();
    }
    response(array('message' => 'success', 'topicId' => $topicId, 'data' => $data, 'sql'=>$sqlArray));
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
