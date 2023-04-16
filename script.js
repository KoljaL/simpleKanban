const pageURL = new URL(window.location.href);
// const API = pageURL.href + '/api.php?';
const API = 'https://dev.rasal.de/skanban/api.php?';
const columnsEL = document.querySelector('.columns');
let columnsArray = [];
document.addEventListener('DOMContentLoaded', () => {
    getTopics();
});

document.addEventListener(
    'dragstart',
    function (ev) {
        onDragStart(ev);
    },
    false
);
document.addEventListener(
    'dragend',
    (ev) => {
        // console.log(ev.dataTransfer);
    },
    false
);
document.addEventListener(
    'dragover',
    function (ev) {
        onDragOver(ev);
    },
    false
);

document.addEventListener(
    'dragleave',
    function (ev) {
        onDragLeave(ev);
    },
    false
);

document.addEventListener(
    'drop',
    function (ev) {
        onDrop(ev);
    },
    false
);

function onDragStart(ev) {
    ev.dataTransfer.setData('text/plain', event.target.id);
    // ev.target.style.backgroundColor = 'yellow';
    console.log('drag ev', ev);
    // console.log('drag ID', event.target.id);
}

function onDragOver(ev) {
    ev.preventDefault();
    // console.log('dragover ev', ev);
    if (ev.target.classList.contains('column__topics')) {
        ev.target.classList.add('column__topics--drag-hover');
    }
}

function onDragLeave(ev) {
    ev.preventDefault();
    // console.log('dragleave ev', ev);
    if (ev.target.classList.contains('column__topics')) {
        ev.target.classList.remove('column__topics--drag-hover');
    }
}

function onDrop(ev) {
    ev.preventDefault();
    console.log(ev);
    console.log(ev.dataTransfer.getData('text/plain'));
    const draggedTopic = document.getElementById(ev.dataTransfer.getData('text/plain'));
    // var data = ev.dataTransfer.getData('text');
    const elID = ev.target.parentNode.id;
    console.log(draggedTopic);
    // const child = document.getElementById(ev.dataTransfer.getData('text'));
    const child = document.querySelector(`#${elID} .column__topics`);
    console.log(ev.target);
    console.log(child);
    ev.target.appendChild(draggedTopic);

    if (ev.target.classList.contains('column__topics')) {
        ev.target.classList.remove('column__topics--drag-hover');
    }

    const topicId = elID.split('_')[1];
    const columnId = ev.target.parentElement.id.split('_')[1];
    console.log(topicId);
    console.log(columnId);
    // fetch(API + 'moveTopic', {
    //     method: 'POST',
    //     body: JSON.stringify({ topicId, columnId }),
    // })
    //     .then((response) => response.json())
    //     .then((data) => {
    //         console.log(data);
    //     });
}

async function getTopics() {
    try {
        const response = await fetch(API + 'topics');
        const topics = await response.json();
        console.log('topics', topics);
        createColumns(topics);
    } catch (error) {
        console.error(error);
    }
}
async function createColumns(topics) {
    fetch(API + 'columns')
        .then((response) => response.json())
        .then((data) => {
            // console.log(topics);
            // console.log(data);
            columnsArray = data;
            data.forEach((column) => {
                // console.log(column.id);
                // console.log(topics[column.id]);

                const columnEL = document.createElement('article');
                columnEL.classList.add('column');
                columnEL.id = 'column_' + column.id;
                columnEL.innerHTML = `
                  <header class="column__header">
                      <h2>${column.column_name}</h2>
                      <span class="column__header__add-topic header_icon icon_plus" onclick="showNewTopicForm(event)"></span>
                  </header> 
                  <div class="column__topics"></div>
              `;
                columnsEL.appendChild(columnEL);

                if (topics[column.id]) {
                    topics[column.id].forEach((topic) => {
                        // console.log(topic);
                        const topicEL = document.createElement('details');
                        topicEL.classList.add('topic');
                        topicEL.id = 'topic_' + topic.id;
                        topicEL.setAttribute('draggable', 'true');
                        topicEL.innerHTML = `
                      <summary class="topic__title" role="button">${topic.title}</summary>
                      <small>${topic.created}</small>
                      <p class="topic__description">${topic.content}</p> 
                      <small>By ${topic.author}</small>
                  `;
                        columnEL.querySelector('.column__topics').appendChild(topicEL);
                    });
                }
            });
        });
}

function showNewTopicForm(event) {
    console.log(event);
    const columnId = event.target.parentElement.parentElement.id.split('_')[1];
    console.log(columnId);
    const posLeft = event.target.getBoundingClientRect().left;
    const posTop = event.target.getBoundingClientRect().top;
    const posHeight = event.target.getBoundingClientRect().height;
    const authorName = window.localStorage.getItem('skanbanName') || '';

    const newTopic = document.createElement('article');
    newTopic.classList.add('new-topic__modal');
    newTopic.style.left = posLeft + 'px';
    newTopic.style.top = posTop + posHeight + 'px';
    newTopic.innerHTML = `
        <header class="new-topic__header">
            <h2>New Topic</h2>
            <span class="new-topic__header__close header_icon icon_close" role="button" onclick="closeNewTopic(event)"></span>
        </header>
        <form class="new-topic__form">
          <select name="column">
            ${columnsArray.map((column) => `<option value="${column.id}" ${column.id == columnId ? 'selected' : ''}>${column.column_name}</option>`)}
          </select>
          <input type="text" name="title" placeholder="Topic title" />
          <textarea name="content" placeholder="Topic content"></textarea>
          <input type="text" name="author" placeholder="Name" value="${authorName}" />  
          <footer class="new-topic__footer">
            <span class="new-topic__footer__add-topic" role="button" onclick="addTopic(event, ${columnId})">Add Topic</span>
            <span class="new-topic__footer__error"></span>
          </footer>
        </form>
    `;
    columnsEL.appendChild(newTopic);
}

function closeNewTopic(event) {
    event.target.parentElement.parentElement.remove();
}

function addTopic(event, columnId) {
    event.preventDefault();
    const form = event.target.parentElement.parentElement;
    const formData = new FormData(form);
    const date = new Date().toLocaleDateString('de-de', { year: 'numeric', month: 'numeric', day: 'numeric', hour: 'numeric', minute: 'numeric' });
    const newTopic = {
        title: formData.get('title'),
        content: formData.get('content'),
        column: formData.get('column'),
        author: formData.get('author'),
        created: date,
    };
    // const columnId = id;
    window.localStorage.setItem('skanbanName', newTopic.author);
    console.log(newTopic);
    fetch(API + 'addTopic', {
        method: 'POST',
        body: JSON.stringify(newTopic),
    })
        .then((response) => response.json())
        .then((data) => {
            console.log(data);
            if (data.message === 'success') {
                const column = document.querySelector('#column_' + newTopic.column);
                const topic = document.createElement('details');
                topic.id = 'topic_' + data.topic_id;
                topic.classList.add('topic');
                topic.setAttribute('draggable', 'true');
                topic.innerHTML = `
                    <summary class="topic__title" role="button">${newTopic.title}</summary>
                    <small>${newTopic.created}</small>
                    <p class="topic__description">${newTopic.content}</p> 
                    <small>By ${newTopic.author}</small>
                `;
                column.querySelector('.column__topics').appendChild(topic);
                form.parentElement.remove();
            } else {
                form.parentElement.querySelector('.new-topic__footer__error').innerHTML = data.message;
            }
        });
}
