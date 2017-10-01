(function(){
  "use strict";

  var Inspection = function() {

    if(Inspection.instance) {
      return Inspection.instance;
    }

    this.neededURL = "";
    this.myTable = "";
	this.editing = false;
	this.inspection_id = 0;
    Inspection.instance = this;

    this.init();
    };

    window.Inspection = Inspection;


    Inspection.prototype = {
      init: function() {
        this.getData();
        this.listenEvents();
      },

      listenEvents: function() {

         document.querySelector("#inspection_id").addEventListener("change", function() {

        });



        document.querySelector("#save_edit").addEventListener("click", function() {
          Inspection.instance.saveUpdateData();
        });

        document.addEventListener("click", function(e) {

          if(!$('#editmodal').hasClass('in')) {
            Inspection.instance.editing = false;
          } else {
            Inspection.instance.editing = true;
          }

          if(e.target.parentElement.className === "del-btn") {
            var c = confirm("Kas oled kindel, et soovid kustutada?");
            if(c) {
              Inspection.instance.deleteInspection(e.target.parentElement.dataset.id);
            }
          }

          if(e.target.parentElement.className === "edit-btn") {
            Inspection.instance.getUpdateData(e.target.parentElement.dataset.id);
          }

        });

      },

	   deleteInspection: function(id) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //console.log(this.responseText);
               {
                  Inspection.instance.myTable.destroy();
                  Inspection.instance.getData();

                }
            }
        };
        xmlhttp.open("GET", "../inc/ajax.php?deleteinspection=" + id, true);
        xmlhttp.send();

      },
			getUpdateData: function(id) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              var data = JSON.parse(this.responseText);
				      console.log(data);
              Inspection.instance.fillUpdateData(data);

              Inspection.instance.inspection_edit_id = data.real_inspection_id;
              Inspection.instance.editing = true;
              //Service.instance.getObjectsSelect();


            }
        };
        xmlhttp.open("GET", "../inc/ajax.php?getinspectiondatabyid=" + id, true);
        xmlhttp.send();
      },

      fillUpdateData: function(data) {

        document.querySelector("#inspection_id").value;		document.querySelector("#object_type_edit").value;																						document.querySelector("#object_id_edit").value;		
        document.querySelector("#time_edit").value = data.adress;
        document.querySelector("#time_approved_edit").value = data.contact;
   


      },

      saveUpdateData: function() {
        var id = document.querySelector("#inspection_id").value;		var object_type = document.querySelector("#object_type_edit").value;																						var object_id = document.querySelector("#object_id_edit").value;		
        var time = document.querySelector("#time_edit").value;
        var time_approved = document.querySelector("#time_approved_edit").value;
      


        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
				console.log(this.responseText);
                document.querySelector("#close_edit").click();
                //if(document.querySelector("#my_tasks")) {
                  //*Service.instance.countMytasks();
                 // Service.instance.getMyTasks();
              //  } else {
                  Inspection.instance.myTable.destroy();
                 Inspection.instance.getData();
                //}
            }
        };
        xmlhttp.open("POST", "../inc/ajax.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("saveinspectionupdate=" + id + "&object_type=" + object_type + "&object_id=" + object_id + "&time=" + time + "&time_approved=" + time_approved);


	 },


      getData: function() {
        this.neededURL = "../inc/ajax.php?getinspectiondata=1";

        document.querySelector("#table_head").style.display = "table-header-group";

        /*if(this.myTable !== "") {
          this.myTable.destroy();
        }*/

        this.myTable = $('#inspection').DataTable({
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
                "url": Inspection.instance.neededURL,
                "dataSrc": "",


            },
          "columns": [
                {
                 "render": function ( data, type, full, meta ) {
                     return '<span class="Inspection_values" data-id="name-' + full.id + '">' + full.object_id + '</span>';
                 }
                },
                {
                 "render": function ( data, type, full, meta ) {
                     return '<span class="Inspection_values" data-id="time-' + full.ID + '">' + full.time + '</span>';
                 }
                },
                {
                 "render": function ( data, type, full, meta ) {
                     return '<span class="Inspection_values" data-id="time_approved-' + full.ID + '">' + full.time_approved + '</span>';
                 }
                },
               
                {
                 sortable: false,
                 "render": function ( data, type, full, meta ) {
                     return '<span class="edit-btn" data-id="' + full.ID + '" data-toggle="modal" data-target="#editmodal"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></span><span class="del-btn" data-id="' + full.ID + '"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></span>';
                 }
                }

                /*
                <span class="edit-btn" data-id="23" data-toggle="modal" data-target="#editmodal"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></span>
                <span class="del-btn" data-id="23"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></span>

                */
                /*{ "defaultContent": '<span class="edit-btn" data-toggle="modal" data-id="' + "id" + '" data-target="#editmodal"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></span>' }*/
            ]

        });



        //Näita tulbad kui oled admin
        /*if(App.instance.usergroup === "3" || App.instance.usergroup === "4") {
          this.myTable.column(3).visible(true); // Paketi tulp
          this.myTable.column(5).visible(true); // Halda tulp
        }*/

      }




    };








}) ();
