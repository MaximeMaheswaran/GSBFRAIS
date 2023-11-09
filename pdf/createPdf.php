<?php
require('../include/class.pdogsb.inc.php');
include('../include/fct.inc.php');
require("./fpdf/fpdf.php");
$pdo = PdoGsb::getPdoGsb();
session_start();
$idVisiteur = $_SESSION['idVisiteur'];
$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];
$mois = $_GET ['mois'];


$ligneFF = $pdo -> getLesFraisForfait($idVisiteur, $mois);
$ligneFHF = $pdo -> getLesFraisHorsForfait($idVisiteur, $mois);
$FraisForfait = $pdo -> getFraisForfait();


$km = $ligneFF[1];
$nui = $ligneFF[2];
$rep = $ligneFF[3];
$kmff = $FraisForfait[1];
$nuiff = $FraisForfait[2];
$repff= $FraisForfait[3];


// Créer une nouvelle instance de la classe FPDF
$pdf = new FPDF();

// Ajouter une page
$pdf->AddPage();


// Logo : 8 >position à gauche du document (en mm), 2 >position en haut du document, 80 >largeur de l'image en mm). La hauteur est calculée automatiquement.
$pdf->Image('../images/logo.jpg', 85, 2);
// Saut de ligne 20 mm
$pdf->Ln(30);

// Titre gras (B) police Helbetica de 11
$pdf->SetFont('Helvetica', 'B', 17);
// color du texte en blue
$pdf->SetTextColor(60, 78, 150);
// fond de couleur gris (valeurs en RGB)
$pdf->setFillColor(255, 255, 255);
// position du coin supérieur gauche par rapport à la marge gauche (mm)
$pdf->SetX(75);
// Texte : 60 >largeur ligne, 8 >hauteur ligne. Premier 0 >pas de bordure, 1 >retour à la ligneensuite, C >centrer texte, 1> couleur de fond ok  
$pdf->Cell(60, 8, 'ETAT DE FRAIS ENGAGES', 0, 1, 'C', 1);
// Saut de ligne 10 mm
$pdf->Ln(1);

// Ajouter phrase d'attention
$pdf->SetFont("Arial", "B", 11);
$pdf->Cell(80);
$pdf->Cell(30, 10, "A retourner accompagné des justificatifs au plus tard le 10 du mois qui suit l’engagement des frais", 0, 0, "C");
$pdf->Ln(10);

// ajouter  visiteur matricule , nom et mois
$pdf->SetFont("Arial", "B", 14);
$pdf->Cell(30, 10, "Visiteur", 0, 0, "C");
$pdf->SetFont("Arial", "", 14);
$pdf->Cell(30, 10, "Nom : ", 0, 0, "C");
$pdf->Cell(60, 10, "$nom"." "." $prenom", 0, 0, "C");
$pdf->Ln(10);
$pdf->Cell(100, 10, "Matricule : ", 0, 0, "C");
$pdf->Cell(1, 10, "$idVisiteur", 0, 0, "C");
$pdf->Ln(10);
$pdf->Cell(90, 10, "Mois : ", 0, 0, "C");
$pdf->Cell(1, 10, "$mois", 0, 0, "C");
$pdf->Ln(20);


// Ajouter les détails de la facture
$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(15);
$pdf->Cell(40, 10, "Frais Forfaitaires", 1, 0, "C");
$pdf->Cell(40, 10, "Quantité", 1, 0, "C");
$pdf->Cell(40, 10, "Montant unitaire", 1, 0, "C");
$pdf->Cell(40, 10, "Total", 1, 1, "C");
$pdf->SetFont("Arial", "B", 11);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(15);
$pdf->Cell(40, 10, "Nuitée", 1, 0);
$pdf->Cell(40, 10, "$nui[2]", 1, 0, "C");
$pdf->Cell(40, 10, "$nuiff[2]", 1, 0, "C");
$pdf->Cell(40, 10, "$nui[2]"*"$nuiff[2]", 1, 1, "C");
$pdf->Cell(15);
$pdf->Cell(40, 10, "Repas Midi", 1, 0);
$pdf->Cell(40, 10, "$rep[2]", 1, 0, "C");
$pdf->Cell(40, 10, "$repff[2]", 1, 0, "C");
$pdf->Cell(40, 10, "$rep[2]"*"$repff[2]", 1, 1, "C");
$pdf->Cell(15);
$pdf->Cell(40, 10, "Kilométrage", 1, 0);
$pdf->Cell(40, 10, "$km[2]", 1, 0, "C");
$pdf->Cell(40, 10, "$kmff[2]", 1, 0, "C");
$pdf->Cell(40, 10, "$km[2]"*"$kmff[2]", 1, 1, "C");
$pdf->Ln(10);

// autre frais
$pdf->SetFont("Arial", "B", 12);
$pdf->SetTextColor(60, 78, 150);
$pdf->Cell(80);
$pdf->Cell(10, 10,"Autres Frais", "C");
$pdf->Ln(10);
$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(15);
$pdf->Cell(20, 10, "Date", 1, 0, "C");
$pdf->Cell(120, 10, "Libellé", 1, 0, "C");
$pdf->Cell(20, 10, "Montant", 1, 1, "C");
$pdf->SetFont("Arial", "B", 11);
$pdf->SetTextColor(0,0,0);
foreach($ligneFHF as $ligne) {
 $pdf->Cell(15);
$pdf->Cell(20, 10, "$ligne[date]", 1, 0, "C");
$pdf->Cell(120, 10, "$ligne[libelle]", 1, 0, "C");
$pdf->Cell(20, 10, "$ligne[montant]", 1, 1, "C");   
}
$pdf->Ln(10);



// Sortie du document
$pdf->Output("Commandn°" . "yiufriyutiu.pdf", 'I');
