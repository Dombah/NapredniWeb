<?php

require "DiplomskiRadovi.php";
include("./simplehtmldom/simple_html_dom.php");

$conn = new mysqli("localhost", "root", "", "radovi");
if ($conn->connect_error) {
    die("Greška spajanja na bazu: " . $conn->connect_error);
}

$radovi = [];

for ($i = 2; $i <= 2; $i++) {
    $url = "https://stup.ferit.hr/index.php/zavrsni-radovi/page/" . $i;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    if ($response === false) {
        echo "cURL error ($url): " . curl_error($curl) . "<br>";
        curl_close($curl);
        continue;
    }
    curl_close($curl);

    $html = str_get_html($response);
    if (!$html) {
        echo "<br>simple_html_dom NE može parsirati odgovor za $url.<br>";
        continue;
    }

    foreach ($html->find('article') as $article) {
        $img = $article->find('img', 0);
        $a   = $article->find('a', 0);

        if (!$img || !$a) {
            continue;
        }

        // OIB iz URL‑a slike
        preg_match('/\d+/', $img->src, $m);
        $oib = $m[0] ?? '';

        $naziv = trim($a->plaintext);
        $link  = $a->href;

        // dohvat teksta rada sa stranice linka
        $c2 = curl_init($link);
        curl_setopt($c2, CURLOPT_RETURNTRANSFER, true);
        $resp2 = curl_exec($c2);
        curl_close($c2);

        $page = str_get_html($resp2);
        $tekst = '';

        if ($page) {
            // svi <p> unutar .post-content
            $paragraphs = $page->find('div.post-content p');
            foreach ($paragraphs as $p) {
                $t = trim($p->plaintext);
                // preskoči prazne i samo-bold naslove tipa "Opis teme:"
                if ($t === '' || stripos($t, 'opis teme') !== false) {
                    continue;
                }
                $tekst = $t;
                break; // uzmi prvi "pravi" paragraf
            }
        }

        $obj = new DiplomskiRadovi();
        $obj->create($naziv, $tekst, $link, $oib);
        $radovi[] = $obj;
    }
}

// testni ispis
foreach ($radovi as $r) {
    echo "<hr>";
    echo "Naziv: {$r->naziv_rada}<br>";
    echo "OIB: {$r->oib_tvrtke}<br>";
    echo "Link: {$r->link_rada}<br>";

    if (trim($r->tekst_rada) === '') {
        echo "Tekst rada: [PRAZNO] – element nije pronađen ili nema teksta.<br>";
    } else {
        echo "Tekst (prvih 300 znakova):<br>";
        echo "<pre>" . htmlspecialchars(substr($r->tekst_rada, 0, 300)) . "</pre>";
    }
}
if (!empty($radovi)) {
    $radovi[0]->save($conn);
    echo "<h2>Spremljeno u bazu.</h2>";
}

// primjer: čitanje iz baze
$d = new DiplomskiRadovi();
$result = $d->read($conn);

echo "<h2>Radovi iz baze:</h2>";
while ($row = $result->fetch_assoc()) {
    echo "<hr>";
    echo "ID: {$row['ID']}<br>";
    echo "Naziv: {$row['naziv_rada']}<br>";
    echo "OIB: {$row['oib_tvrtke']}<br>";
    echo "Link: {$row['link_rada']}<br>";
    echo "Tekst (prvih 300 znakova):<br>";
    echo "<pre>" . htmlspecialchars(substr($row['tekst_rada'], 0, 300)) . "</pre>";
}

$conn->close();
?>
