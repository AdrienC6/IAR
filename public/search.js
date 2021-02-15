searchButton = document.querySelector('#searchButton');
words = document.querySelector('#words');
searchForm = document.querySelector('#searchForm');

searchButton.addEventListener('click', (e) => {
    e.preventDefault();

    if (words.value == "") {
        words.value = "Veuillez saisir au moins un mot clé";
        words.style.color = "#EC6436";
        setTimeout(() => {
            words.value = "";
            words.placeholder = "Recherche..."

        }, 1500);
    } else {
        searchForm.submit();
    }
})