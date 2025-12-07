var express = require('express');
var router = express.Router();

// In-memory storage for projects (in production, use a database)
let projects = [
  {
    id: 1,
    naziv: 'Web aplikacija',
    opis: 'Razvoj web aplikacije za upravljanje projektima',
    cijena: 5000,
    obavljeniPoslovi: 'Dizajn, backend razvoj',
    datumPocetka: '2025-01-01',
    datumZavrsetka: '2025-06-30',
    clanovi: [
      { id: 1, ime: 'Ana Anić', uloga: 'Project Manager' },
      { id: 2, ime: 'Ivan Ivić', uloga: 'Developer' }
    ]
  },
  {
    id: 2,
    naziv: 'Mobilna aplikacija',
    opis: 'Razvoj mobilne aplikacije za iOS i Android',
    cijena: 8000,
    obavljeniPoslovi: 'UI/UX dizajn, razvoj iOS verzije',
    datumPocetka: '2025-02-15',
    datumZavrsetka: '2025-08-31',
    clanovi: [
      { id: 3, ime: 'Marko Marković', uloga: 'iOS Developer' }
    ]
  },
  {
    id: 3,
    naziv: 'E-commerce platforma',
    opis: 'Izrada online trgovine s integracijom plaćanja',
    cijena: 12000,
    obavljeniPoslovi: 'Analiza zahtjeva, dizajn baze podataka',
    datumPocetka: '2025-03-01',
    datumZavrsetka: '2025-12-31',
    clanovi: [
      { id: 4, ime: 'Petra Petrić', uloga: 'Full Stack Developer' },
      { id: 5, ime: 'Josip Josipović', uloga: 'QA Engineer' }
    ]
  }
];

let nextId = 4;
let nextMemberId = 6;

/* GET projects listing. */
router.get('/', function(req, res, next) {
  res.render('projects/index', { 
    title: 'Projekti', 
    projects: projects 
  });
});

/* GET new project form. */
router.get('/new', function(req, res, next) {
  res.render('projects/new', { title: 'Novi projekt' });
});

/* POST create new project. */
router.post('/', function(req, res, next) {
  const newProject = {
    id: nextId++,
    naziv: req.body.naziv,
    opis: req.body.opis,
    cijena: parseFloat(req.body.cijena),
    obavljeniPoslovi: req.body.obavljeniPoslovi,
    datumPocetka: req.body.datumPocetka,
    datumZavrsetka: req.body.datumZavrsetka,
    clanovi: []
  };
  projects.push(newProject);
  res.redirect('/projects');
});

/* GET edit project form. */
router.get('/:id/edit', function(req, res, next) {
  const project = projects.find(p => p.id === parseInt(req.params.id));
  if (!project) {
    return res.status(404).send('Projekt nije pronađen');
  }
  res.render('projects/edit', { 
    title: 'Uredi projekt', 
    project: project 
  });
});

/* GET delete project. */
router.get('/:id/delete', function(req, res, next) {
  projects = projects.filter(p => p.id !== parseInt(req.params.id));
  res.redirect('/projects');
});

/* POST update project. */
router.post('/:id', function(req, res, next) {
  const project = projects.find(p => p.id === parseInt(req.params.id));
  if (!project) {
    return res.status(404).send('Projekt nije pronađen');
  }
  
  project.naziv = req.body.naziv;
  project.opis = req.body.opis;
  project.cijena = parseFloat(req.body.cijena);
  project.obavljeniPoslovi = req.body.obavljeniPoslovi;
  project.datumPocetka = req.body.datumPocetka;
  project.datumZavrsetka = req.body.datumZavrsetka;
  
  res.redirect('/projects');
});

/* GET project details. */
router.get('/:id', function(req, res, next) {
  const project = projects.find(p => p.id === parseInt(req.params.id));
  if (!project) {
    return res.status(404).send('Projekt nije pronađen');
  }
  res.render('projects/details', { 
    title: 'Detalji projekta', 
    project: project 
  });
});

/* POST add team member to project. */
router.post('/:id/members', function(req, res, next) {
  const project = projects.find(p => p.id === parseInt(req.params.id));
  if (!project) {
    return res.status(404).send('Projekt nije pronađen');
  }
  
  if (!project.clanovi) {
    project.clanovi = [];
  }
  
  const newMember = {
    id: nextMemberId++,
    ime: req.body.ime,
    uloga: req.body.uloga
  };
  
  project.clanovi.push(newMember);
  res.redirect('/projects/' + req.params.id);
});

/* GET delete team member from project. */
router.get('/:projectId/members/:memberId/delete', function(req, res, next) {
  const project = projects.find(p => p.id === parseInt(req.params.projectId));
  if (!project) {
    return res.status(404).send('Projekt nije pronađen');
  }
  
  if (project.clanovi) {
    project.clanovi = project.clanovi.filter(m => m.id !== parseInt(req.params.memberId));
  }
  
  res.redirect('/projects/' + req.params.projectId);
});

module.exports = router;
