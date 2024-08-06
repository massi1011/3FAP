document.addEventListener('DOMContentLoaded', () => {
    fetchProduits();
    fetchServices();
});


function fetchProduits() {
    fetch('get_produits.php')
        .then(response => response.json())
        .then(data => {
            const catalogue = document.getElementById('catalogue-produits');
            catalogue.innerHTML = '';
            data.forEach(produit => {
                catalogue.innerHTML += `
                    <div class="produit">
                        <h4>${produit.nom}</h4>
                        <img src="${produit.image_url}" alt="${produit.nom}" style="width:200px; height:auto;" />
                        <p>${produit.description}</p>
                        <p>Prix: ${produit.prix}€</p>
                        <button onclick="ajouterAuPanier(${produit.id}, '${produit.nom}', ${produit.prix})">Ajouter au Panier</button>
                    </div>
                `;
            });
        });
}


function fetchServices() {
    fetch('get_services.php')
        .then(response => response.json())
        .then(data => {
            const serviceSelect = document.getElementById('service');
            serviceSelect.innerHTML = '';
            data.forEach(service => {
                serviceSelect.innerHTML += `<option value="${service.id}">${service.nom}</option>`;
            });
        });
}




function ajouterAuPanier(id, nom, prix) {
    // Récupère le panier depuis le stockage local
    let panier = JSON.parse(localStorage.getItem('panier')) || [];
    const produitExist = panier.find(item => item.id === id);
    if (produitExist) {
        produitExist.quantite += 1;
    } else {
        panier.push({ id, nom, prix, quantite: 1 });
    }

    // Sauvegarde le panier mis à jour dans le stockage local
    localStorage.setItem('panier', JSON.stringify(panier));
    afficherPanier();
   
}



function afficherPanier() {
    const panier = JSON.parse(localStorage.getItem('panier')) || [];
    const panierDiv = document.getElementById('panier');
    panierDiv.innerHTML = '';
    panier.forEach(item => {
        panierDiv.innerHTML += `
            <div>
                <p>${item.nom} - ${item.quantite || 0} x ${item.prix ? item.prix.toFixed(2) : '0.00'}€</p>
            </div>)}
        `;
    });
}

function validerPanier() {
    const panier = JSON.parse(localStorage.getItem('panier')) || [];

    if (panier.length === 0) {
        alert("Votre panier est vide.");
        return;
    }

    fetch('valider_panier.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(panier)
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        localStorage.removeItem('panier');
        afficherPanier();
    })
    .catch(error => console.error('Erreur:', error));
}