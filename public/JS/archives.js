const search = document.querySelector('#search');
const yearSelector = document.querySelector('#yearSelector');

search.addEventListener('click', ()=>{
    let links = document.querySelectorAll('.pdf-link')
    links.forEach(link=>{
        if (link.dataset.year == yearSelector.value) {
            link.style.display = "block";
        } else {
            link.style.display = "none";
        }
    })
})