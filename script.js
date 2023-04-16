const pageURL = new URL(window.location.href);
const API = pageURL.origin + '/api.php?';
const columnsEL = document.querySelector('.columns');
let columnsArray = [];
document.addEventListener('DOMContentLoaded', () => {
    getTopics();
    // getColumns();
});

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
                console.log(column.column_name);
                // console.log(topics[column.id]);

                const columnEL = document.createElement('article');
                columnEL.classList.add('column');
                columnEL.id = 'column_' + column.id;
                columnEL.innerHTML = `
                  <header class="column__header">
                      <h2>${column.column_name}</h2>
                      <span class="column__header__add-topic header_icon icon_plus" onclick="showNewTopicForm(event)"></span>
                  </header> 
                  <div class="column__topics">
                  </div>
                
              `;
                columnsEL.appendChild(columnEL);

                if (topics[column.id]) {
                    topics[column.id].forEach((topic) => {
                        // console.log(topic);
                        const topicEL = document.createElement('details');
                        topicEL.classList.add('topic');
                        // topicEL.addAttribute('draggable', 'true');
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
    const id = event.target.parentElement.parentElement.id.split('_')[1];
    console.log(id);
    const posLeft = event.target.getBoundingClientRect().left;
    const posTop = event.target.getBoundingClientRect().top;
    const posHeight = event.target.getBoundingClientRect().height;

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
            ${columnsArray.map((column) => `<option value="${column.id}" ${column.id == id ? 'selected' : ''}>${column.column_name}</option>`)}
          </select>
          <input type="text" name="title" placeholder="Topic title" />
          <textarea name="content" placeholder="Topic content"></textarea>
          <input type="text" name="name" placeholder="Name" />  
          <footer class="new-topic__footer">
            <span class="new-topic__footer__add-topic secondary outline" role="button" onclick="addTopic(event, ${id})">Add Topic</span>
          </footer>
        </form>
    `;
    columnsEL.appendChild(newTopic);

    // const column = document.querySelector('#column_' + id);
    // const topic = document.createElement('details');
    // topic.classList.add('topic');
    // topic.innerHTML = `
    //     <summary class="topic__title" role="button">New Topic</summary>
    //     <p class="topic__description">
    //         <p>Topic content</p>
    //     </p>
    // `;
    // column.querySelector('.column__topics').appendChild(topic);
}

function closeNewTopic(event) {
    event.target.parentElement.parentElement.remove();
}
