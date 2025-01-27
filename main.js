function openForm(eventId) {
    document.getElementById('rsvpModal').style.display = 'flex';
    document.getElementById('event_id').value = eventId;
}

// Fungsi untuk menutup form pop-up
function closeForm() {
    document.getElementById('rsvpModal').style.display = 'none';
}