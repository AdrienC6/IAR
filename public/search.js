searchButton = document.querySelector('#searchButton');
words = document.querySelector('#words');
searchForm = document.querySelector('#searchForm');

searchButton.addEventListener('click', (e) => {
    e.preventDefault();

    if (words.value == "") {
        words.placeholder = "Veuillez saisir au moins un mot clé";
        setTimeout(() => {
            words.placeholder = "Recherche..."

        }, 1500);
    } else {
        searchForm.submit();
    }
})