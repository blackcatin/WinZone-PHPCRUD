$(document).ready(function() {
    $("#addEventForm").on("submit", function(event) {
        event.preventDefault(); // Mencegah halaman untuk refresh

        var formData = new FormData(this); // Ambil data form

        $.ajax({
            url: "", // URL untuk memproses form, gunakan halaman yang sama
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                // Tampilkan modal feedback
                $("#modalMessage").text("Event berhasil ditambahkan!");
                $("#modalFeedback").fadeIn();
                
                // Misalnya, refresh daftar event tanpa me-refresh seluruh halaman
                $(".event-list").load(location.href + " .event-list");
            },
            error: function() {
                // Tampilkan modal feedback jika terjadi error
                $("#modalMessage").text("Gagal menambahkan event. Silakan coba lagi.");
                $("#modalFeedback").fadeIn();
            }
        });
    });

    $(".close, #modalOKButton").click(function() { 
        // Tutup modal ketika tombol 'X' atau tombol 'OK' diklik
        $("#modalFeedback").fadeOut();
    });
});
