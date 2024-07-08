// скрипти для сторінки авторів

const authorsHeader = document.getElementById('authors-from-json');
authorsHeader.innerHTML = 'Click to get authors from JSON';

authorsHeader.onclick = function (event) {
    console.log(event)
    renderAuthorsFromJSON();
}

function renderAuthorsFromJSON() {
    fetch('/authors.json', {
        headers: {
            "Content-Type": "application/json",
        }
    })
        .then(response => response.json())
        .then(function (authors) {
            console.log(authors);
            let html = '';
            for (let i = 0; i < authors.length; i++) {
                let author = authors[i];
                html += `<div>${author.firstName} ${author.lastName}</div>`;
            }
            document.getElementById('authors-from-json').innerHTML = html;
        })
}