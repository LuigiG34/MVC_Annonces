/**
 * AJAX with XMLHTTP on load
 */
window.addEventListener('load', () => {
    let boutons = document.querySelectorAll('.form-check-input');

    for(let bouton of boutons){
        bouton.addEventListener('click', activer);
    }
})

/**
 * send request with data-id value, update "actif"
 */
function activer()
{
    let xmlhttp = new XMLHttpRequest;

    xmlhttp.open('GET', '/mvc/public/admin/activeAnnonce/'+this.dataset.id);

    xmlhttp.send();
}