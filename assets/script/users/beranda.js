$(function () {

    let addVar = $('#add-var').html();
    $('#add-var').remove();

    let lang = {
        "buttons": {
            "pageLength": "Tampilkan %d data"
        },
        "decimal": "",
        "emptyTable": "Tidak ditemukan data di sini",
        "info": "Hasil _START_ sampai _END_ dari _TOTAL_ baris",
        "infoEmpty": "Hasil 0 sampai 0 dari 0 baris",
        "infoFiltered": "(Seleksi dari _MAX_ tota baris)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Tampilkan data _MENU_",
        "loadingRecords": "Mengambil data...",
        "processing": "Memproses data...",
        "search": "Saring:",
        "zeroRecords": "Pencarian tidak menemukan hasil",
        "paginate": {
            "first": "Awal",
            "last": "Akhir",
            "next": "Selanjutnya",
            "previous": "Sebelumnya"
        },
        "aria": {
            "sortAscending": ": pilih untuk mengurutkan kecil ke besar",
            "sortDescending": ": pilih untuk mengurutkan besar ke kecil"
        }
    };

    let menu = [
        [5, 10, 25, 50],
        ['5', '10', '25', '50']
    ];

    $('#pdf-table').DataTable({
        dom: 'Bfrtip',
        ordering: false,
        stateSave: true,
        lengthMenu: menu,
        buttons: [
            {
                extend: "pageLength",
                className: "btn-sm"
            },
            {
                className: "btn-sm btn-primary",
                text: '<i class="fa fa-plus"></i>',
                action: function () {
                    window.location.href = "pdf/main";
                }
            },
        ],
        language: lang,
    });
    $('#xlsx-table').DataTable({
        dom: 'Bfrtip',
        ordering: false,
        stateSave: true,
        lengthMenu: menu,
        buttons: [
            {
                extend: "pageLength",
                className: "btn-sm"
            },
            {
                className: "btn-sm btn-primary",
                text: '<i class="fa fa-plus"></i>',
                action: function () {
                    window.location.href = "excel/main";
                }
            },
        ],
        language: lang,
    });
    $('#mail-table').DataTable({
        dom: 'Bfrtip',
        ordering: false,
        stateSave: true,
        lengthMenu: menu,
        buttons: [
            {
                extend: "pageLength",
                className: "btn-sm"
            },
            {
                className: "btn-sm btn-primary",
                text: '<i class="fa fa-plus"></i>',
                action: function () {
                    window.location.href = "mail/main";
                }
            },
        ],
        language: lang,
    });
    $('#image-table').DataTable({
        dom: 'Bfrtip',
        ordering: false,
        stateSave: true,
        lengthMenu: menu,
        buttons: [
            {
                extend: "pageLength",
                className: "btn-sm"
            },
            {
                className: "btn-sm btn-primary",
                text: '<i class="fa fa-plus"></i>',
                action: function () {
                    window.location.href = "images/main";
                }
            },
        ],
        language: lang,
    });
    $('#var-table').DataTable({
        dom: 'Bfrtip',
        ordering: false,
        stateSave: true,
        lengthMenu: menu,
        buttons: [
            {
                extend: "pageLength",
                className: "btn-sm"
            },
            {
                className: "btn-sm btn-primary",
                text: '<i class="fa fa-plus"></i>',
                action: function () {
                    let content = addVar;
                    bootbox.dialog({
                        title: `Variabel baru`,
                        message: content,
                    });
                }
            },
        ],
        language: lang,
    });
});