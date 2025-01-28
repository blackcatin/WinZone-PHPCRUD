<?php
session_start();
include('connection.php');
require('fpdf/fpdf.php');

// Cek apakah ada rsvp_id
if (!isset($_GET['rsvp_id'])) {
    die("RSVP ID tidak valid.");
}

$rsvp_id = $_GET['rsvp_id'];

// Ambil data dari database berdasarkan RSVP ID
$sql = "SELECT rsvp.name, rsvp.email, rsvp.no_telp, rsvp.nama_sekolah, 
               events.name AS event_name, events.date, events.start_time, 
               events.end_time, events.location
        FROM rsvp
        JOIN events ON rsvp.event_id = events.event_id
        WHERE rsvp.rsvp_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $rsvp_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Data tidak ditemukan.");
}

$data = $result->fetch_assoc();

// **1. Buat PDF dengan FPDF**
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// **Header**
$pdf->Cell(0, 10, 'TICKET', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Event: ' . $data['event_name'], 0, 1, 'C');
$pdf->Cell(0, 10, 'Date: ' . $data['date'] . ' | Time: ' . $data['start_time'] . ' - ' . $data['end_time'], 0, 1, 'C');
$pdf->Cell(0, 10, 'Location: ' . $data['location'], 0, 1, 'C');

// **Info RSVP**
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Issued to:', 0, 1, 'L');

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Name: ' . $data['name'], 0, 1, 'L');
$pdf->Cell(0, 10, 'Email: ' . $data['email'], 0, 1, 'L');
$pdf->Cell(0, 10, 'Phone: ' . $data['no_telp'], 0, 1, 'L');
$pdf->Cell(0, 10, 'School: ' . $data['nama_sekolah'], 0, 1, 'L');

// **Footer**
$pdf->Ln(20);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 10, 'Thank you for your RSVP!', 0, 1, 'C');

// **Output PDF**
$pdf->Output('D', 'Event_Ticket_' . $rsvp_id . '.pdf'); // ✅ PDF akan langsung terunduh
?>
