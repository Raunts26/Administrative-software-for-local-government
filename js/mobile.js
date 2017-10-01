(function(){
  "use strict";

  var Mobile = function() {

    if(Mobile.instance) {
      return Mobile.instance;
    }

    this.MobileData = [];
    this.myTable = "";
    this.neededURL = "";

    Mobile.instance = this;

    this.init();
    };

    window.Mobile = Mobile;

    Mobile.prototype = {
      init: function() {
        this.listenChanges();
      },

      listenChanges: function() {
        document.querySelector("#org").addEventListener("change", function() {
          if(document.querySelector("#org").value === "Rae Vallavalitsus") {
            Mobile.instance.showDepartment();
          } else {
            Mobile.instance.hideDepartment();
          }
          Mobile.instance.cleanTable();
          Mobile.instance.getData();
        });

        document.querySelector("#dep").addEventListener("change", function() {
          Mobile.instance.cleanTable();
          Mobile.instance.getData();
        });
      },

      showDepartment: function() {
        document.querySelector("#dep").style.display = "block";
      },

      hideDepartment: function() {
        document.querySelector("#dep").style.display = "none";
      },

      cleanTable: function() {
        //var parent = document.querySelector("#mobile-data");
        //var rows = document.querySelectorAll(".row");
        document.querySelector("#mobile-data").innerHTML = "";
        /*for(var i = 0; i < rows.length; i++) {
          parent.removeChild(rows[i]);
        }*/
      },

      getData: function() {
        if(document.querySelector("#org").value === "Rae Vallavalitsus") {
          this.neededURL = "../inc/mobile_functions.php?getdepdata=" + document.querySelector("#dep").value;
        } else {
          this.neededURL = "../inc/mobile_functions.php?getdata=" + document.querySelector("#org").value;
        }

        document.querySelector("#table_head").style.display = "table-header-group";

        if(this.myTable !== "") {
          this.myTable.destroy();
        }

        this.myTable = $('#mobiletable').DataTable({
          "language": {
            "decimal":        "",
            "emptyTable":     "Ei leitud vasteid",
            "info":           "Näitan vasteid _START_ kuni _END_. Kokku _TOTAL_ vastet",
            "infoEmpty":      "0 vastet leitud",
            "infoFiltered":   "(Otsitud _MAX_ vaste seast)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "Näita _MENU_ vastet",
            "loadingRecords": "Laen...",
            "processing":     "Töötlen...",
            "search":         "Otsi:",
            "zeroRecords":    "Ei leitud mitte ühtegi vastet",
            "paginate": {
                "first":      "Esimene",
                "last":       "Viimane",
                "next":       "Järgmine",
                "previous":   "Eelmine"
              }
          },
          "ajax": {
                "url": Mobile.instance.neededURL,
                "dataSrc": "",
                "async": false,


            },
          "columns": [
                {
                 "render": function ( data, type, full, meta ) {
                     return '<span class="mobile_values" data-hidden="' + full.hidden + '" data-id="name-' + full.id + '">' + full.name + '</span>';
                 }
                },
                {
                 "render": function ( data, type, full, meta ) {
                     return '<span class="mobile_values" data-id="job-' + full.id + '">' + full.job + '</span>';
                 }
                },
                {
                 "render": function ( data, type, full, meta ) {
                     return '<span class="mobile_values" data-id="phone-' + full.id + '">' + full.phone + '</span>';
                 }
                },
                {
                  visible: false,
                 "render": function ( data, type, full, meta ) {
                     return '<span class="mobile_values" data-id="info-' + full.id + '">' + full.info + '</span>';
                 }
                },
                {
                  visible: false,
                  sortable: false,
                 "render": function ( data, type, full, meta ) {
                     return '<span class="mobile_values" data-id="comment-' + full.id + '">' + full.comment + '</span>';
                 }
                },
                {
                 sortable: false,
                 visible: false,
                 "render": function ( data, type, full, meta ) {
                     return '<span class="edit-btn" data-id="' + full.id + '" data-toggle="modal" data-target="#editmodal"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></span><span class="del-btn" data-number="' + full.phone + '" data-id="' + full.id + '"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></span>';
                 }
                }

                /*
                <span class="edit-btn" data-id="23" data-toggle="modal" data-target="#editmodal"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></span>
                <span class="del-btn" data-id="23"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></span>

                */
                /*{ "defaultContent": '<span class="edit-btn" data-toggle="modal" data-id="' + "id" + '" data-target="#editmodal"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></span>' }*/
            ]
        });

        if(this.myTable !== "") {
          document.querySelector("#tutorial_msg").style.display = "none";
        } else {
          document.querySelector("#tutorial_msg").style.display = "block";
        }

        //Näita tulbad kui oled admin
        if(App.instance.usergroup === "3" || App.instance.usergroup === "4") {
          this.myTable.column(3).visible(true); // Paketi tulp
          this.myTable.column(5).visible(true); // Halda tulp
        } else {
          Mobile.instance.hideRows();

        }

      },

      hideRows: function() {

        var values = document.querySelectorAll(".mobile_values");

        for(var i = 0; i < values.length; i++) {
          if(values[i].dataset.hidden === "1") {
            values[i].parentElement.parentElement.style.display = "none";
          }
        }

      }


    };


}) ();
