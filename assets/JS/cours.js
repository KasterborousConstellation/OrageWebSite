// On stocke tous les cours pour pouvoir les filtrer plus tard
let filtreActif = null;
let tousLesCours = [];
document.addEventListener('DOMContentLoaded', () => {
    // On récupère tous les cours du DOM
    tousLesCours = Array.from(document.querySelectorAll('.course-card'));

    // On ouvre la première catégorie par défaut
    /*const premierToggle = document.querySelector('.tree-toggle');
    if (premierToggle) {
        basculerArborescence(premierToggle);
    }*/
    // On attache les événements aux boutons d'arborescence
    document.querySelectorAll('.tree-toggle').forEach(toggle => {
        toggle.addEventListener('click', function () {
            basculerArborescence(this);
            filtrerParNiveau(this.dataset.niveau);
        });
    });
    document.getElementById('search-button').onclick= () =>{
        appliquerFiltre();
    };
    document.getElementById('search-input').onchange =()=>{
        appliquerFiltre();
    };
    // On attache les événements aux niveaux
    document.querySelectorAll('.tree-niveau').forEach(item => {
        item.addEventListener('click', function () {
            filtrerParCategorie(this.dataset.categorie, this.dataset.niveau, this);
        });
    });

    // On attache les événements pour effacer le filtre
    document.getElementById('clearFilterBtn').addEventListener('click', effacerFiltre);
    document.getElementById('showAllCoursesBtn').addEventListener('click', effacerFiltre);

    // On attache les événements pour le téléchargement
    document.querySelectorAll('.download-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const courseId = this.dataset.courseId;
            const courseName = this.dataset.courseName;
            gererTelechargement(courseId, courseName);
        });
    });
    appliquerFiltre();
});

/**
 * Fonction pour ouvrir/fermer une branche de l'arborescence
 */
function basculerArborescence(element) {
    const noeud = element.parentElement;
    const enfants = noeud.querySelector('.tree-children'); // Utilisée ici
    const fleche = element.querySelector('.tree-toggle-arrow');

    noeud.classList.toggle('open');

    if (noeud.classList.contains('open')) {
        fleche.style.transform = 'rotate(90deg)';
    } else {
        fleche.style.transform = '';
    }
}

/**
 * Fonction pour filtrer par niveau (ex: Débutant, Intermédiaire)
 */
function filtrerParCategorie(categorie, niveau, element) {
    // On retire la classe "active" de tous les éléments
    document.querySelectorAll('.tree-item').forEach(item => {
        item.classList.remove('active');
    });

    // On ajoute la classe "active" à l'élément cliqué
    element.classList.add('active');

    // On définit le filtre actif
    filtreActif = {categorie, niveau};
    appliquerFiltre();

    // On affiche la barre de filtre
    const barreFiltre = document.getElementById('filterBar');
    const texteFiltre = document.getElementById('filterText');
    barreFiltre.style.display = 'block';
    texteFiltre.textContent = `${niveau} > ${categorie}`;

    // On scroll vers la section des cours
    document.querySelector('.courses-section').scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}

/**
 * Fonction pour filtrer par catégorie (ex: Mathématiques, Informatique)
 */
function filtrerParNiveau(niveau) {
    // On retire la classe "active" de tous les niveaux
    document.querySelectorAll('.tree-item').forEach(item => {
        item.classList.remove('active');
    });

    // On définit le filtre (niveau est null pour afficher tous les niveaux)
    filtreActif = {categorie:null,  niveau};
    appliquerFiltre();

    // On affiche la barre de filtre
    const barreFiltre = document.getElementById('filterBar');
    const texteFiltre = document.getElementById('filterText');
    barreFiltre.style.display = 'block';
    texteFiltre.textContent = niveau;

    // On scroll vers la section des cours
    document.querySelector('.courses-section').scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}

/**
 * Applique le filtre actif sur les cours
 */
function appliquerFiltre() {
    const etatVide = document.getElementById('emptyState');
    const grilleCours = document.getElementById('coursesGrid');
    const search = removeAccents(document.getElementById('search-input').value.toLowerCase());
    let nombreCoursVisibles = 0;
    tousLesCours.forEach(carte => {
        const categorie = carte.dataset.category;
        const niveau = carte.dataset.niveau;
        const name = carte.dataset.name;
        // On vérifie si le cours correspond au filtre
        const categorieOk = !filtreActif?.categorie || categorie === filtreActif.categorie;
        const niveauOk = !filtreActif?.niveau || niveau === filtreActif.niveau;
        const textOk = (!search || search.length ===0 ||
            removeAccents(name.toLowerCase()).includes(search)
            ||
            removeAccents(categorie.toLowerCase()).includes(search)
            ||
            removeAccents(niveau.toLowerCase()).includes(search));
        if ((filtreActif||search||search.length>0) && (!categorieOk || !niveauOk||!textOk)) {
            carte.style.display = 'none';
        } else {
            carte.style.display = '';
            nombreCoursVisibles++;
            carte.classList.add('fade-in');
            setTimeout(() => carte.classList.remove('fade-in'), 500);
        }
    });

    // On met à jour le compteur
    const compteur = document.getElementById('resultsCount');
    compteur.innerHTML = `<strong>${nombreCoursVisibles}</strong> cours trouvé${nombreCoursVisibles > 1 ? 's' : ''}`;

    // On affiche ou masque l'état vide
    if (nombreCoursVisibles === 0) {
        etatVide.style.display = 'flex';
        grilleCours.style.display = 'none';
    } else {
        etatVide.style.display = 'none';
        grilleCours.style.display = 'grid';
    }
}

/**
 * Efface le filtre actif et réaffiche tous les cours
 */
function effacerFiltre() {
    filtreActif = null;

    // On retire la classe active des items
    document.querySelectorAll('.tree-item').forEach(item => {
        item.classList.remove('active');
    });

    // On masque la barre de filtre
    document.getElementById('filterBar').style.display = 'none';

    // On réaffiche tous les cours
    tousLesCours.forEach(carte => {
        carte.style.display = '';
        carte.classList.add('fade-in');
        setTimeout(() => carte.classList.remove('fade-in'), 500);
    });

    // On met à jour le compteur
    const compteur = document.getElementById('resultsCount');
    compteur.innerHTML = `<strong>${tousLesCours.length}</strong> cours trouvés`;

    // On réaffiche la grille

    document.getElementById('coursesGrid').style.display = 'grid';
}

/**
 * Gère le téléchargement d’un cours
 */
function gererTelechargement(courseId, courseName) {
    if (confirm(`Voulez-vous télécharger le cours "${courseName}" ?`)) {
        const bouton = event.target.closest('.download-btn');
        bouton.innerHTML = '<span class="btn-icon">✓</span> Téléchargement...';
        bouton.classList.add('btn-success');

        setTimeout(() => {
            bouton.innerHTML = '<span class="btn-icon">📥</span> Télécharger';
            bouton.classList.remove('btn-success');

            // TODO: Ajouter la logique réelle de téléchargement
            // window.location.href = `/course/download/${courseId}`;
            alert('Le téléchargement va commencer...');
        }, 1500);
    }
}
const removeAccents = str =>
    str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
