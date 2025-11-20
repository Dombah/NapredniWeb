<?php
require_once "iRadovi.php";

class DiplomskiRadovi implements iRadovi {

    public $naziv_rada;
    public $tekst_rada;
    public $link_rada;
    public $oib_tvrtke;

    private static $items = []; // lista svih objekata

    public function create($naziv, $tekst, $link, $oib): void {
        $this->naziv_rada = $naziv;
        $this->tekst_rada = $tekst;
        $this->link_rada  = $link;
        $this->oib_tvrtke = $oib;
        self::$items[] = $this;
    }

    public function save($conn): void {
        $sql = "INSERT IGNORE INTO diplomski_radovi
                (naziv_rada, tekst_rada, link_rada, oib_tvrtke)
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        foreach (self::$items as $item) {
            $stmt->bind_param(
                "ssss",
                $item->naziv_rada,
                $item->tekst_rada,
                $item->link_rada,
                $item->oib_tvrtke
            );
            $stmt->execute();
        }
        $stmt->close();
    }

    public function read($conn) {
        $sql = "SELECT * FROM diplomski_radovi";
        return $conn->query($sql);
    }
}
?>