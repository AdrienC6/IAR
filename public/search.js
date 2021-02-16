
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

// Modal for Event Detail
modal = document.querySelectorAll('.modal');

Array.from(modal).forEach(modal => {
    modal.addEventListener('click', () => {
        detail = document.getElementById(modal.dataset.id);
        detail.style.display = 'block';
        iH = document.querySelector('.hide-' + modal.dataset.id);
        iH.addEventListener('click', () => {
            detail.style.display = "none"
        })
    })
})

