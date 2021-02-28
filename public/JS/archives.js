const search = document.querySelector('#search');
const yearSelector = document.querySelector('#yearSelector');
let links = document.querySelectorAll('.pdf-link')

links.forEach(link=>{
    link.style.display = "none";
})

search.addEventListener('click', ()=>{
    links.forEach(link=>{
        if (link.dataset.year == yearSelector.value) {
            link.style.display = "block";
        } else {
            link.style.display = "none";
        }
    })
})