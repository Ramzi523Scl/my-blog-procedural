
// let ul = document.querySelector('.navbar-nav');
//
// ul.addEventListener('click', (e) => {
//     ul.querySelector('.active').classList.remove('active');
//     let click = e.target;
//     click.classList.add('active');
// })

let posts = document.querySelectorAll('.post-delete');
let post;
let postId;
posts.forEach((elem) => {
    elem.addEventListener('click', (event) => {
        event.preventDefault();
        console.log(event.target.className === 'post-delete');
        if(event.target.className === 'post-delete') {
            postId = elem.id;
            sendId(postId);
        }

    });
});

function sendId(postId) {
    console.log(postId)
    let params = new URLSearchParams('postId' + postId);
    fetch('..function/delete_post.php', {
        method: 'POST',
        body: params
    }).then(
        response => response.text()
    ).then(
        text => {
            // Обработка ответа от сервера
            console.log('Ответ от сервера ' + text);
        })
        .catch(error => {
            console.log('Ошибки ' + error);

            // Обработка ошибок
        });
}
// console.log(posts);